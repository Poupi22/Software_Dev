const bcrypt = require('bcrypt');
const { query } = require('../config/database');
const { sendAdminCredentialsEmail } = require('../config/email');
require('dotenv').config();

class Seeder {
  async seedAdmin() {
    const adminEmail = process.env.ADMIN_EMAIL;
    const adminPassword = process.env.ADMIN_PASSWORD;
    const adminName = process.env.ADMIN_NAME || 'Admin Pharmacare';
    
    console.log(`📧 Checking admin user: ${adminEmail}`);
    
    // Check if admin already exists
    const result = await query(
      'SELECT id, email FROM utilisateurs WHERE email = $1 AND role = $2',
      [adminEmail, 'ADMIN']
    );
    
    if (result.rows.length === 0) {
      console.log('👤 Creating admin user...');
      
      // Hash password
      const saltRounds = 10;
      const hashedPassword = await bcrypt.hash(adminPassword, saltRounds);
      
      // Create admin user
      const insertResult = await query(
        `INSERT INTO utilisateurs (nom, email, mot_de_passe, role, statut)
         VALUES ($1, $2, $3, 'ADMIN', 'ACTIF')
         RETURNING id, email, nom`,
        [adminName, adminEmail, hashedPassword]
      );
      
      const admin = insertResult.rows[0];
      console.log('✅ Admin user created:', admin.email);
      
      // Send credentials email
      try {
        await sendAdminCredentialsEmail(adminEmail, adminPassword);
        console.log('📧 Admin credentials sent to:', adminEmail);
      } catch (emailError) {
        console.error('⚠️ Failed to send email:', emailError.message);
      }
      
      return admin;
    } else {
      console.log('ℹ️ Admin user already exists:', result.rows[0].email);
      return result.rows[0];
    }
  }
  
  async seedCategories() {
    console.log('📂 Seeding categories...');
    
    const categories = [
      { nom: 'Antibiotiques', description: 'Médicaments antibactériens' },
      { nom: 'Antalgiques', description: 'Médicaments contre la douleur' },
      { nom: 'Vitamines', description: 'Compléments vitaminés' },
      { nom: 'Antihypertenseurs', description: 'Traitement de l\'hypertension' },
      { nom: 'Antidiabétiques', description: 'Traitement du diabète' },
      { nom: 'Anti-inflammatoires', description: 'Médicaments contre l\'inflammation' },
    ];
    
    for (const cat of categories) {
      await query(
        `INSERT INTO categories (nom, description)
         VALUES ($1, $2)
         ON CONFLICT (nom) DO NOTHING`,
        [cat.nom, cat.description]
      );
    }
    console.log('✅ Categories seeded');
  }
  
  async run() {
    console.log('🌱 Starting seeder...');
    try {
      await this.seedAdmin();
      await this.seedCategories();
      console.log('🎉 Seeder completed successfully!');
    } catch (error) {
      console.error('❌ Seeder error:', error.message);
      throw error;
    }
  }
}

// Run seeder if called directly
if (require.main === module) {
  const seeder = new Seeder();
  seeder.run()
    .then(() => process.exit(0))
    .catch(err => {
      console.error('Seeder failed:', err);
      process.exit(1);
    });
}

module.exports = Seeder;