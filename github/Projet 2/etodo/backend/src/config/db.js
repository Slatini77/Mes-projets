const mysql = require("mysql2/promise");
// db - Crée un pool MySQL. Permet d’exécuter pool.query(...). Exporté pour toutes les routes.
const pool = mysql.createPool({
  host: process.env.MYSQL_HOST,
  user: process.env.MYSQL_USER,
  password: process.env.MYSQL_PASSWORD,
  database: process.env.MYSQL_DATABASE,
  port: process.env.MYSQL_PORT,
  waitForConnections: true,
  connectionLimit: 10,
});

module.exports = pool;
