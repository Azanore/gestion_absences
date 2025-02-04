<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de connexion</title>
    <link rel="icon" type="image/x-icon" href="media/ecole.png">
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <?php
    include('middleware/logged.php');
    require('middleware/connexion.php');
    $stmt = $cnx->prepare("SELECT * FROM utilisateurs where role = 'Directeur'");
    $stmt->execute();
    $directeur = $stmt->fetchAll(PDO::FETCH_OBJ);
    $stmt = $cnx->prepare("SELECT * FROM utilisateurs where role = 'Surveillant'");
    $stmt->execute();
    $surveillant = $stmt->fetchAll(PDO::FETCH_OBJ);
    ?>
    <div class="background">
        <div class="login-container">
            <form class="login-form" action='controllers/loginController.php' method='post'>
                <h2>Directeur</h2>
                <label for="email1">E-mail :</label>
                <input type="email" id="email1" name="email" required value="<?php echo count($directeur) > 0 ? $directeur[0]->email : ''; ?>">
                <label for="password1">Mot de passe :</label>
                <input type="password" id="password1" name="mdp" required value="<?php echo count($directeur) > 0 ? $directeur[0]->mot_de_passe : ''; ?>">
                <button type="submit">Se connecter</button>
            </form>
        </div>
        <div class="login-container">
            <form class="login-form" action='controllers/loginController.php' method='post'>
                <h2>Surveillant</h2>
                <label for="email2">E-mail :</label>
                <input type="email" id="email2" name="email" required value="<?php echo count($surveillant) > 0 ? $surveillant[0]->email : ''; ?>">
                <label for="password2">Mot de passe :</label>
                <input type="password" id="password2" name="mdp" required value="<?php echo count($surveillant) > 0 ? $surveillant[0]->mot_de_passe : ''; ?>">
                <button type="submit">Se connecter</button>
            </form>
        </div>
    </div>

</body>

</html>