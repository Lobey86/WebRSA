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
SELECT add_missing_table_field ('public', 'traitementspcgs66', 'reversedo', 'TYPE_BOOLEANNUMBER');
ALTER TABLE traitementspcgs66 ALTER COLUMN reversedo SET DEFAULT '0'::TYPE_BOOLEANNUMBER;
UPDATE traitementspcgs66 SET reversedo = '0'::TYPE_BOOLEANNUMBER WHERE reversedo IS NULL;
ALTER TABLE traitementspcgs66 ALTER COLUMN reversedo SET NOT NULL;


SELECT add_missing_table_field ('public', 'bilansparcours66', 'personne_id', 'INTEGER');
SELECT add_missing_constraint ('public', 'bilansparcours66', 'bilansparcours66_personne_id_fkey', 'personnes', 'personne_id');
UPDATE bilansparcours66
	SET personne_id = (
		SELECT orientsstructs.personne_id
			FROM orientsstructs
			WHERE orientsstructs.id = orientstruct_id
	) WHERE personne_id IS NULL;
ALTER TABLE bilansparcours66 ALTER COLUMN personne_id SET NOT NULL;

-------------------------------------------------------------------------------------------------------------
-- 20120220 : Ajout de la clé primaire decisiondefautinsertionep66 dans le dossier PCGs
--				une fois ce dernier généré par l'avis émis par l'EP
-------------------------------------------------------------------------------------------------------------
SELECT add_missing_table_field ('public', 'dossierspcgs66', 'decisiondefautinsertionep66_id', 'INTEGER');
SELECT add_missing_constraint ('public', 'dossierspcgs66', 'dossierspcgs66_decisiondefautinsertionep66_id_fkey', 'decisionsdefautsinsertionseps66', 'decisiondefautinsertionep66_id');
DROP INDEX IF EXISTS dossierspcgs66_decisiondefautinsertionep66_id_idx;
CREATE UNIQUE INDEX dossierspcgs66_decisiondefautinsertionep66_id_idx ON dossierspcgs66 (decisiondefautinsertionep66_id);

SELECT alter_table_drop_column_if_exists('public', 'questionspcgs66', 'descriptionpdo_id');
SELECT add_missing_table_field ('public', 'questionspcgs66', 'decisionpcg66_id', 'INTEGER');
SELECT add_missing_constraint ('public', 'questionspcgs66', 'questionspcgs66_decisionpcg66_id_fkey', 'decisionspcgs66', 'decisionpcg66_id');

SELECT add_missing_table_field ('public', 'decisionsdossierspcgs66', 'defautinsertion', 'TYPE_DEFAUTINSERTIONPCG66');
SELECT add_missing_table_field ('public', 'decisionsdossierspcgs66', 'recidive', 'TYPE_NO');
SELECT add_missing_table_field ('public', 'decisionsdossierspcgs66', 'phase', 'TYPE_PHASEPCG66');
SELECT add_missing_table_field ('public', 'decisionsdossierspcgs66', 'compofoyerpcg66_id', 'INTEGER');
SELECT add_missing_constraint ('public', 'decisionsdossierspcgs66', 'decisionsdossierspcgs66_compofoyerpcg66_id_fkey', 'composfoyerspcgs66', 'compofoyerpcg66_id');

-------------------------------------------------------------------------------------------------------------
-- 20120222 : Ajout d'une valeur activ/inactif pour les informations paramétrables des dossiers PCGs 66
--			dont on n'aurait plus besoin apr la suite
-------------------------------------------------------------------------------------------------------------
SELECT add_missing_table_field ('public', 'composfoyerspcgs66', 'actif', 'TYPE_NO');
ALTER TABLE composfoyerspcgs66 ALTER COLUMN actif SET DEFAULT 'O';
UPDATE composfoyerspcgs66 SET actif = 'O' WHERE actif IS NULL;
ALTER TABLE composfoyerspcgs66 ALTER COLUMN actif SET NOT NULL;

SELECT add_missing_table_field ('public', 'decisionspcgs66', 'actif', 'TYPE_NO');
ALTER TABLE decisionspcgs66 ALTER COLUMN actif SET DEFAULT 'O';
UPDATE decisionspcgs66 SET actif = 'O' WHERE actif IS NULL;
ALTER TABLE decisionspcgs66 ALTER COLUMN actif SET NOT NULL;

-------------------------------------------------------------------------------------------------------------
-- 20120223 : Ajout d'une clé primaire pointant sur la table des décisions PCGs paramétrabless
-------------------------------------------------------------------------------------------------------------
SELECT add_missing_table_field ('public', 'decisionsdossierspcgs66', 'decisionpcg66_id', 'INTEGER');
SELECT add_missing_constraint ('public', 'decisionsdossierspcgs66', 'decisionsdossierspcgs66_decisionpcg66_id_fkey', 'decisionspcgs66', 'decisionpcg66_id');

-- Correction: les valeurs "suspensiondefaut" et "suspensionnonrespect" étaient inversées avec les traductions.
SELECT public.alter_enumtype ( 'TYPE_DECISIONDEFAUTINSERTIONEP66', ARRAY['suspensionnonrespect','suspensiondefaut','maintien','reorientationprofverssoc','reorientationsocversprof','annule','reporte', 'suspensionnonrespecttmp','suspensiondefauttmp'] );

UPDATE decisionsdefautsinsertionseps66 SET decision = 'suspensionnonrespecttmp' WHERE decision = 'suspensiondefaut';
UPDATE decisionsdefautsinsertionseps66 SET decision = 'suspensiondefauttmp' WHERE decision = 'suspensionnonrespect';
UPDATE decisionsdefautsinsertionseps66 SET decision = 'suspensiondefaut' WHERE decision = 'suspensiondefauttmp';
UPDATE decisionsdefautsinsertionseps66 SET decision = 'suspensionnonrespect' WHERE decision = 'suspensionnonrespecttmp';

SELECT public.alter_enumtype ( 'TYPE_DECISIONDEFAUTINSERTIONEP66', ARRAY['suspensionnonrespect','suspensiondefaut','maintien','reorientationprofverssoc','reorientationsocversprof','annule','reporte'] );

-- Correction: ...
SELECT public.alter_enumtype ( 'TYPE_DEFAUTINSERTIONPCG66', ARRAY['nc_cg','nc_pe','nr_cg','nr_pe','nc_no', 'suspensiondefaut_audition_orientation', 'suspensiondefaut_auditionpe', 'suspensionnonrespect_audition', 'suspensionnonrespect_auditionpe', 'suspensiondefaut_audition_nonorientation'] );

UPDATE questionspcgs66 SET defautinsertion = 'suspensiondefaut_audition_orientation' WHERE defautinsertion = 'nc_cg';
UPDATE questionspcgs66 SET defautinsertion = 'suspensiondefaut_auditionpe' WHERE defautinsertion = 'nc_pe';
UPDATE questionspcgs66 SET defautinsertion = 'suspensionnonrespect_audition' WHERE defautinsertion = 'nr_cg';
UPDATE questionspcgs66 SET defautinsertion = 'suspensionnonrespect_auditionpe' WHERE defautinsertion = 'nr_pe';
UPDATE questionspcgs66 SET defautinsertion = 'suspensiondefaut_audition_nonorientation' WHERE defautinsertion = 'nc_no';

SELECT public.alter_enumtype ( 'TYPE_DEFAUTINSERTIONPCG66', ARRAY['suspensiondefaut_audition_orientation', 'suspensiondefaut_auditionpe', 'suspensionnonrespect_audition', 'suspensionnonrespect_auditionpe', 'suspensiondefaut_audition_nonorientation'] );


ALTER TABLE bilansparcours66 ALTER COLUMN orientstruct_id DROP NOT NULL;

-------------------------------------------------------------------------------------------------------------
-- 20120229 : Ajout de tables supplémentaires afin de mettre en place le module Courriers
--              dans les traitementspcgs66
-------------------------------------------------------------------------------------------------------------

DROP TABLE IF EXISTS piecestypescourrierspcgs66 CASCADE;
DROP TABLE IF EXISTS typescourrierspcgs66 CASCADE;


CREATE TABLE typescourrierspcgs66 (
  	id 				SERIAL NOT NULL PRIMARY KEY,
	name                            VARCHAR(250) NOT NULL,
        created				TIMESTAMP WITHOUT TIME ZONE,
	modified			TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE typescourrierspcgs66 IS 'Types de courriers liés à un traitement PCG (cg66)';
DROP INDEX IF EXISTS typescourrierspcgs66_name_idx;
CREATE INDEX typescourrierspcgs66_name_idx ON typescourrierspcgs66(name);

CREATE TABLE piecestypescourrierspcgs66 (
  	id 				SERIAL NOT NULL PRIMARY KEY,
	name                            VARCHAR(250) NOT NULL,
        typecourrierpcg66_id            INTEGER NOT NULL REFERENCES typescourrierspcgs66(id) ON DELETE CASCADE ON UPDATE CASCADE,
        created				TIMESTAMP WITHOUT TIME ZONE,
	modified			TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE piecestypescourrierspcgs66 IS 'Pièces pour les courriers liés à un traitement PCG (cg66)';
DROP INDEX IF EXISTS piecestypescourrierspcgs66_typecourrierpcg66_id_idx;
DROP INDEX IF EXISTS piecestypescourrierspcgs66_name_idx;
CREATE INDEX piecestypescourrierspcgs66_typecourrierpcg66_id_idx ON piecestypescourrierspcgs66(typecourrierpcg66_id);
CREATE INDEX piecestypescourrierspcgs66_name_idx ON piecestypescourrierspcgs66(name);

-------------------------------------------------------------------------------------------------------------
-- 20120301 : Ajout d'une clé manquante dans la table traitementspcgs66
-------------------------------------------------------------------------------------------------------------

SELECT add_missing_table_field ('public', 'traitementspcgs66', 'typecourrierpcg66_id', 'INTEGER');
SELECT add_missing_constraint ('public', 'traitementspcgs66', 'traitementspcgs66_typecourrierpcg66_id_fkey', 'typescourrierspcgs66', 'typecourrierpcg66_id');
DROP INDEX IF EXISTS traitementspcgs66_typecourrierpcg66_id_idx;

-------------------------------------------------------------------------------------------------------------
-- 20120301 : Ajout d'une table de liaison entre la table traitementspcgs66 et la table piecestypescourrierspcgs66
-------------------------------------------------------------------------------------------------------------
DROP TABLE IF EXISTS piecestraitementspcgs66;
CREATE TABLE piecestraitementspcgs66 (
  	id 				SERIAL NOT NULL PRIMARY KEY,
	traitementpcg66_id              INTEGER NOT NULL REFERENCES traitementspcgs66(id) ON DELETE CASCADE ON UPDATE CASCADE,
	piecetypecourrierpcg66_id       INTEGER NOT NULL REFERENCES piecestypescourrierspcgs66(id) ON DELETE CASCADE ON UPDATE CASCADE,
	commentaire                     TEXT DEFAULT NULL,
	created				TIMESTAMP WITHOUT TIME ZONE,
	modified			TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE piecestraitementspcgs66 IS 'Table de liaison entre les traitements PCG et les pièces liées à un type de courrier PCG (cg66)';
DROP INDEX IF EXISTS piecestraitementspcgs66_piecetypecourrierpcg66_id_idx;
DROP INDEX IF EXISTS piecestraitementspcgs66_traitementpcg66_id_idx;
CREATE INDEX piecestraitementspcgs66_piecetypecourrierpcg66_id_idx ON piecestraitementspcgs66(piecetypecourrierpcg66_id);
CREATE INDEX piecestraitementspcgs66_traitementpcg66_id_idx ON piecestraitementspcgs66(traitementpcg66_id);

-- 20120319: une entrée de aidesapres66 possède une et une seule entrée de fraisdeplacements66

DROP INDEX IF EXISTS fraisdeplacements66_aideapre66_id_idx;
CREATE UNIQUE INDEX fraisdeplacements66_aideapre66_id_idx ON fraisdeplacements66(aideapre66_id);


-------------------------------------------------------------------------------------------------------------
-- 20120321 : Ajout d'une table pour les propositions de décision du CER du CG66
-------------------------------------------------------------------------------------------------------------

DROP TABLE IF EXISTS proposdecisionscers66;
CREATE TABLE proposdecisionscers66 (
  	id 								SERIAL NOT NULL PRIMARY KEY,
    contratinsertion_id             INTEGER NOT NULL REFERENCES contratsinsertion(id) ON DELETE CASCADE ON UPDATE CASCADE,
    isvalidcer						TYPE_NO NOT NULL DEFAULT 'N',
    datevalidcer					DATE NOT NULL,
    motifficheliaison               TEXT DEFAULT NULL,
    motifnotifnonvalid              TEXT DEFAULT NULL,
    created							TIMESTAMP WITHOUT TIME ZONE,
	modified						TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE proposdecisionscers66 IS 'Table de proposition de décisiondu CER (cg66)';
DROP INDEX IF EXISTS proposdecisionscers66_contratinsertion_id_idx;
DROP INDEX IF EXISTS proposdecisionscers66_isvalidcer_idx;
CREATE UNIQUE INDEX proposdecisionscers66_contratinsertion_id_idx ON proposdecisionscers66(contratinsertion_id);
CREATE INDEX proposdecisionscers66_isvalidcer_idx ON proposdecisionscers66(isvalidcer);

DROP TABLE IF EXISTS motifscersnonvalids66;
CREATE TABLE motifscersnonvalids66 (
  	id 								SERIAL NOT NULL PRIMARY KEY,
    name							VARCHAR(250) NOT NULL,
    created							TIMESTAMP WITHOUT TIME ZONE,
	modified						TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE motifscersnonvalids66 IS 'Table de paramétrage des motifs de non validation d''un CET(cg66)';
DROP INDEX IF EXISTS motifscersnonvalids66_name_idx;
CREATE UNIQUE INDEX motifscersnonvalids66_name_idx ON motifscersnonvalids66(name);


DROP TABLE IF EXISTS motifscersnonvalids66_proposdecisionscers66;
CREATE TABLE motifscersnonvalids66_proposdecisionscers66 (
  	id 								SERIAL NOT NULL PRIMARY KEY,
	propodecisioncer66_id           INTEGER NOT NULL REFERENCES proposdecisionscers66(id) ON DELETE CASCADE ON UPDATE CASCADE,
	motifcernonvalid66_id       	INTEGER NOT NULL REFERENCES motifscersnonvalids66(id) ON DELETE CASCADE ON UPDATE CASCADE,
	created							TIMESTAMP WITHOUT TIME ZONE,
	modified						TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE motifscersnonvalids66_proposdecisionscers66 IS 'Table de liaison entre les propositions de décisions du CER et les motifs en cas de non validation (cg66)';
DROP INDEX IF EXISTS motifscersnonvalids66_proposdecisionscers66_propodecisioncer66_id_idx;
DROP INDEX IF EXISTS motifscersnonvalids66_proposdecisionscers66_motifcernonvalid66_id_idx;
CREATE INDEX motifscersnonvalids66_proposdecisionscers66_propodecisioncer66_id_idx ON motifscersnonvalids66_proposdecisionscers66(propodecisioncer66_id);
CREATE INDEX motifscersnonvalids66_proposdecisionscers66_motifcernonvalid66_id_idx ON motifscersnonvalids66_proposdecisionscers66(motifcernonvalid66_id);


-------------------------------------------------------------------------------------------------------------
-- 20120322 : Ajout d'une valeur dans l'enum de position du CER
-------------------------------------------------------------------------------------------------------------

SELECT add_missing_table_field ('public', 'contratsinsertion', 'datenotification', 'DATE');

 SELECT public.alter_enumtype ( 'TYPE_POSITIONCER', ARRAY['encours', 'attvalid', 'annule', 'fincontrat', 'encoursbilan', 'attrenouv', 'perime', 'nonvalide', 'attsignature', 'valid', 'nonvalid', 'validnotifie', 'nonvalidnotifie'] );



-------------------------------------------------------------------------------------------------------------
-- 20120402 : Ajout d'une table supplémentaire pour la liste des modèles liés aux types de courrier PCG66
-------------------------------------------------------------------------------------------------------------

-------------------------------------------------------------------------------------------------------------
-- 20120402 : Ajout d'une table supplémentaire pour la liste des modèles liés aux types de courrier PCG66
-------------------------------------------------------------------------------------------------------------

DROP TABLE IF EXISTS modelestypescourrierspcgs66 CASCADE;
CREATE TABLE modelestypescourrierspcgs66 (
  	id 				SERIAL NOT NULL PRIMARY KEY,
	name                            VARCHAR(250) NOT NULL,
	typecourrierpcg66_id            INTEGER NOT NULL REFERENCES typescourrierspcgs66(id) ON DELETE CASCADE ON UPDATE CASCADE,
	modeleodt			VARCHAR(250) NOT NULL,
	created				TIMESTAMP WITHOUT TIME ZONE,
	modified			TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE modelestypescourrierspcgs66 IS 'Modèles de courriers PCG (cg66)';
DROP INDEX IF EXISTS modelestypescourrierspcgs66_typecourrierpcg66_id_idx;
DROP INDEX IF EXISTS modelestypescourrierspcgs66_name_idx;
DROP INDEX IF EXISTS modelestypescourrierspcgs66_modeleodt_idx;
CREATE INDEX modelestypescourrierspcgs66_typecourrierpcg66_id_idx ON modelestypescourrierspcgs66(typecourrierpcg66_id);
CREATE INDEX modelestypescourrierspcgs66_name_idx ON modelestypescourrierspcgs66(name);
CREATE INDEX modelestypescourrierspcgs66_modeleodt_idx ON modelestypescourrierspcgs66(modeleodt);


DROP TABLE IF EXISTS piecestypescourrierspcgs66 CASCADE;
DROP TABLE IF EXISTS piecesmodelestypescourrierspcgs66 CASCADE;
CREATE TABLE piecesmodelestypescourrierspcgs66 (
  	id 				SERIAL NOT NULL PRIMARY KEY,
	name                            VARCHAR(250) NOT NULL,
	modeletypecourrierpcg66_id            INTEGER NOT NULL REFERENCES modelestypescourrierspcgs66(id) ON DELETE CASCADE ON UPDATE CASCADE,
	created				TIMESTAMP WITHOUT TIME ZONE,
	modified			TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE piecesmodelestypescourrierspcgs66 IS 'Pièces pour les modèles de courriers PCG (cg66)';
DROP INDEX IF EXISTS piecesmodelestypescourrierspcgs66_modeletypecourrierpcg66_id_idx;
DROP INDEX IF EXISTS piecesmodelestypescourrierspcgs66_name_idx;
CREATE INDEX piecesmodelestypescourrierspcgs66_modeletypecourrierpcg66_id_idx ON piecesmodelestypescourrierspcgs66(modeletypecourrierpcg66_id);
CREATE INDEX piecesmodelestypescourrierspcgs66_name_idx ON piecesmodelestypescourrierspcgs66(name);


DROP TABLE IF EXISTS piecestraitementspcgs66 CASCADE;
DROP TABLE IF EXISTS modelestraitementspcgs66 CASCADE;
CREATE TABLE modelestraitementspcgs66 (
  	id 				SERIAL NOT NULL PRIMARY KEY,
	traitementpcg66_id              INTEGER NOT NULL REFERENCES traitementspcgs66(id) ON DELETE CASCADE ON UPDATE CASCADE,
	modeletypecourrierpcg66_id       INTEGER NOT NULL REFERENCES modelestypescourrierspcgs66(id) ON DELETE CASCADE ON UPDATE CASCADE,
	commentaire                     TEXT DEFAULT NULL,
	created				TIMESTAMP WITHOUT TIME ZONE,
	modified			TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE modelestraitementspcgs66 IS 'Table de liaison entre les traitements PCG et les modèles de courrier PCG (cg66)';
DROP INDEX IF EXISTS modelestraitementspcgs66_modeletypecourrierpcg66_id_idx;
DROP INDEX IF EXISTS modelestraitementspcgs66_traitementpcg66_id_idx;
CREATE INDEX modelestraitementspcgs66_modeletypecourrierpcg66_id_idx ON modelestraitementspcgs66(modeletypecourrierpcg66_id);
CREATE INDEX modelestraitementspcgs66_traitementpcg66_id_idx ON modelestraitementspcgs66(traitementpcg66_id);



DROP TABLE IF EXISTS modelestraitementspcgs66_piecesmodelestypescourrierspcgs66 CASCADE;
DROP TABLE IF EXISTS mtpcgs66_pmtcpcgs66 CASCADE;
CREATE TABLE mtpcgs66_pmtcpcgs66 (
  	id 				SERIAL NOT NULL PRIMARY KEY,
	modeletraitementpcg66_id              INTEGER NOT NULL REFERENCES modelestraitementspcgs66(id) ON DELETE CASCADE ON UPDATE CASCADE,
	piecemodeletypecourrierpcg66_id       INTEGER NOT NULL REFERENCES piecesmodelestypescourrierspcgs66(id) ON DELETE CASCADE ON UPDATE CASCADE,
	created				TIMESTAMP WITHOUT TIME ZONE,
	modified			TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE mtpcgs66_pmtcpcgs66 IS 'Table de liaison pour stocker les pièces liées à un modèle d''un traitement  PCG (cg66) (NB normalement devrait porter le nom de modelestraitementspcgs66_piecesmodelestypescourrierspcgs66)';
DROP INDEX IF EXISTS mtpcgs66_pmtcpcgs66_modeletraitementpcg66_id_idx;
DROP INDEX IF EXISTS mtpcgs66_pmtcpcgs66_piecemodeletypecourrierpcg66_id_idx;
CREATE INDEX mtpcgs66_pmtcpcgs66_modeletraitementpcg66_id_idx ON mtpcgs66_pmtcpcgs66(modeletraitementpcg66_id);
CREATE INDEX mtpcgs66_pmtcpcgs66_piecemodeletypecourrierpcg66_id_idx ON mtpcgs66_pmtcpcgs66(piecemodeletypecourrierpcg66_id);

DROP INDEX IF EXISTS modelestraitementspcgs66_piecesmodelestypescourrierspcgs66_piecemodeletypecourrierpcg66_id_idx;
DROP INDEX IF EXISTS modelestraitementspcgs66_piecesmodelestypescourrierspcgs66_modeletraitementpcg66_id_idx;

-------------------------------------------------------------------------------------------------------------
-- 20120402 : Ajout d'une table supplémentaire pour la liste des modèles liés aux types de courrier PCG66
-------------------------------------------------------------------------------------------------------------
DROP TABLE IF EXISTS modelestypescourrierspcgs66_situationspdos;
CREATE TABLE modelestypescourrierspcgs66_situationspdos (
  	id 								SERIAL NOT NULL PRIMARY KEY,
	modeletypecourrierpcg66_id           INTEGER NOT NULL REFERENCES modelestypescourrierspcgs66(id) ON DELETE CASCADE ON UPDATE CASCADE,
	situationpdo_id       	INTEGER NOT NULL REFERENCES situationspdos(id) ON DELETE CASCADE ON UPDATE CASCADE,
	created							TIMESTAMP WITHOUT TIME ZONE,
	modified						TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE modelestypescourrierspcgs66_situationspdos IS 'Table de liaison entre les modèles de courriers PCGs et les motifs concernant la personne (PCG cg66)';
DROP INDEX IF EXISTS modelestypescourrierspcgs66_situationspdos_modeletypecourrierpcg66_id_idx;
DROP INDEX IF EXISTS modelestypescourrierspcgs66_situationspdos_situationpdo_id_idx;
CREATE INDEX modelestypescourrierspcgs66_situationspdos_modeletypecourrierpcg66_id_idx ON modelestypescourrierspcgs66_situationspdos(modeletypecourrierpcg66_id);
CREATE INDEX modelestypescourrierspcgs66_situationspdos_situationpdo_id_idx ON modelestypescourrierspcgs66_situationspdos(situationpdo_id);

-------------------------------------------------------------------------------------------------------------
-- 20120503 : Ajout d'une contrainte d'unicité dans la table modelestraitementspcgs66
--	Il n'y a qu'un seul modèle de traitement pour un traitement donné
-------------------------------------------------------------------------------------------------------------
DROP INDEX IF EXISTS modelestraitementspcgs66_traitementpcg66_id_idx;
CREATE UNIQUE INDEX modelestraitementspcgs66_traitementpcg66_id_idx ON modelestraitementspcgs66(traitementpcg66_id);

/* -----------------------------------------------------------------------------
	Nouveau Gedooo
	1°) FIXME: il faudrait remplacer, mais une mise à jour sur acos casserait l'arbre:
		- Gedooos:contratinsertion par Contratsinsertion:impression
		- Gedooos:apre par Apres:impression
		- Gedooos:relanceapre par Relancesapres:impression

	2°) FIXME: faire les traductions (pour la page de droits)

	3°) Nettoyage du code:
		a°) la tables montantsconsommes (et son modèle) n'ont pas l'air d'être utilisés -> grep -nr "\(Montantconsomme\|montantsconsommes\)" app | grep -v "\(\.svn\|\.sql\|/tests/\)"

	4°) Dans les modèles odt suivants, on a à présent de vraies dates / heures (revoir les documents):
		- Contratinsertion/notificationop.odt
			* Personne.dtnai
			* Contratinsertion.datevalidation_ci
			* Contratinsertion.dd_ci
			* Contratinsertion.df_ci
			* Dossier.dtdemrsa
		- CUI/cui.odt
			* Personne.dtnai
		- Rendezvous
			* Rendezvous.daterdv
			* Rendezvous.heurerdv

	5°) Dans les modèles odt suivants, des chemins ont changé:
		- Rendezvous
			* dossier_rsa_xxxx -> dossier_xxxx
			* rendezvous.referent_id -> referent_qual, referent_nom, referent_prenom
			* Les enregistrements de modèles suivants ne seront plus présents: Entretien, Fichiermodule, Sanctionrendezvousep58


	6°) FIXME: au 66, problème lors de l'impression
		- /contratsinsertion/notifbenef/9588 (Propodecisioncer66.isvalidcer est vide)
		- /cohortesci/valides/page:1/Filtre__date_saisi_ci:0/Filtre__decision_ci:V/Dossier__dernier:1/Situationdossierrsa__etatdosrsa_choice:0/sort:Contratinsertion.decision_ci/direction:asc

	7°) FIXME: Undefined offset au 66: /contratsinsertion/index/55245

----------------------------------------------------------------------------- */

UPDATE acos SET alias = 'Apres66:impression' WHERE alias = 'Apres66:apre';
UPDATE acos SET alias = 'Cohortescomitesapres:impression' WHERE alias = 'Cohortescomitesapres:notificationscomitegedooo';
UPDATE acos SET alias = 'Cuis:impression' WHERE alias = 'Cuis:gedooo';
UPDATE acos SET alias = 'Rendezvous:impression' WHERE alias = 'Rendezvous:gedooo';

-- *****************************************************************************
COMMIT;
-- *****************************************************************************