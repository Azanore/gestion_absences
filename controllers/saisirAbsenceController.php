<?php
require("../middleware/connexion.php");
if (isset($_POST['formAll'])) {
    for ($i = 0; $i < (count($_POST) - 3) / 4; $i++) {
        $stmt = $cnx->prepare("INSERT INTO absences (id_stagiaire, date_absence, motif, heures_absence) VALUES(:a,:b,:c,:d)");
        $stmt->bindParam(":a", $_POST['id_stagiaire' . $i]);
        $stmt->bindParam(":b", $_POST['date' . $i]);
        $stmt->bindParam(":c", $_POST['status' . $i]);
        $stmt->bindParam(":d", $_POST['nb_abs' . $i]);
        $stmt->execute();
    }
} else {
    $stmt = $cnx->prepare("INSERT INTO absences (id_stagiaire, date_absence, motif, heures_absence) VALUES(:a,:b,:c,:d)");
    $stmt->bindParam(":a", $_POST['id_stagiaire']);
    $stmt->bindParam(":b", $_POST['date']);
    $stmt->bindParam(":c", $_POST['status']);
    $stmt->bindParam(":d", $_POST['nb_abs']);
    $stmt->execute();
}
header('location: ../saisirAbsence.php?id_groupe='.$_POST['id_groupe'].'&date='. $_POST['date_ini']);
