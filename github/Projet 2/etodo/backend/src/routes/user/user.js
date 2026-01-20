const express = require("express");
const auth = require("../../middleware/auth.js");
const {
  getUserByIdOrEmail,
  getAllUserTodos,
  getAuthenticatedUser,
  updateUser,
  deleteUser
} = require("./user.query.js");

const router = express.Router();

// Toutes les routes sont protégées
router.use(auth);

// GET /user -> informations du user connecté
router.get("/", async (req, res) => {
  try {
    const user = await getAuthenticatedUser(req.user.id);
    if (!user) return res.status(404).json({ msg: "Not found" });

    res.json(user);
  } catch (err) {
    console.error(err);
    res.status(500).json({ msg: "Internal server error" });
  }
});

// GET /user/todos -> toutes les tâches du user connecté
router.get("/todos", async (req, res) => {
  try {
    const todos = await getAllUserTodos(req.user.id);
    res.json(todos);
  } catch (err) {
    console.error(err);
    res.status(500).json({ msg: "Internal server error" });
  }
});

// GET /users/:id ou /users/:email
router.get("/:id", async (req, res) => {
  try {
    const param = req.params.id;
    const user = await getUserByIdOrEmail(param);

    if (!user) return res.status(404).json({ msg: "Not found" });

    res.json(user);
  } catch (err) {
    console.error(err);
    res.status(500).json({ msg: "Internal server error" });
  }
});

// PUT /users/:id -> mise à jour
router.put("/:id", async (req, res) => {
  try {
    const { email, password, firstname, name } = req.body;

    if (!email || !password || !firstname || !name)
      return res.status(400).json({ msg: "Bad parameter" });

    const updated = await updateUser(req.params.id, {
      email,
      password,
      firstname,
      name
    });

    if (!updated) return res.status(404).json({ msg: "Not found" });

    res.json(updated);
  } catch (err) {
    console.error(err);
    res.status(500).json({ msg: "Internal server error" });
  }
});

// DELETE /users/:id -> suppression
router.delete("/:id", async (req, res) => {
  try {
    const deleted = await deleteUser(req.params.id);

    if (!deleted) return res.status(404).json({ msg: "Not found" });

    res.json({ msg: `Successfully deleted record number: ${req.params.id}` });
  } catch (err) {
    console.error(err);
    res.status(500).json({ msg: "Internal server error" });
  }
});

module.exports = router;
