<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Função para escapar dados para evitar XSS e injeção de SQL (versão básica)
function escape($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recebe os dados do formulário e aplica a função de escape
    $nome = isset($_POST["nome"]) ? escape($_POST["nome"]) : '';
    $email = isset($_POST["email"]) ? escape($_POST["email"]) : '';
    $anydesk = isset($_POST["anydesk"]) ? escape($_POST["anydesk"]) : '';
    $problema = isset($_POST["descricao"]) ? escape($_POST["descricao"]) : '';

    // Validação básica (pode e deve ser expandida)
    if (empty($nome) || empty($email) || empty($anydesk) || empty($problema)) {
        echo "Erro: Todos os campos são obrigatórios.";
        exit; // Interrompe a execução se houver campos vazios
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Erro: Endereço de e-mail inválido.";
        exit;
    }

    // --- CONEXÃO COM O BANCO DE DADOS (MySQLi - Orientado a Objetos) ---
    $servername = "localhost";
    $username = "seu_usuario"; // SUBSTITUA PELO SEU USUÁRIO
    $password = "sua_senha";    // SUBSTITUA PELA SUA SENHA
    $dbname = "seu_banco_de_dados";    // SUBSTITUA PELO NOME DO SEU BANCO

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error); // Em produção, trate o erro melhor
    }

    // --- PREPARED STATEMENT (para evitar injeção de SQL) ---
    $stmt = $conn->prepare("INSERT INTO chamados (nome, email, anydesk, descricao) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nome, $email, $anydesk, $problema); // "ssss" indica 4 strings

    if ($stmt->execute()) {
        // --- SUCESSO: Redireciona para chamados.html ---
        header("Location: ../chamados.html"); // Caminho relativo correto
        exit();
    } else {
        echo "Erro ao abrir o chamado: " . $stmt->error;  // Em produção, trate o erro de forma mais elegante
    }

    $stmt->close();
    $conn->close();

} else {
    echo "Acesso inválido."; // Se a página for acessada diretamente (não via POST)
}
?>