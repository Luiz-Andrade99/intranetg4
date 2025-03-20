<?php
session_start(); // Inicia a sessão *aqui* para que esteja disponível em todas as páginas

// Configurações do banco de dados
define('DB_HOST', 'localhost');
define('DB_USER', 'seu_usuario'); // SUBSTITUA PELO SEU USUÁRIO
define('DB_PASS', 'sua_senha');    // SUBSTITUA PELA SUA SENHA
define('DB_NAME', 'seu_banco_de_dados'); // SUBSTITUA PELO NOME DO BANCO

// URL base do seu site (opcional, mas útil)
define('SITE_URL', 'http://localhost/intranetg4');

// Ativa o error reporting para desenvolvimento (desative em produção)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configurações de timezone (opcional, mas recomendado)
date_default_timezone_set('America/Sao_Paulo'); // Ajuste para o seu fuso horário

?>