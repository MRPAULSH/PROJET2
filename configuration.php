<?php
include('menu.php');
//verifiction des autorisation
$role = $_SESSION['Role'];
$page = 'configuration';

$check = mysqli_query($bdd, "SELECT autorise FROM roles_permissions WHERE role='$role' AND module='$page'");
$perm = mysqli_fetch_assoc($check);

if (!$perm || !$perm['autorise']) {
    die("<h3>Accès refusé</h3>");
}
// Récupération des rôles
$roles = mysqli_query($bdd, "SELECT DISTINCT role FROM roles_permissions");
$modules = ['produits', 'commandes', 'utilisateurs', 'stocks', 'statistiques','configuration'];

if (isset($_POST['save'])) {
    foreach ($_POST['permissions'] as $role => $perms) {
        foreach ($modules as $module) {
            $autorise = isset($perms[$module]) ? 1 : 0;
            mysqli_query($bdd, "UPDATE roles_permissions SET autorise=$autorise WHERE role='$role' AND module='$module'");
        }
    }

    echo "<script>
        Swal.fire('Mis à jour', 'Autorisations modifiées.', 'success');
    </script>";
}
?>

<div class="content" id="content">
    <div class="container mt-4">
        <h2>🔐 Gestion des autorisations</h2>
        <br>
        <form method="post">
        <div class="table-container">
            <table class="table table-bordered">
                <thead >
                    <tr style="background-color:#34495e; color:white;">
                        <th style="background-color:#34495e; color:white;">Module</th>
                        <?php foreach ($roles as $r) { ?>
                            <th style="background-color:#34495e; color:white;"><?php echo $r['role'] ?></th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($modules as $module) { ?>
                        <tr>
                            <td><?php echo ucfirst($module) ?></td>
                            <?php
                            mysqli_data_seek($roles, 0); // repositionner le pointeur
                            foreach ($roles as $r) {
                                $role = $r['role'];
                                $check = mysqli_query($bdd, "SELECT autorise FROM roles_permissions WHERE role='$role' AND module='$module'");
                                $a = mysqli_fetch_assoc($check)['autorise'];
                            ?>
                                <td>
                                    <input type="checkbox" name="permissions[<?php echo $role ?>][<?php echo $module ?>]" <?php echo $a ? 'checked' : '' ?>>
                                </td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                </tbody>
            </table> <br>
            <center><button type="submit" name="save" class="btn btn-primary"><i class="fas fa-save"></i> Enregistrer</button></center>
        </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
