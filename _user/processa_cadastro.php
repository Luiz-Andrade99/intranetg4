<?php
require_once '../_includes/conexao.php';
require_once '../_includes/funcoes.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!verificarTokenCSRF($_POST['csrf_token'])) {
        die("Erro de segurança: Token CSRF inválido.");
    }

    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $senha = $_POST["senha"];
    $confirmar_senha = $_POST["confirmar_senha"];

    $erros = validarDadosCadastro($nome, $email, $senha, $confirmar_senha);

    $conn = conectarBanco(); //Conecta

    if ($conn) { // Verifica se a conexão foi bem-sucedida
        $stmt_verifica = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt_verifica->bind_param("s", $email);
        $stmt_verifica->execute();
        $stmt_verifica->store_result(); // Armazena o resultado

        if ($stmt_verifica->num_rows > 0) {
            $erros[] = "Este e-mail já está cadastrado.";
        }

        $stmt_verifica->close(); // Fecha o statement

        if (empty($erros)) {
            // Se não houver erros de validação NEM e-mail duplicado, insere o usuário
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT); // Criptografa a senha

            $stmt_inserir = $conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
            $stmt_inserir->bind_param("sss", $nome, $email, $senha_hash);

            if ($stmt_inserir->execute()) {
                header("Location: login.html"); // Redireciona para o login
                exit(); // IMPORTANTE!
            } else {
                error_log("Erro ao cadastrar usuário: " . $stmt_inserir->error);
                $erros[] = "Erro ao cadastrar. Tente novamente.";
            }
            $stmt_inserir->close(); //Fecha o statement
        }
        $conn->close(); // Fecha a conexão em *todos* os casos
    } else {
        // Trata o erro de conexão
        $erros[] = "Erro ao conectar ao banco de dados. Tente novamente."; // Mensagem genérica
    }

    // Se chegou aqui, é porque houve erros de validação, de conexão, ou de inserção.
    if(!empty($erros)){
        $_SESSION['erros_cadastro'] = $erros;
        $_SESSION['dados_antigos_cadastro'] = $_POST; // Armazena
    }
    header("Location: cadastro.html");
    exit();

} else {
    header("Location: cadastro.html");
    exit();
}
?>