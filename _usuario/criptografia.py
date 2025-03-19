import bcrypt

senha = "minhaSenha123"
salt = bcrypt.gensalt()
senha_hash = bcrypt.hashpw(senha.encode(), salt)

print(senha_hash.decode())  # Salve isso no banco de dados
