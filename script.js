var dateEvenement = new Date("April 5, 2025 00:00:00").getTime();

var x = setInterval(function() {
  var maintenant = new Date().getTime();
  var distance = dateEvenement - maintenant;

  var jours = Math.floor(distance / (1000 * 60 * 60 * 24));
  var heures = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var secondes = Math.floor((distance % (1000 * 60)) / 1000);

  if (document.getElementById("temps")) {
    document.getElementById("temps").innerHTML =
      jours + "j " + heures + "h " + minutes + "m " + secondes + "s ";
  }

  if (distance < 0) {
    clearInterval(x);
    document.getElementById("temps").innerHTML = "🚗 Le Rallye est lancé !";
  }
}, 1000);

// Sécuriser cette partie pour éviter les erreurs sur certaines pages
const menuToggle = document.querySelector('.menu-toggle');
const navLinks = document.querySelector('.nav-links');

if (menuToggle && navLinks) {
  menuToggle.addEventListener('click', () => {
    navLinks.classList.toggle('active');
  });
}

// Marquer le lien actif automatiquement
document.addEventListener("DOMContentLoaded", function () {
  const currentPage = window.location.pathname.split("/").pop();
  const navLinks = document.querySelectorAll(".nav-links a");

  navLinks.forEach(link => {
    const href = link.getAttribute("href");
    if (href === currentPage) {
      link.classList.add("active");
    } else {
      link.classList.remove("active");
    }
  });
});
