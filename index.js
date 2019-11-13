const express = require('express');
const app = express();
const http = require('http').Server(app);
const io = require('socket.io')(http);

app.get('/', function(req, res) {
    res.send ("Hello")
});

io.sockets.on('connection', function(socket) {
    socket.on('notify', function(round) {

        console.log ('changed notified:' + round);
        io.emit('onChange'+round, 'changed');
        
    });
});

const server = http.listen(8080, function() {
    console.log('listening on *:8080');
});