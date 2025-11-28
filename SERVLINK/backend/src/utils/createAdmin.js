const pool = require('../config/database');
const bcrypt = require('bcryptjs');
const nodemailer = require('nodemailer');
require('dotenv').config();

// Configuration email avec les variables d'environnement
const transporter = nodemailer.createTransport({
  host: process.env.SMTP_HOST,
  port: process.env.SMTP_PORT,
  secure: false, // false pour le port 587
  auth: {
    user: process.env.SMTP_USER,
    pass: process.env.SMTP_PASSWORD,
  },
});

// Vérifier la configuration email
transporter.verify((error, success) => {
  if (error) {
    console.error('❌ Configuration email invalide:', error);
  } else {
    console.log('✅ Configuration email valide');
  }
});

async function sendEmail(to, password) {
  const mailOptions = {
    from: process.env.EMAIL_FROM,
    to: to,
    subject: 'Bienvenue sur SERVLINK - Vos identifiants Admin',
    html: `
      <div style="font-family: Arial, sans-serif; max-width: 600px;">
        <h2 style="color: #2563eb;">Bienvenue sur SERVLINK!</h2>
        <p>Votre compte administrateur a été créé avec succès.</p>
        <div style="background: #f3f4f6; padding: 15px; border-radius: 5px;">
          <p><strong>Email:</strong> ${to}</p>
          <p><strong>Mot de passe:</strong> ${password}</p>
        </div>
        <p style="color: #6b7280; margin-top: 20px;">
          <strong>Important:</strong> Merci de changer votre mot de passe lors de votre première connexion.
        </p>
        <hr>
        <p style="color: #6b7280; font-size: 12px;">© 2024 SERVLINK - Tous droits réservés</p>
      </div>
    `
  };
  
  return transporter.sendMail(mailOptions);
}

function generatePassword() {
  const length = 12;
  const charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
  let password = '';
  for (let i = 0; i < length; i++) {
    password += charset.charAt(Math.floor(Math.random() * charset.length));
  }
  return password;
}

async function createAdmin() {
  try {
    console.log('🚀 Création de l\'administrateur...\n');
    
    const adminEmail = process.env.ADMIN_EMAIL;
    const adminName = process.env.ADMIN_NAME;
    const plainPassword = generatePassword();
    const hashedPassword = await bcrypt.hash(plainPassword, 10);
    
    console.log(`📧 Email admin: ${adminEmail}`);
    console.log(`👤 Nom admin: ${adminName}`);
    console.log(`🔑 Mot de passe généré: ${plainPassword}\n`);
    
    // Vérifier si l'admin existe déjà
    const existingUser = await pool.query(
      'SELECT id, email FROM profiles WHERE email = $1',
      [adminEmail]
    );
    
    let userId;
    
    if (existingUser.rows.length === 0) {
      // Créer le profil
      const newUser = await pool.query(
        `INSERT INTO profiles (full_name, email) 
         VALUES ($1, $2) 
         RETURNING id, full_name, email`,
        [adminName, adminEmail]
      );
      userId = newUser.rows[0].id;
      console.log('✅ Profil admin créé dans la table profiles');
    } else {
      userId = existingUser.rows[0].id;
      console.log('ℹ️  Le profil admin existe déjà');
    }
    
    // Assigner le rôle admin
    await pool.query(
      `INSERT INTO user_roles (user_id, role) 
       VALUES ($1, 'admin') 
       ON CONFLICT (user_id, role) DO NOTHING`,
      [userId]
    );
    console.log('✅ Rôle admin assigné dans user_roles');
    
    // Envoyer l'email
    console.log('\n📨 Envoi de l\'email...');
    await sendEmail(adminEmail, plainPassword);
    console.log(`✅ Email envoyé avec succès à ${adminEmail}`);
    
    // Vérifier que tout est bien en place
    const verification = await pool.query(
      `SELECT p.id, p.full_name, p.email, r.role 
       FROM profiles p 
       JOIN user_roles r ON p.id = r.user_id 
       WHERE p.email = $1`,
      [adminEmail]
    );
    
    console.log('\n✅ Vérification finale:');
    console.log(`   ID: ${verification.rows[0].id}`);
    console.log(`   Nom: ${verification.rows[0].full_name}`);
    console.log(`   Email: ${verification.rows[0].email}`);
    console.log(`   Rôle: ${verification.rows[0].role}`);
    
    console.log('\n✨ Administrateur créé avec succès!');
    process.exit(0);
  } catch (error) {
    console.error('❌ Erreur détaillée:', error);
    process.exit(1);
  }
}

createAdmin();