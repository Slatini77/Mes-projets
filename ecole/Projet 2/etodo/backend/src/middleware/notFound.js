// Middleware notfound - Retourne un JSON { msg: "Not found" } si aucune route ne correspond.
function notFound(req, res, next) {
  res.status(404).json({ msg: "Not found" });
}

module.exports = notFound;
