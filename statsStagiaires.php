<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques - Stagiaires</title>
    <link rel="stylesheet" href="css/table.css">
    <link rel="icon" type="image/x-icon" href="media/ecole.png">
    <link rel="stylesheet" href="css/statsStagiaires.css">
</head>

<body>
    <?php
    require("middleware/auth.php");
    require('middleware/directeurVerify.php');
    require('navBar.php');
    require('middleware/connexion.php');

    $query = "
    SELECT stagiaires.*, filieres.*, groupes.*, 
           justified_absences.total AS justified, 
           unjustified_absences.total AS unjustified 
    FROM stagiaires 
    JOIN groupes ON groupes.id_groupe = stagiaires.id_groupe 
    JOIN filieres ON filieres.id_filiere = groupes.id_filiere 
    LEFT JOIN (
        SELECT stagiaires.id_stagiaire, SUM(absences.heures_absence) AS total 
        FROM stagiaires 
        INNER JOIN absences ON stagiaires.id_stagiaire = absences.id_stagiaire 
        WHERE absences.motif = 'justifiée' 
        GROUP BY stagiaires.id_stagiaire
    ) AS justified_absences ON stagiaires.id_stagiaire = justified_absences.id_stagiaire 
    LEFT JOIN (
        SELECT stagiaires.id_stagiaire, SUM(absences.heures_absence) AS total 
        FROM stagiaires 
        INNER JOIN absences ON stagiaires.id_stagiaire = absences.id_stagiaire 
        WHERE absences.motif = 'injustifiée' 
        GROUP BY stagiaires.id_stagiaire
    ) AS unjustified_absences ON stagiaires.id_stagiaire = unjustified_absences.id_stagiaire
";

    if (isset($_GET['id_groupe']) && !empty($_GET['id_groupe'])) {
        $query .= " WHERE groupes.id_groupe = :id_groupe";
    }

    $stmt = $cnx->prepare($query);

    if (isset($_GET['id_groupe']) && !empty($_GET['id_groupe'])) {
        $stmt->bindParam(':id_groupe', $_GET['id_groupe'], PDO::PARAM_INT);
    }

    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_OBJ); ?>

    <div id='filterall'>
        <h2>Filtrer par :</h2>
        <div id='filters'>
            <input id='input1' placeholder='Nom + Prénom' <?php echo count($result) == 0 ? 'disabled' : ''; ?>>
            <input id='input2' placeholder='Filière' <?php echo count($result) == 0 ? 'disabled' : ''; ?>>
            <input id='input3' placeholder='Groupe' <?php echo count($result) == 0 ? 'disabled' : ''; ?>>
            <input id='input4' placeholder='Sanction' <?php echo count($result) == 0 ? 'disabled' : ''; ?>>
        </div>
    </div>
    <div id='table-container'>
        <table class='styled-table'>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom et Prénom</th>
                    <th>Filière</th>
                    <th>Groupe</th>
                    <th title="Nombre d'heures d'absence justifiée">Hrs Abs. Just.</th>
                    <th title="Nombre d'heures d'absence injustifiée">Hrs Abs. Injust.</th>
                    <th>Sanction</th>
                    <th>Rapport</th>

                </tr>
            </thead>
            <tbody>

                <?php if (count($result) == 0) : ?>
                    <tr>
                        <td colspan="8" style="text-align: center;">Aucun résultat</td>
                    </tr>
                <?php else : ?>

                    <?php
                    $num = 0;
                    foreach ($result as $r) {
                        if (!isset($r->justified)) {
                            $r->justified = 0;
                        }
                        if (!isset($r->unjustified)) {
                            $r->unjustified = 0;
                        }

                        $total = $r->unjustified;
                        $sanction = "";

                        switch (true) {
                            case ($total >= 0 && $total < 5):
                                $sanction = "Pas de sanction";
                                break;
                            case ($total >= 5 && $total < 10):
                                $sanction = "1ère Mise en garde";
                                break;
                            case ($total >= 10 && $total < 15):
                                $sanction = "2ème Mise en garde";
                                break;
                            case ($total >= 15 && $total < 20):
                                $sanction = "1ère avertissement";
                                break;
                            case ($total >= 20 && $total < 25):
                                $sanction = "2ème avertissement";
                                break;
                            case ($total >= 25 && $total < 30):
                                $sanction = "Blâme";
                                break;
                            case ($total >= 30 && $total < 35):
                                $sanction = "Exclusion de 2 jours";
                                break;
                            case ($total >= 35 && $total <= 50):
                                $sanction = "Exclusion temporaire ou définitive à l'appréciation du conseil de discipline";
                                break;
                            case ($total > 50):
                                $sanction = "Exclusion définitive";
                                break;
                            default:
                                $sanction = "Pas de sanction";
                                break;
                        }
                        $num++;
                        echo "
    <input type='hidden' value ='$r->id_stagiaire'>
    <tr>
    <td>$num</td>
    <td>$r->nom $r->prenom</td>
    <td>$r->nom_filiere</td>
    <td>$r->nom_groupe</td>
    <td>$r->justified</td>
    <td>$r->unjustified</td>
    <td>$sanction</td>
    <td><button type='button' class='rapport'>Créer</td>
    </tr>";
                    } ?> <?php endif; ?>
            </tbody>
        </table>
    </div>
    <script src="jquery/jquery-3.7.1.js"></script>
    <script>
        $(document).ready(function() {
            var butt = $('.rapport');
            var body = $('body');
            butt.click(function() {
                var tr = $(this).parent().parent().children();
                var fullname = tr.eq(1).text();
                var id_stagiaire = $(this).parent().parent().prev().val();
                body.append(`
                <section>
                    <article>
                        <form action="rapport.php" method="post" >
                        <label>Créer un rapport à propos de : </label><span>${fullname}</span><hr><br>
                        <label>De : <br><br></label><input type="date" name="date1" required><br><br>
                        <label>A : <br><br></label><input type="date" name="date2" required><br><br><br>
                        <input type="hidden" name="id_stagiaire" value ="${id_stagiaire}">
                        <div>
                            <button class="creer" type="submit">Créer</button>
                            <button class="cancel" type="button">Cancel</button>
                        </div>
                        </form>
                    </article>
                </section>`);
                var section = $('section');
                section.css({
                    position: "fixed",
                    background: "rgba(0, 0, 0, 0.5)",
                    left: "50%",
                    top: "50%",
                    height: "100%",
                    width: "95%",
                    margin: "auto",
                    transform: "translate(-50%,-50%)",
                    padding: "20px 40px",
                    zIndex: 10,
                    display: "flex",
                    justifyContent: "center",
                    alignItems: "center"
                });
                var article = $('article');
                article.css({
                    background: "rgba(0, 0, 0, 0.8)",
                    padding: "20px 40px",
                    borderRadius: "10 px",
                    boxShadow: "0 2 px 4 px rgba(0, 0, 0, 0.1)",
                    color: "white",
                    width: "400px",
                    textAlign: "center",
                    borderRadius: "10px",
                });
                var butdiv = $('article div');
                butdiv.css({
                    display: "flex",
                });
                var buts = $('article div button');
                buts.css({
                    width: " 100%",
                    padding: " 10px",
                    backgroundColor: " #009174",
                    border: " none",
                    color: " white",
                    borderRadius: " 5px",
                    cursor: " pointer",
                    fontSize: " 16px",
                    margin: "0 20px"
                });
                buts.mouseover(function() {
                    $(this).css({
                        backgroundColor: "#007A63"
                    })
                })
                buts.mouseout(function() {
                    $(this).css({
                        backgroundColor: "#009174"
                    })
                })
                var date = $('article input[type="date"]');
                date.css({
                    width: " 200px",
                    height: "35px",
                    borderRadius: " 5px",
                    padding: "0 10px",
                });
                var span = $('section span');
                span.css({
                    color: "#009174",
                    fontWeight: "bold"
                })
                var cancel = $('.cancel');
                cancel.click(function() {
                    $(this).parent().parent().parent().parent().remove()
                })
            })
            function filterTable() {
                var input1 = $('#input1').val().toLowerCase();
                var input2 = $('#input2').val().toLowerCase();
                var input3 = $('#input3').val().toLowerCase();
                var input4 = $('#input4').val().toLowerCase();

                $('tbody>tr').each(function() {
                    var td2 = $(this).find('td:nth-child(2)').text().toLowerCase();
                    var td3 = $(this).find('td:nth-child(3)').text().toLowerCase();
                    var td4 = $(this).find('td:nth-child(4)').text().toLowerCase();
                    var td7 = $(this).find('td:nth-child(7)').text().toLowerCase();

                    if (td2.includes(input1) && td3.includes(input2) && td4.includes(input3) && td7.includes(input4)) {
                        $(this).show();
                        if ($(".tradd")) {
                            $('.tradd').remove();
                        }
                    } else {
                        $(this).hide();
                    }
                });
            }

            function ajoutTR() {
                tr = $('table tbody tr:visible');
                if (tr.length == 0) {
                    $("table tbody").append('<tr class="tradd"><td colspan="7" style="text-align: center;">Aucun résultat</td></tr>')
                }
            }
            $('#input1, #input2, #input3, #input4').on('input', filterTable);
            $('#input1, #input2, #input3, #input4').on('input', ajoutTR);

        });
    </script>
</body>

</html>