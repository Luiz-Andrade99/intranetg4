CREATE TABLE usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    senha_hash VARCHAR(255) NOT NULL,
    role_id INT NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

CREATE TABLE roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(50) UNIQUE NOT NULL
);

CREATE TABLE permissoes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) UNIQUE NOT NULL
);

CREATE TABLE role_permissoes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    role_id INT,
    permissao_id INT,
    FOREIGN KEY (role_id) REFERENCES roles(id),
    FOREIGN KEY (permissao_id) REFERENCES permissoes(id)
);

CREATE TABLE log_acessos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT,
    data_login TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip VARCHAR(50),
    sucesso BOOLEAN,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

INSERT INTO roles (nome) VALUES 
('Administrador'), 
('Suporte'), 
('Usuário');

INSERT INTO permissoes (nome) VALUES 
('Gerenciar Usuários'),
('Abrir Chamados'),
('Visualizar Chamados'),
('Responder Mensagens no Chat'),
('Gerenciar Configurações');

-- O Administrador tem todas as permissões
INSERT INTO role_permissoes (role_id, permissao_id) 
SELECT 1, id FROM permissoes;

-- O Suporte pode abrir chamados e visualizar chamados
INSERT INTO role_permissoes (role_id, permissao_id) 
VALUES 
(2, 2),  -- Abrir Chamados
(2, 3),  -- Visualizar Chamados
(2, 4);  -- Responder Mensagens no Chat

-- O Usuário pode apenas abrir chamados e usar o chat
INSERT INTO role_permissoes (role_id, permissao_id) 
VALUES 
(3, 2),  -- Abrir Chamados
(3, 4);  -- Responder Mensagens no Chat

SELECT p.nome 
FROM usuarios u
JOIN roles r ON u.role_id = r.id
JOIN role_permissoes rp ON r.id = rp.role_id
JOIN permissoes p ON rp.permissao_id = p.id
WHERE u.id = ?;
