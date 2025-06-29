<?php
include('menu.php');

// c est pour supprimer un utilisateur et on recupere ses infos par la methode get 
if (isset($_GET['supp'])) {
    $id = $_GET['supp'];
    $delete = $bdd->prepare("DELETE FROM utilisateurs WHERE id = ?");
    $delete->execute([$id]);
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Utilisateur supprimé',
            showConfirmButton: false,
            timer: 1500
        });
    </script>";
}
?>

<div class="content" id="content">
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Liste des utilisateurs</h2> <br>
            <a href="utilisateur.php" class="btn btn-success" style="text-decoration:none;">
                <i class="fas fa-user-plus"></i> Ajouter un utilisateur
            </a>
        </div>
<br>

<br> <div class="table-container">
        <table id="products-table" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th style="background-color:#34495e; color:white;">Nom d'utilisateur</th>
                    <th style="background-color:#34495e; color:white;">Mot de passe</th>
                    <th style="background-color:#34495e; color:white;">Rôle</th>
                    <th colspan="2" style="background-color:#34495e; color:white;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($_GET['supp'])) {
                    $id = $_GET['supp'];
                    $delete = $bdd->prepare("DELETE FROM utilisateurs WHERE id = ?");
                    $delete->execute([$id]);

                    echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Utilisateur supprimé',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    </script>";
                }

                $liste = $bdd->query("SELECT * FROM utilisateurs");
                while ($u = $liste->fetch_assoc()) {
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($u['Nom_utilisateur']) ?></td>
                        <td><?php echo htmlspecialchars($u['Mot_de_Passe']) ?></td>
                        <td><?php echo htmlspecialchars($u['Role']) ?></td>
                        <td>
                            <a href="modifier_utilisateur.php?id=<?php echo $u['id'] ?>">
                                <i class="fas fa-edit text-primary" style="color:blue;"></i>
                            </a>
                        </td>
                        <td>
                            <a href="liste_utilisateur.php?supp=<?php echo $u['id'] ?>" onclick="return confirm('Supprimer cet utilisateur ?')">
                                <i class="fas fa-trash text-danger ml-2" style="color:red;"></i>
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

    </div>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
