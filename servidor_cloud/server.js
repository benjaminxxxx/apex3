const express = require('express');
const http = require('http');
const socketIo = require('socket.io');
const cors = require('cors'); // Importa el middleware cors

const app = express();
const server = http.createServer(app);
const io = require("socket.io")(server, {
  cors: {
    origin: "https://apexally.es",
    methods: ["GET", "POST"]
  }
});

let connectedUsers = {};
let userSockets = {};

io.on('connection', (socket) => {
  const handshakeData = socket.request;
  const userId = handshakeData._query['user'];

  const userId2 = socket.handshake.query.user;
  userSockets[userId2] = socket.id;

  // Check if user already exists in the connectedUsers object
  if (!connectedUsers[userId]) {
    connectedUsers[userId] = {
      id: userId,
      connections: [socket.id] // Store socket id in connections array
    };
  } else {
    connectedUsers[userId].connections.push(socket.id);
  }

  console.log('Usuario conectado:', userId, 'con socket ID:', socket.id);

  // Emit the updated user list with data
  io.emit('users', Object.values(connectedUsers).map(user => ({
    id: user.id
  })));

  socket.on('disconnect', () => {
    console.log('Usuario desconectado:', socket.id);

    // Remove the socket id from the user's connections
    connectedUsers[userId].connections = connectedUsers[userId].connections.filter(id => id !== socket.id);

    // If no more connections are left for this user, remove the user
    if (connectedUsers[userId].connections.length === 0) {
      delete connectedUsers[userId];
    }

    // Emit the updated user list after disconnection
    io.emit('users', Object.values(connectedUsers).map(user => ({
      id: user.id,
      name: user.name,
      avatar: user.avatar
    })));
  });
  socket.on('getConnectedUsers', () => {

    io.emit('users', Object.values(connectedUsers).map(user => ({
      id: user.id,
      name: user.name,
      avatar: user.avatar
    })));
  });


  socket.on('newMessage', data => {
    console.log(data.userId);
    const recipientSocketId = userSockets[data.userId];
    if (recipientSocketId) {
      io.to(recipientSocketId).emit('receiveMessage', data);
    }
  });
});

const PORT = process.env.PORT || 3000;

server.listen(PORT, () => {
  console.log(`Servidor de Socket.IO escuchando en ...:${PORT}`);
});
