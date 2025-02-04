    <link rel="stylesheet" href="css/navBar.css">
    <body>
        <nav class="navbar">
            <label for='show&hide'><span>|</span><span>|</span><span>|</span></label>
            <input id='show&hide' type='checkbox'>
            <ul>
                <li title='Acceuil' id='gauche'><a href="home.php"><img src='media/accueil.png'></a></li>
                <li><a href="choixFiliere.php?id_link=1">Saisie d'absences</a></li>
                <li><a href="modifierAbsence.php">Modification d'absences</a></li>
                <li><a href="choixFiliere.php?id_link=2">Suivi d'absences</a></li>
                <?php
                if ($_SESSION['authUser']->role == "Directeur") {
                    echo '<li><a href="stats.php">Statistiques</a></li>';
                } ?>
                <li title='Déconnexion' id='droit'><a href="controllers/logoutController.php"><img src='media/deconnexion.png'></a></li>
            </ul>
        </nav>
    </body>