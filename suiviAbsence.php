<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivi d'absences injustifiées</title>
    <link rel="stylesheet" href="css/table.css">
    <link rel="icon" type="image/x-icon" href="media/ecole.png">
    <link rel="stylesheet" href="css/suiviAbsence.css">
</head>

<body>
    <?php
    require('middleware/auth.php');
    require("navBar.php");
    require("middleware/connexion.php");

    $stmt = $cnx->prepare("SELECT *,sum(absences.heures_absence) as total FROM stagiaires inner join absences on stagiaires.id_stagiaire=absences.id_stagiaire where id_groupe=:id AND motif='injustifiée' group by stagiaires.id_stagiaire");
    $stmt->bindParam(':id', $_GET["id_groupe"]);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_OBJ); ?>

    <div id='filterall'>
        <h2>Filtrer par :</h2>
        <div id='filters'>
            <input id='input1' placeholder='CIN' <?php echo count($result) == 0 ? 'disabled' : ''; ?>>
            <input id='input2' placeholder='Nom et Prénom' <?php echo count($result) == 0 ? 'disabled' : ''; ?>>
            <input id='input3' placeholder='E-mail' <?php echo count($result) == 0 ? 'disabled' : ''; ?>>
            <input id='input4' type='number' min='0' placeholder="Heures d'absence" <?php echo count($result) == 0 ? 'disabled' : ''; ?>>
            <input id='input5' placeholder='Sanction' <?php echo count($result) == 0 ? 'disabled' : ''; ?>>
        </div>
    </div>
    <div id='table-container'>
        <table class='styled-table'>
            <thead>
                <tr>
                    <th>#</th>
                    <th>CIN</th>
                    <th>Nom et Prénom</th>
                    <th>E-mail</th>
                    <th title="Nombre d'heures d'absence injustifiées">Hrs Abs. Injust.</th>
                    <th>Type sanction</th>
                    <th>Contacter stagiaire</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($result) == 0) : ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">Aucun résultat</td>
                    </tr>
                <?php else : ?>

                    <?php
                    $num = 0;
                    foreach ($result as $r) {
                        $num++;
                        $total = $r->total;
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
                        echo "  
        <tr>
        <td>$num</td>
        <td>$r->cin</td>
        <td>$r->nom $r->prenom</td>
        <td>$r->email</td>
        <td>$r->total</td>
        <td>$sanction</td>
        <td><button type='button' class='contacter'>Envoyer un E-mail</td>
        </tr>";
                    } ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <script src="jquery/jquery-3.7.1.js"></script>
    <script>
        $(document).ready(function() {
            var butt = $('.contacter');
            var body = $('body');
            butt.click(function() {
                var tr = $(this).parent().parent().children();
                var fullname = tr.eq(2).text();
                var sujet = tr.eq(5).text();
                var emailStagi = tr.eq(3).text();
                body.append(`
                <section>
                    <article>
                        <label>Envoyer un E-mail à : </label><span>${fullname}</span><hr><br>
                        <label>Sujet : <br><br></label><input type="text" id="sujet"value="${sujet}"><br><br>
                        <label>Contenu : <br><br></label><textarea id="body">    Bonjour ${fullname},

Nous avons remarqué plusieurs absences de votre part récemment. Ces absences risquent de nuire à votre progression académique.
Merci de bien vouloir me contacter pour discuter de votre situation et trouver des solutions.

Cordialement,
Directeur
ISTA Hay Salam</textarea><br><br><br>
                        <div>
                            <button class="envoyer" type="button">Envoyer</button>
                            <button class="cancel" type="button">Cancel</button>
                        </div>
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
                    margin:"auto",
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

                var text = $('article [type="text"]');
                text.css({
                    width: "fit-content",
                    height: "35px",
                    borderRadius: " 5px",
                    padding: "0 10px",
                    textAlign: "center"
                });
                var textarea = $('article textarea');
                textarea.css({
                    height: "100px",
                    width: " 90%",
                    borderRadius: " 5px",
                    padding: "10px",
                    resize: "none",
                    lineHeight: "16px"
                });
                var span = $('section span');
                span.css({
                    color: "#009174",
                    fontWeight: "bold"
                })
                span.css({
                    color: "#009174",
                    fontWeight: "bold"
                })
                var cancel = $('.cancel');
                cancel.click(function() {
                    $(this).parent().parent().parent().remove()
                })
                var envoyer = $('.envoyer');
                envoyer.click(function() {
                    console.log("haha")
                    var confirmed = confirm(`Êtes-vous sûr de vouloir envoyer un E-mail à ${fullname} ?`);
                    if (confirmed) {
                        var sujet1 = $('#sujet').val();
                        var body1 = $('#body').val();
                        body.append(`<a id="link" href="mailto:${emailStagi}?subject=${encodeURIComponent(sujet1)}&body=${encodeURIComponent(body1)}"></a>`)
                        window.location.href = $("#link").attr('href');
                        $("#link").remove();
                        $(this).parent().parent().parent().remove();
                    }
                })
            })

            function filterTable() {
                var input1 = $('#input1').val().toLowerCase();
                var input2 = $('#input2').val().toLowerCase();
                var input3 = $('#input3').val().toLowerCase();
                var input4 = $('#input4').val();
                var input5 = $('#input5').val().toLowerCase();

                $('tbody>tr').each(function() {
                    var td2 = $(this).find('td:nth-child(2)').text().toLowerCase();
                    var td3 = $(this).find('td:nth-child(3)').text().toLowerCase();
                    var td4 = $(this).find('td:nth-child(4)').text().toLowerCase();
                    var td5 = $(this).find('td:nth-child(5)').text().toLowerCase();
                    var td6 = $(this).find('td:nth-child(6)').text().toLowerCase();

                    if (td2.includes(input1) && td3.includes(input2) && td4.includes(input3) && td5.includes(input4) && td6.includes(input5)) {
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
            $('#input1, #input2, #input3, #input4,#input5').on('input', filterTable);
            $('#input1, #input2, #input3, #input4,#input5').on('input', ajoutTR);

        });
    </script>
</body>

</html>