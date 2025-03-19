from fastapi import FastAPI, HTTPException, Depends
from pydantic import BaseModel
import sqlite3
import bcrypt

app = FastAPI()

# Modelo de usuário
class User(BaseModel):
    nome: str
    email: str
    senha: str

# Função para conectar ao banco
def get_db():
    conn = sqlite3.connect("usuarios.db")
    return conn, conn.cursor()

# Rota de cadastro
@app.post("/cadastro/")
def cadastro(user: User):
    conn, cursor = get_db()

    # Hash da senha
    senha_hash = bcrypt.hashpw(user.senha.encode(), bcrypt.gensalt()).decode()

    try:
        cursor.execute("INSERT INTO usuarios (nome, email, senha_hash) VALUES (?, ?, ?)",
                       (user.nome, user.email, senha_hash))
        conn.commit()
    except:
        raise HTTPException(status_code=400, detail="E-mail já cadastrado")

    return {"mensagem": "Usuário cadastrado com sucesso!"}
