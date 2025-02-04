<?php
session_start();
require 'includes/db.php';
require 'includes/functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->execute([$username, $password, $role]);

    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>OFPPT | Register</title>
    <link rel="stylesheet" href="css/styles.css">
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
            margin: 30px 160px;
            
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
            color:  #004b93;
            font-weight: 500;
        }

        input , select{
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
        p{
            text-align:center;
        }
    </style>
</head>
<body >
<div >
    <h1>Gestion d'abscence des stagiaires</h1>
    <img src="assets/ofppt-logo.png" alt="logo">    
    <form method="POST" action="">
        <input type="email" id="username" name="username" placeholder="Entrer Username" required>
        <input type="password" id="password" name="password" placeholder="Entrer Password" required>
        <select id="role" name="role">
            <option value="surveillant">Surveillant</option>
            <option value="directeur">Directeur</option>
        </select>
        <button type="submit">S'inscrire</button>
    </form>
    <p>j'ai un compte?<a href="./login.php">Login</a></p>
    </div>
</body>
</html>
