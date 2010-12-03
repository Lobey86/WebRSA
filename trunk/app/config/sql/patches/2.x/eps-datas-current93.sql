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

INSERT INTO regroupementseps ( name ) VALUES
	( 'CLI 1' );

INSERT INTO eps ( name, regroupementep_id, saisineepreorientsr93 ) VALUES
	( 'CLI 1, équipe 1.1', 1, 'ep' );

INSERT INTO fonctionsmembreseps ( name ) VALUES
	( 'Chef de projet de ville' ),
	( 'Représentant de Pôle Emploi' ),
	( 'Chargé d''insertion' );

INSERT INTO membreseps ( ep_id, fonctionmembreep_id, qual, nom, prenom ) VALUES
	( 1, 1, 'Mlle.', 'Dupont', 'Anne' ),
	( 1, 1, 'M.', 'Martin', 'Pierre' ),
	( 1, 2, 'M.', 'Dubois', 'Alphonse' ),
	( 1, 2, 'Mme.', 'Roland', 'Adeline' );

INSERT INTO eps_zonesgeographiques ( ep_id, zonegeographique_id ) VALUES
	( 1, 14 ), -- EPINAY-SUR-SEINE
	( 1, 31 ), -- PIERREFITTE-SUR-SEINE
	( 1, 36 ); -- SAINT-OUEN

INSERT INTO motifsreorients ( name ) VALUES
	( 'Motif réorientation 1' ),
	( 'Motif réorientation 2' );

SELECT pg_catalog.setval('seanceseps_id_seq', 1, true);
INSERT INTO seanceseps VALUES ( 1, 1, 104, '2010-10-28 10:00:00', NULL );

TRUNCATE situationspdos CASCADE;
SELECT pg_catalog.setval('situationspdos_id_seq', ( SELECT COALESCE( max(situationspdos.id) + 1, 1 ) FROM situationspdos ), false);
INSERT INTO situationspdos (libelle) VALUES
	('Evaluation revenus non salariés')
;

TRUNCATE statutspdos CASCADE;
SELECT pg_catalog.setval('statutspdos_id_seq', ( SELECT COALESCE( max(statutspdos.id) + 1, 1 ) FROM statutspdos ), false);
INSERT INTO statutspdos (libelle) VALUES
	('TI')
;

TRUNCATE descriptionspdos CASCADE;
SELECT pg_catalog.setval('descriptionspdos_id_seq', ( SELECT COALESCE( max(descriptionspdos.id) + 1, 1 ) FROM descriptionspdos ), false);
INSERT INTO descriptionspdos (name, dateactive, declencheep) VALUES
	('Courrier à l\'allocataire', 'datedepart', '0'),
	('Pièces arrivées', 'datereception', '0'),
	('Courrier Révision de ressources', 'datedepart', '0'),
	('Enquête administrative demandée', 'datedepart', '0'),
	('Enquête administrative reçue', 'datereception', '0'),
	('Saisine EP Dépt', 'datedepart', '1')
;

-- *****************************************************************************
COMMIT;
-- *****************************************************************************
