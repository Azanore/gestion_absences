<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques</title>
    <link rel="icon" type="image/x-icon" href="media/ecole.png">
    <link rel="stylesheet" href="css/stats.css">
</head>

<body>
    <?php
    require("middleware/auth.php");
    require('middleware/directeurVerify.php');
    require("navBar.php");
    require('middleware/connexion.php');

    $stmt = $cnx->prepare("SELECT count(*) as total FROM stagiaires ");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_OBJ);

    $stmt1 = $cnx->prepare("SELECT count(*) as total FROM filieres ");
    $stmt1->execute();
    $result1 = $stmt1->fetch(PDO::FETCH_OBJ);

    $stmt2 = $cnx->prepare("SELECT count(*) as total FROM groupes ");
    $stmt2->execute();
    $result2 = $stmt2->fetch(PDO::FETCH_OBJ);
    ?>

    <div class='div1'>
        <h2>Statistiques</h2>
        <div class='div'>
            <a href='statsStagiaires.php'>
                <article class='gauche'>
                    <span>Stagiaires</span>
                    <div>Total des stagiaires : <?php echo $result->total ?></div>
                </article>
                <article class='droite'>
                    <img src='media/education.png'>
                </article>
            </a>
            <a href='statsFilieres.php'>
                <article class='gauche'>
                    <span>Filières</span>
                    <div>Total des filières : <?php echo $result1->total ?></div>
                </article>
                <article class='droite'>
                    <img src='media/batiment.png'>
                </article>
            </a>
            <a href='statsGroupes.php'>
                <article class='gauche'>
                    <span>Groupes</span>
                    <div>Total des groupes : <?php echo $result2->total ?></div>
                </article>
                <article class='droite'>
                    <img src='media/enseignements.png'>
                </article>
            </a>
        </div>
    </div>
</body>

</html>