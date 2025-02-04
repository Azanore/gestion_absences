<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choix de la Filière</title>
    <link rel="icon" type="image/x-icon" href="media/ecole.png">
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
</head>

<body>
    <?php
    require('middleware/auth.php');
    require("navBar.php");
    require("middleware/connexion.php");
    $stmt = $cnx->prepare("SELECT * FROM filieres");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_OBJ);
    ?>
    <div class="form-container">
        <h2>Choisissez la Filière</h2>
        <form action="choixGroupe.php" method="get">
            <input type="hidden" name="id_link" value="<?php echo $_GET['id_link']; ?>">
            <label for="id_filiere">Filière :</label>
            <select id="id_filiere" name="id_filiere" required>
                <?php
                foreach ($result as $r) {
                    echo "<option value='$r->id_filiere'>$r->nom_filiere</option>";
                }
                ?>
            </select>
            <input type="submit" value="Continuer">
        </form>
    </div>
</body>

</html>