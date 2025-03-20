<?php

// Função para escapar dados (evita XSS)
function escape($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// Função de validação de e-mail
function validarEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Função para gerar um token CSRF
function gerarTokenCSRF() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Função para verificar o token CSRF
function verificarTokenCSRF($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

//Funções de validação do cadastro e suporte
function validarDadosCadastro($nome, $email, $senha, $confirmar_senha){
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
    if (!validarForcaSenha($senha)) {
      $erros[] = "A senha deve ter pelo menos 8 caracteres, incluindo números, letras maiúsculas e minúsculas.";
    }

    return $erros;
}

function validarDadosSuporte($nome, $email, $anydesk, $descricao){
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

  return $erros;
}
function validarForcaSenha($senha) {
  if (strlen($senha) < 8) {
      return false; // Muito curta
  }
  if (!preg_match("#[0-9]+#", $senha)) {
      return false; // Não tem números
  }
  if (!preg_match("#[a-z]+#", $senha)) {
      return false; // Não tem letras minúsculas
  }
  if (!preg_match("#[A-Z]+#", $senha)) {
      return false; // Não tem letras maiúsculas
  }
  // if (!preg_match("#\W+#", $senha)) { // Opcional:  Não tem caracteres especiais
  //     return false;
  // }
  return true;
}


// Função para exibir mensagens de erro/sucesso (uso geral)
function exibirMensagens() {
    if (isset($_SESSION['erros']) && !empty($_SESSION['erros'])) {
        echo '<div class="erros">';
        foreach ($_SESSION['erros'] as $erro) {
            echo '<p>' . htmlspecialchars($erro) . '</p>';
        }
        echo '</div>';
        unset($_SESSION['erros']); // Limpa os erros
    }

    if (isset($_SESSION['mensagem_sucesso']) && !empty($_SESSION['mensagem_sucesso'])) {
        echo '<div class="sucesso">';
        echo '<p>' . htmlspecialchars($_SESSION['mensagem_sucesso']) . '</p>';
        echo '</div>';
        unset($_SESSION['mensagem_sucesso']); // Limpa a mensagem de sucesso
    }
}
?>