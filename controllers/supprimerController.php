<?php 

require('../middleware/connexion.php');
$stmt = $cnx->prepare('delete from absences where id_absence = :a');
$stmt->bindParam(':a',$_GET['id_absence']);
$stmt->execute();
header("location: ../modifierAbsence.php");
?>