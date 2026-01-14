const express = require("express");
const router = express.Router();

// On récupère les fonctions qui parlent à la BDD
const {
  getAllTodos,
  getTodoById,
  createTodo,
  updateTodo,
  deleteTodo,
} = require("./todos.query.js");



// GET /todos -> liste toutes les tâches de l'utilisateur connecté
router.get("/", async (req, res, next) => {
  try {
    const userId = req.user.id; // adapte si besoin
    const todos = await getAllTodos(userId);
    res.status(200).json(todos);
  } catch (err) {
    next(err);
  }
});

// GET /todos/:id -> une tâche par id
router.get("/:id", async (req, res, next) => {
  try {
    const todo = await getTodoById(req.params.id);

    if (!todo) {
      return res.status(404).json({ msg: "Todo not found" });
    }
    res.status(200).json(todo);
  } catch (err) {
    next(err);
  }
});

// POST /todos -> crée une tâche
router.post("/", async (req, res, next) => {
  try {
    const userId = req.user.id; // user connecté

    const { title, description, due_time, status } = req.body;

    if (!title || !description || !due_time || !status) {
      return res.status(400).json({ msg: "Missing fields" });
    }

    const todo = await createTodo({
      title,
      description,
      due_time,
      status,
      user_id: userId,
    });

    res.status(201).json(todo);
  } catch (err) {
    next(err);
  }
});

// PUT /todos/:id -> met à jour une tâche
router.put("/:id", async (req, res, next) => {
  try {
    const userId = req.user.id;
    const { title, description, due_time, status } = req.body;

    if (!title || !description || !due_time || !status) {
      return res.status(400).json({ msg: "Missing fields" });
    }

    const todo = await updateTodo(req.params.id, {
      title,
      description,
      due_time,
      status,
      user_id: userId,
    });

    if (!todo) {
      return res.status(404).json({ msg: "Todo not found" });
    }

    res.status(200).json(todo);
  } catch (err) {
    next(err);
  }
});

// DELETE /todos/:id -> supprime une tâche
router.delete("/:id", async (req, res, next) => {
  try {
    const deleted = await deleteTodo(req.params.id);

    if (!deleted) {
      return res.status(404).json({ msg: "Todo not found" });
    }

    res.status(200).json({ msg: `Successfully deleted record number: ${req.params.id}` });
  } catch (err) {
    next(err);
  }
});

module.exports = router;