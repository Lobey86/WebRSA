SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;
SET search_path = public, pg_catalog;
SET default_tablespace = '';
SET default_with_oids = false;

-- *****************************************************************************
-- "Rattrappage" du patch patch-version1.0.8.8.1.sql pour le CG 66
-- *****************************************************************************
BEGIN;
-- *****************************************************************************

ALTER TABLE etatsliquidatifs RENAME COLUMN objet TO commentaire;

ALTER TABLE parametresfinanciers ADD COLUMN lib_natureanalytique VARCHAR(250) DEFAULT NULL;

ALTER TABLE etatsliquidatifs ADD COLUMN lib_natureanalytique VARCHAR(250) DEFAULT NULL;

-- -----------------------------------------------------------------------------

ALTER TABLE formsqualifs ADD COLUMN qualsuivi VARCHAR(3);
ALTER TABLE formsqualifs ADD COLUMN nomsuivi VARCHAR(50);
ALTER TABLE formsqualifs ADD COLUMN prenomsuivi VARCHAR(50);
ALTER TABLE formsqualifs ADD COLUMN numtelsuivi VARCHAR(10);

ALTER TABLE formspermsfimo ADD COLUMN qualsuivi VARCHAR(3);
ALTER TABLE formspermsfimo ADD COLUMN nomsuivi VARCHAR(50);
ALTER TABLE formspermsfimo ADD COLUMN prenomsuivi VARCHAR(50);
ALTER TABLE formspermsfimo ADD COLUMN numtelsuivi VARCHAR(10);

ALTER TABLE actsprofs ADD COLUMN qualsuivi VARCHAR(3);
ALTER TABLE actsprofs ADD COLUMN nomsuivi VARCHAR(50);
ALTER TABLE actsprofs ADD COLUMN prenomsuivi VARCHAR(50);
ALTER TABLE actsprofs ADD COLUMN numtelsuivi VARCHAR(10);

ALTER TABLE permisb ADD COLUMN qualsuivi VARCHAR(3);
ALTER TABLE permisb ADD COLUMN nomsuivi VARCHAR(50);
ALTER TABLE permisb ADD COLUMN prenomsuivi VARCHAR(50);
ALTER TABLE permisb ADD COLUMN numtelsuivi VARCHAR(10);

ALTER TABLE acqsmatsprofs ADD COLUMN qualsuivi VARCHAR(3);
ALTER TABLE acqsmatsprofs ADD COLUMN nomsuivi VARCHAR(50);
ALTER TABLE acqsmatsprofs ADD COLUMN prenomsuivi VARCHAR(50);
ALTER TABLE acqsmatsprofs ADD COLUMN numtelsuivi VARCHAR(10);

ALTER TABLE accscreaentr ADD COLUMN qualsuivi VARCHAR(3);
ALTER TABLE accscreaentr ADD COLUMN nomsuivi VARCHAR(50);
ALTER TABLE accscreaentr ADD COLUMN prenomsuivi VARCHAR(50);
ALTER TABLE accscreaentr ADD COLUMN numtelsuivi VARCHAR(10);

ALTER TABLE locsvehicinsert ADD COLUMN qualsuivi VARCHAR(3);
ALTER TABLE locsvehicinsert ADD COLUMN nomsuivi VARCHAR(50);
ALTER TABLE locsvehicinsert ADD COLUMN prenomsuivi VARCHAR(50);
ALTER TABLE locsvehicinsert ADD COLUMN numtelsuivi VARCHAR(10);

ALTER TABLE amenagslogts ADD COLUMN qualsuivi VARCHAR(3);
ALTER TABLE amenagslogts ADD COLUMN nomsuivi VARCHAR(50);
ALTER TABLE amenagslogts ADD COLUMN prenomsuivi VARCHAR(50);
ALTER TABLE amenagslogts ADD COLUMN numtelsuivi VARCHAR(10);

-- *****************************************************************************
COMMIT;
-- *****************************************************************************
