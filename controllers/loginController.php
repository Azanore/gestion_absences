<?php

require('../middleware/connexion.php');
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (!empty($_POST['email']) &&  !empty($_POST['mdp'])) {
        $stmt = $cnx->prepare("SELECT * from utilisateurs where email=:a AND mot_de_passe=:b");
        $stmt->bindParam(":a", $_POST['email']);
        $mdp = $_POST['mdp'];
        $stmt->bindParam(":b", $mdp);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        if (count($result) > 0) {
            session_start();
            $_SESSION['authUser'] = $result[0];
            header('location: ../home.php');
        } else {
            header('location: ../login.php');
        }
    } else {
        header('location: ../login.php');
    }
} else {
    header('location: ../login.php');
}
