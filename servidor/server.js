const express = require('express');
const http = require('http');
const socketIo = require('socket.io');
const cors = require('cors'); // Importa el middleware cors

const app = express();
const server = http.createServer(app);
const io = require("socket.io")(server, {
  cors: {
    origin: "https://apexally.test",
    methods: ["GET", "POST"]
  }
});

let connectedUsers = {}; // Object to store connected users with their data

io.on('connection', (socket) => {
  const handshakeData = socket.request;
  const userId = handshakeData._query['id'];
  const userName = handshakeData._query['name'];
  const userAvatar = handshakeData._query['avatar'];

  // Check if user already exists in the connectedUsers object
  if (!connectedUsers[userId]) {
    connectedUsers[userId] = {
      id: userId,
      name: userName,
      avatar: userAvatar,
      connections: [socket.id] // Store socket id in connections array
    };
  } else {
    connectedUsers[userId].connections.push(socket.id);
  }

  console.log('Usuario conectado:', userId, 'con socket ID:', socket.id);

  // Emit the updated user list with data
  io.emit('users', Object.values(connectedUsers).map(user => ({
    id: user.id,
    name: user.name,
    avatar: user.avatar
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
});

const PORT = process.env.PORT || 3000;

server.listen(PORT, () => {
  console.log(`Servidor de Socket.IO escuchando en http://localhost:${PORT}`);
});
