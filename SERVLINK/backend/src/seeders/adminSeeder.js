const pool = require('../config/database');
const nodemailer = require('nodemailer');
require('dotenv').config();

// Configuration email
const transporter = nodemailer.createTransport({
  host: process.env.SMTP_HOST,
  port: process.env.SMTP_PORT,
  secure: false,
  auth: {
    user: process.env.SMTP_USER,
    pass: process.env.SMTP_PASSWORD,
  },
});

async function sendAdminEmail(email, password, name) {
  const mailOptions = {
    from: process.env.EMAIL_FROM,
    to: email,
    subject: 'SERVLINK - Vos identifiants administrateur',
    html: `
      <div style="font-family: Arial, sans-serif; max-width: 600px;">
        <h2 style="color: #2563eb;">Bienvenue sur SERVLINK</h2>
        <p>Bonjour <strong>${name}</strong>,</p>
        <p>Votre compte administrateur a été créé avec succès.</p>
        <div style="background: #f3f4f6; padding: 15px; border-radius: 5px; margin: 20px 0;">
          <p><strong>Email :</strong> ${email}</p>
          <p><strong>Mot de passe :</strong> ${password}</p>
        </div>
        <p>Merci de changer votre mot de passe lors de votre première connexion.</p>
        <hr>
        <p style="color: #6b7280; font-size: 12px;">© 2025 SERVLINK - Tous droits réservés</p>
      </div>
    `
  };
  
  return transporter.sendMail(mailOptions);
}

async function seedAdmin() {
  const adminEmail = process.env.ADMIN_EMAIL;
  const adminPassword = process.env.ADMIN_PASSWORD;
  const adminName = process.env.ADMIN_NAME;
  
  // Vérifier si admin existe déjà
  const existingAdmin = await pool.query(
    `SELECT p.id FROM profiles p 
     JOIN user_roles r ON p.id = r.user_id 
     WHERE r.role = 'admin' AND p.email = $1`,
    [adminEmail]
  );
  
  if (existingAdmin.rows.length > 0) {
    console.log('✅ Admin existe déjà');
    return;
  }
  
  // Créer l'admin
  const newAdmin = await pool.query(
    `INSERT INTO profiles (full_name, email) 
     VALUES ($1, $2) RETURNING id`,
    [adminName, adminEmail]
  );
  
  await pool.query(
    `INSERT INTO user_roles (user_id, role) VALUES ($1, 'admin')`,
    [newAdmin.rows[0].id]
  );
  
  // Envoyer l'email
  try {
    await sendAdminEmail(adminEmail, adminPassword, adminName);
    console.log(`✅ Email envoyé à ${adminEmail}`);
  } catch (error) {
    console.error(`❌ Erreur d'envoi d'email: ${error.message}`);
  }
  
  console.log(`✅ Admin créé avec succès !`);
  console.log(`📧 Email: ${adminEmail}`);
  console.log(`🔑 Mot de passe: ${adminPassword}`);
}

module.exports = seedAdmin;