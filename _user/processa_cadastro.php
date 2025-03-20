<?php
session_start();
require_once '../includes/conexao.php'; // Corrigido o caminho
require_once '../includes/funcoes.php'; // Corrigido o caminho

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Verifica CSRF
    if (!verificarTokenCSRF($_POST['csrf_token'])) {
        die("Erro de segurança: Token CSRF inválido.");
    }

    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $senha = $_POST["senha"];
    $confirmar_senha = $_POST["confirmar_senha"];

    // Validação
    $erros = [];
    if (empty($nome)) {
        $erros[] = "O nome é obrigatório.";
    }
    if (empty($email) || !validarEmail($email)) {
        $erros[] = "E-mail inválido.";
    }
    if (empty($senha)) {
        $erros[] = "A senha é obrigatória.";
    }
    if ($senha !== $confirmar_senha) {
        $erros[] = "As senhas não coincidem.";
    }

    //Verifica se o usuário já existe
    $conn = conectarBanco();
    $stmt_verifica = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt_verifica->bind_param("s", $email);
    $stmt_verifica->execute();
    $stmt_verifica->store_result();

    if ($stmt_verifica->num_rows > 0) {
        $erros[] = "Este e-mail já está cadastrado.";
    }
    $stmt_verifica->close();

    if (empty($erros)) {

        // Hash da senha (CRÍTICO!)
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        // Inserção no banco de dados (COM PREPARED STATEMENT)
        $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nome, $email, $senha_hash);

        if ($stmt->execute()) {
            // Cadastro bem-sucedido, redireciona para o login
            header("Location: login.html");
            exit();
        } else {
            // Erro ao inserir (log em produção, mensagem genérica para o usuário)
            error_log("Erro ao cadastrar usuário: " . $stmt->error);
            $erros[] = "Erro ao cadastrar. Tente novamente.";
        }

        $stmt->close();
        $conn->close();

    }  // <-- FECHAMENTO DO if (empty($erros))

    // Se chegou aqui, é porque houve erros de validação ou do banco
    $_SESSION['erros_cadastro'] = $erros;
    $_SESSION['dados_antigos_cadastro'] = $_POST; // Salva os dados
    header("Location: cadastro.html");
    exit();

} else { // <-- FECHAMENTO DO if ($_SERVER["REQUEST_METHOD"] == "POST")
    // Acesso não-POST, redireciona
    header("Location: cadastro.html");
    exit();
} // <-- FECHAMENTO DO else

?>