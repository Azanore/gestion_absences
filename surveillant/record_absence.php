<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';
include '../includes/header.php';
$filiere;
$group_number;
$stagiaires = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['filter'])) {
        $filiere = $_POST['filiere'];
        $group_number = $_POST['group_number'];
        $absence_date = $_POST['absence_date'];

        try {
            $stmt = $conn->prepare("SELECT * FROM stagiaires WHERE filiere = :filiere AND groupe = :group_number");
            $stmt->bindParam(':filiere', $filiere);
            $stmt->bindParam(':group_number', $group_number);
            $stmt->execute();
            $stagiaires = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    if (isset($_POST['create'])) {
        $stagiaire_id = $_POST['stagiaire_id'];
        $absence_date = $_POST['absence_date'];
        $status = $_POST['status'];
        $hours = $_POST['hours'];
        $filiere = $_POST['filiere'];
        $group_number = $_POST['group_number'];

        try {
            $stmt = $conn->prepare("INSERT INTO absences (stagiaire_id, absence_date, status, hours, filiere, group_number) VALUES (:stagiaire_id, :absence_date, :status, :hours, :filiere, :group_number)");
            for ($i = 0; $i < count($stagiaire_id); $i++) {
                $stmt->bindParam(':stagiaire_id', $stagiaire_id[$i]);
                $stmt->bindParam(':absence_date', $absence_date);
                $stmt->bindParam(':status', $status[$i]);
                $stmt->bindParam(':hours', $hours[$i]);
                $stmt->bindParam(':filiere', $filiere);
                $stmt->bindParam(':group_number', $group_number);
                $stmt->execute();
            }

            $message = "Absences ajoutées avec succès!";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>
<style>
    * {
    padding: 0;
    margin: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
    }

    body {
        width: 100%;
    }

    nav {
        display: flex;
        align-items: center;
        justify-content: space-evenly;
        margin: 0 100px;
        margin-top: 15px;
    }

    img {
        width: 100px;
        cursor: pointer;
    }

    ul {
        display: flex;
        gap: 20px;
    }

    li {
        list-style-type: none;
        user-select: none;
        cursor: pointer;
        width: 160px;
        padding: 10px 0;
        text-align: center;
    }

    li:hover {
        border-bottom: 2px solid #004b93;
        background-color: #eeeeee;
    }

    ul a {
        color: #004b93;
        text-decoration: none;
    }

    .container {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        margin-top: 90px;
    }

    .form_1 {
        width: 80%;
        background-color: #eee;
        padding: 30px;
        margin-top:90px;
        display: flex;
        gap: 40px;
        border-left: 4px solid #004b93;
       
    }

    .gapping {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .gapping input,
    .gapping select {
        padding: 8px 15px;
        border-radius: 5px;
        border: none;
        outline: none;
        color: #004b93;
    }

    .gapping input:focus,
    .gapping select:focus {
        border: 2px solid #004b93;
    }

    td select,
    td input {
        padding: 8px 15px;
        border-radius: 5px;
        border: none;
        outline: none;
        color: #004b93;
    }

    .btn button {
        background-color: #fff;
        font-size: 18px;
        color: #008b45;
        border: 1px solid #008b45;
        padding: 8px 15px;
        border-radius: 5px;
        cursor: pointer;
        user-select: none;
    }

    .btn button:hover {
        background-color: #008b45;
        color: #fff;
        border: 1px solid #008b45;
        transition: ease-in 0.2s;
    }

    table {
        margin: 40px 200px;
        width: 60%;
        border-collapse: collapse;
        font-family: Arial, sans-serif;
        font-size: 14px;
        text-align: center;
    }

    table thead {
        background-color: #008b45;
        color: #ffffff;
    }

    table th,
    table td {
        padding: 12px 15px;
        border: 1px solid #dddddd;
    }

    table tbody tr {
        border-bottom: 1px solid #dddddd;
    }

    table tbody tr:hover {
        background-color: #f1f1f1;
    }

    table tbody tr:last-of-type {
        border-bottom: 2px solid #008b45;
    }

    .valid-btn {
        margin:0px 900px;
        background-color: #004b93;
        color: #fff;
        border: none;
        border-radius: 5px;
        padding: 10px 30px;
        cursor: pointer;
        user-select: none;
    }
</style>

<div class="container" style="margin:auto;">
    <?php if (isset($message)) {
        echo "<p class='text-success'>$message</p>";
    } ?>

    <form class="form_1" id="absence-form" method="POST" action="" >
                <div class="gapping">
                    <label for="filiere">Filiere: </label>
                    <select id="filiere" name="filiere" required>
                        <option value="developpement">Developpement Digital</option>
                        <option value="security">Securite Informatique</option>
                        <option value="tm">Reseau informatique</option>
                    </select>
                </div>
                <div class="gapping" >
                    <label for="group_number">Group:</label>
                    <select id="group_number" name="group_number" id="groupe" required>
                        <option value="201">201</option>
                        <option value="103">103</option>
                        <option value="300">300</option>
                    </select>
                </div>
                <div class="gapping">
                    <label for="absence_date">Date:</label>
                    <input type="date" id="absence_date" name="absence_date" id="date_absence" required>
                </div>
            <button type="submit" name="filter" class="btn">Afficher</button>
        </div>
    </form>

    <?php if (!empty($stagiaires)) { ?>
    <form method="post" class="display: flex; flex-direction: column; align-items: center;">
        <input type="hidden" name="absence_date" value="<?php echo htmlspecialchars($_POST['absence_date']); ?>">
        <input type="hidden" name="filiere" value="<?php echo htmlspecialchars($_POST['filiere']); ?>">
        <input type="hidden" name="group_number" value="<?php echo htmlspecialchars($_POST['group_number']); ?>">
        <table id="main-table">
            <thead>
                <th>Nom/Prénom</th>
                <th>CIN</th>
                <th>Status</th>
                <th>Nbr /h absence</th>
            </thead>
            <tbody>
                <?php foreach ($stagiaires as $stagiaire) { ?>
                <tr class="order">
                    <td>
                        <div>
                            <input type="hidden" name="stagiaire_id[]" value="<?php echo $stagiaire['id']; ?>">
                            <input type="text"  name="stagiaire_name[]" value="<?php echo htmlspecialchars($stagiaire['name']); ?>" readonly>
                        </div>
                    </td>
                    <td>
                        <div >
                            <input type="text"  name="cin[]" value="<?php echo htmlspecialchars($stagiaire['cin']); ?>" readonly>
                        </div>
                    </td>
                    <td>
                        <div>
                            <select  name="status[]" required>
                                <option value="présence">presence</option>
                                <option value="absence justifiée">justifie</option>
                                <option value="absence injustifiée">unjustifie</option>
                            </select>
                        </div>
                    </td>
                    <td>
                        <div >
                            <input type="number" name="hours[]" value="0" required>
                        </div>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <button type="submit" name="create" class="valid-btn" height="100px">Valider</button>
    <?php } ?>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
