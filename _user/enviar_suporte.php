<?php
session_start(); // Inicia a sessão
require_once '../includes/conexao.php'; // Inclui a conexão
require_once '../includes/funcoes.php'; // Inclui funções

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Verifica o token CSRF
    if (!verificarTokenCSRF($_POST['csrf_token'])) {
        die("Erro de segurança: Token CSRF inválido."); // Ou trate de forma mais elegante
    }

    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $anydesk = $_POST["anydesk"];
    $descricao = $_POST["descricao"];

    // Validação (expandida)
    $erros = [];
    if (empty($nome)) {
        $erros[] = "O nome é obrigatório.";
    }
    if (empty($email) || !validarEmail($email)) {
        $erros[] = "E-mail inválido.";
    }
    if (empty($anydesk)) {
        $erros[] = "O AnyDesk é obrigatório.";
    }
    if (empty($descricao)) {
        $erros[] = "A descrição é obrigatória.";
    }

    if (empty($erros)) {
        $conn = conectarBanco();
        if ($conn) {
            // Prepared Statement
            $stmt = $conn->prepare("INSERT INTO chamados (nome, email, anydesk, descricao) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nome, $email, $anydesk, $descricao);

            if ($stmt->execute()) {
                header("Location: ../chamados.html");
                exit();
            } else {
                 error_log("Erro ao inserir chamado: " . $stmt->error); //LOG
                 $erros[] = "Erro ao abrir o chamado. Tente novamente."; //Mensagem amigável
            }
            $stmt->close();
            $conn->close();
        } else {
             $erros[] = "Erro ao conectar ao banco."; //Mensagem amigável
        }
    }

     // Se houver erros, eles serão exibidos no formulário (você precisa adicionar a exibição no HTML)
    $_SESSION['erros'] = $erros;
    $_SESSION['dados_antigos'] = $_POST; // Armazena os dados antigos para preencher o formulário novamente
    header("Location: ../suport.html"); // Redireciona de volta para o formulário
    exit();
} else {
     header("Location: ../suport.html"); //Redirecionar se acesso não for POST
     exit;
}
?>