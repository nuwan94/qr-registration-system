var mysql = require('mysql');
var con = mysql.createConnection({
	host: "localhost",
	user: "root",
	password: "",
	database: "realhack"
});

con.connect(function (err) {
	if (err) throw err;
});


