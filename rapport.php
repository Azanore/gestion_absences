<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport d'absences</title>
    <link rel="icon" type="image/x-icon" href="media/ecole.png">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>
    <link rel="stylesheet" href="css/table.css">
    <link rel="stylesheet" href="css/rapport.css">
</head>

<body>
    <?php
    if (!isset($_POST['id_stagiaire'])) {
        header('location:statsStagiaires.php');
        exit();
    }
    require("middleware/auth.php");
    require('middleware/directeurVerify.php');
    require('navBar.php');
    require('middleware/connexion.php');

    try {
        $id_stagiaire = $_POST['id_stagiaire'];
        $date1 = $_POST['date1'];
        $date2 = $_POST['date2'];

        $stmt = $cnx->prepare("SELECT * FROM absences, stagiaires, filieres, groupes 
        WHERE stagiaires.id_groupe = groupes.id_groupe 
        AND filieres.id_filiere = groupes.id_filiere 
        AND absences.id_stagiaire = stagiaires.id_stagiaire 
        AND stagiaires.id_stagiaire = :a 
        AND absences.date_absence >= :b 
        AND absences.date_absence <= :c");

        $stmt->bindParam(":a", $id_stagiaire);
        $stmt->bindParam(":b", $date1);
        $stmt->bindParam(":c", $date2);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt2 = $cnx->prepare("SELECT * FROM stagiaires, filieres, groupes 
        WHERE stagiaires.id_groupe = groupes.id_groupe 
        AND filieres.id_filiere = groupes.id_filiere 
        AND id_stagiaire = :d");
        $stmt2->bindParam(":d", $id_stagiaire);
        $stmt2->execute();
        $result2 = $stmt2->fetchAll(PDO::FETCH_OBJ);

        echo "<section>
        <h2 align='center'>Rapport d'absences du stagiaire :</h2>
        <div id='infosStagiaire'>
            <ul>
                <li>Nom et prénom : <b>" . $result2[0]->nom . " " . $result2[0]->prenom . "</b></li>
                <li>Groupe : <b>" . $result2[0]->nom_groupe . " </b></li>
                <li>Filière : <b>" . $result2[0]->nom_filiere . "</b></li>
            </ul>
        </div>";

        echo "    <div id='table-container'>
<table id='tableData' class='styled-table'><thead><tr>
        <th>Date</th>
        <th>Status</th>
        <th>Nombre d'heures d'absence</th>
        </tr></thead><tbody>";
        if (count($result) > 0) {
            foreach ($result as $r) {
                echo "<tr>
                        <td>$r->date_absence</td>
                        <td>$r->motif</td>
                        <td>$r->heures_absence</td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='3' align='center'>Ce stagiaire ne s'est jamais absenté pendant la période fournie.</td></tr>";
        }
        echo "</tbody></table></div>
                <button onclick='generateAndPreviewPDF()' type='button' id='generate'>Générer le PDF</button>
        </section>";
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
    ?>

    <script>
        function generateAndPreviewPDF() {
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF();

            doc.setFontSize(18);
            const titleText = "Rapport d'absences du stagiaire :";
            const titleWidth = doc.getStringUnitWidth(titleText) * doc.internal.getFontSize() / doc.internal.scaleFactor;
            const titleX = (doc.internal.pageSize.width - titleWidth) / 2;
            doc.text(titleText, titleX, 40);

            doc.setFontSize(12);
            const infosStagiaire = document.getElementById('infosStagiaire').innerText.trim();
            doc.text(infosStagiaire, 10, 55);

            const tableTopMargin = 75;
            doc.autoTable({
                html: '#tableData',
                startY: tableTopMargin,
                theme: 'striped',
                styles: {
                    fontSize: 12,
                    fontStyle: 'normal',
                }
            });
            const pdfDataUri = doc.output('datauristring');
            const pdfWindow = window.open();
            pdfWindow.document.body.innerHTML = `<iframe width="100%" height="100%" src="${pdfDataUri}"></iframe>`;
        }
    </script>
</body>

</html>