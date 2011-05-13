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
UPDATE proposorientationscovs58 SET decisioncov = 'accepte' WHERE decisioncov IS NULL;
ALTER TABLE proposorientationscovs58 ALTER COLUMN decisioncov SET NOT NULL;
SELECT add_missing_table_field ('public', 'proposcontratsinsertioncovs58', 'decisioncov', 'VARCHAR(10)');
UPDATE proposcontratsinsertioncovs58 SET decisioncov = 'accepte' WHERE decisioncov IS NULL;
ALTER TABLE proposcontratsinsertioncovs58 ALTER COLUMN decisioncov SET NOT NULL;

-- *****************************************************************************
COMMIT;
-- *****************************************************************************
