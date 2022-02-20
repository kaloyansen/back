START TRANSACTION;

-- ============================================================
--   Suppression et création de la base de données 
-- ============================================================
DROP DATABASE IF EXISTS totobase;
CREATE DATABASE totobase;
USE totobase;

-- ============================================================
--   Création de la table                            
-- ============================================================

CREATE TABLE postit (
    `id` int NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL,
    `body`  text(255) NOT NULL,
    `position` varchar(255) NOT NULL,
    `status` varchar(255) NOT NULL,
    `color` varchar(255) NOT NULL,
    primary key (id)
  );

-- ============================================================
--   Insertion des enregistrements
-- ============================================================

INSERT INTO postit VALUES (NULL, 'title1', 'created by an api rest', 'unknown', 'ok', 'red');
INSERT INTO postit VALUES (NULL, 'title2', 'created by an api rest', 'unknown', 'happy', 'blue');
INSERT INTO postit VALUES (NULL, 'title3', 'created by an api rest', 'unknown', 'sad', 'green');
INSERT INTO postit VALUES (NULL, 'title4', 'created by an api rest', 'unknown', 'ok', 'white');
INSERT INTO postit VALUES (NULL, 'title5', 'created by an api rest', 'unknown', 'ok', 'red');
INSERT INTO postit VALUES (NULL, 'title6', 'created by an api rest', 'unknown', 'dead', 'black');
INSERT INTO postit VALUES (NULL, 'title7', 'created by an api rest', 'unknown', 'ok', 'red');
INSERT INTO postit VALUES (NULL, 'title8', 'created by an api rest', 'unknown', 'sick', 'blue');

commit;
