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
$absence_date;
$absences = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['filter'])) {
        $filiere = $_POST['filiere'];
        $group_number = $_POST['group_number'];
        $absence_date = $_POST['absence_date'];

        try {
            $stmt = $conn->prepare("SELECT a.*, s.name, s.cin FROM absences a JOIN stagiaires s ON a.stagiaire_id = s.id WHERE a.filiere = :filiere AND a.group_number = :group_number AND a.absence_date = :absence_date");
            $stmt->bindParam(':filiere', $filiere);
            $stmt->bindParam(':group_number', $group_number);
            $stmt->bindParam(':absence_date', $absence_date);
            $stmt->execute();
            $absences = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    if (isset($_POST['update'])) {
        $absence_ids = $_POST['absence_id'];
        $status = $_POST['status'];
        $hours = $_POST['hours'];

        try {
            $stmt = $conn->prepare("UPDATE absences SET status = :status, hours = :hours WHERE id = :absence_id");
            for ($i = 0; $i < count($absence_ids); $i++) {
                $stmt->bindParam(':status', $status[$i]);
                $stmt->bindParam(':hours', $hours[$i]);
                $stmt->bindParam(':absence_id', $absence_ids[$i]);
                $stmt->execute();
            }

            $message = "Absences mises à jour avec succès!";
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
        display: flex;
        gap: 40px;
        border-left: 4px solid #004b93;
       
    }
    .form_2{
        width: 60%;
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

    .edit-btn {
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
<div class="container">
    <?php if (isset($message)) {
        echo "<p class='text-success'>$message</p>";
    } ?>

    <form method="post" id="absence-form" class="form_1" >
                <div class="gapping">
                    <label for="filiere">Filiere: </label>
                    <select id="filiere" name="filiere" required>
                        <option value="developpement">Developpement Digital</option>
                        <option value="security">Securite Informatique</option>
                        <option value="tm">Reseau informatique</option>
                    </select>
                </div >
                <div class="gapping" >
                    <label for="group_number">Group:</label>
                    <select id="groupe" name="group_number" id="group_number" required>
                        <option value="201">201</option>
                        <option value="103">103</option>
                        <option value="300">300</option>
                    </select>
                </div >
                <div class="gapping">
                    <label for="absence_date">Date:</label>
                    <input type="date" id="absence_date" name="absence_date" id="date_absence" required>
                </div>
                <div class="gapping">
                    <button type="submit" name="filter" class="btn">Afficher</button>
                </div>
            </div>
           
        </div>
    </form>

    <?php if (!empty($absences)) { ?>
    <form method="POST" class="form_2">
        <table id="main-table" class="table table-striped">
            <thead>
                <th>Nom/Prénom</th>
                <th>CIN</th>
                <th>Status</th>
                <th>Nbr /h absence</th>
            </thead>
            <tbody>
                <?php foreach ($absences as $absence) { ?>
                <tr class="order">
                    <td>
                        <div >
                            <input type="hidden" name="absence_id[]" value="<?php echo $absence['id']; ?>">
                            <input type="text" value="<?php echo htmlspecialchars($absence['name']); ?>" readonly>
                        </div>
                    </td>
                    <td>
                        <div >
                            <input type="text"  value="<?php echo htmlspecialchars($absence['cin']); ?>" readonly>
                        </div>
                    </td>
                    <td>
                        <div>
                            <select  name="status[]" required>
                                <option value="absence justifiée" <?php echo $absence['status'] == 'absence justifiée' ? 'selected' : ''; ?>>justifié</option>
                                <option value="absence injustifiée" <?php echo $absence['status'] == 'absence injustifiée' ? 'selected' : ''; ?>>unjustifié</option>
                                <option value="présence" <?php echo $absence['status'] == 'présence' ? 'selected' : ''; ?>>présence</option>
                            </select>
                        </div>
                    </td>
                    <td>
                        <div >
                            <input type="number" name="hours[]" value="<?php echo $absence['hours']; ?>" required>
                        </div>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <button type="submit" name="update" id="edit-btn" class="edit-btn ">Modifier</button>
    </form>
    <?php } ?>
</div>

<?php include '../includes/footer.php'; ?>
