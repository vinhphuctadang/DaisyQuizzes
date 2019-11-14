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

  	serverEmitter.on('finish', function (round, time) {
    // this message will be sent to all connected users
    	console.log ("event emitter finish round");
    	socket.emit('onFinished'+round);
  	});
});


app.get('/notify/:round/:time', function(request, response) {
	var round = request.params ["round"];
	var time = request.params ["time"];
	serverEmitter.emit ('notify', round, time);
	response.send ('notified');
});

app.get('/finish/:round', function(request, response) { // tuyên bố kết thúc màn chơi
	var round = request.params ["round"];
	var time = request.params ["time"];
	serverEmitter.emit ('finish', round, time);
	response.send ('finish notified');
});

const server = http.listen(8080, function() {
    console.log('listening on *:8080');
});