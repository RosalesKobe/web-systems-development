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
app.use(express.urlencoded({ extended: true }));
app.use(express.urlencoded({ extended: true }));

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
                // Add new query for time track here
                const timeTrackSql = `
                  SELECT date, hours_rendered FROM timetrack
                  WHERE record_id IN (
                    SELECT record_id FROM internshiprecords WHERE intern_id = ?
                  )
                  ORDER BY date ASC
                `;
                db.query(timeTrackSql, [internResults[0].intern_id], (timeTrackErr, timeTrackResults) => {
                  if (timeTrackErr) {
                    console.error('MySQL error:', timeTrackErr);
                    return res.status(500).send('Internal Server Error');
                  } else {
                    // Now pass all the data to the EJS template
                    res.render('intern_profile', {
                      username: req.session.username,
                      interndetails: internResults[0],
                      programNames: programResults,
                      feedback: feedbackResults,
                      timeEntries: timeTrackResults // Add this line for time track data
                    });
                  }
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
          feedback: [],
          timeEntries: [] // Handle the case where time track data is not found
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
    // First, update any records that should be marked as completed
    const updateStatusSql = `
      UPDATE internshiprecords
      SET record_status = 'Completed'
      WHERE intern_id = (
        SELECT intern_id FROM interndetails WHERE user_id = ?
      ) AND (hours_remaining <= 0 AND record_status <> 'Completed')
    `;

    // Execute the update query
    db.query(updateStatusSql, [req.session.userId], (updateErr, updateResults) => {
      if (updateErr) {
        console.error('MySQL error during status update:', updateErr);
        return res.status(500).send('Internal Server Error');
      }
      console.log(`${updateResults.affectedRows} records updated`);
      // Continue with fetching the internship records after the update
      const internshipRecordsSql = `
        SELECT ir.*, ad.firstName AS adviserFirstName, ad.lastName AS adviserLastName
        FROM internshiprecords AS ir
        JOIN adviserdetails AS ad ON ir.adviser_id = ad.adviser_id
        WHERE ir.intern_id = ?
      `;

      // Query to get the timetrack records
      const timeTrackSql = `
        SELECT * FROM timetrack WHERE record_id IN (
          SELECT record_id FROM internshiprecords WHERE intern_id = (
            SELECT intern_id FROM interndetails WHERE user_id = ?
          )
        )
      `;

      // Execute the first query to get the internship records
      db.query(internshipRecordsSql, [req.session.userId], (internshipErr, internshipResults) => {
        if (internshipErr) {
          console.error('MySQL error:', internshipErr);
          return res.status(500).send('Internal Server Error');
        }

        // Execute the second query to get the timetrack records
        db.query(timeTrackSql, [req.session.userId], (timetrackErr, timetrackResults) => {
          if (timetrackErr) {
            console.error('MySQL error:', timetrackErr);
            return res.status(500).send('Internal Server Error');
          }

          // Render the 'intern_worktrack.ejs' template with both sets of data
          res.render('intern_worktrack', {
            username: req.session.username,
            records: internshipResults,
            timeEntries: timetrackResults, // Pass the timetrack data to the template
          });
        });
      });
    });
  } else {
    // If no username in session or user is not an intern, redirect to login page
    res.redirect('/');
  }
});


//Route for adding data to the timetrack table
app.post('/submit_time_entry', (req, res) => {
  const { date, timeIn, timeOut, hoursRendered } = req.body;
  const internUserId = req.session.userId; // Get the intern's user ID from session

  // Begin transaction to ensure data consistency
  db.beginTransaction((transactionErr) => {
    if (transactionErr) {
      console.error('Error starting transaction:', transactionErr);
      return res.status(500).send('Error processing your request');
    }
  
    // First, find the record_id for the logged-in intern
    const recordQuery = `
        SELECT record_id, hours_remaining FROM internshiprecords
        WHERE intern_id = (
            SELECT intern_id FROM interndetails WHERE user_id = ?
        );
    `;

    db.query(recordQuery, [internUserId], (err, recordResults) => {
        if (err) {
            console.error('Error querying record_id:', err);
            db.rollback(() => {
              res.status(500).send('Error finding your record');
            });
            return;
        }

        if (recordResults.length > 0) {
            const record = recordResults[0];
            const record_id = record.record_id;
            const newHoursRemaining = record.hours_remaining - parseFloat(hoursRendered);

            // Query to insert data into the timetrack table
            const insertQuery = `
                INSERT INTO timetrack (record_id, date, timein, timeout, hours_rendered)
                VALUES (?, ?, ?, ?, ?);
            `;

            db.query(insertQuery, [record_id, date, timeIn, timeOut, hoursRendered], (insertErr, insertResults) => {
                if (insertErr) {
                    console.error('Error inserting into timetrack:', insertErr);
                    db.rollback(() => {
                      res.status(500).send('Error submitting time entry');
                    });
                    return;
                }
                
                // Update the hours in the internshiprecords table
                const updateInternshipRecordQuery = `
                  UPDATE internshiprecords
                  SET hours_completed = hours_completed + ?, hours_remaining = ?
                  WHERE record_id = ?;
                `;

                db.query(updateInternshipRecordQuery, [parseFloat(hoursRendered), newHoursRemaining, record_id], (updateErr, updateResults) => {
                  if (updateErr) {
                      console.error('Error updating internship record:', updateErr);
                      db.rollback(() => {
                        res.status(500).send('Error updating internship hours');
                      });
                      return;
                  }

                  // If everything went well, commit the transaction
                  db.commit((commitErr) => {
                    if (commitErr) {
                      console.error('Error committing transaction:', commitErr);
                      db.rollback(() => {
                        res.status(500).send('Error finalizing your request');
                      });
                      return;
                    }
                    res.redirect('/intern_worktrack'); // Redirect back to the work track page
                  });
                });
            });
        } else {
            db.rollback(() => {
              res.status(400).send('No internship record found for you');
            });
        }
    });
  });
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
  if (req.session.userType === 'Adviser' && req.session.userId) {
    // Fetch internship records for all students associated with the logged-in adviser from the database
    const sql = `
    SELECT ir.hours_completed, ir.hours_remaining, ir.start_date, ir.end_date, ir.record_status,
           id.firstName AS internFirstName, id.lastName AS internLastName
    FROM internshiprecords AS ir
    JOIN adviserdetails AS ad ON ir.adviser_id = ad.adviser_id
    JOIN interndetails AS id ON ir.intern_id = id.intern_id
    WHERE ad.adviser_id = 2
    `;

    db.query(sql, [req.session.userId], (err, results) => {
      if (err) {
        console.error('MySQL error:', err);
        res.status(500).send('Internal Server Error');
      } else {
        // Render the 'adviser_worktrack.ejs' template with the data
        res.render('adviser_worktrack', {
          username: req.session.username,
          records: results, // Pass the fetched data to the template
        });
      }
    });
  } else {
    // If no username in session or user is not an adviser, redirect to login page
    res.redirect('/');
  }
});



app.listen(port, () => {
  console.log(`Server is running on port ${port}`);
});
