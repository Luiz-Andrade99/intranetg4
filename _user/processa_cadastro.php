<?php
// Configuração do banco de dados
$servername = "localhost"; // Altere conforme seu ambiente
$username = "root"; // Usuário do banco
$password = ""; // Senha do banco (deixe vazio se for XAMPP)
$dbname = "intranetg4"; // Nome do banco de dados

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Receber dados do formulário
$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Criptografar senha

// Preparar e executar a inserção no banco
$stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $nome, $email, $senha);

if ($stmt->execute()) {
    echo "Usuário cadastrado com sucesso!";
} else {
    echo "Erro ao cadastrar usuário: " . $stmt->error;
}

// Fechar conexão
$stmt->close();
$conn->close();
?>
