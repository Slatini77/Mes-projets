// script - Connexion/déconnexion Récupération des todos Ajout / modification / suppression via fetch()

// =========================
// Config API
// =========================
const API_URL = "http://localhost:3000";

// =========================
// Récupération des éléments du DOM
// =========================

// Sections
const loginSection = document.getElementById("login-section");
const registerSection = document.getElementById("register-section");
const todosSection = document.getElementById("todos-section");

// Navigation
const btnLogin = document.getElementById("btn-login");
const btnRegister = document.getElementById("btn-register");
const btnLogout = document.getElementById("btn-logout");

// Formulaires
const loginForm = document.getElementById("login-form");
const registerForm = document.getElementById("register-form");
const todoForm = document.getElementById("todo-form");
const editForm = document.getElementById("edit-form");

// Zones de message
const loginMsg = document.getElementById("login-msg");
const registerMsg = document.getElementById("register-msg");
const todoMsg = document.getElementById("todo-msg");
const editMsg = document.getElementById("edit-msg");

// Todos
const todoList = document.getElementById("todo-list");

// Inputs création todo
const todoTitleInput = document.getElementById("todo-title");
const todoDescInput = document.getElementById("todo-desc");
const todoDueInput = document.getElementById("todo-due");
const todoStatusInput = document.getElementById("todo-status");

// Inputs édition todo
const editTitleInput = document.getElementById("edit-title-input");
const editDescInput = document.getElementById("edit-desc-input");
const editDueInput = document.getElementById("edit-due-input");
const editStatusInput = document.getElementById("edit-status-input");
const editTitle = document.getElementById("edit-title");

// Variable pour stocker l'id de la todo en cours d'édition
let currentEditTodoId = null;

// =========================
// Helpers
// =========================

function getToken() {
  return localStorage.getItem("token");
}

function setToken(token) {
  if (token) {
    localStorage.setItem("token", token);
  } else {
    localStorage.removeItem("token");
  }
}

// Affiche une section et cache les autres
function showSection(sectionId) {
  [loginSection, registerSection, todosSection].forEach((sec) => {
    sec.classList.add("hidden");
    sec.classList.remove("active");
  });

  const section = document.getElementById(sectionId);
  if (section) {
    section.classList.remove("hidden");
    section.classList.add("active");
  }
}

// Met à jour l'affichage des boutons en fonction de la connexion
function updateAuthUI() {
  const token = getToken();
  if (token) {
    btnLogin.classList.add("hidden");
    btnRegister.classList.add("hidden");
    btnLogout.classList.remove("hidden");
  } else {
    btnLogin.classList.remove("hidden");
    btnRegister.classList.remove("hidden");
    btnLogout.classList.add("hidden");
  }
}

// Nettoyer les messages
function clearMessages() {
  loginMsg.textContent = "";
  registerMsg.textContent = "";
  todoMsg.textContent = "";
  editMsg.textContent = "";
}

// =========================
// Navigation
// =========================

btnLogin.addEventListener("click", () => {
  clearMessages();
  showSection("login-section");
});

btnRegister.addEventListener("click", () => {
  clearMessages();
  showSection("register-section");
});

btnLogout.addEventListener("click", () => {
  setToken(null);
  clearMessages();
  todoList.innerHTML = "";
  showSection("login-section");
  updateAuthUI();
});

// =========================
// Connexion
// =========================

loginForm.addEventListener("submit", async (e) => {
  e.preventDefault();
  clearMessages();

  const email = document.getElementById("login-email").value.trim();
  const password = document.getElementById("login-password").value;

  if (!email || !password) {
    loginMsg.textContent = "Veuillez remplir tous les champs.";
    return;
  }

  try {
    const res = await fetch(`${API_URL}/login`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ email, password }),
    });

    const data = await res.json().catch(() => ({}));

    if (!res.ok) {
      loginMsg.textContent = data.msg || "Échec de la connexion.";
      return;
    }

    if (!data.token) {
      loginMsg.textContent = "Réponse invalide du serveur (pas de token).";
      return;
    }

    setToken(data.token);
    updateAuthUI();
    showSection("todos-section");
    await fetchTodos();
  } catch (err) {
    console.error(err);
    loginMsg.textContent = "Erreur réseau lors de la connexion.";
  }
});

// =========================
// Inscription
// =========================

registerForm.addEventListener("submit", async (e) => {
  e.preventDefault();
  clearMessages();

  const firstname = document.getElementById("register-firstname").value.trim();
  const name = document.getElementById("register-name").value.trim();
  const email = document.getElementById("register-email").value.trim();
  const password = document.getElementById("register-password").value;

  if (!firstname || !name || !email || !password) {
    registerMsg.textContent = "Veuillez remplir tous les champs.";
    return;
  }

  try {
    const res = await fetch(`${API_URL}/register`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ firstname, name, email, password }),
    });

    const data = await res.json().catch(() => ({}));

    if (!res.ok) {
      registerMsg.textContent =
        data.msg || "Échec de l'inscription, vérifiez vos informations.";
      return;
    }

    // Le backend renvoie normalement { msg: "...", token }
    if (data.token) {
      setToken(data.token);
      updateAuthUI();
      showSection("todos-section");
      await fetchTodos();
    } else {
      registerMsg.textContent =
        data.msg || "Inscription réussie, vous pouvez vous connecter.";
      showSection("login-section");
    }
  } catch (err) {
    console.error(err);
    registerMsg.textContent = "Erreur réseau lors de l'inscription.";
  }
});

// =========================
// Gestion des TODOS
// =========================

// Récupérer la liste des todos
async function fetchTodos() {
  const token = getToken();
  if (!token) {
    showSection("login-section");
    return;
  }

  try {
    const res = await fetch(`${API_URL}/todos`, {
      method: "GET",
      headers: {
        Authorization: token,
      },
    });

    const data = await res.json().catch(() => []);

    if (!res.ok) {
      console.error("Erreur fetch todos:", data);
      todoMsg.textContent = data.msg || "Impossible de charger les tâches.";
      return;
    }

    renderTodos(Array.isArray(data) ? data : []);
  } catch (err) {
    console.error(err);
    todoMsg.textContent = "Erreur réseau lors du chargement des tâches.";
  }
}

// Afficher les todos dans le DOM
function renderTodos(todos) {
  todoList.innerHTML = "";

  if (!todos.length) {
    todoList.innerHTML = "<p>Aucune tâche pour le moment.</p>";
    return;
  }

  todos.forEach((todo) => {
    const item = document.createElement("div");
    item.className = "todo-item";

    const dueDate = todo.due_time
      ? new Date(todo.due_time).toLocaleString("fr-FR")
      : "Pas d'échéance";

    item.innerHTML = `
      <h4>${todo.title || "(Sans titre)"}</h4>
      <p>${todo.description || ""}</p>
      <p><strong>Échéance :</strong> ${dueDate}</p>
      <p><strong>Statut :</strong> ${todo.status}</p>
      <button class="btn-edit" data-id="${todo.id}">Modifier</button>
      <button class="btn-delete" data-id="${todo.id}">Supprimer</button>
    `;

    todoList.appendChild(item);
  });

  // Boutons d'édition / suppression
  document.querySelectorAll(".btn-edit").forEach((btn) => {
    btn.addEventListener("click", () => {
      const id = btn.getAttribute("data-id");
      const todo = todos.find((t) => String(t.id) === String(id));
      if (!todo) return;

      currentEditTodoId = todo.id;
      editTitleInput.value = todo.title || "";
      editDescInput.value = todo.description || "";
      if (todo.due_time) {
        const d = new Date(todo.due_time);
        const iso = new Date(d.getTime() - d.getTimezoneOffset() * 60000)
          .toISOString()
          .slice(0, 16);
        editDueInput.value = iso;
      } else {
        editDueInput.value = "";
      }
      editStatusInput.value = todo.status || "not started";

      editTitle.classList.remove("hidden");
      editForm.classList.remove("hidden");
      editMsg.textContent = "";
    });
  });

  document.querySelectorAll(".btn-delete").forEach((btn) => {
    btn.addEventListener("click", async () => {
      const id = btn.getAttribute("data-id");
      await deleteTodo(id);
    });
  });
}

// Création d'une nouvelle todo
todoForm.addEventListener("submit", async (e) => {
  e.preventDefault();
  clearMessages();

  const title = todoTitleInput.value.trim();
  const description = todoDescInput.value.trim();
  const due_time = todoDueInput.value; // datetime-local
  const status = todoStatusInput.value;

  if (!title || !description || !due_time || !status) {
    todoMsg.textContent = "Veuillez remplir tous les champs de la tâche.";
    return;
  }

  const token = getToken();
  if (!token) {
    todoMsg.textContent = "Vous devez être connecté pour créer une tâche.";
    return;
  }

  try {
    const res = await fetch(`${API_URL}/todos`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Authorization: token,
      },
      body: JSON.stringify({ title, description, due_time, status }),
    });

    const data = await res.json().catch(() => ({}));

    if (!res.ok) {
      todoMsg.textContent = data.msg || "Erreur lors de la création de la tâche.";
      return;
    }

    todoForm.reset();
    await fetchTodos();
    todoMsg.textContent = "Tâche créée avec succès.";
  } catch (err) {
    console.error(err);
    todoMsg.textContent = "Erreur réseau lors de la création de la tâche.";
  }
});

// Édition d'une todo
editForm.addEventListener("submit", async (e) => {
  e.preventDefault();
  clearMessages();

  if (!currentEditTodoId) {
    editMsg.textContent = "Aucune tâche sélectionnée.";
    return;
  }

  const title = editTitleInput.value.trim();
  const description = editDescInput.value.trim();
  const due_time = editDueInput.value;

  const status = editStatusInput.value;

  if (!title || !description || !due_time || !status) {
    editMsg.textContent = "Veuillez remplir tous les champs.";
    return;
  }

  const token = getToken();
  if (!token) {
    editMsg.textContent = "Vous devez être connecté.";
    return;
  }

  try {
    const res = await fetch(`${API_URL}/todos/${currentEditTodoId}`, {
      method: "PUT",
      headers: {
        "Content-Type": "application/json",
        Authorization: token,
      },
      body: JSON.stringify({ title, description, due_time, status }),
    });

    const data = await res.json().catch(() => ({}));

    if (!res.ok) {
      editMsg.textContent = data.msg || "Erreur lors de la modification.";
      return;
    }

    editMsg.textContent = "Tâche mise à jour.";
    editForm.classList.add("hidden");
    editTitle.classList.add("hidden");
    currentEditTodoId = null;
    await fetchTodos();
  } catch (err) {
    console.error(err);
    editMsg.textContent = "Erreur réseau lors de la modification.";
  }
});

// Suppression d'une todo
async function deleteTodo(id) {
  const token = getToken();
  if (!token) {
    todoMsg.textContent = "Vous devez être connecté.";
    return;
  }

  try {
    const res = await fetch(`${API_URL}/todos/${id}`, {
      method: "DELETE",
      headers: {
        Authorization: token,
      },
    });

    const data = await res.json().catch(() => ({}));

    if (!res.ok) {
      todoMsg.textContent = data.msg || "Erreur lors de la suppression.";
      return;
    }

    await fetchTodos();
    todoMsg.textContent = "Tâche supprimée.";
  } catch (err) {
    console.error(err);
    todoMsg.textContent = "Erreur réseau lors de la suppression.";
  }
}

// =========================
// Initialisation au chargement
// =========================

window.addEventListener("load", async () => {
  clearMessages();
  updateAuthUI();

  const token = getToken();
  if (token) {
    // On essaye de charger les todos directement
    showSection("todos-section");
    await fetchTodos();
  } else {
    showSection("login-section");
  }
});