<?php
session_start();
require 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        if ($user['role'] == 'directeur') {
            header("Location: directeur/dashboard.php");
        } else {
            header("Location: surveillant/record_absence.php");
        }
        exit();
    } else {
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>OFPPT | Login</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        *{
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body{
            display: flex;
            align-items: center;
            flex-direction: column;
            justify-content: center;
            min-height: 90vh;
        }

        .container{ 
            height: auto;
            background-color: #eee;
            padding: 20px;
            border-radius: 5px;
            position: relative;
            display: flex;
            align-items: center;
            flex-direction: column;
        }

        .line{
            background: #004b93;
            position: absolute;
            width: 100%;
            height: 3px;
            left: 0;
            top: 0;
        }

        h1{
            color: #004b93;
            margin: 0px 0px;
            font-size: 30px;
        }

        img{
            width: 150px;
            margin-bottom: 20px;
        }

        form{
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            margin-top: 20px;
            width: 100%;
        }

        form header{
            font-size: 20px;
            color: #004b93;
            font-weight: 500;
        }

        input{
            width: 70%;
            padding: 10px 10px;
            outline: none;
            border: none;
            border-radius: 5px;
        }

        input:focus{
            border: 2px solid #004b93;
        }

        button{
            width: 70%;
            background-color: #008b45;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 0;
            cursor: pointer;
            margin: 10px 0;
        }
    </style>
</head>
<body>
<? include('./includes/header.php'); ?>
    <div class="container" style="width:500px;">
        <div class="line"></div>
        <h1>Gestion d'absence des stagiaires</h1>
        <img src="assets/ofppt-logo.png" >
        <form method="post">
            <header>Authentification</header>
            <input type="username" name="username" placeholder="Entrer email">
            <input type="password" name="password" placeholder="Entrer password">
            <button type="submit">S'authentifier</button>
            <p>Pas de compte? <a href="register.php">S'inscrire</a></p>
        </form>
    </div>
</body>
</html>
