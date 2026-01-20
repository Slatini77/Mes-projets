const pool = require("../../config/db.js");
const bcrypt = require("bcryptjs");

// Récupère un utilisateur par email ou id 
async function getUserByIdOrEmail(param) {
  const [rows] = await pool.query(
    "SELECT * FROM user WHERE id = ? OR email = ?",
    [param, param]
  );
  return rows[0];
}

// Récupère l'utilisateur connecté (/user)
async function getAuthenticatedUser(id) {
  const [rows] = await pool.query(
    "SELECT * FROM user WHERE id = ?",
    [id]
  );
  return rows[0];
}

// Récupère les todos de l'utilisateur (/user/todos)
async function getAllUserTodos(id) {
  const [rows] = await pool.query(
    "SELECT * FROM todo WHERE user_id = ?",
    [id]
  );
  return rows;
}

// Met à jour un utilisateur (/users/:id)
async function updateUser(id, { email, password, firstname, name }) {
  const hashedPassword = await bcrypt.hash(password, 10);

  const [result] = await pool.query(
    "UPDATE user SET email = ?, password = ?, firstname = ?, name = ? WHERE id = ?",
    [email, hashedPassword, firstname, name, id]
  );

  if (result.affectedRows === 0) return null;

  const [rows] = await pool.query(
    "SELECT * FROM user WHERE id = ?",
    [id]
  );

  return rows[0];
}

// Supprime un utilisateur (/users/:id)
async function deleteUser(id) {
  const [result] = await pool.query(
    "DELETE FROM user WHERE id = ?",
    [id]
  );
  return result.affectedRows > 0;
}

module.exports = {
  getUserByIdOrEmail,
  getAuthenticatedUser,
  getAllUserTodos,
  updateUser,
  deleteUser,
};
