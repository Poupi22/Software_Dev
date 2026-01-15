const nodemailer = require('nodemailer');
require('dotenv').config();

const transporter = nodemailer.createTransport({
  host: process.env.SMTP_HOST,
  port: parseInt(process.env.SMTP_PORT),
  secure: false,
  auth: {
    user: process.env.SMTP_USER,
    pass: process.env.SMTP_PASSWORD,
  },
});

const sendEmail = async (to, subject, html) => {
  try {
    const info = await transporter.sendMail({
      from: `"PharmaCare" <${process.env.EMAIL_FROM}>`,
      to,
      subject,
      html,
    });
    console.log('Email sent:', info.messageId);
    return info;
  } catch (error) {
    console.error('Email error:', error);
    throw error;
  }
};

const sendAdminCredentialsEmail = async (email, password) => {
  const html = `
    <!DOCTYPE html>
    <html>
    <head>
      <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #2c3e50; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .credentials { background: #e8f4f8; padding: 15px; margin: 20px 0; border-left: 4px solid #3498db; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #777; }
        .btn { display: inline-block; padding: 10px 20px; background: #3498db; color: white; text-decoration: none; border-radius: 5px; }
      </style>
    </head>
    <body>
      <div class="container">
        <div class="header">
          <h1>🏥 PharmaCare</h1>
          <p>Système de Gestion de Stock</p>
        </div>
        <div class="content">
          <h2>Bienvenue sur PharmaCare !</h2>
          <p>Votre compte administrateur a été créé avec succès. Voici vos identifiants de connexion :</p>
          
          <div class="credentials">
            <p><strong>📧 Email :</strong> ${email}</p>
            <p><strong>🔑 Mot de passe :</strong> ${password}</p>
          </div>
          
          <p><strong>⚠️ Important :</strong> Pour des raisons de sécurité, nous vous recommandons de changer votre mot de passe après votre première connexion.</p>
          
          <p style="text-align: center;">
            <a href="http://localhost:3000/login" class="btn">Se connecter</a>
          </p>
          
          <p><strong>📱 Application Mobile :</strong> Téléchargez notre application pour gérer votre pharmacie depuis votre smartphone.</p>
        </div>
        <div class="footer">
          <p>Cet email a été envoyé automatiquement. Merci de ne pas y répondre.</p>
          <p>&copy; 2024 PharmaCare - Tous droits réservés</p>
        </div>
      </div>
    </body>
    </html>
  `;
  return sendEmail(email, 'Bienvenue sur PharmaCare - Vos identifiants de connexion', html);
};

const sendGerantCredentialsEmail = async (email, password, nom) => {
  const html = `
    <!DOCTYPE html>
    <html>
    <head>
      <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #27ae60; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .credentials { background: #e8f4f8; padding: 15px; margin: 20px 0; border-left: 4px solid #27ae60; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #777; }
        .btn { display: inline-block; padding: 10px 20px; background: #27ae60; color: white; text-decoration: none; border-radius: 5px; }
      </style>
    </head>
    <body>
      <div class="container">
        <div class="header">
          <h1>🏥 PharmaCare</h1>
          <p>Système de Gestion de Stock</p>
        </div>
        <div class="content">
          <h2>Bienvenue ${nom} !</h2>
          <p>Votre compte Gérant a été créé avec succès par l'administrateur.</p>
          <p>Voici vos identifiants de connexion :</p>
          
          <div class="credentials">
            <p><strong>📧 Email :</strong> ${email}</p>
            <p><strong>🔑 Mot de passe :</strong> ${password}</p>
          </div>
          
          <p><strong>⚠️ Important :</strong> Pour des raisons de sécurité, nous vous recommandons de changer votre mot de passe après votre première connexion.</p>
          
          <p style="text-align: center;">
            <a href="http://localhost:3000/login" class="btn">Se connecter</a>
          </p>
          
          <p><strong>📱 Application Mobile :</strong> Téléchargez notre application pour gérer la pharmacie depuis votre smartphone.</p>
        </div>
        <div class="footer">
          <p>Cet email a été envoyé automatiquement. Merci de ne pas y répondre.</p>
          <p>&copy; 2024 PharmaCare - Tous droits réservés</p>
        </div>
      </div>
    </body>
    </html>
  `;
  return sendEmail(email, 'Bienvenue sur PharmaCare - Vos identifiants Gérant', html);
};

module.exports = { sendEmail, sendAdminCredentialsEmail, sendGerantCredentialsEmail };