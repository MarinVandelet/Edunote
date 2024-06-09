document.addEventListener('DOMContentLoaded', function () {
    const themeToggleBtn = document.getElementById('theme-toggle');

    // Vérifiez l'état du thème au chargement de la page
    if (localStorage.getItem('theme') === 'dark-mode') {
        document.body.classList.add('dark-mode');
        if (themeToggleBtn) {
            themeToggleBtn.textContent = '☀';
        }
    }

    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', toggleTheme);
    }

    function toggleTheme() {
        document.body.classList.toggle('dark-mode');
        const isDarkMode = document.body.classList.contains('dark-mode');
        localStorage.setItem('theme', isDarkMode ? 'dark-mode' : 'light-mode');
        themeToggleBtn.textContent = isDarkMode ? '☀' : '🌙';

        // Log for debugging
        console.log('Theme changed to:', isDarkMode ? 'dark-mode' : 'light-mode');
        console.log('Local Storage theme:', localStorage.getItem('theme'));
    }

    const boutonConnexion = document.querySelector('.submit-boutton');
    if (boutonConnexion) {
        boutonConnexion.addEventListener('click', function() {
            boutonConnexion.classList.add('error');
        });
    }
});
document.addEventListener("DOMContentLoaded", function() {
    toggleFields();
    adjustButtonLayout();
});

function adjustButtonLayout() {
    const buttons = document.querySelectorAll(".button-container > *");
    const userTypeButton = document.querySelector(".usertype");
    const totalButtons = buttons.length;

    if (totalButtons % 2 !== 0) {
        document.querySelector(".button-container").classList.add("odd-buttons");
    }
}
// script.js

// Fonction pour filtrer et afficher les utilisateurs
function filterAndDisplayUsers() {
    // Récupérer le filtre sélectionné
    var filter = document.querySelector('.filter-buttons button.active').dataset.filter;

    // Récupérer le texte de la recherche
    var searchText = document.getElementById('searchInput').value.trim();

    // Envoyer une requête AJAX au serveur pour récupérer les utilisateurs filtrés
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'get_users.php?filter=' + filter + '&search=' + searchText, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Mettre à jour le tableau HTML avec les données reçues du serveur
            document.getElementById('userTable').innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}

// Fonction pour appliquer le filtre sélectionné
function applyFilter(button) {
    // Supprimer la classe 'active' de tous les boutons de filtre
    var buttons = document.querySelectorAll('.filter-buttons button');
    buttons.forEach(function(btn) {
        btn.classList.remove('active');
    });

    // Ajouter la classe 'active' au bouton cliqué
    button.classList.add('active');

    // Filtrer et afficher les utilisateurs
    filterAndDisplayUsers();
}

// Écouter les événements de clic sur les boutons de filtre
var filterButtons = document.querySelectorAll('.filter-buttons button');
filterButtons.forEach(function(btn) {
    btn.addEventListener('click', function() {
        applyFilter(this);
    });
});

// Écouter les événements de saisie dans la barre de recherche
document.getElementById('searchInput').addEventListener('keyup', function() {
    filterAndDisplayUsers();
});

// Charger les utilisateurs au chargement de la page
window.onload = function() {
    filterAndDisplayUsers();
};
