<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques - Groupes</title>
    <link rel="icon" type="image/x-icon" href="media/ecole.png">
    <link rel="stylesheet" href="css/statsGroupes.css">
</head>

<body>
    <?php
    require("middleware/auth.php");
    require('middleware/directeurVerify.php');
    require('navBar.php');
    require('middleware/connexion.php');

    $query = "
    SELECT g.id_groupe, g.nom_groupe, COUNT(s.id_stagiaire) as total
    FROM groupes g
    LEFT JOIN stagiaires s ON g.id_groupe = s.id_groupe
";
    if (isset($_GET['id_filiere']) && !empty($_GET['id_filiere'])) {
        $id_filiere = $_GET['id_filiere'];
        $query .= " WHERE g.id_filiere = :id_filiere";
    }
    $query .= " GROUP BY g.id_groupe, g.nom_groupe";

    $stmt = $cnx->prepare($query);
    if (isset($id_filiere)) {
        $stmt->bindParam(':id_filiere', $id_filiere);
    }
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_OBJ);

    echo "<div class='div1'><h2>Ensemble des Groupes</h2><div class='div'>";
    foreach ($result as $groupe) {
        echo "
    <a href='statsStagiaires.php?id_groupe=$groupe->id_groupe'>
        <article class='gauche'>
            <span>{$groupe->nom_groupe}</span>
            <div>Total des stagiaires : {$groupe->total}</div>
        </article>
        <article class='droite'>
            <img src='media/enseignements.png' alt='Image'>
        </article>
    </a>";
    }
    echo "</div></div>";
    ?>
</body>

</html>