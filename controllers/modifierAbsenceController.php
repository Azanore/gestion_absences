<?php
require("../middleware/connexion.php");
if (isset($_POST['formAll'])) {
    for ($i = 0; $i < ((count($_POST)) - 1) / 4; $i++) {
        $stmt = $cnx->prepare("UPDATE  absences set  date_absence=:a, motif=:b, heures_absence=:c where id_absence=:d");
        $stmt->bindParam(":a", $_POST['date' . $i]);
        $stmt->bindParam(":b", $_POST['status' . $i]);
        $stmt->bindParam(":c", $_POST['nb_abs' . $i]);
        $stmt->bindParam(":d", $_POST['id_absence' . $i]);
        $stmt->execute();
    }
} else {
    $stmt = $cnx->prepare("UPDATE absences set motif= :a,heures_absence=:b, date_absence=:c where id_absence=:d");
    $stmt->bindParam(":a", $_POST['status']);
    $stmt->bindParam(":b", $_POST['nb_abs']);
    $stmt->bindParam(":c", $_POST['date']);
    $stmt->bindParam(":d", $_POST['id_absence']);
    $stmt->execute();
}
header('location: ../modifierAbsence.php');
