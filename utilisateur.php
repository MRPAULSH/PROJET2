<?php
include('menu.php');

//verifiction des autorisation
$role = $_SESSION['Role'];
$page = 'utilisateurs';

$check = mysqli_query($bdd, "SELECT autorise FROM roles_permissions WHERE role='$role' AND module='$page'");
$perm = mysqli_fetch_assoc($check);

if (!$perm || !$perm['autorise']) {
    die("<h3>Accès refusé</h3>");
}

if (isset($_POST['enregistrer'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $insert = $bdd->prepare("INSERT INTO utilisateurs (Nom_utilisateur, Mot_de_Passe, Role) VALUES (?, ?, ?)");
    $insert->bind_param("sss", $username, $password, $role); // s = string
    $insert->execute();

    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Utilisateur ajouté avec succès',
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

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2 id="modal-title" style="margin: 0;">Ajouter un utilisateur</h2>
    <a href="liste_utilisateur.php" style="text-decoration: none;">
        <button id="add-product" class="btn btn-primary">Liste des utilisateur</button>
    </a>
</div>

        <form method="POST">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="username">Nom d'utilisateur</label>
                    <input type="text" class="form-control" name="username" id="username" required>
                </div>

                <div class="form-group col-md-6">
                    <label for="password">Mot de passe</label>
                    <input type="text" class="form-control" name="password" id="password" required>
                </div>
            </div>

            <div class="form-group">
                <label for="role">Rôle</label>
                <select class="form-control" name="role" id="role" required>
                    <option value="Admin">Admin</option>
                    <option value="auditeur">Auditeur</option>
                    <option value="Boss">Boss</option>
                    <option value="user">User</option>
                </select>
            </div>

            <center>
                <button type="submit" name="enregistrer" class="btn btn-primary">
                <i class="fas fa-save"></i> Enregistrer
            </button>
            </center>
        </form>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Style -->
<style>
.form-wrapper {
    background-color: #f9f9f9;
    padding: 30px;
    max-width: 1000px;
    margin: 20px auto;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    position: relative;
}
h2 {
    color: #2c3e50;
}
.close {
    position: absolute;
    top: 15px;
    right: 20px;
    font-size: 20px;
    cursor: pointer;
    color: #999;
}
.close:hover {
    color: red;
}
</style>
