<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceuil</title>
    <link rel="stylesheet" href="css/table.css">
    <link rel="icon" type="image/x-icon" href="media/ecole.png">
    <link rel="stylesheet" href="css/home.css">
</head>

<body>
    <?php
    require('middleware/auth.php');
    require('middleware/connexion.php');
    require('navBar.php');
    ?>
    <main>
        <h1>Vous êtes connecté en tant que
            <?php
            echo " " . $_SESSION["authUser"]->role;
            ?>
        </h1>


        <section>
            <h2>Progrès</h2>
            <div class="cont">
                <article>
                    <fieldset>
                        <legend><b>Tâches accomplies</b></legend>
                        <ul>
                            <li>Saisie, modification ou suppression d'une absence</li>
                            <li>Saisie ou modification de plusieurs absences simultanément</li>
                            <li>Filtrage pour faciliter la recherche</li>
                            <li>Envoi d'un e-mail aux stagiaires</li>
                            <li>Ajout de l'interface "Statistiques", visible pour le directeur seulement</li>
                            <li>Création d'un rapport sous forme PDF</li>
                            <li>Responsive design</li>
                        </ul>
                    </fieldset>
                </article>
                <article>
                    <fieldset>
                        <legend><b>Tâches non-accomplies</b></legend>
                        <ul>
                            <li>Filtrage par année scolaire</li>
                            <li>Archives</li>
                        </ul>
                    </fieldset>
                </article>
            </div>
        </section>
        <section>
            <h2>Informations supplémentaires</h2>
            <div class="cont2">
                <article>
                    <fieldset>
                        <legend><b>Informations générales</b></legend>
                        <ul>
                            <li>Pour vous faciliter la connexion, les données des deux comptes (Directeur et Surveillant) seront saisies automatiquement (et dynamiquement) dans la page de connexion</li>
                            <li>Si votre demande ne renvoie aucun résultat, un message sera affiché dans le tableau. En plus, les champs du filtrage seront désactivés</li>
                            <li>Si vous vous connectez en tant que Surveillant, l'interface "Statistiques" sera inaccessible</li>
                            <li>Lorsque vous utilisez le(s) filtre(s) et que vous cliquez sur "Tout saisir" ou "Tout modifier", seuls les résultats visibles seront traités</li>
                        </ul>
                    </fieldset>
                </article>
                <article>
                    <fieldset>
                        <legend><b>Saisie d'absences</b></legend>
                        <ul>
                            <li>Sélectionnez la filière</li>
                            <li>Sélectionnez le groupe et la date</li>
                            <li>Cette page contient les stagiaires de chaque groupe séparément</li>
                            <li>Saisissez les informations de l'absence</li>
                            <li>Cliquez sur "Valider" pour saisir l'absence d'un seul stagiaire</li>
                            <li>Cliquez sur "Tout valider" pour saisir les absences de tous les stagiaires visibles dans la liste</li>
                        </ul>
                    </fieldset>
                </article>
                <article>
                    <fieldset>
                        <legend><b>Modification d'absences</b></legend>
                        <ul>
                            <li>Cette page contient toutes les absences enregistrées dans la base de données par ordre décroissant</li>
                            <li>Modifiez les informations de l'absence</li>
                            <li>Cliquez sur "Modifier" pour modifier l'absence d'un seul stagiaire</li>
                            <li>Cliquez sur "Tout modifier" pour modifier les absences de tous les stagiaires visibles dans la liste</li>
                        </ul>
                    </fieldset>
                </article>
                <article>
                    <fieldset>
                        <legend><b>Suivi d'absences</b></legend>
                        <ul>
                            <li>Sélectionnez la filière</li>
                            <li>Sélectionnez le groupe</li>
                            <li>Cette page contient les absences de chaque groupe séparément</li>
                            <li>Seuls les stagiaires ayant déjà été absents sans justificatif seront affichés</li>
                            <li>Cliquez sur "Envoyer un E-mail" pour contacter le stagiaire</li>
                        </ul>
                    </fieldset>
                </article>
                <article>
                    <fieldset>
                        <legend><b>Statistiques</b></legend>
                        <ul>
                            <li>Ce bouton n'est affiché que pour le Directeur</li>
                            <li>La page "Statistiques" n'est accessible que pour le Directeur</li>
                            <li>Cette page contient la totalité des stagiaires, des filières et des groupes de l'établissement</li>
                            <li>Vous pouvez filtrer par filière ou par groupe</li>
                            <li>Cliquez sur "Créer" pour générer un rapport d'absences à propos du stagiaire pendant la période fournie</li>
                        </ul>
                    </fieldset>
                </article>
            </div>
        </section>


        </section>
    </main>
    <script src='jquery/jquery-3.7.1.js'></script>
    <script>
        $(document).ready(function() {
            $("article").hide();
            $("main section").click(function() {
                var article = $(this).find("article");
                var h2 = $(this).find("h2");

                var windowWidth = $(window).width();
                var isSmallScreen = (windowWidth <= 680);

                if (isSmallScreen) {
                    h2.animate({
                        marginBottom: article.is(":hidden") ? "20px" : "0px"
                    }, 250);
                    article.toggle(0);
                } else {
                    h2.animate({
                        marginBottom: article.is(":hidden") ? "20px" : "0px"
                    }, 1000);
                    article.slideToggle(500);
                }
            });
        });
    </script>


</body>

</html>