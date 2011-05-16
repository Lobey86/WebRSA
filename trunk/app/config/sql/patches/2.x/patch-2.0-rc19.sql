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
SELECT add_missing_table_field ('public', 'membreseps', 'numvoie', 'VARCHAR(6)');
SELECT add_missing_table_field ('public', 'membreseps', 'typevoie', 'VARCHAR(4)');
SELECT add_missing_table_field ('public', 'membreseps', 'nomvoie', 'VARCHAR(100)');
SELECT add_missing_table_field ('public', 'membreseps', 'compladr', 'VARCHAR(100)');
SELECT add_missing_table_field ('public', 'membreseps', 'codepostal', 'CHAR(5)');
SELECT add_missing_table_field ('public', 'membreseps', 'ville', 'VARCHAR(100)');

ALTER TABLE actionscandidats ALTER COLUMN contractualisation SET NOT NULL;

SELECT add_missing_table_field ('public', 'proposorientationscovs58', 'decisioncov', 'CHAR(10)');
SELECT add_missing_table_field ('public', 'proposcontratsinsertioncovs58', 'decisioncov', 'VARCHAR(10)');

UPDATE contratsinsertion 
    SET decision_ci = 'N'
    WHERE decision_ci IN ( 'A', 'R' );

UPDATE contratsinsertion 
    SET datevalidation_ci = null
    WHERE decision_ci IN ( 'N', 'E' );

UPDATE contratsinsertion 
    SET datevalidation_ci = dd_ci
    WHERE
        decision_ci = 'V'
        AND datevalidation_ci IS NULL;


ALTER TABLE contratsinsertion ADD CONSTRAINT contratsinsertion_decision_ci_datevalidation_ci_check CHECK(
    ( decision_ci = 'V' AND datevalidation_ci IS NOT NULL )
    OR ( decision_ci <> 'V' AND datevalidation_ci IS NULL )
);


SELECT add_missing_table_field ('public', 'proposorientationscovs58', 'referent_id', 'INTEGER');
SELECT add_missing_constraint ('public', 'proposorientationscovs58', 'proposorientationscovs58_referent_id_fkey', 'referents', 'referent_id');
SELECT add_missing_table_field ('public', 'proposorientationscovs58', 'covreferent_id', 'INTEGER');
SELECT add_missing_constraint ('public', 'proposorientationscovs58', 'proposorientationscovs58_covreferent_id_fkey', 'referents', 'covreferent_id');

SELECT add_missing_table_field ('public', 'eps_membreseps', 'suppleant_id', 'INTEGER');
SELECT add_missing_constraint ('public', 'eps_membreseps', 'eps_membreseps_suppleant_id_fkey', 'referents', 'suppleant_id');

SELECT add_missing_table_field ('public', 'membreseps', 'suppleant_id', 'INTEGER');
ALTER TABLE membreseps DROP COLUMN suppleant_id;

-- *****************************************************************************
COMMIT;
-- *****************************************************************************
