const { Pool } = require('pg');
require('dotenv').config();

const pool = new Pool({
  host: process.env.DB_HOST || 'localhost',
  port: parseInt(process.env.DB_PORT) || 5432,
  database: process.env.DB_NAME || 'pharmacare',
  user: process.env.DB_USER || 'postgres',
  password: process.env.DB_PASSWORD,
  max: 5,
  idleTimeoutMillis: 10000,
  connectionTimeoutMillis: 5000, // 5 secondes timeout
});

async function testConnection() {
  let client;
  try {
    console.log('Testing database connection...');
    console.log('Config:', {
      host: process.env.DB_HOST,
      port: process.env.DB_PORT,
      database: process.env.DB_NAME,
      user: process.env.DB_USER,
      password: '***'
    });
    
    client = await pool.connect();
    const result = await client.query('SELECT NOW() as time, current_database() as db');
    console.log('✅ Database connected successfully!');
    console.log('📅 Server time:', result.rows[0].time);
    console.log('🗄️ Database:', result.rows[0].db);
    
    // Test if tables exist
    const tables = await client.query(`
      SELECT table_name 
      FROM information_schema.tables 
      WHERE table_schema = 'public' 
      ORDER BY table_name
    `);
    console.log('📋 Tables in database:', tables.rows.map(r => r.table_name).join(', '));
    
  } catch (error) {
    console.error('❌ Database connection failed:', error.message);
    console.error('Full error:', error);
  } finally {
    if (client) client.release();
    await pool.end();
  }
}

testConnection();