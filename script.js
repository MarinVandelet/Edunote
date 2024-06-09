var boutonConnexion = document.querySelector('.submit-boutton');

boutonConnexion.addEventListener('click', function() {
    boutonConnexion.classList.add('error');
}); 

function toggleTheme() {
    document.body.classList.toggle('dark-mode');
    const themeToggleBtn = document.getElementById('theme-toggle');
    themeToggleBtn.textContent = document.body.classList.contains('dark-mode') ? '☀' : '🌙';
}


