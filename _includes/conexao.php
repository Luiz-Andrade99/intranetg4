<?php
require_once 'config.php'; // Inclui as configurações

function conectarBanco() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        error_log("Erro de conexão MySQL: " . $conn->connect_error); // REGISTRA O ERRO
        return false; // RETORNA FALSE em caso de erro
    }

    $conn->set_charset("utf8mb4"); // Define o charset
    return $conn;
}
?>