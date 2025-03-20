<?php
session_start();
require_once '../includes/conexao.php';
require_once '../includes/funcoes.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!verificarTokenCSRF($_POST['csrf_token'])) {
        die("Erro de segurança: Token CSRF inválido.");
    }

    $email = $_POST["email"];
    $senha = $_POST["senha"];

    $erros = [];
    if (empty($email) || !validarEmail($email)) {
        $erros[] = "E-mail inválido.";
    }
    if (empty($senha)) {
        $erros[] = "A senha é obrigatória.";
    }

    if (empty($erros)) {
        $conn = conectarBanco();
        if ($conn) {
            $stmt = $conn->prepare("SELECT id, nome, senha FROM usuarios WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $usuario = $result->fetch_assoc();
                // Verifica a senha (usando password_verify)
                if (password_verify($senha, $usuario['senha'])) {
                    // Login bem-sucedido!
                    $_SESSION['usuario_id'] = $usuario['id'];
                    $_SESSION['usuario_nome'] = $usuario['nome'];

                    header("Location: ../index.html"); // Redireciona para a página principal
                    exit();
                } else {
                    $erros[] = "Senha incorreta.";
                }
            } else {
                $erros[] = "Usuário não encontrado.";
            }
            $stmt->close();
            $conn->close();

        } else {
            $erros[] = "Erro ao conectar ao banco de dados.";
        }
    }
    //Se tiver erros
    $_SESSION['erros_login'] = $erros;
    header("Location: login.html");
    exit();

} else {
    header("Location: login.html"); //Redireciona de acesso não for POST
    exit;
}

?>