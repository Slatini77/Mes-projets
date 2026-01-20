const express = require("express");
const bcrypt = require("bcryptjs");
const jwt = require("jsonwebtoken");
const pool = require("../../config/db.js");

const router = express.Router();

// POST /register - But : créer un utilisateur. Étapes : Vérifie les paramètres requis. Vérifie si l'email existe déjà. Hash le mot de passe. Enregistre en base. Retour : confirmation + ID."

router.post("/register", async (req, res) => {
  try {
    const { email, password, name, firstname } = req.body;

    if (!email || !password || !name || !firstname)
      return res.status(400).json({ msg: "Bad parameter" });

    const [userExists] = await pool.query(
      "SELECT * FROM user WHERE email = ?", 
      [email]
    );

    if (userExists.length > 0)
      return res.status(409).json({ msg: "Account already exists" });

    const hashedPassword = await bcrypt.hash(password, 10);

    await pool.query(
      "INSERT INTO user (email, password, name, firstname, created_at) VALUES (?, ?, ?, ?, NOW())",
      [email, hashedPassword, name, firstname]
    );

    const [user] = await pool.query(
      "SELECT * FROM user WHERE email = ?", 
      [email]
    );

    const token = jwt.sign(
      { id: user[0].id }, 
      process.env.SECRET, 
      { expiresIn: "1h" }
    );

    res.status(201).json({ token });
  } catch (err) {
    console.error(err);
    res.status(500).json({ msg: "Internal server error" });
  }
});

// POST /login - But : connecter un utilisateur. Étapes : Récupère l'utilisateur via l’email. Compare le password avec bcrypt.compare. Génère un token JWT d'une heure. Retour : { token: "...", user: {...} }.
router.post("/login", async (req, res) => {
  try {
    const { email, password } = req.body;

    if (!email || !password)
      return res.status(400).json({ msg: "Bad parameter" });

    const [rows] = await pool.query(
      "SELECT * FROM user WHERE email = ?", 
      [email]
    );

    if (rows.length === 0)
      return res.status(401).json({ msg: "Invalid Credentials" });

    const user = rows[0];
    const valid = await bcrypt.compare(password, user.password);

    if (!valid)
      return res.status(401).json({ msg: "Invalid Credentials" });

    const token = jwt.sign(
      { id: user.id }, 
      process.env.SECRET, 
      { expiresIn: "1h" }
    );

    res.json({ token });
  } catch (err) {
    console.error(err);
    res.status(500).json({ msg: "Internal server error" });
  }
});

module.exports = router;
