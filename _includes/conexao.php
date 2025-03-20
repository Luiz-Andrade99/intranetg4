<?php
require_once 'config.php'; // Inclui as configurações

function conectarBanco() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        // Em produção, registre o erro em um log e retorne false.  NÃO exiba o erro diretamente.
        error_log("Erro de conexão: " . $conn->connect_error);
        return false;
    }

    $conn->set_charset("utf8mb4"); // Define o charset para UTF-8 (boa prática)
    return $conn;
}

?>