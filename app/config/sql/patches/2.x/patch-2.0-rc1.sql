-- *****************************************************************************
-- Cristal v. 28 / iRSA v. 3.2
-- FIXME: les tables suivisappuisorientation, parcours et orientations doivent-
-- elles être complétées et utilisées par l'appli ?
-- *****************************************************************************

SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;
SET search_path = public, pg_catalog;
SET default_tablespace = '';
SET default_with_oids = false;

BEGIN;

-- -----------------------------------------------------------------------------
--
-- -----------------------------------------------------------------------------

-- FIXME: table créée dans le patch 1.0.8.9
-- FIXME: faire drop / create ?

/*CREATE TABLE transmissionsflux (
    id						SERIAL NOT NULL PRIMARY KEY,
    identificationflux_id	integer NOT NULL REFERENCES identificationsflux (id),
    nbtotdemrsatransm 		integer DEFAULT NULL
);

CREATE INDEX transmissionsflux_identificationflux_id ON transmissionsflux (identificationflux_id);*/

-- -----------------------------------------------------------------------------
--
-- -----------------------------------------------------------------------------

-- FIXME: correspondance / pas de correspondance ?
ALTER TABLE suivisinstruction ADD COLUMN suiirsa CHAR(2) DEFAULT NULL;
ALTER TABLE suivisinstruction DROP COLUMN etatirsa;

-- -----------------------------------------------------------------------------
-- Dsp / Dspps, Dspfs
-- -----------------------------------------------------------------------------

CREATE TYPE type_nos AS ENUM ( 'N', 'O', 'S' );
CREATE TYPE type_nov AS ENUM ( 'N', 'O', 'V' );
CREATE TYPE type_sitpersdemrsa AS ENUM ( '0101', '0102', '0103', '0104', '0105', '0106', '0107', '0108', '0109' );
CREATE TYPE type_nivetu AS ENUM ( '1201', '1202', '1203', '1204', '1205', '1206', '1207' );
CREATE TYPE type_nivdipmaxobt AS ENUM ( '2601', '2602', '2603', '2604', '2605', '2606' );
CREATE TYPE type_hispro AS ENUM ( '1901', '1902', '1903', '1904' );
CREATE TYPE type_cessderact AS ENUM ( '2701', '2702' );
CREATE TYPE type_duractdomi AS ENUM ( '2104', '2105', '2106', '2107' );
CREATE TYPE type_inscdememploi AS ENUM ( '4301', '4302', '4303', '4304'  );
CREATE TYPE type_accoemploi AS ENUM ( '1801', '1802', '1803'  );
CREATE TYPE type_natlog AS ENUM ( '0901', '0902', '0903', '0904', '0905', '0906', '0907', '0908', '0909', '0910', '0911', '0912', '0913' );
CREATE TYPE type_demarlog AS ENUM ( '1101', '1102', '1103' );

CREATE TABLE dsps (
    id                      SERIAL NOT NULL PRIMARY KEY,
    personne_id             INTEGER NOT NULL REFERENCES personnes(id),
	-- Généralités
    sitpersdemrsa   		type_sitpersdemrsa DEFAULT NULL,
    topisogroouenf   		type_booleannumber DEFAULT NULL,
    topdrorsarmiant   		type_booleannumber DEFAULT NULL,
    drorsarmianta2   		type_nos DEFAULT NULL,
    topcouvsoc    			type_booleannumber DEFAULT NULL,
	-- SituationSociale - CommunSituationSociale
    accosocfam    			type_nov DEFAULT NULL,
    libcooraccosocfam  		VARCHAR(250) DEFAULT NULL,
    accosocindi    			type_nov DEFAULT NULL,
    libcooraccosocindi  	VARCHAR(250) DEFAULT NULL,
    soutdemarsoc   			type_nov DEFAULT NULL,
	-- NiveauEtude
    nivetu     				type_nivetu DEFAULT NULL,
    nivdipmaxobt   			type_nivdipmaxobt DEFAULT NULL,
    annobtnivdipmax   		CHAR(4) DEFAULT NULL,
    topqualipro    			type_booleannumber DEFAULT NULL,
    libautrqualipro   		VARCHAR(100) DEFAULT NULL,
    topcompeextrapro  		type_booleannumber DEFAULT NULL,
    libcompeextrapro  		VARCHAR(100) DEFAULT NULL,
	-- DisponibiliteEmploi
    topengdemarechemploi	type_booleannumber DEFAULT NULL,
	-- SituationProfessionnelle
    hispro     				type_hispro DEFAULT NULL,
    libderact    			VARCHAR(100) DEFAULT NULL,
    libsecactderact   		VARCHAR(100) DEFAULT NULL,
    cessderact    			type_cessderact DEFAULT NULL,
    topdomideract   		type_booleannumber DEFAULT NULL,
    libactdomi    			VARCHAR(100) DEFAULT NULL,
    libsecactdomi   		VARCHAR(100) DEFAULT NULL,
    duractdomi    			type_duractdomi DEFAULT NULL,
    inscdememploi   		type_inscdememploi DEFAULT NULL,
    topisogrorechemploi  	type_booleannumber DEFAULT NULL,
    accoemploi    			type_accoemploi DEFAULT NULL,
    libcooraccoemploi  		VARCHAR(100) DEFAULT NULL,
    topprojpro    			type_booleannumber DEFAULT NULL,
    libemploirech   		VARCHAR(250) DEFAULT NULL,
    libsecactrech   		VARCHAR(250) DEFAULT NULL,
    topcreareprientre		type_booleannumber DEFAULT NULL,
    concoformqualiemploi 	type_nos DEFAULT NULL,
	-- Mobilite - CommunMobilite
    topmoyloco    			type_booleannumber DEFAULT NULL,
    toppermicondub   		type_booleannumber DEFAULT NULL,
    topautrpermicondu  		type_booleannumber DEFAULT NULL,
    libautrpermicondu  		VARCHAR(100) DEFAULT NULL,
	-- DifficulteLogement - CommunDifficulteLogement
    natlog     				type_natlog DEFAULT NULL,
    demarlog				type_demarlog DEFAULT NULL
);
CREATE UNIQUE INDEX dsps_personne_id_idx ON dsps (personne_id);

-- -----------------------------------------------------------------------------

CREATE TYPE type_difsoc AS ENUM ( '0401', '0402', '0403', '0404', '0405', '0406', '0407' );
CREATE TABLE detailsdifsocs (
    id      		SERIAL NOT NULL PRIMARY KEY,
    dsp_id			INTEGER NOT NULL REFERENCES dsps(id),
	difsoc			type_difsoc NOT NULL,
	libautrdifsoc	VARCHAR(100) DEFAULT NULL
);
CREATE INDEX detailsdifsocs_dsp_id_idx ON detailsdifsocs (dsp_id);

-- -----------------------------------------------------------------------------

CREATE TYPE type_nataccosocfam AS ENUM ( '0410', '0411', '0412', '0413' );
CREATE TABLE detailsaccosocfams (
    id      			SERIAL NOT NULL PRIMARY KEY,
    dsp_id				INTEGER NOT NULL REFERENCES dsps(id),
	nataccosocfam		type_nataccosocfam NOT NULL,
	libautraccosocfam	VARCHAR(100) DEFAULT NULL
);
CREATE INDEX detailsaccosocfams_dsp_id_idx ON detailsaccosocfams (dsp_id);

-- -----------------------------------------------------------------------------

CREATE TYPE type_nataccosocindi AS ENUM ( '0416', '0417', '0418', '0419', '0420' );
CREATE TABLE detailsaccosocindis (
    id      			SERIAL NOT NULL PRIMARY KEY,
    dsp_id				INTEGER NOT NULL REFERENCES dsps(id),
	nataccosocindi		type_nataccosocindi NOT NULL,
	libautraccosocindi	VARCHAR(100) DEFAULT NULL
);
CREATE INDEX detailsaccosocindis_dsp_id_idx ON detailsaccosocindis (dsp_id);

-- -----------------------------------------------------------------------------

CREATE TYPE type_difdisp AS ENUM ( '0501', '0502', '0503', '0504', '0505', '0506' );
CREATE TABLE detailsdifdisps (
    id      	SERIAL NOT NULL PRIMARY KEY,
    dsp_id		INTEGER NOT NULL REFERENCES dsps(id),
	difdisp		type_difdisp NOT NULL
);
CREATE INDEX detailsdifdisps_dsp_id_idx ON detailsdifdisps (dsp_id);

-- -----------------------------------------------------------------------------

CREATE TYPE type_natmob AS ENUM ( '2504', '2501', '2502', '2503' );
CREATE TABLE detailsnatmobs (
    id      	SERIAL NOT NULL PRIMARY KEY,
    dsp_id		INTEGER NOT NULL REFERENCES dsps(id),
	natmob		type_natmob NOT NULL
);
CREATE INDEX detailsnatmobs_dsp_id_idx ON detailsnatmobs (dsp_id);

-- -----------------------------------------------------------------------------

CREATE TYPE type_diflog AS ENUM ( '1001', '1002', '1003', '1004', '1005', '1006', '1007', '1008', '1009' );
CREATE TABLE detailsdiflogs (
    id      		SERIAL NOT NULL PRIMARY KEY,
    dsp_id			INTEGER NOT NULL REFERENCES dsps(id),
	diflog			type_diflog NOT NULL,
	libautrdiflog	VARCHAR(100) DEFAULT NULL
);
CREATE INDEX detailsdiflogs_dsp_id_idx ON detailsdiflogs (dsp_id);

-- -----------------------------------------------------------------------------
-- INFO: suppression des doublons sur personne_id dans dspps (FIXME: on garde le 1er?)
-- -----------------------------------------------------------------------------

DELETE FROM dspps_accoemplois WHERE dspps_accoemplois.dspp_id IN(
	SELECT d1.id
		FROM dspps AS d1,
			dspps AS d2
		WHERE d1.personne_id = d2.personne_id
			AND d1.id > d2.id
);

DELETE FROM dspps_difdisps WHERE dspps_difdisps.dspp_id IN(
	SELECT d1.id
		FROM dspps AS d1,
			dspps AS d2
		WHERE d1.personne_id = d2.personne_id
			AND d1.id > d2.id
);

DELETE FROM dspps_difsocs WHERE dspps_difsocs.dspp_id IN(
	SELECT d1.id
		FROM dspps AS d1,
			dspps AS d2
		WHERE d1.personne_id = d2.personne_id
			AND d1.id > d2.id
);

DELETE FROM dspps_nataccosocindis WHERE dspps_nataccosocindis.dspp_id IN(
	SELECT d1.id
		FROM dspps AS d1,
			dspps AS d2
		WHERE d1.personne_id = d2.personne_id
			AND d1.id > d2.id
);

DELETE FROM dspps_natmobs WHERE dspps_natmobs.dspp_id IN(
	SELECT d1.id
		FROM dspps AS d1,
			dspps AS d2
		WHERE d1.personne_id = d2.personne_id
			AND d1.id > d2.id
);

DELETE FROM dspps_nivetus WHERE dspps_nivetus.dspp_id IN(
	SELECT d1.id
		FROM dspps AS d1,
			dspps AS d2
		WHERE d1.personne_id = d2.personne_id
			AND d1.id > d2.id
);

DELETE FROM dspps WHERE dspps.id IN(
	SELECT d1.id
		FROM dspps AS d1,
			dspps AS d2
		WHERE d1.personne_id = d2.personne_id
			AND d1.id > d2.id
);

-- -----------------------------------------------------------------------------
-- INFO: suppression des doublons sur foyer_id dans dspfs (FIXME: on garde le 1er?)
-- -----------------------------------------------------------------------------

DELETE FROM dspfs_diflogs WHERE dspfs_diflogs.dspf_id IN(
	SELECT d1.id
		FROM dspfs AS d1,
			dspfs AS d2
		WHERE d1.foyer_id = d2.foyer_id
			AND d1.id > d2.id
);

DELETE FROM dspfs_nataccosocfams WHERE dspfs_nataccosocfams.dspf_id IN(
	SELECT d1.id
		FROM dspfs AS d1,
			dspfs AS d2
		WHERE d1.foyer_id = d2.foyer_id
			AND d1.id > d2.id
);

DELETE FROM dspfs WHERE dspfs.id IN(
	SELECT d1.id
		FROM dspfs AS d1,
			dspfs AS d2
		WHERE d1.foyer_id = d2.foyer_id
			AND d1.id > d2.id
);

-- -----------------------------------------------------------------------------
-- INFO: mise à NULL de tous les champs vides
-- -----------------------------------------------------------------------------

-- dspps
UPDATE dspps SET drorsarmiant = NULL WHERE TRIM(drorsarmiant) = '';
UPDATE dspps SET drorsarmianta2 = NULL WHERE TRIM(drorsarmianta2) = '';
UPDATE dspps SET couvsoc = NULL WHERE TRIM(couvsoc) = '';
UPDATE dspps SET libautrdifsoc = NULL WHERE TRIM(libautrdifsoc) = '';
UPDATE dspps SET elopersdifdisp = NULL WHERE TRIM(elopersdifdisp) = '';
UPDATE dspps SET obstemploidifdisp = NULL WHERE TRIM(obstemploidifdisp) = '';
UPDATE dspps SET soutdemarsoc = NULL WHERE TRIM(soutdemarsoc) = '';
UPDATE dspps SET libautraccosocindi = NULL WHERE TRIM(libautraccosocindi) = '';
UPDATE dspps SET libcooraccosocindi = NULL WHERE TRIM(libcooraccosocindi) = '';
UPDATE dspps SET rappemploiquali = NULL WHERE TRIM(rappemploiquali) = '';
UPDATE dspps SET rappemploiform = NULL WHERE TRIM(rappemploiform) = '';
UPDATE dspps SET libautrqualipro = NULL WHERE TRIM(libautrqualipro) = '';
UPDATE dspps SET permicondub = NULL WHERE TRIM(permicondub) = '';
UPDATE dspps SET libautrpermicondu = NULL WHERE TRIM(libautrpermicondu) = '';
UPDATE dspps SET libcompeextrapro = NULL WHERE TRIM(libcompeextrapro) = '';
UPDATE dspps SET persisogrorechemploi = NULL WHERE TRIM(persisogrorechemploi) = '';
UPDATE dspps SET libcooraccoemploi = NULL WHERE TRIM(libcooraccoemploi) = '';
UPDATE dspps SET hispro = NULL WHERE TRIM(hispro) = '';
UPDATE dspps SET libderact = NULL WHERE TRIM(libderact) = '';
UPDATE dspps SET libsecactderact = NULL WHERE TRIM(libsecactderact) = '';
UPDATE dspps SET domideract = NULL WHERE TRIM(domideract) = '';
UPDATE dspps SET libactdomi = NULL WHERE TRIM(libactdomi) = '';
UPDATE dspps SET libsecactdomi = NULL WHERE TRIM(libsecactdomi) = '';
UPDATE dspps SET duractdomi = NULL WHERE TRIM(duractdomi) = '';
UPDATE dspps SET libemploirech = NULL WHERE TRIM(libemploirech) = '';
UPDATE dspps SET libsecactrech = NULL WHERE TRIM(libsecactrech) = '';
UPDATE dspps SET creareprisentrrech = NULL WHERE TRIM(creareprisentrrech) = '';
UPDATE dspps SET moyloco = NULL WHERE TRIM(moyloco) = '';
UPDATE dspps SET diplomes = NULL WHERE TRIM(diplomes) = '';
UPDATE dspps SET dipfra = NULL WHERE TRIM(dipfra) = '';

-- dspfs
UPDATE dspfs SET motidemrsa = NULL WHERE TRIM(motidemrsa) = '';
UPDATE dspfs SET accosocfam = NULL WHERE TRIM(accosocfam) = '';
UPDATE dspfs SET libautraccosocfam = NULL WHERE TRIM(libautraccosocfam) = '';
UPDATE dspfs SET libcooraccosocfam = NULL WHERE TRIM(libcooraccosocfam) = '';
UPDATE dspfs SET natlog = NULL WHERE TRIM(natlog) = '';
UPDATE dspfs SET libautrdiflog = NULL WHERE TRIM(libautrdiflog) = '';
UPDATE dspfs SET demarlog = NULL WHERE TRIM(demarlog) = '';

-- -----------------------------------------------------------------------------

-- FIXME: pourquoi perd-on les entrées de personnes_id 174448 à 174536
-- IDÉE: INNER JOIN dspfs

INSERT INTO dsps (personne_id, sitpersdemrsa, topdrorsarmiant, drorsarmianta2, topcouvsoc, accosocfam, libcooraccosocfam, accosocindi, libcooraccosocindi, soutdemarsoc, libautrqualipro, libcompeextrapro, nivetu, annobtnivdipmax, topisogrorechemploi, accoemploi, libcooraccoemploi, hispro, libderact, libsecactderact, cessderact, topdomideract, libactdomi, libsecactdomi, duractdomi, libemploirech, libsecactrech, topcreareprientre, natlog, demarlog, topmoyloco, toppermicondub, libautrpermicondu)
	SELECT	dspps.personne_id AS personne_id,
			CAST( dspfs.motidemrsa AS type_sitpersdemrsa) AS sitpersdemrsa,
			CAST( ( CASE WHEN drorsarmiant = 'O' THEN '1' WHEN drorsarmiant = 'N' THEN '0' ELSE NULL END ) AS type_booleannumber ) AS topdrorsarmiant, -- FIXME: P -> ?
			CAST( ( CASE WHEN drorsarmianta2 = 'P' THEN NULL ELSE drorsarmianta2 END ) AS type_nos ) AS drorsarmianta2, -- FIXME: P ?
			CAST( ( CASE WHEN couvsoc = 'O' THEN '1' WHEN couvsoc = 'N' THEN '0' ELSE NULL END ) AS type_booleannumber ) AS topcouvsoc, -- FIXME: P -> ?
			CAST( ( CASE WHEN accosocfam = 'P' THEN NULL ELSE accosocfam END ) AS type_nov ) AS accosocfam, -- FIXME: P ?
			dspfs.libcooraccosocfam AS libcooraccosocfam,
			CAST ( CASE WHEN COUNT(dspps_nataccosocindis.*) > 0 THEN 'O' ELSE null END AS type_nov ) AS accosocindi,
			dspps.libcooraccosocindi AS libcooraccosocindi,
			CAST( ( CASE WHEN soutdemarsoc = 'P' THEN NULL ELSE soutdemarsoc END ) AS type_nov ) AS soutdemarsoc, -- FIXME: P ?
			dspps.libautrqualipro AS libautrqualipro,
			dspps.libcompeextrapro AS libcompeextrapro,
			CAST( MIN(nivetus.code) AS type_nivetu ) AS nivetu,
			EXTRACT(YEAR FROM annderdipobt) AS annobtnivdipmax,
			CAST( ( CASE WHEN persisogrorechemploi = 'O' THEN '1' WHEN persisogrorechemploi = 'N' THEN '0' WHEN persisogrorechemploi = '0' THEN '0' WHEN persisogrorechemploi = '1' THEN '1' ELSE NULL END ) AS type_booleannumber ) AS topisogrorechemploi,
			CAST( MIN(accoemplois.code) AS type_accoemploi ) AS accoemploi,
			dspps.libcooraccoemploi AS libcooraccoemploi,
			CAST( dspps.hispro AS type_hispro ) AS hispro,
			dspps.libderact AS libderact,
			dspps.libsecactderact AS libsecactderact,
			CAST( ( CASE WHEN AGE( dspps.dfderact ) IS NULL THEN NULL WHEN AGE( dspps.dfderact ) <= '1 year' THEN '2701' ELSE '2702' END ) AS type_cessderact ) AS cessderact,
			CAST( ( CASE WHEN domideract = 'O' THEN '1' WHEN domideract = 'N' THEN '0' WHEN domideract = '0' THEN '0' WHEN domideract = '1' THEN '1' ELSE NULL END ) AS type_booleannumber ) AS topdomideract,
			dspps.libactdomi AS libactdomi,
			dspps.libsecactdomi AS libsecactdomi,
			CAST( dspps.duractdomi AS type_duractdomi ) AS duractdomi,
			dspps.libemploirech AS libemploirech,
			dspps.libsecactrech AS libsecactrech,
			CAST( ( CASE WHEN creareprisentrrech = 'O' THEN '1' WHEN creareprisentrrech = 'N' THEN '0' WHEN creareprisentrrech = '0' THEN '0' WHEN creareprisentrrech = '1' THEN '1' ELSE NULL END ) AS type_booleannumber ) AS topcreareprientre,
			CAST( dspfs.natlog AS type_natlog ) AS natlog,
			CAST( dspfs.demarlog AS type_demarlog ) AS demarlog,
			CAST( ( CASE WHEN moyloco = 'O' THEN '1' WHEN moyloco = 'N' THEN '0' WHEN moyloco = '0' THEN '0' WHEN moyloco = '1' THEN '1' ELSE NULL END ) AS type_booleannumber ) AS topmoyloco, -- FIXME: valeurs pour les autres ?
			CAST( ( CASE WHEN permicondub = 'O' THEN '1' WHEN permicondub = 'N' THEN '0' WHEN permicondub = '0' THEN '0' WHEN permicondub = '1' THEN '1' ELSE NULL END ) AS type_booleannumber ) AS toppermicondub,
			dspps.libautrpermicondu AS libautrpermicondu
		FROM dspps
			INNER JOIN personnes ON personnes.id = dspps.personne_id
			INNER JOIN foyers ON personnes.foyer_id = foyers.id
			INNER JOIN dspfs ON dspfs.foyer_id = foyers.id
			LEFT OUTER JOIN dspps_nataccosocindis ON dspps_nataccosocindis.dspp_id = dspps.id
			LEFT OUTER JOIN dspps_nivetus ON dspps_nivetus.dspp_id = dspps.id
			LEFT OUTER JOIN nivetus ON dspps_nivetus.nivetu_id = nivetus.id
			LEFT OUTER JOIN dspps_accoemplois ON dspps_accoemplois.dspp_id = dspps.id
			LEFT OUTER JOIN accoemplois ON dspps_accoemplois.accoemploi_id = accoemplois.id
		GROUP BY dspps_nataccosocindis.dspp_id, dspps.id, dspps.personne_id,
			dspfs.motidemrsa, dspps.drorsarmiant, dspps.drorsarmianta2, dspps.couvsoc, dspfs.accosocfam, dspfs.libcooraccosocfam, dspps.soutdemarsoc, dspps.libcooraccosocindi, dspps.libautrqualipro, dspps.libcompeextrapro, dspps_nivetus.dspp_id, dspps.annderdipobt, dspps.persisogrorechemploi, dspps.libcooraccoemploi, dspps.hispro, dspps.libderact, dspps.libsecactderact, dspps.dfderact, dspps.domideract, dspps.libactdomi, dspps.libsecactdomi, dspps.duractdomi, dspps.libemploirech, dspps.libsecactrech, dspps.creareprisentrrech, dspps_accoemplois.dspp_id, dspfs.natlog, dspfs.demarlog, dspps_nataccosocindis.dspp_id, dspps.moyloco, dspps.permicondub, dspps.libautrpermicondu;

-- -----------------------------------------------------------------------------

INSERT INTO detailsdifsocs (dsp_id, difsoc, libautrdifsoc)
	SELECT
			dsps.id,
			CAST( difsocs.code AS type_difsoc ) AS difsoc,
			( CASE WHEN difsocs.code = '0407' THEN dspps.libautrdifsoc ELSE NULL END ) AS libautrdifsoc
		FROM dspps_difsocs
			INNER JOIN difsocs ON dspps_difsocs.difsoc_id = difsocs.id
			INNER JOIN dspps ON dspps.id = dspps_difsocs.dspp_id
			INNER JOIN dsps ON dspps.personne_id = dsps.personne_id;

-- INFO: le choix "Autres" n'était pas toujours coché, alors qu'on avait un libellé pour lui
-- FIXME: ????

-- INSERT INTO detailsdifsocs (dsp_id, difsoc, libautrdifsoc)
-- 	SELECT
-- 			dsps.id AS dsp_id,
-- 			CAST( '0407' AS type_difsoc ) AS difsoc,
-- 			dspps.libautrdifsoc
-- 		FROM dspps
-- 			INNER JOIN dsps ON dspps.personne_id = dsps.personne_id
-- 		WHERE libautrdifsoc IS NOT NULL OR TRIM(libautrdifsoc) <> ''
-- 			AND dspps.id NOT IN (
-- 				SELECT dspps_difsocs.dspp_id
-- 					FROM dspps_difsocs
-- 						INNER JOIN difsocs ON dspps_difsocs.dspp_id = difsocs.id
-- 					WHERE difsocs.code = '0407'
-- 			)
-- 			AND dsps.id NOT IN (
-- 				SELECT detailsdifsocs.dsp_id
-- 					FROM detailsdifsocs
-- 					WHERE detailsdifsocs.difsoc = '0407'
-- 			);

-- -----------------------------------------------------------------------------

INSERT INTO detailsaccosocfams (dsp_id, nataccosocfam, libautraccosocfam)
	SELECT
			dsps.id AS dsp_id,
			CAST( nataccosocfams.code AS type_nataccosocfam ) AS nataccosocfam,
			( CASE WHEN nataccosocfams.code = '0413' THEN dspfs.libautraccosocfam ELSE NULL END ) AS libautraccosocfam
		FROM dspfs_nataccosocfams
			INNER JOIN dspfs ON dspfs.id = dspfs_nataccosocfams.dspf_id
			INNER JOIN nataccosocfams ON dspfs_nataccosocfams.nataccosocfam_id = nataccosocfams.id
			INNER JOIN personnes ON dspfs.foyer_id = personnes.foyer_id
			INNER JOIN dsps ON personnes.id = dsps.personne_id;

-- INFO: le choix "Autres" n'était pas toujours coché, alors qu'on avait un libellé pour lui
-- FIXME: ????

-- INSERT INTO detailsaccosocfams (dsp_id, nataccosocfam, libautraccosocfam)
-- 	SELECT
-- 			dsps.id AS dsp_id,
-- 			CAST( '0413' AS type_nataccosocfam ) AS nataccosocfam,
-- 			dspfs.libautraccosocfam
-- 		FROM dspfs
-- 			INNER JOIN personnes ON dspfs.foyer_id = personnes.foyer_id
-- 			INNER JOIN dsps ON personnes.id = dsps.personne_id
-- 		WHERE libautraccosocfam IS NOT NULL OR TRIM(libautraccosocfam) <> ''
-- 			AND dspfs.id NOT IN (
-- 				SELECT dspfs_nataccosocfams.dspf_id
-- 					FROM dspfs_nataccosocfams
-- 						INNER JOIN nataccosocfams ON dspfs_nataccosocfams.nataccosocfam_id = nataccosocfams.id
-- 					WHERE nataccosocfams.code = '0413'
-- 			)
-- 			AND dsps.id NOT IN (
-- 				SELECT detailsaccosocfams.dsp_id
-- 					FROM detailsaccosocfams
-- 					WHERE detailsaccosocfams.nataccosocfam = '0413'
-- 			);

-- -----------------------------------------------------------------------------

INSERT INTO detailsaccosocindis (dsp_id, nataccosocindi, libautraccosocindi)
	SELECT
			dsps.id AS dsp_id,
			CAST( nataccosocindis.code AS type_nataccosocindi ) AS nataccosocindi,
			( CASE WHEN nataccosocindis.code = '0420' THEN dspps.libautraccosocindi ELSE NULL END ) AS libautraccosocindi
		FROM dspps_nataccosocindis
			INNER JOIN nataccosocindis ON dspps_nataccosocindis.nataccosocindi_id = nataccosocindis.id
			INNER JOIN dspps ON dspps.id = dspps_nataccosocindis.dspp_id
			INNER JOIN dsps ON dspps.personne_id = dsps.personne_id
		WHERE nataccosocindis.code <> '0415';

-- INFO: le choix "Autres" n'était pas toujours coché, alors qu'on avait un libellé pour lui
-- FIXME: ????

-- INSERT INTO detailsaccosocindis (dsp_id, nataccosocindi, libautraccosocindi)
-- 	SELECT
-- 			dsps.id AS dsp_id,
-- 			CAST( '0420' AS type_nataccosocindi ) AS nataccosocindi,
-- 			dspps.libautraccosocindi
-- 		FROM dspps
-- 			INNER JOIN dsps ON dspps.personne_id = dsps.personne_id
-- 		WHERE libautraccosocindi IS NOT NULL OR TRIM(libautraccosocindi) <> ''
-- 			AND dspps.id NOT IN (
-- 				SELECT dspps_nataccosocindis.dspp_id
-- 					FROM dspps_nataccosocindis
-- 						INNER JOIN nataccosocindis ON dspps_nataccosocindis.nataccosocindi_id = nataccosocindis.id
-- 					WHERE nataccosocindis.code = '0420'
-- 			)
-- 			AND dsps.id NOT IN (
-- 				SELECT detailsaccosocindis.dsp_id
-- 					FROM detailsaccosocindis
-- 					WHERE detailsaccosocindis.nataccosocindi = '0420'
-- 			);

-- -----------------------------------------------------------------------------

INSERT INTO detailsdifdisps (dsp_id, difdisp)
	SELECT
			dsps.id AS dsp_id,
			CAST( difdisps.code AS type_difdisp ) AS difdisp
		FROM dspps_difdisps
			INNER JOIN difdisps ON dspps_difdisps.difdisp_id = difdisps.id
			INNER JOIN dspps ON dspps.id = dspps_difdisps.dspp_id
			INNER JOIN dsps ON dspps.personne_id = dsps.personne_id;

-- INFO: le choix "Autres" n'était pas toujours coché, alors qu'on avait un libellé pour lui
-- FIXME: ????

-- -----------------------------------------------------------------------------

INSERT INTO detailsnatmobs (dsp_id, natmob)
	SELECT
			dsps.id AS dsp_id,
			CAST( natmobs.code AS type_natmob ) AS natmob
		FROM dspps_natmobs
			INNER JOIN natmobs ON dspps_natmobs.natmob_id = natmobs.id
			INNER JOIN dspps ON dspps.id = dspps_natmobs.dspp_id
			INNER JOIN dsps ON dspps.personne_id = dsps.personne_id;

-- -----------------------------------------------------------------------------

/*
CREATE TYPE type_diflog AS ENUM ( '1001', '1002', '1003', '1004', '1005', '1006', '1007', '1008', '1009' );
CREATE TABLE detailsdiflogs (
    id      		SERIAL NOT NULL PRIMARY KEY,
    dsp_id			INTEGER NOT NULL REFERENCES dsps(id),
	diflog			type_diflog NOT NULL,
	libautrdiflog	VARCHAR(100) DEFAULT NULL
);
CREATE INDEX detailsdiflogs_dsp_id_idx ON detailsdiflogs (dsp_id);
*/

INSERT INTO detailsdiflogs (dsp_id, diflog, libautrdiflog)
	SELECT
			dsps.id AS dsp_id,
			CAST( diflogs.code AS type_diflog ) AS diflog,
			( CASE WHEN diflogs.code = '1009' THEN dspfs.libautrdiflog ELSE NULL END ) AS libautrdiflog
		FROM dspfs_diflogs
			INNER JOIN dspfs ON dspfs.id = dspfs_diflogs.dspf_id
			INNER JOIN diflogs ON dspfs_diflogs.diflog_id = diflogs.id
			INNER JOIN personnes ON dspfs.foyer_id = personnes.foyer_id
			INNER JOIN dsps ON personnes.id = dsps.personne_id;

-- INFO: le choix "Autres" n'était pas toujours coché, alors qu'on avait un libellé pour lui
-- FIXME: ????

-- -----------------------------------------------------------------------------
--
-- -----------------------------------------------------------------------------

ALTER TABLE foyers ADD COLUMN mtestrsa numeric(9,2) DEFAULT NULL;

-- -----------------------------------------------------------------------------
--
-- -----------------------------------------------------------------------------

CREATE TYPE type_sitperssocpro AS ENUM (
    'AF',
    'EF',
    'RE'
);

-- FIXME: doit-on remplir / modifier cette table dans l'appli lors du passage dans nos formulaires

CREATE TABLE suivisappuisorientation (
    id				SERIAL NOT NULL PRIMARY KEY,
    personne_id 	INTEGER NOT NULL REFERENCES personnes(id),
    topoblsocpro	type_booleannumber DEFAULT NULL, -- FIXME: remplace prestations.toppersdrodevorsa ?
    topsouhsocpro	type_booleannumber DEFAULT NULL,
    sitperssocpro	type_sitperssocpro DEFAULT NULL,
    dtenrsocpro		date DEFAULT NULL,
    dtenrparco		date DEFAULT NULL,
    dtenrorie		date DEFAULT NULL
);

CREATE INDEX suivisappuisorientation_personne_id ON suivisappuisorientation (personne_id);

-- -----------------------------------------------------------------------------

CREATE TYPE type_natparco AS ENUM (
    'AS',
    'PP',
    'PS'
);

CREATE TYPE type_motimodparco AS ENUM (
    'CL',
    'EA'
);

CREATE TABLE parcours (
    id 					SERIAL NOT NULL PRIMARY KEY,
    personne_id			INTEGER NOT NULL REFERENCES personnes (id),
    natparcocal			type_natparco,
    natparcomod			type_natparco,
    toprefuparco		type_booleannumber,
    motimodparco		type_motimodparco,
    raisocorgdeciorie	VARCHAR(60) DEFAULT NULL,
    numvoie				VARCHAR(6) DEFAULT NULL,
    typevoie			VARCHAR(4) DEFAULT NULL,
    nomvoie				VARCHAR(25) DEFAULT NULL,
    complideadr			VARCHAR(38) DEFAULT NULL,
    compladr			VARCHAR(26) DEFAULT NULL,
    lieudist			VARCHAR(32) DEFAULT NULL,
    codepos				VARCHAR(5) DEFAULT NULL,
    locaadr				VARCHAR(26) DEFAULT NULL,
    numtelorgdeciorie	VARCHAR(10) DEFAULT NULL,
    dtrvorgdeciorie		DATE DEFAULT NULL,
    hrrvorgdeciorie		TIME WITHOUT TIME ZONE DEFAULT NULL,
    libadrrvorgdeciorie VARCHAR(160) DEFAULT NULL,
    numtelrvorgdeciorie VARCHAR(10) DEFAULT NULL
);

CREATE INDEX parcours_personne_id ON parcours (personne_id);

-- -----------------------------------------------------------------------------

CREATE TABLE orientations (
    id				SERIAL NOT NULL PRIMARY KEY,
    personne_id		INTEGER NOT NULL REFERENCES personnes (id),
    raisocorgorie	VARCHAR(60) DEFAULT NULL,
    numvoie			VARCHAR(6) DEFAULT NULL,
    typevoie		VARCHAR(4) DEFAULT NULL,
    nomvoie			VARCHAR(25) DEFAULT NULL,
    complideadr		VARCHAR(38) DEFAULT NULL,
    compladr		VARCHAR(26) DEFAULT NULL,
    lieudist		VARCHAR(32) DEFAULT NULL,
    codepos			VARCHAR(5) DEFAULT NULL,
    locaadr			VARCHAR(26) DEFAULT NULL,
    numtelorgorie	VARCHAR(10) DEFAULT NULL,
    dtrvorgorie		DATE DEFAULT NULL,
    hrrvorgorie		TIME WITHOUT TIME ZONE DEFAULT NULL,
    libadrrvorgorie VARCHAR(160) DEFAULT NULL,
    numtelrvorgorie VARCHAR(10) DEFAULT NULL
);

CREATE INDEX orientations_personne_id ON orientations (personne_id);

-- -----------------------------------------------------------------------------

COMMIT;

-- *****************************************************************************



--------------- Ajout du 12/04/2010 à 10h50 ------------------

ALTER TABLE evenements ALTER COLUMN heuliq TYPE timestamp with time zone;
ALTER TABLE evenements ALTER COLUMN heuliq TYPE time;