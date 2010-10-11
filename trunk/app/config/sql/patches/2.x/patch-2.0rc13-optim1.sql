SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;
SET search_path = public, pg_catalog;
SET default_tablespace = '';
SET default_with_oids = false;

-- *****************************************************************************
BEGIN;
-- *****************************************************************************

DROP INDEX IF EXISTS prestations_allocataire_rsa_idx;
CREATE INDEX prestations_allocataire_rsa_idx
	ON prestations (personne_id, natprest, rolepers)
	WHERE natprest = 'RSA' AND rolepers IN ( 'DEM', 'CJT' );

DROP INDEX IF EXISTS adressesfoyers_actuelle_rsa_idx;
CREATE INDEX adressesfoyers_actuelle_rsa_idx
	ON adressesfoyers (foyer_id, rgadr)
	WHERE rgadr = '01';

DROP INDEX IF EXISTS situationsdossiersrsa_etatdosrsa_ouvert_idx;
CREATE INDEX situationsdossiersrsa_etatdosrsa_ouvert_idx
	ON situationsdossiersrsa (dossier_id, etatdosrsa)
	WHERE etatdosrsa IN ( '2', '3', '4' );


-------------------------- Ajout du 05/10/2010 -----------------------------

UPDATE apres
	SET eligibiliteapre = 'O'
	WHERE eligibiliteapre = 'N';

-------------------------- Ajout du 08/10/2010 -----------------------------

-- Il est possible que vous ayez à commenter la ou les commande(s) suivante:
ALTER TABLE controlesadministratifs
	ADD COLUMN foyer_id INTEGER NOT NULL;

ALTER TABLE controlesadministratifs
	ADD CONSTRAINT controlesadministratifs_foyer_id_fk
	FOREIGN KEY (foyer_id) REFERENCES foyers (id)
	ON UPDATE CASCADE
	ON DELETE CASCADE;

CREATE INDEX controlesadministratifs_foyer_id_idx
	ON controlesadministratifs (foyer_id);

-- Corrections mauvaises valeurs par défaut pour le type time -> voir avec Gaétan
-- ALTER TABLE comitesapres ALTER COLUMN heurecomite SET DEFAULT now();

ALTER TABLE personnes ADD COLUMN numfixe VARCHAR(14);
ALTER TABLE personnes ADD COLUMN numport VARCHAR(14);


-- *****************************************************************************
COMMIT;
-- *****************************************************************************
