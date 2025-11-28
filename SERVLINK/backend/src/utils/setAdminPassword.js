const pool = require('../config/database');
const bcrypt = require('bcryptjs');
require('dotenv').config();

async function setAdminPassword() {
  try {
    const adminEmail = process.env.ADMIN_EMAIL;
    const newPassword = 'Admin123'; // Change ce mot de passe
    
    // Ici on ne stocke PAS le mot de passe dans la DB
    // On utilise bcrypt juste pour hasher, mais le stockage sera dans auth plus tard
    const hashedPassword = await bcrypt.hash(newPassword, 10);
    
    console.log(`✅ Mot de passe pour ${adminEmail}: ${newPassword}`);
    console.log(`🔒 Hash: ${hashedPassword}`);
    console.log('\n⚠️  Pour l\'instant, on utilise l\'auth Supabase plus tard');
    
    process.exit(0);
  } catch (error) {
    console.error('❌ Erreur:', error);
    process.exit(1);
  }
}

setAdminPassword();