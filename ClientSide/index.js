const express = require('express');
const mysql = require('mysql2');
const ejs = require('ejs');
const session = require('express-session');
const bcrypt = require('bcrypt');
const async = require('async');

const app = express();
const port = 3333;

// DB
const db = mysql.createConnection({
  host: 'localhost',
  user: 'root',
  password: '',
  database: 'ojt-websys',
});
db.connect(err => {
  if (err) console.error('Error connecting to MySQL:', err);
  else console.log('Connected to the database');
});

// App middleware
app.use(express.urlencoded({ extended: true }));
app.use(express.json());
app.set('view engine', 'ejs');
app.use(express.static('public'));
app.use(session({
  secret: 'secret',
  resave: false,
  saveUninitialized: true,
  cookie: { secure: false }
}));

// Utils

// Format a JS Date as YYYY-MM-DD using local calendar (no timezone shift)
function ymdLocal(d) {
  const dt = (d instanceof Date) ? d : new Date(d);
  const y = dt.getFullYear();
  const m = String(dt.getMonth() + 1).padStart(2, '0');
  const day = String(dt.getDate()).padStart(2, '0');
  return `${y}-${m}-${day}`;
}

// Convert incoming form date (yyyy-mm-dd or dd/mm/yyyy) to YYYY-MM-DD string
function sqlDateFromInput(value) {
  if (!value) return null;
  if (value.includes('-')) {               // yyyy-mm-dd
    const [y, m, d] = value.split('-');
    return `${y}-${m.padStart(2,'0')}-${d.padStart(2,'0')}`;
  }
  if (value.includes('/')) {               // dd/mm/yyyy
    const [d, m, y] = value.split('/');
    return `${y}-${m.padStart(2,'0')}-${d.padStart(2,'0')}`;
  }
  return null;
}


// Routes
app.get('/', (req, res) => {
  res.render('index', { title: 'Client Login Page' });
});

app.get('/logout', (req, res) => {
  req.session.destroy(() => res.redirect('/'));
});

// LOGIN
app.post('/login', (req, res) => {
  const { username, password, userType } = req.body;
  const query = 'SELECT * FROM users WHERE username = ? AND user_type = ?';
  db.query(query, [username, userType], (err, results) => {
    if (err) {
      console.error('MySQL error:', err);
      return res.status(500).json({ success: false, message: 'Internal Server Error' });
    }
    if (!results.length) {
      console.log('User not found or incorrect user type');
      return res.json({ success: false, message: 'Invalid user' });
    }
    const user = results[0];
    bcrypt.compare(password, user.password.replace(/^\$2y\$/, '$2b$'), (bcryptErr, match) => {
      if (bcryptErr) {
        console.error('bcrypt error:', bcryptErr);
        return res.status(500).json({ success: false, message: 'Internal Server Error' });
      }
      if (!match) return res.json({ success: false, message: 'Invalid credentials' });

      req.session.username = username;
      req.session.userType = userType;
      req.session.userId = user.user_id;

      let redirectUrl = '/intern_profile';
      if (userType === 'Adviser') redirectUrl = '/adviser_profile';
      else if (userType === 'Supervisor') redirectUrl = '/supervisor_profile';

      return res.json({ success: true, redirectUrl });
    });
  });
});

// INTERN PROFILE
app.get('/intern_profile', (req, res) => {
  if (req.session.userType !== 'Intern' || !req.session.userId) return res.redirect('/');

  const internDetailsSql = `
    SELECT interndetails.*,
           CASE WHEN COALESCE(ir.checklist_completed,0)=0 THEN 'Not yet Submitted'
                WHEN ir.checklist_completed=1 THEN 'Submitted' ELSE 'Unknown' END AS RequirementsStatus
    FROM interndetails
    LEFT JOIN internshiprecords ir ON interndetails.intern_id = ir.intern_id
    WHERE interndetails.user_id = ?
  `;
  db.query(internDetailsSql, [req.session.userId], (err, internRows) => {
    if (err) { console.error(err); return res.status(500).send('Internal Server Error'); }
    if (!internRows.length) {
      return res.render('intern_profile', {
        username: req.session.username, interndetails: {},
        programNames: [], feedback: [], timeEntries: [], requirementsStatus: 'Unknown'
      });
    }
    const intern = internRows[0];

    const programDetailsSql = 'SELECT program_name FROM ojtprograms';
    db.query(programDetailsSql, (programErr, programResults) => {
      if (programErr) { console.error(programErr); return res.status(500).send('Internal Server Error'); }

      const feedbackSql = `
        SELECT f.feedback_text
        FROM feedback f
        JOIN internshiprecords ir ON f.record_id = ir.record_id
        WHERE ir.intern_id = ?
      `;
      db.query(feedbackSql, [intern.intern_id], (fbErr, fbRows) => {
        if (fbErr) { console.error(fbErr); return res.status(500).send('Internal Server Error'); }

        const timeTrackSql = `
          SELECT date, hours_submit FROM timetrack
          WHERE record_id IN (SELECT record_id FROM internshiprecords WHERE intern_id = ?)
          ORDER BY date ASC
        `;
        db.query(timeTrackSql, [intern.intern_id], (ttErr, ttRows) => {
          if (ttErr) { console.error(ttErr); return res.status(500).send('Internal Server Error'); }

          res.render('intern_profile', {
            username: req.session.username,
            interndetails: intern,
            programNames: programResults,
            feedback: fbRows,
            timeEntries: ttRows,
            requirementsStatus: intern.RequirementsStatus
          });
        });
      });
    });
  });
});

// INTERN WORKTRACK (GET)
app.get('/intern_worktrack', (req, res) => {
  if (req.session.userType !== 'Intern' || !req.session.userId) return res.redirect('/');

  const internshipRecordsSql = `
    SELECT ir.record_id, ir.checklist_completed, ir.hours_completed, ir.hours_remaining,
           ir.start_date, ir.end_date,
           CASE WHEN ir.hours_remaining <= 0 THEN 'Completed' ELSE 'In Progress' END AS record_status,
           ad.firstName AS adviserFirstName, ad.lastName AS adviserLastName,
           sd.firstName AS supervisorFirstName, sd.lastName  AS supervisorLastName
    FROM internshiprecords ir
    JOIN adviserdetails   ad ON ir.adviser_id    = ad.adviser_id
    JOIN supervisordetails sd ON ir.supervisor_id = sd.supervisor_id
    WHERE ir.intern_id = (SELECT intern_id FROM interndetails WHERE user_id = ?)
  `;

  const timeTrackSql = `
    SELECT record_id, DATE_FORMAT(date,'%Y-%m-%d') AS date_str, hours_submit
    FROM timetrack
    WHERE record_id IN (
      SELECT record_id FROM internshiprecords
      WHERE intern_id = (SELECT intern_id FROM interndetails WHERE user_id = ?)
    )
    ORDER BY date ASC
  `;

  db.query(internshipRecordsSql, [req.session.userId], (err, records) => {
    if (err) { console.error(err); return res.status(500).send('Internal Server Error'); }

    // derive per-record min/max for the date picker
    const today = new Date();
    const enriched = records.map(r => {
      const start = new Date(r.start_date);
      const end   = r.end_date ? new Date(r.end_date) : today;
      const max   = new Date(Math.min(today, end));
      return Object.assign({}, r, {
        dateMin: ymdLocal(start),
        dateMax: ymdLocal(max),
        start_date_str: ymdLocal(start),
        end_date_str: ymdLocal(end)
      });
    });
    
    db.query(timeTrackSql, [req.session.userId], (ttErr, timeEntries) => {
      if (ttErr) { console.error(ttErr); return res.status(500).send('Internal Server Error'); }

      // one-time flash
      const flash = req.session.message || null;
      delete req.session.message;           // ensure it won't persist
      // optional: prevent caching of this page
      res.set('Cache-Control', 'no-store');

      res.render('intern_worktrack', {
        username: req.session.username,
        records: enriched,
        timeEntries,
        message: flash
      });
    });
  });
});

// INTERN WORKTRACK (POST time entry)
app.post('/submit_time_entry', (req, res) => {
  if (req.session.userType !== 'Intern' || !req.session.userId) return res.redirect('/');

  const DAILY_HOURS_CAP = 8; // change policy here if needed

  const { date, hoursSubmit, record_id } = req.body;
  const submitted = parseFloat(hoursSubmit);

  // parse date helper (supports yyyy-mm-dd and dd/mm/yyyy)
  function parseYMD(value) {
    if (!value) return null;
    if (value.includes('-')) { const [y,m,d] = value.split('-').map(Number); return new Date(y,m-1,d); }
    if (value.includes('/')) { const [d,m,y] = value.split('/').map(Number); return new Date(y,m-1,d); }
    return null;
  }

  if (!record_id || !date || !Number.isFinite(submitted) || submitted <= 0) {
    req.session.message = { type: 'error', text: 'Invalid date or hours.' };
    return res.redirect('/intern_worktrack');
  }

    const inputSqlDate = sqlDateFromInput(date);
    if (!inputSqlDate) {
      req.session.message = { type: 'error', text: 'Invalid date.' };
      return res.redirect('/intern_worktrack');
    }

  db.beginTransaction(txErr => {
    if (txErr) { console.error(txErr); req.session.message = { type:'error', text:'Could not start transaction.' }; return res.redirect('/intern_worktrack'); }

    const q1 = `
      SELECT record_id, intern_id, start_date, end_date,
             hours_completed, hours_remaining, record_status
      FROM internshiprecords
      WHERE record_id = ? AND intern_id = (SELECT intern_id FROM interndetails WHERE user_id = ?)
      FOR UPDATE
    `;
    db.query(q1, [record_id, req.session.userId], (e1, rows) => {
      if (e1) { console.error(e1); return db.rollback(() => { req.session.message = { type:'error', text:'Error finding record.' }; res.redirect('/intern_worktrack'); }); }
      if (!rows.length) return db.rollback(() => { req.session.message = { type:'error', text:'Record not found.' }; res.redirect('/intern_worktrack'); });

      const rec = rows[0];
    if (Number(rec.hours_remaining) <= 0) {
      return db.rollback(() => {
        req.session.message = { type:'error', text:'Internship already completed.' };
        res.redirect('/intern_worktrack');
      });
    }

      // Compare by YYYY-MM-DD strings to avoid timezone issues
      const startStr = ymdLocal(new Date(rec.start_date));
      const todayStr = ymdLocal(new Date());
      const endStr   = rec.end_date ? ymdLocal(new Date(rec.end_date)) : null;

      if (inputSqlDate < startStr || inputSqlDate > todayStr || (endStr && inputSqlDate > endStr)) {
        return db.rollback(() => { req.session.message = { type:'error', text:'Date out of range.' }; res.redirect('/intern_worktrack'); });
      }

      const allowed = Math.min(DAILY_HOURS_CAP, Number(rec.hours_remaining));
      const toApply = Math.min(submitted, allowed);

      if (toApply <= 0) {
        return db.rollback(() => { req.session.message = { type:'error', text:'No hours available to log.' }; res.redirect('/intern_worktrack'); });
      }

      const insert = `INSERT INTO timetrack (record_id, date, hours_submit) VALUES (?, ?, ?)`;
      db.query(insert, [rec.record_id, inputSqlDate, toApply], (e2) => {
        if (e2) { console.error(e2); return db.rollback(() => { req.session.message = { type:'error', text:'Error submitting time entry.' }; res.redirect('/intern_worktrack'); }); }

        const upd = `
          UPDATE internshiprecords
          SET hours_completed = hours_completed + ?,
              hours_remaining = GREATEST(hours_remaining - ?, 0),
              record_status   = CASE WHEN (hours_remaining - ?) <= 0 THEN 'Completed' ELSE 'In Progress' END
          WHERE record_id = ?
        `;
        db.query(upd, [toApply, toApply, toApply, rec.record_id], (e3) => {
          if (e3) { console.error(e3); return db.rollback(() => { req.session.message = { type:'error', text:'Error updating internship hours.' }; res.redirect('/intern_worktrack'); }); }

          db.commit(e4 => {
            if (e4) { console.error(e4); return db.rollback(() => { req.session.message = { type:'error', text:'Error finalizing request.' }; res.redirect('/intern_worktrack'); }); }
            req.session.message = { type:'success', text:'Time entry submitted.' };
            res.redirect('/intern_worktrack');
          });
        });
      });
    });
  });
});

/* ----------------------- ADVISER ----------------------- */

// Adviser profile
app.get('/adviser_profile', (req, res) => {
  if (req.session.userType !== 'Adviser' || !req.session.userId) return res.redirect('/');

  const adviserDetailsSql = 'SELECT * FROM adviserdetails WHERE user_id = ?';
  db.query(adviserDetailsSql, [req.session.userId], (err, rows) => {
    if (err) { console.error(err); return res.status(500).send('Internal Server Error'); }
    if (!rows.length) {
      return res.render('adviser_profile', { username: req.session.username, adviserdetails:{}, programNames:[] });
    }
    const programDetailsSql = 'SELECT program_name FROM ojtprograms';
    db.query(programDetailsSql, (perr, prows) => {
      if (perr) { console.error(perr); return res.status(500).send('Internal Server Error'); }
      res.render('adviser_profile', { username: req.session.username, adviserdetails: rows[0], programNames: prows });
    });
  });
});

// Adviser worktrack
app.get('/adviser_worktrack', (req, res) => {
  if (req.session.userType !== 'Adviser' || !req.session.userId) return res.redirect('/');

  const sql = `
    SELECT ir.hours_completed, ir.hours_remaining, ir.start_date, ir.end_date,
           CASE WHEN ir.hours_remaining <= 0 THEN 'Completed' ELSE 'In Progress' END AS record_status,
           id.firstName AS internFirstName, id.lastName AS internLastName, id.classCode AS internCC
    FROM internshiprecords ir
    JOIN adviserdetails ad ON ir.adviser_id = ad.adviser_id
    JOIN interndetails id  ON ir.intern_id  = id.intern_id
    WHERE ad.user_id = ?
  `;
  db.query(sql, [req.session.userId], (err, results) => {
    if (err) { console.error(err); return res.status(500).send('Internal Server Error'); }
    res.render('adviser_worktrack', { username: req.session.username, records: results });
  });
});

// Adviser checklist (GET)
app.get('/adviser_checklist', (req, res) => {
  if (req.session.userType !== 'Adviser' || !req.session.userId) return res.redirect('/login');

  const sql = `
    SELECT id.firstName AS internFirstName, id.lastName AS internLastName, id.classCode,
           ad.firstName AS adviserFirstName, ad.lastName AS adviserLastName,
           c.companyName, ir.checklist_completed, ir.record_id
    FROM interndetails id
    JOIN adviserdetails ad ON id.adviser_id = ad.adviser_id
    JOIN company c         ON id.company_id  = c.company_id
    JOIN internshiprecords ir ON id.intern_id = ir.intern_id
    WHERE ad.user_id = ?
  `;
  db.query(sql, [req.session.userId], (err, results) => {
    if (err) { console.error(err); return res.status(500).send('Internal Server Error'); }
    res.render('adviser_checklist', { records: results });
  });
});

// Adviser checklist (POST)
app.post('/adviser_checklist', (req, res) => {
  if (req.session.userType !== 'Adviser' || !req.session.userId) return res.redirect('/login');

  const rawAllIds = req.body.record_id_name || [];
  const rawChecked = req.body.checklistCompleted || [];
  const allIds = Array.isArray(rawAllIds) ? rawAllIds.map(Number) : [Number(rawAllIds)];
  const checkedIds = new Set((Array.isArray(rawChecked) ? rawChecked : [rawChecked]).map(Number));

  db.beginTransaction(err => {
    if (err) { console.error(err); req.session.message = 'Could not start database transaction'; return res.redirect('/adviser_checklist'); }

    const updateRecord = (record_id, isChecked, done) => {
      const sql = `UPDATE internshiprecords SET checklist_completed = ? WHERE record_id = ?`;
      db.query(sql, [isChecked ? 1 : 0, record_id], done);
    };

    const tasks = allIds.map(record_id => done => updateRecord(record_id, checkedIds.has(Number(record_id)), done));
    if (!tasks.length) { req.session.message = 'No changes detected'; return res.redirect('/adviser_checklist'); }

    async.series(tasks, (e) => {
      if (e) { console.error(e); return db.rollback(() => { req.session.message = 'Failed to update records'; res.redirect('/adviser_checklist'); }); }
      db.commit(cerr => {
        if (cerr) { console.error(cerr); return db.rollback(() => { req.session.message = 'Failed to commit changes'; res.redirect('/adviser_checklist'); }); }
        req.session.message = 'Checklist successfully updated';
        res.redirect('/adviser_checklist');
      });
    });
  });
});

/* --------------------- SUPERVISOR --------------------- */

app.get('/supervisor_profile', (req, res) => {
  if (req.session.userType !== 'Supervisor' || !req.session.userId) return res.redirect('/login');

  const supervisorDetailsSql = 'SELECT * FROM supervisordetails WHERE user_id = ?';
  db.query(supervisorDetailsSql, [req.session.userId], (err, rows) => {
    if (err) { console.error(err); return res.status(500).send('Internal Server Error'); }
    const programDetailsSql = 'SELECT program_name FROM ojtprograms';
    db.query(programDetailsSql, (perr, prows) => {
      if (perr) { console.error(perr); return res.status(500).send('Internal Server Error'); }
      res.render('supervisor_profile', { username: req.session.username, supervisordetails: rows[0] || {}, programNames: prows });
    });
  });
});

app.get('/supervisor_worktrack', (req, res) => {
  if (req.session.userType !== 'Supervisor' || !req.session.userId) return res.redirect('/');

  const sql = `
    SELECT ir.hours_completed, ir.hours_remaining, ir.start_date, ir.end_date,
           CASE WHEN ir.hours_remaining <= 0 THEN 'Completed' ELSE 'In Progress' END AS record_status,
           id.firstName AS internFirstName, id.lastName AS internLastName, id.classCode AS internCC
    FROM internshiprecords ir
    JOIN supervisordetails sd ON ir.supervisor_id = sd.supervisor_id
    JOIN interndetails id     ON ir.intern_id     = id.intern_id
    WHERE sd.user_id = ?
  `;
  db.query(sql, [req.session.userId], (err, results) => {
    if (err) { console.error(err); return res.status(500).send('Internal Server Error'); }
    res.render('supervisor_worktrack', { username: req.session.username, records: results });
  });
});

app.get('/supervisor_checklist', (req, res) => {
  if (req.session.userType !== 'Supervisor' || !req.session.userId) return res.redirect('/login');

  const sql = `
    SELECT id.firstName AS internFirstName, id.lastName AS internLastName, id.classCode,
           ad.firstName AS adviserFirstName, ad.lastName AS adviserLastName,
           c.companyName, ir.checklist_completed, ir.record_id
    FROM interndetails id
    JOIN adviserdetails ad    ON id.adviser_id    = ad.adviser_id
    JOIN supervisordetails sd ON id.supervisor_id = sd.supervisor_id
    JOIN company c            ON id.company_id    = c.company_id
    JOIN internshiprecords ir ON id.intern_id     = ir.intern_id
    WHERE sd.user_id = ?
  `;
  db.query(sql, [req.session.userId], (err, results) => {
    if (err) { console.error(err); return res.status(500).send('Internal Server Error'); }
    res.render('supervisor_checklist', { records: results });
  });
});

app.post('/supervisor_checklist', (req, res) => {
  if (req.session.userType !== 'Supervisor' || !req.session.userId) return res.redirect('/login');

  const rawAllIds = req.body.record_id_name || [];
  const rawChecked = req.body.checklistCompleted || [];
  const allIds = Array.isArray(rawAllIds) ? rawAllIds.map(Number) : [Number(rawAllIds)];
  const checkedIds = new Set((Array.isArray(rawChecked) ? rawChecked : [rawChecked]).map(Number));

  db.beginTransaction(err => {
    if (err) { console.error(err); req.session.message = 'Could not start database transaction'; return res.redirect('/supervisor_checklist'); }

    const updateRecord = (record_id, isChecked, done) => {
      const sql = `UPDATE internshiprecords SET checklist_completed = ? WHERE record_id = ?`;
      db.query(sql, [isChecked ? 1 : 0, record_id], done);
    };

    const tasks = allIds.map(record_id => done => updateRecord(record_id, checkedIds.has(Number(record_id)), done));
    if (!tasks.length) { req.session.message = 'No changes detected'; return res.redirect('/supervisor_checklist'); }

    async.series(tasks, (e) => {
      if (e) { console.error(e); return db.rollback(() => { req.session.message = 'Failed to update records'; res.redirect('/supervisor_checklist'); }); }
      db.commit(cerr => {
        if (cerr) { console.error(cerr); return db.rollback(() => { req.session.message = 'Failed to commit changes'; res.redirect('/supervisor_checklist'); }); }
        req.session.message = 'Checklist successfully updated';
        res.redirect('/supervisor_checklist');
      });
    });
  });
});

// START
app.listen(port, () => console.log(`Server is running on port ${port}`));
