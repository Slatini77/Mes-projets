require("dotenv").config();
const express = require("express");
const cors = require("cors");
const auth = require("./middleware/auth.js");
const notFound = require("./middleware/notFound.js");
const authRoutes = require("./routes/auth/auth.js");
const userRoutes = require("./routes/user/user.js");
const todosRoutes = require("./routes/todos/todos.js");
const pool = require("./config/db.js");

const app = express();

// CORS FIX
app.use(cors({
  origin: "*",
  methods: "GET,POST,PUT,DELETE",
  allowedHeaders: "Content-Type,Authorization"
}));

// Parse JSON
app.use(express.json());

// Test BDD
(async () => {
  try {
    await pool.query("SELECT 1");
    console.log("✅ Connected to MySQL Database:", process.env.MYSQL_DATABASE);
  } catch (err) {
    console.error("❌ Database connection failed:", err.message);
  }
})();

// ROUTES NON PROTÉGÉES
app.use("/", authRoutes);   // /register et /login

// ROUTES PROTÉGÉES
app.use("/user", auth, userRoutes);
app.use("/users", auth, userRoutes);
app.use("/todos", auth, todosRoutes);

// Welcome
app.get("/", (req, res) => {
  res.json({ msg: "Welcome to E-Todo API" });
});

// 404
app.use(notFound);

// Start
const PORT = process.env.PORT || 3000;
app.listen(PORT, () => console.log(`🚀 Server running on port ${PORT}`));
