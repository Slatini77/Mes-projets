import pool from "../../config/db.js";
import bcrypt from "bcryptjs";

// Récupère un utilisateur par ID ou email
export async function getUserByIdOrEmail(param) {
  const [rows] = await pool.query(
    "SELECT * FROM user WHERE id = ? OR email = ?",
    [param, param]
  );
  return rows[0];
}

// Récupère les infos de l’utilisateur connecté
export async function getAuthenticatedUser(id) {
  const [rows] = await pool.query("SELECT * FROM user WHERE id = ?", [id]);
  return rows[0];
}

// Récupère tous les todos d’un utilisateur
export async function getAllUserTodos(id) {
  const [rows] = await pool.query("SELECT * FROM todo WHERE user_id = ?", [id]);
  return rows;
}

// Met à jour les informations d’un utilisateur
export async function updateUser(id, { email, password, firstname, name }) {
  const hashedPassword = await bcrypt.hash(password, 10);
  const [result] = await pool.query(
    "UPDATE user SET email = ?, password = ?, firstname = ?, name = ? WHERE id = ?",
    [email, hashedPassword, firstname, name, id]
  );
  if (result.affectedRows === 0) return null;

  const [rows] = await pool.query("SELECT * FROM user WHERE id = ?", [id]);
  return rows[0];
}

// Supprime un utilisateur
export async function deleteUser(id) {
  const [result] = await pool.query("DELETE FROM user WHERE id = ?", [id]);
  return result.affectedRows > 0;
}
