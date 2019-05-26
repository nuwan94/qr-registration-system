var express = require('express');
var app = express();
var http = require('http').Server(app);
var io = require('socket.io')(http);

var mysql = require('mysql');
var con = mysql.createConnection({
	host: "localhost",
	user: "root",
	password: "",
	database: "realhack"
});

con.connect(function (err) {
	if (err) console.log(err);
});

app.use(express.static('public'));

app.get('/', function (req, res) {
	res.sendFile(__dirname + '/index.html');
});

/* Emitting Statistics */

function emitRegCount() {
	sql = "SELECT COUNT(*) AS NUM FROM users WHERE ureg='1'";
	var result = con.query(sql, function (err, count, fields) {
		if (err) console.log(err);
		io.emit('RegCount', count[0].NUM);
	});
}

function emitLunCount() {
	sql = "SELECT COUNT(*) AS NUM FROM users WHERE ulun='1'";
	var result = con.query(sql, function (err, count, fields) {
		if (err) console.log(err);
		io.emit('LunCount', count[0].NUM);
	});
}

function emitDinCount() {
	sql = "SELECT COUNT(*) AS NUM FROM users WHERE udin='1'";
	var result = con.query(sql, function (err, count, fields) {
		if (err) console.log(err);
		io.emit('DinCount', count[0].NUM);
	});
}

function emitBrkCount() {
	sql = "SELECT COUNT(*) AS NUM FROM users WHERE ubrk='1'";
	var result = con.query(sql, function (err, count, fields) {
		if (err) console.log(err);
		io.emit('BrkCount', count[0].NUM);
	});
}

function emitAll() {
	emitRegCount();
	emitLunCount();
	emitDinCount();
	emitBrkCount();
}

function AddtoLog(u, t, m) {
	updateSQL = "INSERT INTO log (uid, tid, activity) VALUES ('" + u + "','" + t + "','" + m + "')";
	con.query(updateSQL, function (err, res) {
		if (err) console.log(err);
	});
}

function checkValidId(id) {
	var tid = id.split("T")[1].split("M")[0];
	var mid = id.split("T")[1].split("M")[1];

	if (parseInt(tid) < 16 && parseInt(tid) > 0 && parseInt(mid) < 5 && parseInt(mid) > 0) {
		return true;
	} else {
		return false;
	}
}


io.on('connection', function (socket) {

	console.log('+ connected.');
	emitAll();

	socket.on('Request', function (response) {

		var tag = response.split('-')[0];
		var id = response.split('-')[1];

		var checkId = checkValidId(id);

		if (id != null && checkId) {
			con.query("SELECT * FROM users WHERE uid = '" + id + "' LIMIT 1", function (err, udetails, fields) {
				con.query("SELECT * FROM team WHERE tid = '" + udetails[0].tid + "'", function (err, tdetails, fields) {

					if (response.startsWith("r-")) {
						if (udetails[0].ureg == 0) {
							var registerSQL = "UPDATE users SET ureg = '1' WHERE uid = '" + id + "'";
							con.query(registerSQL, function (err, res) {});
							AddtoLog(id, udetails[0].tid, udetails[0].udname + " Registerd.");
						} else {
							tag = "re";
							AddtoLog(id, udetails[0].tid, udetails[0].udname + " Error in registration.");
						}
					}

					if (response.startsWith("l-")) {
						if (udetails[0].ulun == 0 && udetails[0].ureg == 1) {
							var dinnerSQL = "UPDATE users SET ulun = '1' WHERE uid = '" + id + "'";
							con.query(dinnerSQL, function (err, res) {
								if (err) console.log(err);
							});
						} else if (udetails[0].ureg == 0) {
							tag = "nr";
							AddtoLog(id, udetails[0].tid, udetails[0].udname + " Not Registered.");
						} else {
							tag = "le";
							AddtoLog(id, udetails[0].tid, udetails[0].udname + " Error in lunch.");
						}
					}

					if (response.startsWith("d-")) {
						if (udetails[0].udin == 0 && udetails[0].ureg == 1) {
							var dinnerSQL = "UPDATE users SET udin = '1' WHERE uid = '" + id + "'";
							con.query(dinnerSQL, function (err, res) {
								if (err) console.log(err);
							});
						} else if (udetails[0].ureg == 0) {
							tag = "nr";
							AddtoLog(id, udetails[0].tid, udetails[0].udname + " Not Registered.");
						} else {
							tag = "de";
							AddtoLog(id, udetails[0].tid, udetails[0].udname + " Error in dinner.");
						}
					}

					if (response.startsWith("b-")) {
						if (udetails[0].ubrk == 0 && udetails[0].ureg == 1) {
							var dinnerSQL = "UPDATE users SET ubrk = '1' WHERE uid = '" + id + "'";
							con.query(dinnerSQL, function (err, res) {
								if (err) console.log(err);
							});
						} else if (udetails[0].ureg == 0) {
							tag = "nr";
							AddtoLog(id, udetails[0].tid, udetails[0].udname + " Not Registered.");
						} else {
							tag = "be";
							AddtoLog(id, udetails[0].tid, udetails[0].udname + " Error in breakfast.");
						}
					}

					if (tag == "c") {
						AddtoLog(id, udetails[0].tid, udetails[0].udname + " Checked.");
					}

					emitAll();
					io.emit('Response', udetails, tdetails, tag);
				});
			});
		} else {
			io.emit('error', response);
		}

	});

	con.on('error', function (err) {
		console.log("[mysql error]", err);
	});

	socket.on('disconnect', function () {
		console.log('- disconnected.');
	});
});

http.listen(3000, function () {
	console.log('listening on *:3000');
});