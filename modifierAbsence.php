<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modification d'absences</title>
    <link rel="stylesheet" href="css/table.css">
    <link rel="icon" type="image/x-icon" href="media/ecole.png">
    <link rel="stylesheet" href="css/modifierAbsence.css">
</head>

<body>
    <?php
    require('middleware/auth.php');
    require("navBar.php");
    require('middleware/connexion.php');

    $stmt = $cnx->prepare("SELECT * FROM absences, stagiaires WHERE absences.id_stagiaire = stagiaires.id_stagiaire ORDER BY absences.id_absence DESC");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_OBJ);
    ?>
    <div id='filterall'>
        <h2>Filtrer par :</h2>
        <div id='filters'>
            <input id='input1' placeholder='CIN' <?php echo count($result) == 0 ? 'disabled' : ''; ?>>
            <input id='input2' placeholder='Nom + Prénom' <?php echo count($result) == 0 ? 'disabled' : ''; ?>>
            <input id='input3' type='date' placeholder="Date d'absence" <?php echo count($result) == 0 ? 'disabled' : ''; ?>>
            <select id='input4' <?php echo count($result) == 0 ? 'disabled' : ''; ?>>
                <option value=''>--- Status ---</option>
                <option value='Justifiée'>Justifiée</option>
                <option value='Injustifiée'>Injustifiée</option>
                <option value='Présence'>Présence</option>
            </select>
            <input id='input5' type='number' min='0' placeholder="Heures d'absence" <?php echo count($result) == 0 ? 'disabled' : ''; ?>>
        </div>
    </div>
    <div id='table-container'>
        <table class='styled-table'>
            <thead>
                <tr>
                    <th>#</th>
                    <th>CIN</th>
                    <th>Nom et Prénom</th>
                    <th>Date d'absence</th>
                    <th>Status</th>
                    <th title="Nombre d'heures d'absence">Hrs Abs.</th>
                    <th>Modifier</th>
                    <th>Supprimer</th>

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
                        $num++;
                        echo "<form action='controllers/modifierAbsenceController.php' method='post'>
                <tr>
                    <td>$num</td>
                    <td><input name='id_absence' type='hidden' value='$r->id_absence'>$r->cin</td>
                    <td>$r->nom $r->prenom</td>
                    <td><input type='date' value='$r->date_absence' name='date' required></td>
                    <td>
                        <select name='status'>
                            <option value='Justifiée' " . ($r->motif == 'Justifiée' ? 'selected' : '') . ">Justifiée</option>
                            <option value='Injustifiée' " . ($r->motif == 'Injustifiée' ? 'selected' : '') . ">Injustifiée</option>
                            <option value='Présence' " . ($r->motif == 'Présence' ? 'selected' : '') . ">Présence</option>
                        </select>
                    </td>
                    <td><input type='number' name='nb_abs' value='$r->heures_absence' min='0' required></td>
                    <td><button type='submit'>Modifier</button></td>
                    <td><button type='button' class='delete' value='controllers/supprimerController.php?id_absence=$r->id_absence' type='button'>Supprimer</button></td>
                </tr>
                </form>";
                    }
                    ?>
                <?php endif; ?>

            </tbody>
        </table>
    </div>
    <button id='modifier'>Tout modifier</button>
    <script src="jquery/jquery-3.7.1.js"></script>

    <script>
        $(document).ready(function() {
            var forms = document.forms;
            var butt = document.querySelector('#modifier');
            var bodyx = document.body;

            butt.addEventListener('click', function() {
                var values = [];
                for (var i = 0; i < forms.length; i++) {
                    if (forms[i].nextElementSibling.style.display != 'none') {
                        var val = [];
                        for (var j = 0; j < forms[i].elements.length; j++) {
                            if (forms[i].elements[j].name) {
                                val.push(forms[i].elements[j].value)
                            }
                        }
                        values.push(val);
                    }
                }
                var formu = document.createElement('form');
                formu.setAttribute('action', 'controllers/modifierAbsenceController.php');
                formu.setAttribute('method', 'post');
                for (var i = 0; i < values.length; i++) {
                    var input = document.createElement('input');
                    input.setAttribute('name', 'id_absence' + i);
                    input.setAttribute('hidden', 'hidden');
                    input.value = values[i][0];
                    formu.appendChild(input);
                    var input = document.createElement('input');
                    input.setAttribute('name', 'date' + i);
                    input.setAttribute('hidden', 'hidden');
                    input.value = values[i][1];
                    formu.appendChild(input);
                    var input = document.createElement('input');
                    input.setAttribute('name', 'status' + i);
                    input.setAttribute('hidden', 'hidden');
                    input.value = values[i][2];
                    formu.appendChild(input);
                    var input = document.createElement('input');
                    input.setAttribute('name', 'nb_abs' + i);
                    input.setAttribute('hidden', 'hidden');
                    input.value = values[i][3];
                    formu.appendChild(input);
                }
                var input = document.createElement('input');
                input.setAttribute('name', 'formAll');
                input.setAttribute('hidden', 'hidden');
                formu.appendChild(input);
                bodyx.appendChild(formu);
                formu.submit();
            })

            function filterTable() {
                var input1 = $('#input1').val().toLowerCase();
                var input2 = $('#input2').val().toLowerCase();
                var input3 = $('#input3').val();
                var input4 = $('#input4').val();
                var input5 = $('#input5').val();

                $('tbody>tr').each(function() {
                    var td2 = $(this).find('td:nth-child(2)').text().toLowerCase();
                    var td3 = $(this).find('td:nth-child(3)').text().toLowerCase();
                    var td4 = $(this).find('td:nth-child(4)>input').val();
                    var td5 = $(this).find('td:nth-child(5) select').val();
                    var td6 = $(this).find('td:nth-child(6)>input').val();

                    if (td2.includes(input1) && td3.includes(input2) && td4.includes(input3) && (input4 === '' || td5 === input4) && td6.includes(input5)) {
                        $(this).show();
                        if ($(".tradd")) {
                            $('.tradd').remove();
                        }
                    } else {
                        $(this).hide();
                    }
                });
            }
            $(".delete").click(function(e) {
                var confirmer = confirm('Etes-vous sûr de vouloir supprimer cette absence ?')
                if (confirmer) {
                    window.location.href = $(this).attr('value');
                }
            })

            function ajoutTR() {
                tr = $('table tbody tr:visible');
                if (tr.length == 0) {
                    $("table tbody").append('<tr class="tradd"><td colspan="8" style="text-align: center;">Aucun résultat</td></tr>')
                }
            }
            $('#input1, #input2, #input3, #input4,#input5').on('input', filterTable);
            $('#input1, #input2, #input3, #input4,#input5').on('input', ajoutTR);
        })
    </script>
</body>

</html>