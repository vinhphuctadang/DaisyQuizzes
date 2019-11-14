const express = require('express');
const app = express();
const http = require('http').Server(app);
const io = require('socket.io')(http);
var events = require('events');
var serverEmitter = new events.EventEmitter();

io.sockets.on('connection', function (socket) {

	//why this? because it have to resolve what socket is
	serverEmitter.on('notify', function (round, time) {
    // this message will be sent to all connected users
    	console.log ("event emitter worked for " + round + ", " + time);
    	socket.emit('onChange'+round, time);
  	});
});


app.get('/notify/:round/:time', function(request, response) {
	var round = request.params ["round"];
	var time = request.params ["time"];
	serverEmitter.emit ('notify', round, time);
	response.send ('notified');
});

const server = http.listen(8080, function() {
    console.log('listening on *:8080');
});