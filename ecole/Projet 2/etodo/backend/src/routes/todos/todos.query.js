const pool = require("../../config/db.js");

// Récupère toutes les todos d'un user
async function getAllTodos(user_id) {
  const [rows] = await pool.query(
    "SELECT * FROM todo WHERE user_id = ?",
    [user_id]
  );
  return rows;
}

// Récupère une todo par ID
async function getTodoById(id) {
  const [rows] = await pool.query(
    "SELECT * FROM todo WHERE id = ?",
    [id]
  );
  return rows[0];
}

// Crée une todo
async function createTodo({ title, description, due_time, status, user_id }) {
  const [result] = await pool.query(
    "INSERT INTO todo (title, description, due_time, user_id, status, created_at) VALUES (?, ?, ?, ?, ?, NOW())",
    [title, description, due_time, user_id, status]
  );

  const [rows] = await pool.query(
    "SELECT * FROM todo WHERE id = ?",
    [result.insertId]
  );

  return rows[0];
}

// Met à jour une todo
async function updateTodo(id, { title, description, due_time, status, user_id }) {
  const [result] = await pool.query(
    "UPDATE todo SET title = ?, description = ?, due_time = ?, user_id = ?, status = ? WHERE id = ?",
    [title, description, due_time, user_id, status, id]
  );

  if (result.affectedRows === 0) return null;

  const [rows] = await pool.query(
    "SELECT * FROM todo WHERE id = ?",
    [id]
  );

  return rows[0];
}

// Supprime une todo
async function deleteTodo(id) {
  const [result] = await pool.query(
    "DELETE FROM todo WHERE id = ?",
    [id]
  );
  return result.affectedRows > 0;
}

module.exports = {
  getAllTodos,
  getTodoById,
  createTodo,
  updateTodo,
  deleteTodo
};
