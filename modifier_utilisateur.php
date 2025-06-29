<?php
include('menu.php');

$role = $_SESSION['Role'];
$page = 'utilisateurs';

$check = mysqli_query($bdd, "SELECT autorise FROM roles_permissions WHERE role='$role' AND module='$page'");
$perm = mysqli_fetch_assoc($check);

if (!$perm || !$perm['autorise']) {
    die("<h3>Accès refusé</h3>");
}





if (!isset($_GET['id'])) {
    echo "ID utilisateur manquant.";
    exit;
}

$id = $_GET['id'];

// Récupération des données de l'utilisateur
$stmt = $bdd->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->bind_param("i", $id); // "i" pour un entier
$stmt->execute();
$result = $stmt->get_result();
$utilisateur = $result->fetch_assoc();

if (!$utilisateur) {
    echo "Utilisateur introuvable.";
    exit;
}

// Traitement de la modification
if (isset($_POST['modifier'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $modif = $bdd->prepare("UPDATE utilisateurs SET Nom_utilisateur=?, Mot_de_Passe=?, Role=? WHERE id=?");
    $modif->bind_param("sssi", $username, $password, $role, $id); // 3 strings et 1 int
    $modif->execute();

    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Utilisateur modifié avec succès',
            showConfirmButton: false,
            timer: 2000
        }).then(() => {
            window.location.href='liste_utilisateur.php';
        });
    </script>";
}
?>

<div class="content" id="content">
    <div class="form-wrapper">
        <span class="close" onclick="window.location.href='liste_utilisateur.php';">&times;</span>
        <h2>Modifier l'utilisateur</h2>
        <form method="POST">
            <div class="form-group">
                <label>Nom d'utilisateur</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($utilisateur['Nom_utilisateur']) ?>" required class="form-control">
            </div>

            <div class="form-group">
                <label>Mot de passe</label>
                <input type="text" name="password" value="<?php echo htmlspecialchars($utilisateur['Mot_de_Passe']) ?>" required class="form-control">
            </div>

            <div class="form-group">
                <label>Rôle</label>
                <select name="role" class="form-control" required>
                    <option value="Admin" <?php echo $utilisateur['Role'] == 'Admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="auditeur" <?php echo $utilisateur['Role'] == 'auditeur' ? 'selected' : '' ?>>auditeur</option>
                    <option value="Boss" <?php echo $utilisateur['Role'] == 'Boss' ? 'selected' : '' ?>>Boss</option>
                    <option value="user" <?php echo $utilisateur['Role'] == 'user' ? 'selected' : '' ?>>user</option>
                </select>
            </div>

            <center>
                <button type="submit" name="modifier" class="btn btn-primary mt-3">Enregistrer les modifications</button>
            </center>
        </form>
    </div>
</div>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<style>
    .form-wrapper {
        max-width: 1000px;
        margin: 40px auto;
        background: #f9f9f9;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        position: relative;
    }

    .form-wrapper h2 {
        margin-bottom: 20px;
        text-align: center;
        color: #2c3e50;
    }

    .close {
        position: absolute;
        top: 15px;
        right: 20px;
        font-size: 22px;
        cursor: pointer;
        color: #aaa;
    }

    .close:hover {
        color: red;
    }
</style>
