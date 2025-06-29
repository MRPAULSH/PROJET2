<?php
session_start(); //pour commencer une session et stocker les données utilisateur
session_destroy(); // On détruit la session précédente pour éviter les conflits
session_start();
include "connexion.php";

$error = "";

if (isset($_POST['bouton'])) {
    $nom = $_POST['nom'];
    $password = $_POST['password'];

    $recup = $conn->prepare("SELECT * FROM utilisateurs WHERE Nom_utilisateur = ? AND Mot_de_Passe = ?");
    $recup->execute([$nom, $password]);

    if ($recup->rowCount() === 1) {
        $user = $recup->fetch();
        $_SESSION['id'] = $user['id'];
        $_SESSION['Nom_utilisateur'] = $user['Nom_utilisateur'];
        $_SESSION['Role'] = $user['Role'];

        header("Location: accueil.php?id=" . $_SESSION['id']);
        exit;
    } else {
        $error = "Nom d'utilisateur ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <style>
        body {
            font-family: Arial;
            background: url('images/image.jpg');
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 0px 12px #bdc3c7;
            width: 350px;
        }
        .form h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .ftn {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #bdc3c7;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #34495e;
            color: white;
            border: none;
            width: 100%;
            padding: 10px;
            margin-top: 20px;
            cursor: pointer;
            border-radius: 4px;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <form method="post" class="form">
        <h1>S'AUTHENTIFIER</h1>
        <?php if ($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>

        <label>Nom d'utilisateur</label>
        <input type="text" class="ftn" name="nom" required placeholder="Nom d'utilisateur">

        <label>Mot de passe</label>
        <input type="password" class="ftn" name="password" required placeholder="Mot de passe">

        <input type="submit" name="bouton" value="Se connecter">
    </form>
</body>
</html>
