START TRANSACTION;

-- ============================================================
--   Suppression et création de la base de données 
-- ============================================================
DROP DATABASE IF EXISTS tickets;
CREATE DATABASE tickets;
USE tickets;

-- ============================================================
--   Création de la table                            
-- ============================================================

CREATE TABLE postit (
    `id` int NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL,
    `body`  text(255) NOT NULL,
    `ActualPosition` varchar(255) NOT NULL,
    `status` varchar(255) NOT NULL,
    `color` varchar(255) NOT NULL,
    primary key (id)
  );

-- ============================================================
--   Insertion des enregistrements
-- ============================================================

INSERT INTO postit VALUES (1, 'ceci est le 1er ticket', 'ticket créé via l\'API Rest', 'A faire', 'A faire', 'R');
INSERT INTO postit VALUES (2, 'ceci est le 2ème ticket', '2 ème ticket créé via l\'API Rest', 'Fait', 'A tester', 'G');

commit;
