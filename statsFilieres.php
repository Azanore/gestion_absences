<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques - Filières</title>
    <link rel="icon" type="image/x-icon" href="media/ecole.png">
    <link rel="stylesheet" href="css/statsFilieres.css">
</head>

<body>
    <?php
    require("middleware/auth.php");
    require('middleware/directeurVerify.php');
    require('navBar.php');
    require('middleware/connexion.php');

    $stmt = $cnx->prepare("SELECT f.id_filiere, f.nom_filiere, COUNT(g.id_groupe) as total
    FROM filieres f
    LEFT JOIN groupes g ON f.id_filiere = g.id_filiere
    GROUP BY f.id_filiere, f.nom_filiere");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_OBJ);

    echo "<div class='div1'>
    <h2>Ensemble des Filières</h2>
    <div class='div'>";
    foreach ($result as $filiere) {
        echo "
    <a href='statsGroupes.php?id_filiere=$filiere->id_filiere'>
        <article class='gauche'>
            <span>{$filiere->nom_filiere}</span>
            <div>Total des groupes : {$filiere->total}</div>
        </article>
        <article class='droite'>
            <img src='media/batiment.png' alt='Image'>
        </article>
    </a>";
    }
    echo "</div>
    </div>";
    ?>
</body>

</html>