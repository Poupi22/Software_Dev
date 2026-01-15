const fs = require('fs');
const path = require('path');
const { query, pool } = require('../config/database');
require('dotenv').config();

class MigrationRunner {
  constructor() {
    this.migrationsTable = 'migrations';
    this.migrationsPath = path.join(__dirname);
  }

  async initMigrationsTable() {
    const createTableSQL = `
      CREATE TABLE IF NOT EXISTS ${this.migrationsTable} (
        id SERIAL PRIMARY KEY,
        name VARCHAR(255) NOT NULL UNIQUE,
        executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
      );
    `;
    await query(createTableSQL);
    console.log('✅ Migrations table initialized');
  }

  async getExecutedMigrations() {
    const result = await query(`SELECT name FROM ${this.migrationsTable} ORDER BY id`);
    return result.rows.map(row => row.name);
  }

  async executeMigration(filePath, migrationName) {
    try {
      let sql = fs.readFileSync(filePath, 'utf8');
      
      // Remove comments (simple version)
      sql = sql.replace(/--.*$/gm, '');
      
      // Execute the entire SQL as a single statement
      // PostgreSQL can handle multiple statements in one query
      await query(sql);
      
      await query(`INSERT INTO ${this.migrationsTable} (name) VALUES ($1)`, [migrationName]);
      
      console.log(`✅ Migration executed: ${migrationName}`);
      return true;
    } catch (error) {
      console.error(`❌ Migration failed: ${migrationName}`, error.message);
      throw error;
    }
  }

  async createNewMigration(name) {
    const timestamp = new Date().toISOString().replace(/[-:T.Z]/g, '').slice(0, 14);
    const fileName = `${timestamp}_${name}.sql`;
    const filePath = path.join(this.migrationsPath, fileName);
    
    const template = `-- Migration: ${name}
-- Created at: ${new Date().toISOString()}
-- Description: 

-- Write your migration SQL here

-- Example:
-- ALTER TABLE ventes ADD COLUMN IF NOT EXISTS new_column VARCHAR(100);
-- CREATE INDEX IF NOT EXISTS idx_new_column ON ventes(new_column);

`;
    
    fs.writeFileSync(filePath, template);
    console.log(`✅ New migration created: ${fileName}`);
    return fileName;
  }

  async runMigrations() {
    await this.initMigrationsTable();
    
    const executed = await this.getExecutedMigrations();
    const files = fs.readdirSync(this.migrationsPath)
      .filter(file => file.endsWith('.sql') && file !== 'migration_runner.js')
      .sort();
    
    let newMigrations = 0;
    
    for (const file of files) {
      if (!executed.includes(file)) {
        const filePath = path.join(this.migrationsPath, file);
        await this.executeMigration(filePath, file);
        newMigrations++;
      }
    }
    
    if (newMigrations === 0) {
      console.log('📭 No new migrations to run');
    } else {
      console.log(`🎉 ${newMigrations} migration(s) executed successfully`);
    }
  }

  async rollbackLast() {
    await this.initMigrationsTable();
    
    const result = await query(
      `SELECT name FROM ${this.migrationsTable} ORDER BY id DESC LIMIT 1`
    );
    
    if (result.rows.length === 0) {
      console.log('No migrations to rollback');
      return;
    }
    
    const lastMigration = result.rows[0].name;
    console.log(`Rolling back: ${lastMigration}`);
    
    // Remove from migrations table
    await query(`DELETE FROM ${this.migrationsTable} WHERE name = $1`, [lastMigration]);
    console.log(`✅ Rollback completed for: ${lastMigration}`);
  }
}

// CLI handling
if (require.main === module) {
  const runner = new MigrationRunner();
  const command = process.argv[2];
  
  switch (command) {
    case 'create':
      const name = process.argv[3];
      if (!name) {
        console.error('❌ Please provide a migration name');
        process.exit(1);
      }
      runner.createNewMigration(name);
      break;
    case 'rollback':
      runner.rollbackLast();
      break;
    default:
      runner.runMigrations().catch(err => {
        console.error('Migration failed:', err);
        process.exit(1);
      });
  }
}

module.exports = MigrationRunner;