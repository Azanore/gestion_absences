<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saisie d'absences</title>
    <link rel="stylesheet" href="css/table.css">
    <link rel="icon" type="image/x-icon" href="media/ecole.png">
    <link rel="stylesheet" href="css/saisirAbsence.css">
</head>

<body>
    <?php
    require('middleware/auth.php');
    require("navBar.php");
    require("middleware/connexion.php");

    $stmt = $cnx->prepare("SELECT * FROM stagiaires WHERE id_groupe=:id");
    $stmt->bindParam(':id', $_GET["id_groupe"]);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_OBJ);
    ?>
    <div id='filterall'>
        <h2>Filtrer par :</h2>
        <div id='filters'>
            <input id='input1' placeholder='CIN' <?php echo count($result) == 0 ? 'disabled' : ''; ?>>
            <input id='input2' placeholder='Nom + Prénom' <?php echo count($result) == 0 ? 'disabled' : ''; ?>>
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
                    <th>Action</th>
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
                        echo "<form action='controllers/saisirAbsenceController.php' method='post'>    
                <input type='hidden' name='id_stagiaire'  value='$r->id_stagiaire'>
                <tr>
                    <td>$num</td>
                    <td>$r->cin</td>
                    <td>$r->nom $r->prenom</td>
                    <td><input name='date' type='date' value='" . $_GET["date"] . "' required></td>
                    <td>
                        <select name='status' required>
                            <option>Justifiée</option>
                            <option>Injustifiée</option>
                            <option>Présence</option>
                        </select>
                    </td>
                    <td><input type='number' name='nb_abs' value='0' min='0' required></td>
                    <td><button type='submit'>Valider</button></td>
                </tr>
                <input type='hidden' name='id_groupe' value='" . $_GET["id_groupe"] . "'>
                <input type='hidden' name='date_ini' value='" . $_GET["date"] . "'>
            </form>";
                    }
                    ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <button id='valider'>Tout valider</button>

    <script src="jquery/jquery-3.7.1.js"></script>
    <script>
        $(document).ready(function() {
            var forms = document.querySelectorAll("tbody form");
            var butt = document.querySelector('#valider');
            var bodyx = document.body;
            butt.addEventListener('click', function() {
                var values = [];
                for (var i = 0; i < forms.length; i++) {
                    console.log(forms[i])
                    if (forms[i].nextElementSibling.nextElementSibling.style.display != 'none') {
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
                formu.setAttribute('action', 'controllers/saisirAbsenceController.php');
                formu.setAttribute('method', 'post');
                for (var i = 0; i < values.length; i++) {
                    var input = document.createElement('input');
                    input.setAttribute('name', 'id_stagiaire' + i);
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

                let params = new URLSearchParams(window.location.search);
                let idGroupe = params.get('id_groupe');
                let date = params.get('date');

                var input = document.createElement('input');
                input.setAttribute('name', 'id_groupe');
                input.setAttribute('hidden', 'hidden');
                input.value = idGroupe;
                formu.appendChild(input);

                var input = document.createElement('input');
                input.setAttribute('name', 'date_ini');
                input.setAttribute('hidden', 'hidden');
                input.value = date;
                formu.appendChild(input);

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
                $('tbody>tr').each(function() {
                    var td2 = $(this).find('td:nth-child(2)').text().toLowerCase();
                    var td3 = $(this).find('td:nth-child(3)').text().toLowerCase();
                    if (td2.includes(input1) && td3.includes(input2)) {
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
            $('#input1, #input2').on('input', filterTable);
            $('#input1, #input2').on('input', ajoutTR);

        })
    </script>
</body>

</html>