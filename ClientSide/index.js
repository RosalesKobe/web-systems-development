const express = require('express');
const mysql = require('mysql');
const ejs = require('ejs');
const session = require('express-session');

const app = express();
const port = 3333;

// Database connection setup
const db = mysql.createConnection({
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
        
        if (password === user.password) {
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
          return res.json({ success: false, message: 'Invalid credentials' });
      }
  });
});


// Profile route for intern
app.get('/intern_profile', (req, res) => {
  // Check if the user is logged in and is an intern
  if (req.session.userType === 'Intern' && req.session.userId) {
    const sql = 'SELECT * FROM interndetails WHERE user_id = ?';
    db.query(sql, [req.session.userId], (err, results) => {
      if (err) {
        console.error('MySQL error:', err);
        res.status(500).send('Internal Server Error');
      } else if (results.length > 0) {
        // Pass the intern details to the EJS template
        res.render('intern_profile', {
          username: req.session.username,
          interndetails: results[0]
        });
      } else {
        // Handle the case where no intern details are found
        console.log('No intern details found for the user.');
        res.render('intern_profile', {
          username: req.session.username,
          interndetails: {}
        });
      }
    });
  } else {
    // If the user is not an intern or not logged in, redirect to login page
    console.log('User is not logged in or not an intern.');
    res.redirect('/login');
  }
});


// Work Track route for intern
app.get('/intern_worktrack', (req, res) => {
  if (req.session.username) {
    res.render('intern_worktrack', { username: req.session.username });
  } else {
    res.redirect('/');
  }
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
  if (req.session.username) {
    // Render profile with username
    console.log(req.session.username); // tignan sa terminal para maverify kung na store maayos username na nag login
    res.render('adviser_profile', { username: req.session.username });
  } else {
    // If no username in session, redirect to login page
    res.redirect('/');
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
