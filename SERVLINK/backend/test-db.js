const pool = require('./src/config/database');

async function testConnection() {
  try {
    const result = await pool.query('SELECT NOW() as current_time');
    console.log('✅ Base de données fonctionne!');
    console.log('Heure actuelle:', result.rows[0].current_time);
    process.exit(0);
  } catch (error) {
    console.error('❌ Erreur:', error.message);
    process.exit(1);
  }
}

testConnection();