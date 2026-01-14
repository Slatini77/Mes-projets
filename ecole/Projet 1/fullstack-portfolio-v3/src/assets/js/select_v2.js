document.addEventListener("DOMContentLoaded", () => {
  const minis = document.querySelectorAll(".mini-card");
  const bigCard = document.querySelector(".profile-card-large");
  const btn = document.getElementById("viewProfileBtn");
  const sound = document.getElementById("clickSound");
  const colors = ["#2c7be5", "#e52ce1", "#2ce56b", "#f0c020"];
  let selected = null;

  minis.forEach((c, i) => {
    c.style.borderColor = colors[i % colors.length];
  });

  document.addEventListener("mousemove", e => {
    const x = (e.clientX / window.innerWidth - 0.5) * 10;
    const y = (e.clientY / window.innerHeight - 0.5) * 10;
    document.body.style.backgroundPosition = `${50 - x}% ${50 - y}%`;
  });

  minis.forEach(card => {
    card.addEventListener("mousemove", e => {
      const rX = (e.offsetY - card.offsetHeight / 2) / 20;
      const rY = (card.offsetWidth / 2 - e.offsetX) / 20;
      card.style.transform = `rotateX(${rX}deg) rotateY(${rY}deg) scale(1.05)`;
    });
    card.addEventListener("mouseleave", () => {
      card.style.transform = "";
    });

    card.addEventListener("click", () => {
      if (sound) sound.play();

      minis.forEach(c => c.classList.remove("active"));
      card.classList.add("active");
      selected = card.dataset.id;

      const imgSrc = card.querySelector("img").src;

      bigCard.style.animation = "none";
      void bigCard.offsetWidth;
      bigCard.style.animation = null;

      bigCard.classList.add("visible");
      bigCard.innerHTML = `<img src="${imgSrc}" alt="profile">`;

      btn.style.display = "inline-block";
      btn.href = `view.php?user_id=${selected}`;
    });
  });

  btn.addEventListener("click", e => {
    e.preventDefault();
    document.body.classList.add("fadeout");
    setTimeout(() => window.location = btn.href, 500);
  });
});
