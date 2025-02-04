<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choix du Groupe et de la Date</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        @media screen and (max-width: 680px) {
            .form-container {
                width: 80% !important;
                padding: 10px 20px;
                margin: auto;
            }
        }
        <?php
        if (isset($_GET['id_link']) && $_GET['id_link'] == 1) {
            echo "li:nth-child(2) { background-color: #009879; }";
            echo "@media screen and (max-width: 680px) {
                li:nth-child(2) { background: none !important; border-bottom: 5px solid #009879 !important; }
            }";
        } elseif (isset($_GET['id_link']) && $_GET['id_link'] == 2) {
            echo "li:nth-child(4) { background-color: #009879; }";
            echo "@media screen and (max-width: 680px) {
                li:nth-child(4) { background: none !important; border-bottom: 5px solid #009879 !important; }
            }";
        }
        ?>
    </style>
    <link rel="icon" type="image/x-icon" href="media/ecole.png">

</head>

<body>
    <?php
    require('middleware/auth.php');
    require("navBar.php");
    require("middleware/connexion.php");

    $stmt = $cnx->prepare("SELECT * FROM groupes WHERE id_filiere=:id");
    $stmt->bindParam(':id', $_GET['id_filiere']);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_OBJ);

    $stmt2 = $cnx->prepare("SELECT * FROM filieres WHERE id_filiere=:id");
    $stmt2->bindParam(':id', $_GET['id_filiere']);
    $stmt2->execute();
    $result2 = $stmt2->fetchAll(PDO::FETCH_OBJ);

    $action = "saisirAbsence.php";
    if ($_GET['id_link'] == 2) {
        $action = "suiviAbsence.php";
    }
    ?>
    <div class="form-container">

        <h2>Choisissez le Groupe <?php if ($_GET['id_link'] == 1) : $time = date("Y-m-d"); ?>
                et la Date <?php endif; ?>
        </h2>
        <form action="<?php echo $action; ?>" method="get">
            <label for="filiere">Filière :</label>
            <select id="filiere" readonly>
                <option><?php echo $result2[0]->nom_filiere; ?></option>
            </select>
            <label for="id_groupe">Groupe :</label>
            <select id="id_groupe" name="id_groupe" required>
                <?php
                foreach ($result as $r) {
                    echo "<option value='$r->id_groupe'>$r->nom_groupe</option>";
                }
                ?>
            </select>
            <?php if ($_GET['id_link'] == 1) : $time = date("Y-m-d"); ?>
                <label for="date">Date :</label>
                <input type="date" id="date" name="date" value="<?php echo $time; ?>" required>
            <?php endif; ?>
            <input type="submit" value="Continuer">
        </form>
    </div>
</body>

</html>