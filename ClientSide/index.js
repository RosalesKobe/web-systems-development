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
              console.log("Login success");
              return res.json({ success: true, redirectUrl: '/profile' });
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

// Dashboard route
app.get('/profile', (req, res) => {
  if (req.session.username) {
    console.log(req.session.username); // tignan sa terminal para maverify kung na store maayos username na nag login
    res.render('profile', { username: req.session.username });
  } else {
    res.redirect('/');
  }
});

app.get('/worktrack', (req, res) => {
  if (req.session.username) {
    // Render profile with username
    res.render('worktrack', { username: req.session.username });
  } else {
    // If no username in session, redirect to login page
    res.redirect('/');
  }
});
app.get('/documents', (req, res) => {
  if (req.session.username) {
    // Render profile with username
    res.render('documents', { username: req.session.username });
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
