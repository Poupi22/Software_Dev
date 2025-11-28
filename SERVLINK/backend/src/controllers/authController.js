const pool = require('../config/database');
const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');

const adminPassword = process.env.ADMIN_PASSWORD;

const login = async (req, res) => {
  try {
    const { email, password } = req.body;
    
    if (!email || !password) {
      return res.status(400).json({ error: 'Email et mot de passe requis' });
    }
    
    const userResult = await pool.query(
      `SELECT p.id, p.full_name, p.email, p.password, p.is_blocked, r.role
       FROM profiles p
       JOIN user_roles r ON p.id = r.user_id
       WHERE p.email = $1`,
      [email]
    );
    
    if (userResult.rows.length === 0) {
      return res.status(401).json({ error: 'Email ou mot de passe incorrect' });
    }
    
    const user = userResult.rows[0];

    if (user.is_blocked) {
      return res.status(403).json({ error: 'Votre compte a été bloqué. Contactez l\'administration.' });
    }

    let isValid = false;
    
    if (user.role === 'admin') {
      isValid = password === adminPassword;
    } else {
      if (user.password) {
        isValid = await bcrypt.compare(password, user.password);
      }
    }
    
    if (!isValid) {
      return res.status(401).json({ error: 'Email ou mot de passe incorrect' });
    }
    
    const token = jwt.sign(
      { userId: user.id, email: user.email, role: user.role },
      process.env.JWT_ACCESS_SECRET,
      { expiresIn: process.env.JWT_ACCESS_EXPIRES }
    );
    
    res.json({
      success: true,
      token,
      user: {
        id: user.id,
        name: user.full_name,
        email: user.email,
        role: user.role
      }
    });
    
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const register = async (req, res) => {
  try {
    const { full_name, email, phone, password, role = 'client' } = req.body;
    
    if (!full_name || !email || !password) {
      return res.status(400).json({ error: 'Nom, email et mot de passe requis' });
    }
    
    const existingUser = await pool.query('SELECT id FROM profiles WHERE email = $1', [email]);
    
    if (existingUser.rows.length > 0) {
      return res.status(400).json({ error: 'Cet email est déjà utilisé' });
    }
    
    const hashedPassword = await bcrypt.hash(password, 10);
    
    const newUser = await pool.query(
      `INSERT INTO profiles (full_name, email, phone, password) 
       VALUES ($1, $2, $3, $4) 
       RETURNING id, full_name, email`,
      [full_name, email, phone, hashedPassword]
    );
    
    const userId = newUser.rows[0].id;
    
    await pool.query(
      `INSERT INTO user_roles (user_id, role) VALUES ($1, $2)`,
      [userId, role]
    );
    
    const token = jwt.sign(
      { userId, email, role },
      process.env.JWT_ACCESS_SECRET,
      { expiresIn: process.env.JWT_ACCESS_EXPIRES }
    );
    
    res.status(201).json({
      success: true,
      token,
      user: {
        id: userId,
        name: full_name,
        email: email,
        role: role
      }
    });
    
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const getProfile = async (req, res) => {
  try {
    const result = await pool.query(
      `SELECT id, full_name, email, phone, created_at 
       FROM profiles WHERE id = $1`,
      [req.userId]
    );
    
    if (result.rows.length === 0) {
      return res.status(404).json({ error: 'Utilisateur non trouvé' });
    }
    
    res.json(result.rows[0]);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

module.exports = { login, register, getProfile };