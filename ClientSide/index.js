const express = require('express');
const mysql = require('mysql');
const ejs = require('ejs');

const app = express();
const port = 3333;

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
app.use(express.static('public'));
app.use(express.json()); // to parse JSON bodies

// Route to render the index page
app.get('/', (req, res) => {
  res.render('index', { title: 'Client Login Page' }); // You can pass any necessary data to your EJS template here
});

// Login route
app.post('/login', (req, res) => {
  const { username, password, userType } = req.body;
  
  const query = 'SELECT * FROM users WHERE username = ? AND user_type = ?';
  
  db.query(query, [username, userType], (err, results) => {
      if (err) {
          // Handle error
          return res.status(500).json({ success: false, message: 'Internal Server Error' });
      }
      
      if (results.length > 0) {
          const user = results[0];
          
          // Since we're not using hashed passwords, compare directly
          if (password === user.password) {
              // Correct password
              console.log("Login success");
              return res.json({ success: true, redirectUrl: '/dashboard' });
          } else {
              // Incorrect password
              return res.json({ success: false, message: 'Invalid credentials' });
          }
      } else {
          // No user found with the username and user type
          return res.json({ success: false, message: 'Invalid credentials' });
      }
  });
});




// Route to fetch intern details and render them in a table
app.get('/interndetails', (req, res) => {
  const sql = 'SELECT * FROM interndetails';

  db.query(sql, (err, results) => {
    if (err) {
      console.error('MySQL error:', err);
      res.status(500).send('Internal Server Error');
    } else {
      // Render the 'interndetails.ejs' template with the data
      res.render('interndetails', { data: results });
    }
  });
});


// Route to fetch intern details and render them in a table
app.get('/internshiprecords', (req, res) => {
  const sql = 'SELECT * FROM internshiprecords';

  db.query(sql, (err, results) => {
    if (err) {
      console.error('MySQL error:', err);
      res.status(500).send('Internal Server Error');
    } else {
      // Render the 'interndetails.ejs' template with the data
      res.render('internshiprecords', { data: results });
    }
  });
});

app.get('/feedback', (req, res) => {
  const sql = 'SELECT * FROM feedback';

  db.query(sql, (err, results) => {
    if (err) {
      console.error('MySQL error:', err);
      res.status(500).send('Internal Server Error');
    } else {
      // Render the 'interndetails.ejs' template with the data
      res.render('feedback', { data: results });
    }
  });
});
// Similar routes for other tables...

app.listen(port, () => {
  console.log(`Server is running on port ${port}`);
});
