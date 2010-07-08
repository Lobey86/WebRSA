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
-----------------------------  Ajout du 01/07/2010 ----------------------------
ALTER TABLE propospdos ADD COLUMN structurereferente_id INTEGER DEFAULT NULL REFERENCES structuresreferentes(id);
CREATE TYPE type_iscomplet AS ENUM ( 'COM', 'INC' );
ALTER TABLE propospdos ADD COLUMN iscomplet type_iscomplet DEFAULT NULL;
ALTER TABLE propospdos ADD COLUMN isvalidation type_booleannumber DEFAULT NULL;
ALTER TABLE propospdos ADD COLUMN validationdecision type_no DEFAULT NULL;
ALTER TABLE propospdos ADD COLUMN datevalidationdecision DATE;

ALTER TABLE propospdos ADD COLUMN isdecisionop type_booleannumber DEFAULT NULL;
ALTER TABLE propospdos ADD COLUMN decisionop type_decisioncomite DEFAULT NULL; -- FIXME: voir les champs à ajouter pr le moment ACC, REF, AJ
ALTER TABLE propospdos ADD COLUMN datedecisionop DATE;
ALTER TABLE propospdos ADD COLUMN observationoop TEXT;

ALTER TABLE cuis ALTER COLUMN compladremployeur DROP NOT NULL;

-----------------------------  Ajout du 05/07/2010 ----------------------------
CREATE TYPE type_etatdossierpdo AS ENUM ( '1', '2', '3', '4', '5', '6' );
ALTER TABLE propospdos ADD COLUMN etatdossierpdo type_etatdossierpdo DEFAULT NULL;



-- Champs manquants pour le passage en version v.32 de Cristal
ALTER TABLE personnes ADD COLUMN numagenpoleemploi CHAR(3) DEFAULT NULL;
ALTER TABLE personnes ADD COLUMN dtinscpoleemploi DATE DEFAULT NULL;
ALTER TABLE suspensionsdroits ADD COLUMN natgroupfsus CHAR(3) DEFAULT NULL;

-----------------------------  Ajout du 06/07/2010 ----------------------------
ALTER TABLE traitementspdos ADD COLUMN hasficheanalyse type_booleannumber DEFAULT NULL;
-- *****************************************************************************
COMMIT;
-- *****************************************************************************

-- *****************************************************************************
--  Ajout de la table Entretiens
-- *****************************************************************************
-- *****************************************************************************
BEGIN;
-- *****************************************************************************

CREATE TYPE type_typeentretien AS ENUM ( 'PHY', 'TEL', 'COU', 'MAI' );
CREATE TABLE entretiens(
    id                              SERIAL NOT NULL PRIMARY KEY,
    personne_id                     INTEGER NOT NULL REFERENCES personnes(id),
    referent_id                     INTEGER NOT NULL REFERENCES referents(id),
    structurereferente_id           INTEGER NOT NULL REFERENCES structuresreferentes(id),
    dateentretien                   DATE,
    typeentretien                   type_typeentretien DEFAULT NULL,
    typerdv_id                      INTEGER DEFAULT NULL REFERENCES typesrdv(id), -- objet du rdv
    rendezvousprevu                 type_booleannumber DEFAULT NULL,
    rendezvous_id                   INTEGER DEFAULT NULL REFERENCES rendezvous(id),
--     dateprochainrdv                 DATE,
    nv_dsp_id                       INTEGER DEFAULT NULL REFERENCES dsps(id),
    vx_dsp_id                       INTEGER DEFAULT NULL REFERENCES dsps(id),
    commentaireentretien            TEXT
);
COMMENT ON TABLE entretiens IS 'Table pour les entretiens des personnes';

CREATE INDEX entretiens_personne_id_idx ON entretiens(personne_id);
CREATE INDEX entretiens_dateentretien_idx ON entretiens(dateentretien);



-- ALTER TABLE cuis ALTER COLUMN orgapayeur SET DEFAULT 'ASP';
ALTER TABLE cuis ALTER COLUMN organisme TYPE VARCHAR(150);
-- *****************************************************************************
COMMIT;
-- *****************************************************************************