const express = require('express');
const nodemailer = require('nodemailer');
const bodyParser = require('body-parser');

const app = express();
const port = 3000;

// Configurar o Express para lidar com JSON
app.use(bodyParser.json());

// Configurar o transporte de e-mail (usando SMTP do Gmail, por exemplo)
const transporter = nodemailer.createTransport({
    service: 'gmail',
    auth: {
        user: 'seu-email@gmail.com',
        pass: 'sua-senha',
    }
});

// Endpoint para enviar o e-mail
app.post('/send-email', (req, res) => {
    const { nome, email, descricao, destinatario, assunto } = req.body;

    const mailOptions = {
        from: email,
        to: destinatario,
        subject: assunto,
        text: `
            Nome: ${nome}
            E-mail: ${email}
            Descrição do Problema:
            ${descricao}
        `
    };

    transporter.sendMail(mailOptions, (error, info) => {
        if (error) {
            console.log(error);
            return res.status(500).send('Erro ao enviar e-mail');
        }
        console.log('Email enviado: ' + info.response);
        return res.status(200).json({ message: 'E-mail enviado com sucesso!' });
    });
});

app.listen(port, () => {
    console.log(`Servidor rodando na porta ${port}`);
});
