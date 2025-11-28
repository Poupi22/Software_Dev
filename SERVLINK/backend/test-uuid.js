const pool = require('./src/config/database');

async function testUUID() {
  try {
    // Insérer un utilisateur avec UUID automatique
    const insertResult = await pool.query(
      `INSERT INTO profiles (full_name, email) 
       VALUES ($1, $2) 
       RETURNING id, full_name, email, created_at`,
      ['Jean Test', 'jean@test.com']
    );
    
    console.log('✅ Utilisateur créé avec UUID:', insertResult.rows[0]);
    
    // Lire tous les utilisateurs
    const allUsers = await pool.query('SELECT * FROM profiles');
    console.log('\n📋 Tous les utilisateurs:');
    allUsers.rows.forEach(user => {
      console.log(`   ID: ${user.id} - Nom: ${user.full_name} - Email: ${user.email}`);
    });
    
    process.exit(0);
  } catch (error) {
    console.error('❌ Erreur:', error.message);
    process.exit(1);
  }
}

testUUID();