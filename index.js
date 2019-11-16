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

  	serverEmitter.on('finish', function (round) {
    // this message will be sent to all connected users
    	console.log ("event emitter finish round: '"+round+"'");
    	socket.emit('onFinished'+round, "reload");
  	});


    serverEmitter.on('player', function (round, player) {
        console.log ("Player " + player + " notify '"+round+"'");
        socket.emit('onPlayer'+round, player);
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
	serverEmitter.emit ('finish', round);
	response.send ('finish notified');
});

app.get('/player/:round/:player/', function(request, response) { // tuyên bố thay đổi điểm
    var round = request.params ["round"];
    var player = request.params ["player"];

    serverEmitter.emit ('player', round, player);
    response.send ('finish notified');
});

const server = http.listen(8080, function() {
    console.log('listening on *:8080');
});