<?php
// Conexão com o banco de dados
$host = "localhost"; // Altere se necessário
$usuario = "root"; // Usuário do banco de dados
$senha = ""; // Senha do banco de dados
$banco = "usuarios"; // Nome do banco de dados

$conn = new mysqli($host, $usuario, $senha, $banco);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Captura os dados do formulário
$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

// Validação simples
if (empty($nome) || empty($email) || empty($senha)) {
    echo "Todos os campos são obrigatórios!";
    exit;
}

// Hash da senha para segurança
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

// Comando SQL para inserir o usuário
$sql = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $nome, $email, $senha_hash);

if ($stmt->execute()) {
    echo "Usuário cadastrado com sucesso!";
    header("Location: login.html"); // Redireciona para a página de login
    exit;
} else {
    echo "Erro ao cadastrar usuário: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
