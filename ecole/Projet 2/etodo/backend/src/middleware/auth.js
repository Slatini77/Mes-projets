const jwt = require("jsonwebtoken");
// Middleware auth - Vérifie si un header Authorization: Bearer token est présent. Décode le JWT. Ajoute req.user = decoded aux routes protégées.
function auth(req, res, next) {
  const token = req.header("Authorization");

  if (!token)
    return res.status(401).json({ msg: "No token, authorization denied" });

  try {
    const decoded = jwt.verify(token, process.env.SECRET);
    req.user = decoded;
    next();
  } catch (err) {
    return res.status(401).json({ msg: "Token is not valid" });
  }
}

module.exports = auth;
