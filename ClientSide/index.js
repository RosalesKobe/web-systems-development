const express = require('express');
const mysql = require('mysql');
const ejs = require('ejs');
const session = require('express-session');
const bcrypt = require('bcrypt');
const saltRounds = 10; // or another number you choose
const app = express();
const port = 3333;
const bodyParser = require('body-parser');

// Database connection setup
const db = mysql.createConnection({
 // host: '192.168.0.207',
 host: 'localhost',
  user: 'root',
  password: '',
  database: 'teampogi',
});

// Connect to MySQL
db.connect((err) => {
  if (err) {
    console.error('Error connecting to MySQL:', err);
  } else {
    console.log('Connected to the database');
  }
});

app.use(bodyParser.urlencoded({ extended: true }));

// Set the view engine to EJS
app.set('view engine', 'ejs');

// Serve static files
app.use(express.static('public'));

// Parse JSON bodies
app.use(express.json());

// Session middleware setup
app.use(session({
  secret: 'secret', // Secret key "slu"
  resave: false,
  saveUninitialized: true,
  cookie: { secure: false } // Set to true if you're using HTTPS
}));

// Route to render the index page
app.get('/', (req, res) => {
  res.render('index', { title: 'Client Login Page' }); // Pass any necessary data to your EJS template
});

// Login route
app.post('/login', (req, res) => {
  const { username, password, userType } = req.body;
  
  const query = 'SELECT * FROM users WHERE username = ? AND user_type = ?';
  
  db.query(query, [username, userType], (err, results) => {
      if (err) {
          console.error('MySQL error:', err);
          return res.status(500).json({ success: false, message: 'Internal Server Error' });
      }
      
      if (results.length > 0) {
        const user = results[0];
        

        // Verify the hashed password
        if (bcrypt.compareSync(password, user.password)) {
          req.session.username = username; // Save username in session
          req.session.userType = userType; // Save userType in session
          req.session.userId = user.user_id; // Save the user's ID in the session
              console.log("Login success");
              console.log(req.session.username)
              console.log(req.session.userType)
              // Redirect to different pages based on the user type
              let redirectUrl = '/intern_profile'; // Default redirect for 'Intern'
              if (userType === 'Adviser') {
                  redirectUrl = '/adviser_profile'; // Redirect for 'Adviser'
              } else if (userType === 'Intern') {
                  redirectUrl = '/intern_profile'; // Redirect for 'Administrator'
              }
              // Add more 'else if' conditions if there are more user types

              return res.json({ success: true, redirectUrl: redirectUrl });
          } else {
              console.log("Incorrect Credentials");
              return res.json({ success: false, message: 'Invalid credentials' });
          }
      } else {
          console.log("User not found or incorrect user type");
          return res.json({ success: false, message: 'Invalid user' });
      }
  });
});


// Profile route for intern
app.get('/intern_profile', (req, res) => {
  if (req.session.userType === 'Intern' && req.session.userId) {
    const internDetailsSql = 'SELECT * FROM interndetails WHERE user_id = ?';
    db.query(internDetailsSql, [req.session.userId], (err, internResults) => {
      if (err) {
        console.error('MySQL error:', err);
        return res.status(500).send('Internal Server Error');
      } else if (internResults.length > 0) {
        const programDetailsSql = 'SELECT program_name FROM ojtprograms WHERE administrator_id = 2';
        db.query(programDetailsSql, (programErr, programResults) => {
          if (programErr) {
            console.error('MySQL error:', programErr);
            return res.status(500).send('Internal Server Error');
          } else {
            // New query to fetch feedback for the logged-in intern
            const feedbackSql = `
              SELECT feedback.feedback_text FROM feedback
              JOIN internshiprecords ON feedback.record_id = internshiprecords.record_id
              WHERE internshiprecords.intern_id = ?
            `;
            db.query(feedbackSql, [internResults[0].intern_id], (feedbackErr, feedbackResults) => {
              if (feedbackErr) {
                console.error('MySQL error:', feedbackErr);
                return res.status(500).send('Internal Server Error');
              } else {
                // Pass intern details, program names, and feedback to the EJS template
                res.render('intern_profile', {
                  username: req.session.username,
                  interndetails: internResults[0],
                  programNames: programResults,
                  feedback: feedbackResults
                });
              }
            });
          }
        });
      } else {
        console.log('No intern details found for the user.');
        res.render('intern_profile', {
          username: req.session.username,
          interndetails: {},
          programNames: [],
          feedback: [] // Add this line to handle the case where intern details are not found
        });
      }
    });
  } else {
    console.log('User is not logged in or not an intern.');
    res.redirect('/login');
  }
});


// Work Track route for intern
app.get('/intern_worktrack', (req, res) => {
  if (req.session.userType === 'Intern' && req.session.userId) {
    // Fetch internship records for the logged-in intern from the database
    const sql = `
      SELECT ir.*, ad.firstName AS adviserFirstName, ad.lastName AS adviserLastName
      FROM internshiprecords AS ir
      JOIN adviserdetails AS ad ON ir.adviser_id = ad.adviser_id
      WHERE ir.intern_id = ?
    `;

    db.query(sql, [req.session.userId], (err, results) => {
      if (err) {
        console.error('MySQL error:', err);
        res.status(500).send('Internal Server Error');
      } else {
        // Render the 'intern_worktrack.ejs' template with the data
        res.render('intern_worktrack', {
          username: req.session.username,
          records: results, // Pass the fetched data to the template
        });
      }
    });
  } else {
    // If no username in session or user is not an intern, redirect to login page
    res.redirect('/');
  }
});

app.post('/submit_time_entry', (req, res) => {
  const { date, timeIn, timeOut, hoursRendered } = req.body;
  const internUserId = req.session.userId; // Get the intern's user ID from session

  // First, find the record_id for the logged-in intern
  const recordQuery = `
      SELECT record_id FROM internshiprecords
      WHERE intern_id = (
          SELECT intern_id FROM interndetails WHERE user_id = ?
      );
  `;

  db.query(recordQuery, [internUserId], (err, recordResults) => {
      if (err) {
          console.error('Error querying record_id:', err);
          return res.status(500).send('Error finding your record');
      }

      if (recordResults.length > 0) {
          const record_id = recordResults[0].record_id;

          // Query to insert data into the timetrack table
          const insertQuery = `
              INSERT INTO timetrack (record_id, date, timein, timeout, hours_rendered)
              VALUES (?, ?, ?, ?, ?);
          `;

          db.query(insertQuery, [record_id, date, timeIn, timeOut, hoursRendered], (insertErr, insertResults) => {
              if (insertErr) {
                  console.error('Error inserting into timetrack:', insertErr);
                  return res.status(500).send('Error submitting time entry');
              }
              res.redirect('/intern_worktrack'); // Redirect back to the work track page
          });
      } else {
          res.status(400).send('No internship record found for you');
      }
  });
});


// Documents route for intern
app.get('/intern_documents', (req, res) => {
  if (req.session.username) {
    // Render profile with username
    res.render('intern_documents', { username: req.session.username });
  } else {
    // If no username in session, redirect to login page
    res.redirect('/');
  }
});

// Profile route for adviser
app.get('/adviser_profile', (req, res) => {
  if (req.session.userType === 'Adviser' && req.session.userId) {
    const adviserDetailsSql = 'SELECT * FROM adviserdetails WHERE user_id = ?';
    db.query(adviserDetailsSql, [req.session.userId], (err, adviserResults) => {
      if (err) {
        console.error('MySQL error:', err);
        return res.status(500).send('Internal Server Error');
      } else if (adviserResults.length > 0) {
        const programDetailsSql = 'SELECT program_name FROM ojtprograms WHERE administrator_id = 2';
        db.query(programDetailsSql, (programErr, programResults) => {
          if (programErr) {
            console.error('MySQL error:', programErr);
            return res.status(500).send('Internal Server Error');
          } else {
            // Pass adviser details, program names, and feedback to the EJS template
            res.render('adviser_profile', {
              username: req.session.username,
              adviserdetails: adviserResults[0],
              programNames: programResults,
            });
          }
        });
      } else {
        console.log('No adviser details found for the user.');
        res.render('adviser_profile', {
          username: req.session.username,
          adviserdetails: {},
          programNames: [],
        });
      }
    });
  } else {
    console.log('User is not logged in or not an adviser.');
    res.redirect('/login');
  }
});




// Work Track route for adviser
app.get('/adviser_worktrack', (req, res) => {
  if (req.session.username) {
    // Render profile with username
    res.render('adviser_worktrack', { username: req.session.username });
  } else {
    // If no username in session, redirect to login page
    res.redirect('/');
  }
});

// Documents route for adviser
app.get('/adviser_documents', (req, res) => {
  if (req.session.username) {
    // Render profile with username
    res.render('adviser_documents', { username: req.session.username });
  } else {
    // If no username in session, redirect to login page
    res.redirect('/');
  }
});


// Route to fetch intern details and render them in a table
// app.get('/interndetails', (req, res) => {
//   const sql = 'SELECT * FROM interndetails';

//   db.query(sql, (err, results) => {
//     if (err) {
//       console.error('MySQL error:', err);
//       res.status(500).send('Internal Server Error');
//     } else {
//       // Render the 'interndetails.ejs' template with the data
//       res.render('interndetails', { data: results });
//     }
//   });
// });

// Route to fetch intern details and render them in a table
// app.get('/internshiprecords', (req, res) => {
//   const sql = 'SELECT * FROM internshiprecords';

//   db.query(sql, (err, results) => {
//     if (err) {
//       console.error('MySQL error:', err);
//       res.status(500).send('Internal Server Error');
//     } else {
//       // Render the 'interndetails.ejs' template with the data
//       res.render('internshiprecords', { data: results });
//     }
//   });
// });

// app.get('/feedback', (req, res) => {
//   const sql = 'SELECT * FROM feedback';

//   db.query(sql, (err, results) => {
//     if (err) {
//       console.error('MySQL error:', err);
//       res.status(500).send('Internal Server Error');
//     } else {
//       // Render the 'interndetails.ejs' template with the data
//       res.render('feedback', { data: results });
//     }
//   });
// });


app.listen(port, () => {
  console.log(`Server is running on port ${port}`);
});
