create database projet2;
use projet2;

create table produits(
	id int auto_increment primary key,
	referencex varchar(07),
    nom varchar(20),
    descrption varchar(50),
    prix_achat double,
    prix_vente double,
    quantite int,
    seuil_alerte varchar(3),
    categorie varchar(25),
    etat int default 0
);

select * from produits;

CREATE TABLE roles_permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role VARCHAR(50),
    module VARCHAR(50),
    autorise BOOLEAN DEFAULT 0
);
CREATE TABLE `utilisateurs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `Nom_utilisateur` varchar(100) NOT NULL,
  `Mot_de_Passe` varchar(255) NOT NULL,
  `Role` enum('Admin','auditeur','Boss','user') DEFAULT 'user',
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);
INSERT INTO roles_permissions (role, module, autorise) VALUES
('Admin', 'produits', 1),
('Admin', 'commandes', 1),
('Admin', 'utilisateurs', 1),
('Admin', 'stocks', 1),
('Admin', 'statistiques', 1),
('Admin', 'configuration', 1),

('Boss', 'produits', 1),
('Boss', 'commandes', 1),
('Boss', 'utilisateurs', 1),
('Boss', 'stocks', 1),
('Boss', 'statistiques', 1),
('Boss', 'configuration', 1),

('Auditeur', 'produits', 0),
('Auditeur', 'commandes', 1),
('Auditeur', 'utilisateurs', 0),
('Auditeur', 'stocks', 1),
('Auditeur', 'statistiques', 1),
('Auditeur', 'configuration', 0),

('User', 'produits', 0),
('User', 'commandes', 1),
('User', 'utilisateurs', 0),
('User', 'stocks', 0),
('User', 'statistiques', 1),
('User', 'configuration', 0);

truncate roles_permissions;
select * from roles_permissions;
