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

app.get('/intern', (req, res) => {

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
