const sendButton = document.getElementById('send-btn');
const messageInput = document.getElementById('message-input');
const messageContainer = document.getElementById('message-container');
const setUsernameButton = document.getElementById('set-username-btn');
const userInput = document.getElementById('username');
const roomSelect = document.getElementById('room');
const enterRoomButton = document.getElementById('enter-room-btn');
const userSection = document.getElementById('user-section');
const roomSection = document.getElementById('room-section');
const chatName = document.getElementById('chat-name');

let username = localStorage.getItem('username') || null;
let currentRoom = null;

// Exibe mensagens no chat
function displayMessage(message, sender) {
    const messageElement = document.createElement('p');
    messageElement.textContent = `${sender}: ${message}`;
    messageContainer.appendChild(messageElement);
    messageContainer.scrollTop = messageContainer.scrollHeight; // Rola para a última mensagem
}

// Salvar e carregar mensagens antigas
window.onload = function() {
    if (username) {
        userSection.style.display = 'none';
        roomSection.style.display = 'block';
    }

    const savedMessages = JSON.parse(localStorage.getItem('chatMessages')) || [];
    savedMessages.forEach(msg => {
        if (msg.room === currentRoom) {
            displayMessage(msg.message, msg.sender);
        }
    });
};

// Definir nome de usuário
setUsernameButton.addEventListener('click', function() {
    const user = userInput.value.trim();
    if (user) {
        username = user;
        localStorage.setItem('username', username);
        userSection.style.display = 'none';
        roomSection.style.display = 'block';
    } else {
        alert('Digite um nome válido!');
    }
});

// Entrar na sala de chat
enterRoomButton.addEventListener('click', function() {
    currentRoom = roomSelect.value;
    chatName.textContent = `Sala: ${currentRoom}`;
    roomSection.style.display = 'none';
    messageContainer.style.display = 'block';
});

// Função para enviar a mensagem
sendButton.addEventListener('click', function() {
    const message = messageInput.value.trim();
    if (message && username && currentRoom) {
        const messageObj = { message: message, sender: username, room: currentRoom };
        displayMessage(message, username);
        messageInput.value = ''; // Limpa o campo de entrada

        // Salva mensagem no localStorage
        const allMessages = JSON.parse(localStorage.getItem('chatMessages')) || [];
        allMessages.push(messageObj);
        localStorage.setItem('chatMessages', JSON.stringify(allMessages));
    } else if (!username) {
        alert('Por favor, insira seu nome!');
    } else if (!currentRoom) {
        alert('Por favor, entre em uma sala!');
    }
});
