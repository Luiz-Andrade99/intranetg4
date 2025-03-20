<?php
require_once '../includes/conexao.php';
require_once '../includes/funcoes.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!verificarTokenCSRF($_POST['csrf_token'])) {
        die("Erro de segurança: Token CSRF inválido.");
    }

    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $anydesk = $_POST["anydesk"];
    $descricao = $_POST["descricao"];


    $erros = validarDadosSuporte($nome, $email, $anydesk, $descricao); // Usa a função

    if (empty($erros)) {
        $conn = conectarBanco();
        if ($conn) {
            $stmt = $conn->prepare("INSERT INTO chamados (nome, email, anydesk, descricao) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nome, $email, $anydesk, $descricao);

            if ($stmt->execute()) {
                $_SESSION['mensagem_sucesso'] = "Chamado aberto com sucesso!"; //Mensagem de sucesso
                header("Location: ../chamados.html");
                exit(); // IMPORTANTE
            } else {
                error_log("Erro ao inserir chamado: " . $stmt->error);
                $erros[] = "Erro ao abrir o chamado. Tente novamente.";
            }

            $stmt->close();
            $conn->close();
        } else {
            $erros[] = "Erro ao conectar ao banco de dados.";
        }
    }

    $_SESSION['erros'] = $erros;
    $_SESSION['dados_antigos'] = $_POST; // Armazena os dados antigos
    header("Location: ../suport.html");
    exit(); // IMPORTANTE

} else {
    header("Location: ../suport.html");
    exit(); // IMPORTANTE
}
?>