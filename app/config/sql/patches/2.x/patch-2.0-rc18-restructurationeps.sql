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

-- 0°) Création de la table commissionseps_dossierseps et déplacement de commissionep_id et etatdossierep depuis dossierseps
ALTER TABLE dossierseps ALTER COLUMN etapedossierep TYPE TEXT;
ALTER TABLE dossierseps ALTER COLUMN etapedossierep SET DEFAULT 'cree'::TEXT;
DROP TYPE IF EXISTS TYPE_ETAPEDOSSIEREP;

CREATE TYPE TYPE_ETATDOSSIEREP AS ENUM ( 'associe', 'decisionep', 'decisioncg', 'traite', 'annule', 'reporte' );
CREATE TABLE commissionseps_dossierseps (
	id      				SERIAL NOT NULL PRIMARY KEY,
	commissionep_id			INTEGER NOT NULL REFERENCES commissionseps(id) ON DELETE CASCADE ON UPDATE CASCADE,
	dossierep_id			INTEGER NOT NULL REFERENCES dossierseps(id) ON DELETE CASCADE ON UPDATE CASCADE,
	etatdossierep			TYPE_ETATDOSSIEREP NOT NULL DEFAULT 'associe'::TYPE_ETATDOSSIEREP
);
COMMENT ON TABLE commissionseps_dossierseps IS 'Passage des dossiers d''EP en commissions d''EP';

INSERT INTO commissionseps_dossierseps (commissionep_id, dossierep_id, etatdossierep)
	SELECT
			commissionep_id AS commissionep_id,
			id AS dossierep_id,
			CAST(
				CASE
					WHEN ( etapedossierep = 'seance' ) THEN 'associe'
					ELSE etapedossierep
				END
				AS TYPE_ETATDOSSIEREP
			) AS etatdossierep
		FROM dossierseps
		WHERE etapedossierep NOT IN ( 'cree', '...' );

ALTER TABLE dossierseps DROP COLUMN etapedossierep;

-- 1°)
CREATE TYPE TYPE_ETATCOMMISSIONEP AS ENUM ( 'cree', 'associe', 'decisionep', 'decisioncg', 'traite', 'annule', 'reporte' );
ALTER TABLE commissionseps ADD COLUMN etatcommissionep TYPE_ETATCOMMISSIONEP DEFAULT 'cree'::TYPE_ETATCOMMISSIONEP;

UPDATE commissionseps
	SET etatcommissionep = CAST(
				CASE
					WHEN ( finalisee = 'ep' ) THEN 'decisioncg'
					WHEN ( finalisee = 'cg' ) THEN 'traite'
					ELSE 'cree'
				END
				AS TYPE_ETATCOMMISSIONEP
			);

ALTER TABLE commissionseps DROP COLUMN finalisee;

-- 2°)
ALTER TABLE decisionsreorientationseps93 ADD COLUMN commissionep_dossierep_id INTEGER DEFAULT NULL REFERENCES commissionseps_dossierseps(id) ON DELETE CASCADE ON UPDATE CASCADE;
UPDATE decisionsreorientationseps93 SET commissionep_dossierep_id = (
	SELECT commissionseps_dossierseps.id
		FROM commissionseps_dossierseps, reorientationseps93, dossierseps
		WHERE commissionseps_dossierseps.dossierep_id = reorientationseps93.dossierep_id
		AND commissionseps_dossierseps.commissionep_id = dossierseps.commissionep_id
		AND reorientationseps93.id = decisionsreorientationseps93.reorientationep93_id
		AND reorientationseps93.dossierep_id = dossierseps.id
);
ALTER TABLE dossierseps DROP COLUMN commissionep_id;
ALTER TABLE decisionsreorientationseps93 DROP COLUMN reorientationep93_id;

-- *****************************************************************************

ALTER TABLE eps ALTER COLUMN defautinsertionep66 TYPE TEXT;
ALTER TABLE eps ALTER COLUMN defautinsertionep66 DROP DEFAULT;
ALTER TABLE eps ALTER COLUMN saisinebilanparcoursep66 TYPE TEXT;
ALTER TABLE eps ALTER COLUMN saisinebilanparcoursep66 DROP DEFAULT;
ALTER TABLE eps ALTER COLUMN saisinepdoep66 TYPE TEXT;
ALTER TABLE eps ALTER COLUMN saisinepdoep66 DROP DEFAULT;
ALTER TABLE eps ALTER COLUMN nonrespectsanctionep93 TYPE TEXT;
ALTER TABLE eps ALTER COLUMN nonrespectsanctionep93 DROP DEFAULT;
ALTER TABLE eps ALTER COLUMN reorientationep93 TYPE TEXT;
ALTER TABLE eps ALTER COLUMN reorientationep93 DROP DEFAULT;
ALTER TABLE eps ALTER COLUMN nonorientationproep58 TYPE TEXT;
ALTER TABLE eps ALTER COLUMN nonorientationproep58 DROP DEFAULT;
ALTER TABLE eps ALTER COLUMN regressionorientationep58 TYPE TEXT;
ALTER TABLE eps ALTER COLUMN regressionorientationep58 DROP DEFAULT;
ALTER TABLE eps ALTER COLUMN sanctionep58 TYPE TEXT;
ALTER TABLE eps ALTER COLUMN sanctionep58 DROP DEFAULT;
ALTER TABLE eps ALTER COLUMN nonorientationproep93 TYPE TEXT;
ALTER TABLE eps ALTER COLUMN nonorientationproep93 DROP DEFAULT;
ALTER TABLE eps ALTER COLUMN regressionorientationep93 TYPE TEXT;
ALTER TABLE eps ALTER COLUMN regressionorientationep93 DROP DEFAULT;


DROP TYPE IF EXISTS TYPE_NIVEAUDECISIONEP CASCADE;
CREATE TYPE TYPE_NIVEAUDECISIONEP AS ENUM ( 'nontraite', 'decisionep', 'decisioncg' );

UPDATE eps SET defautinsertionep66 = 'decisionep' WHERE defautinsertionep66 = 'ep';
UPDATE eps SET defautinsertionep66 = 'decisioncg' WHERE defautinsertionep66 = 'cg';
UPDATE eps SET saisinebilanparcoursep66 = 'decisionep' WHERE saisinebilanparcoursep66 = 'ep';
UPDATE eps SET saisinebilanparcoursep66 = 'decisioncg' WHERE saisinebilanparcoursep66 = 'cg';
UPDATE eps SET saisinepdoep66 = 'decisionep' WHERE saisinepdoep66 = 'ep';
UPDATE eps SET saisinepdoep66 = 'decisioncg' WHERE saisinepdoep66 = 'cg';
UPDATE eps SET nonrespectsanctionep93 = 'decisionep' WHERE nonrespectsanctionep93 = 'ep';
UPDATE eps SET nonrespectsanctionep93 = 'decisioncg' WHERE nonrespectsanctionep93 = 'cg';
UPDATE eps SET reorientationep93 = 'decisionep' WHERE reorientationep93 = 'ep';
UPDATE eps SET reorientationep93 = 'decisioncg' WHERE reorientationep93 = 'cg';
UPDATE eps SET nonorientationproep58 = 'decisionep' WHERE nonorientationproep58 = 'ep';
UPDATE eps SET nonorientationproep58 = 'decisioncg' WHERE nonorientationproep58 = 'cg';
UPDATE eps SET regressionorientationep58 = 'decisionep' WHERE regressionorientationep58 = 'ep';
UPDATE eps SET regressionorientationep58 = 'decisioncg' WHERE regressionorientationep58 = 'cg';
UPDATE eps SET sanctionep58 = 'decisionep' WHERE sanctionep58 = 'ep';
UPDATE eps SET sanctionep58 = 'decisioncg' WHERE sanctionep58 = 'cg';
UPDATE eps SET nonorientationproep93 = 'decisionep' WHERE nonorientationproep93 = 'ep';
UPDATE eps SET nonorientationproep93 = 'decisioncg' WHERE nonorientationproep93 = 'cg';
UPDATE eps SET regressionorientationep93 = 'decisionep' WHERE regressionorientationep93 = 'ep';
UPDATE eps SET regressionorientationep93 = 'decisioncg' WHERE regressionorientationep93 = 'cg';

ALTER TABLE eps ALTER COLUMN defautinsertionep66 TYPE TYPE_NIVEAUDECISIONEP USING CAST(defautinsertionep66 AS TYPE_NIVEAUDECISIONEP);
ALTER TABLE eps ALTER COLUMN defautinsertionep66 SET DEFAULT 'nontraite'::TYPE_NIVEAUDECISIONEP;
ALTER TABLE eps ALTER COLUMN saisinebilanparcoursep66 TYPE TYPE_NIVEAUDECISIONEP USING CAST(saisinebilanparcoursep66 AS TYPE_NIVEAUDECISIONEP);
ALTER TABLE eps ALTER COLUMN saisinebilanparcoursep66 SET DEFAULT 'nontraite'::TYPE_NIVEAUDECISIONEP;
ALTER TABLE eps ALTER COLUMN saisinepdoep66 TYPE TYPE_NIVEAUDECISIONEP USING CAST(saisinepdoep66 AS TYPE_NIVEAUDECISIONEP);
ALTER TABLE eps ALTER COLUMN saisinepdoep66 SET DEFAULT 'nontraite'::TYPE_NIVEAUDECISIONEP;
ALTER TABLE eps ALTER COLUMN nonrespectsanctionep93 TYPE TYPE_NIVEAUDECISIONEP USING CAST(nonrespectsanctionep93 AS TYPE_NIVEAUDECISIONEP);
ALTER TABLE eps ALTER COLUMN nonrespectsanctionep93 SET DEFAULT 'nontraite'::TYPE_NIVEAUDECISIONEP;
ALTER TABLE eps ALTER COLUMN reorientationep93 TYPE TYPE_NIVEAUDECISIONEP USING CAST(reorientationep93 AS TYPE_NIVEAUDECISIONEP);
ALTER TABLE eps ALTER COLUMN reorientationep93 SET DEFAULT 'nontraite'::TYPE_NIVEAUDECISIONEP;
ALTER TABLE eps ALTER COLUMN nonorientationproep58 TYPE TYPE_NIVEAUDECISIONEP USING CAST(nonorientationproep58 AS TYPE_NIVEAUDECISIONEP);
ALTER TABLE eps ALTER COLUMN nonorientationproep58 SET DEFAULT 'nontraite'::TYPE_NIVEAUDECISIONEP;
ALTER TABLE eps ALTER COLUMN regressionorientationep58 TYPE TYPE_NIVEAUDECISIONEP USING CAST(regressionorientationep58 AS TYPE_NIVEAUDECISIONEP);
ALTER TABLE eps ALTER COLUMN regressionorientationep58 SET DEFAULT 'nontraite'::TYPE_NIVEAUDECISIONEP;
ALTER TABLE eps ALTER COLUMN sanctionep58 TYPE TYPE_NIVEAUDECISIONEP USING CAST(sanctionep58 AS TYPE_NIVEAUDECISIONEP);
ALTER TABLE eps ALTER COLUMN sanctionep58 SET DEFAULT 'nontraite'::TYPE_NIVEAUDECISIONEP;
ALTER TABLE eps ALTER COLUMN nonorientationproep93 TYPE TYPE_NIVEAUDECISIONEP USING CAST(nonorientationproep93 AS TYPE_NIVEAUDECISIONEP);
ALTER TABLE eps ALTER COLUMN nonorientationproep93 SET DEFAULT 'nontraite'::TYPE_NIVEAUDECISIONEP;
ALTER TABLE eps ALTER COLUMN regressionorientationep93 TYPE TYPE_NIVEAUDECISIONEP USING CAST(regressionorientationep93 AS TYPE_NIVEAUDECISIONEP);
ALTER TABLE eps ALTER COLUMN regressionorientationep93 SET DEFAULT 'nontraite'::TYPE_NIVEAUDECISIONEP;

SELECT public.alter_columnname_ifexists( 'public', 'decisionsreorientationseps93', 'commissionep_dossierep_id', 'passagecommissionep_id' );
SELECT public.alter_tablename_ifexists( 'public', 'commissionseps_dossierseps', 'passagescommissionseps' );
SELECT rename_sequence_ifexists( 'commissionseps_dossierseps', 'passagescommissionseps' );

ALTER TABLE decisionsreorientationseps93 ALTER COLUMN decision TYPE TEXT;
ALTER TABLE decisionssaisinesbilansparcourseps66 ALTER COLUMN decision TYPE TEXT;
DROP TYPE IF EXISTS TYPE_DECISIONEP;
CREATE TYPE TYPE_DECISIONEP AS ENUM ( 'accepte', 'refuse', 'annule', 'reporte' );
ALTER TABLE decisionsreorientationseps93 ALTER COLUMN decision TYPE TYPE_DECISIONEP USING CAST(decision AS TYPE_DECISIONEP);
ALTER TABLE decisionssaisinesbilansparcourseps66 ALTER COLUMN decision TYPE TYPE_DECISIONEP USING CAST(decision AS TYPE_DECISIONEP);

ALTER TABLE commissionseps ALTER COLUMN etatcommissionep TYPE TEXT;
ALTER TABLE commissionseps ALTER COLUMN etatcommissionep DROP DEFAULT;
DROP TYPE IF EXISTS TYPE_ETATCOMMISSIONEP;
CREATE TYPE TYPE_ETATCOMMISSIONEP AS ENUM ( 'cree', 'associe', 'presence', 'decisionep', 'traiteep', 'decisioncg', 'traite', 'annule', 'reporte' );
ALTER TABLE commissionseps ALTER COLUMN etatcommissionep TYPE TYPE_ETATCOMMISSIONEP USING CAST(etatcommissionep AS TYPE_ETATCOMMISSIONEP);
ALTER TABLE commissionseps ALTER COLUMN etatcommissionep SET DEFAULT 'cree'::TYPE_ETATCOMMISSIONEP;

-- -----------------------------------------------------------------------------
-- 20110414: déplacement des niveaux de décision pour chacune des thématiques
-- depuis la table eps vers la table regroupementseps
-- -----------------------------------------------------------------------------
SELECT add_missing_table_field ('public', 'regroupementseps', 'defautinsertionep66', 'type_niveaudecisionep');
ALTER TABLE regroupementseps ALTER COLUMN defautinsertionep66 SET DEFAULT 'nontraite'::type_niveaudecisionep;

SELECT add_missing_table_field ('public', 'regroupementseps', 'saisinebilanparcoursep66', 'type_niveaudecisionep');
ALTER TABLE regroupementseps ALTER COLUMN saisinebilanparcoursep66 SET DEFAULT 'nontraite'::type_niveaudecisionep;

SELECT add_missing_table_field ('public', 'regroupementseps', 'saisinepdoep66', 'type_niveaudecisionep');
ALTER TABLE regroupementseps ALTER COLUMN saisinepdoep66 SET DEFAULT 'nontraite'::type_niveaudecisionep;

SELECT add_missing_table_field ('public', 'regroupementseps', 'nonrespectsanctionep93', 'type_niveaudecisionep');
ALTER TABLE regroupementseps ALTER COLUMN nonrespectsanctionep93 SET DEFAULT 'nontraite'::type_niveaudecisionep;

SELECT add_missing_table_field ('public', 'regroupementseps', 'reorientationep93', 'type_niveaudecisionep');
ALTER TABLE regroupementseps ALTER COLUMN reorientationep93 SET DEFAULT 'nontraite'::type_niveaudecisionep;

SELECT add_missing_table_field ('public', 'regroupementseps', 'nonorientationproep58', 'type_niveaudecisionep');
ALTER TABLE regroupementseps ALTER COLUMN nonorientationproep58 SET DEFAULT 'nontraite'::type_niveaudecisionep;

SELECT add_missing_table_field ('public', 'regroupementseps', 'regressionorientationep58', 'type_niveaudecisionep');
ALTER TABLE regroupementseps ALTER COLUMN regressionorientationep58 SET DEFAULT 'nontraite'::type_niveaudecisionep;

SELECT add_missing_table_field ('public', 'regroupementseps', 'sanctionep58', 'type_niveaudecisionep');
ALTER TABLE regroupementseps ALTER COLUMN sanctionep58 SET DEFAULT 'nontraite'::type_niveaudecisionep;

SELECT add_missing_table_field ('public', 'regroupementseps', 'nonorientationproep93', 'type_niveaudecisionep');
ALTER TABLE regroupementseps ALTER COLUMN nonorientationproep93 SET DEFAULT 'nontraite'::type_niveaudecisionep;

SELECT add_missing_table_field ('public', 'regroupementseps', 'regressionorientationep93', 'type_niveaudecisionep');
ALTER TABLE regroupementseps ALTER COLUMN regressionorientationep93 SET DEFAULT 'nontraite'::type_niveaudecisionep;

UPDATE regroupementseps SET defautinsertionep66 = 'nontraite' WHERE defautinsertionep66 IS NULL;
UPDATE regroupementseps SET saisinebilanparcoursep66 = 'nontraite' WHERE saisinebilanparcoursep66 IS NULL;
UPDATE regroupementseps SET saisinepdoep66 = 'nontraite' WHERE saisinepdoep66 IS NULL;
UPDATE regroupementseps SET nonrespectsanctionep93 = 'nontraite' WHERE nonrespectsanctionep93 IS NULL;
UPDATE regroupementseps SET reorientationep93 = 'nontraite' WHERE reorientationep93 IS NULL;
UPDATE regroupementseps SET nonorientationproep58 = 'nontraite' WHERE nonorientationproep58 IS NULL;
UPDATE regroupementseps SET regressionorientationep58 = 'nontraite' WHERE regressionorientationep58 IS NULL;
UPDATE regroupementseps SET sanctionep58 = 'nontraite' WHERE sanctionep58 IS NULL;
UPDATE regroupementseps SET nonorientationproep93 = 'nontraite' WHERE nonorientationproep93 IS NULL;
UPDATE regroupementseps SET regressionorientationep93 = 'nontraite' WHERE regressionorientationep93 IS NULL;

ALTER TABLE regroupementseps ALTER COLUMN defautinsertionep66 SET NOT NULL;
ALTER TABLE regroupementseps ALTER COLUMN saisinebilanparcoursep66 SET NOT NULL;
ALTER TABLE regroupementseps ALTER COLUMN saisinepdoep66 SET NOT NULL;
ALTER TABLE regroupementseps ALTER COLUMN nonrespectsanctionep93 SET NOT NULL;
ALTER TABLE regroupementseps ALTER COLUMN reorientationep93 SET NOT NULL;
ALTER TABLE regroupementseps ALTER COLUMN nonorientationproep58 SET NOT NULL;
ALTER TABLE regroupementseps ALTER COLUMN regressionorientationep58 SET NOT NULL;
ALTER TABLE regroupementseps ALTER COLUMN sanctionep58 SET NOT NULL;
ALTER TABLE regroupementseps ALTER COLUMN nonorientationproep93 SET NOT NULL;
ALTER TABLE regroupementseps ALTER COLUMN regressionorientationep93 SET NOT NULL;

ALTER TABLE eps DROP COLUMN defautinsertionep66;
ALTER TABLE eps DROP COLUMN saisinebilanparcoursep66;
ALTER TABLE eps DROP COLUMN saisinepdoep66;
ALTER TABLE eps DROP COLUMN nonrespectsanctionep93;
ALTER TABLE eps DROP COLUMN reorientationep93;
ALTER TABLE eps DROP COLUMN nonorientationproep58;
ALTER TABLE eps DROP COLUMN regressionorientationep58;
ALTER TABLE eps DROP COLUMN sanctionep58;
ALTER TABLE eps DROP COLUMN nonorientationproep93;
ALTER TABLE eps DROP COLUMN regressionorientationep93;

-- -----------------------------------------------------------------------------
-- Champs supplémentaires pour la suppression d'une commission d'ep
-- -----------------------------------------------------------------------------

ALTER TABLE commissionseps ADD COLUMN raisonannulation TEXT DEFAULT NULL;
ALTER TABLE decisionsreorientationseps93 ADD COLUMN raisonnonpassage TEXT DEFAULT NULL;

-- Ajout de contraintes d'unicité
CREATE UNIQUE INDEX passagescommissionseps_commissionep_id_dossierep_id_idx ON passagescommissionseps( commissionep_id, dossierep_id );

CREATE UNIQUE INDEX decisionsreorientationseps93_passagecommissionep_id_etape_idx ON decisionsreorientationseps93( passagecommissionep_id, etape );
/*CREATE UNIQUE INDEX decisionssaisinesbilansparcourseps66_passagecommissionep_id_etape_idx ON decisionssaisinesbilansparcourseps66( passagecommissionep_id, etape );
CREATE UNIQUE INDEX decisionssaisinespdoseps66_passagecommissionep_id_etape_idx ON decisionssaisinespdoseps66( passagecommissionep_id, etape );
CREATE UNIQUE INDEX decisionsdefautsinsertionseps66_passagecommissionep_id_etape_idx ON decisionsdefautsinsertionseps66( passagecommissionep_id, etape );
CREATE UNIQUE INDEX decisionsnonrespectssanctionseps93_passagecommissionep_id_etape_idx ON decisionsnonrespectssanctionseps93( passagecommissionep_id, etape );
CREATE UNIQUE INDEX decisionsnonorientationsproseps93_passagecommissionep_id_etape_idx ON decisionsnonorientationsproseps93( passagecommissionep_id, etape );
CREATE UNIQUE INDEX decisionsnonorientationsproseps58_passagecommissionep_id_etape_idx ON decisionsnonorientationsproseps58( passagecommissionep_id, etape );
CREATE UNIQUE INDEX decisionsregressionsorientationseps58_passagecommissionep_id_etape_idx ON decisionsregressionsorientationseps58( passagecommissionep_id, etape );
CREATE UNIQUE INDEX decisionssanctionseps58_passagecommissionep_id_etape_idx ON decisionssanctionseps58( passagecommissionep_id, etape );*/

-- *****************************************************************************
COMMIT;
-- *****************************************************************************