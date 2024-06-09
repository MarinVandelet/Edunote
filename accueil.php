<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>EDN - Accueil</title>
    <link rel="icon" href="img/icon.png" type="image/x-icon">
    <script src="script.js"></script>
</head>

<body>
    <nav>
        <img src="img/EDN_Logo_blanc.png" alt="EDN_Logo" class="logo">
        <ul class="nav-links">
            <li><a href="accueil.php">Accueil</a></li>
            <li><a href="about.php">A propos</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
        <a href="connexion.php" class="connexion">Connexion</a>
        <button id="theme-toggle" class="theme-toggle" onclick="toggleTheme()">
  <div class="toggle-track">
    <div class="toggle-thumb"></div>
  </div>
  <svg class="icon-sun" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
  <svg class="icon-moon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
</button>
    </nav>


    <div class="container">
        <h1>Bienvenue sur la Plateforme Numérique de Gestion des Notes de l'Université Gustave Eiffel</h1>
        <section id="students">
            <h2>Pour les Étudiants :</h2>
            <p>Connectez-vous simplement à notre plateforme et accédez instantanément à vos résultats académiques. Plus
                besoin d'attendre la publication des notes sur un tableau d'affichage ou de faire des allers-retours
                pour obtenir vos résultats. Avec notre interface conviviale, vous pouvez consulter vos notes où que vous
                soyez, à tout moment.</p>
        </section>
        <section id="teachers">
            <h2>Pour les Enseignants :</h2>
            <p>Notre plateforme offre une suite complète d'outils de gestion des notes, vous permettant de saisir, de
                modifier et de suivre les progrès de vos élèves en temps réel. Vous pouvez facilement attribuer des
                notes, fournir des commentaires personnalisés et analyser les performances individuelles et de groupe
                pour optimiser l'enseignement et l'apprentissage.</p>
        </section>
        <section id="university">
            <h2>Pour l'Université Gustave Eiffel :</h2>
            <p>En intégrant notre plateforme dans votre infrastructure numérique, vous améliorez l'efficacité
                administrative et simplifiez la communication entre les étudiants et les enseignants. Notre solution
                garantit une gestion transparente et sécurisée des données, conformément aux normes de confidentialité
                et de sécurité les plus strictes.</p>
        </section>
    </div>
    <footer>
        <img src="img/logouniv.png" alt="EDN_Logo" class="logo-univ">
        <p>5 boulevard Descartes <br>
            Champs-sur-Marne <br>
            77454 Marne-la-Vallée cedex 2 <br>
            Téléphone : +33 (0)1 60 95 75 00
        </p>

        <ul class="social-icons">
            <li><a href="https://www.facebook.com/UniversiteGustaveEiffel/" class="icon"><img
                        src="img/facebookicon.png"
                        alt="icone facebook" class="icon"></a></li>
            <li><a href="https://twitter.com/UGustaveEiffel" class="icon"><img
                        src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/6f/Logo_of_Twitter.svg/512px-Logo_of_Twitter.svg.png"
                        alt="icone twitter" class="icon"></a></li>
            <li><a href="https://fr.linkedin.com/school/université-gustave-eiffel/" class="icon"><img
                        src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/81/LinkedIn_icon.svg/72px-LinkedIn_icon.svg.png"
                        alt="icone linkedin" class="icon"></a></li>
            <li><a href="https://www.instagram.com/universitegustaveeiffel/" class="icon"><img
                        src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a5/Instagram_icon.png/600px-Instagram_icon.png"
                        alt="icone instagram" class="icon"></a></li>
        </ul>
    </footer>
</body>

</html>