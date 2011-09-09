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

CREATE OR REPLACE FUNCTION public.alter_enumtype ( p_enumtypename text, p_values text[] ) RETURNS void AS
$$
	DECLARE
		v_row			record;
		v_query			text;
		v_enumtypename	text;
	BEGIN
		-- PostgreSQL stocke ses types en minuscule
		v_enumtypename := LOWER( p_enumtypename );

		v_query := 'DROP TABLE IF EXISTS __alter_enumtype;';
		EXECUTE v_query;

		v_query := 'CREATE TEMP TABLE __alter_enumtype(table_schema TEXT, table_name TEXT, column_name TEXT, column_default TEXT);';
		EXECUTE v_query;

		v_query := 'INSERT INTO __alter_enumtype (
						SELECT
								table_schema,
								table_name,
								column_name,
								regexp_replace( column_default, ''^''''(.*)''''::.*$'', E''\\\\1'', ''g'' ) AS column_default
							FROM information_schema.columns
							WHERE
								data_type = ''USER-DEFINED''
								AND udt_name = ''' || v_enumtypename || '''
							ORDER BY
								table_schema,
								table_name,
								column_name
					);';
		EXECUTE v_query;

		-- Première boucle pour tout transformer en TEXT
		FOR v_row IN
			SELECT
					*
				FROM __alter_enumtype
				ORDER BY
					table_schema,
					table_name,
					column_name
		LOOP
			-- DROP DEFAULT
			IF v_row.column_default IS NOT NULL THEN
				v_query := 'ALTER TABLE ' || v_row.table_schema || '.' || v_row.table_name || ' ALTER COLUMN ' || v_row.column_name || ' DROP DEFAULT;';
				EXECUTE v_query;
			END IF;

			-- ALTER COLUMN
			v_query := 'ALTER TABLE ' || v_row.table_schema || '.' || v_row.table_name || ' ALTER COLUMN ' || v_row.column_name || ' TYPE TEXT USING CAST( ' || v_row.column_name || ' AS TEXT );';
			EXECUTE v_query;
		END LOOP;

		v_query := 'DROP TYPE ' || v_enumtypename || ';';
		EXECUTE v_query;

		v_query := 'CREATE TYPE ' || v_enumtypename || ' AS ENUM (''' || array_to_string( p_values, ''', ''' ) || ''' );';
		EXECUTE v_query;

		-- Seconde boucle pour tout transformer en le nouveau type
		FOR v_row IN
			SELECT
					*
				FROM __alter_enumtype
				ORDER BY
					table_schema,
					table_name,
					column_name
		LOOP
			-- ALTER COLUMN
			v_query := 'ALTER TABLE ' || v_row.table_schema || '.' || v_row.table_name || ' ALTER COLUMN ' || v_row.column_name || ' TYPE ' || v_enumtypename || ' USING CAST( ' || v_row.column_name || ' AS ' || v_enumtypename || ' );';
			EXECUTE v_query;

			-- SET DEFAULT
			IF v_row.column_default IS NOT NULL THEN
				v_query := 'ALTER TABLE ' || v_row.table_schema || '.' || v_row.table_name || ' ALTER COLUMN ' || v_row.column_name || ' SET DEFAULT ''' || v_row.column_default || '''::' || v_enumtypename || ';';
				EXECUTE v_query;
			END IF;
		END LOOP;

		v_query := 'DROP TABLE IF EXISTS __alter_enumtype;';
		EXECUTE v_query;
	END;
$$
LANGUAGE plpgsql;

COMMENT ON FUNCTION public.alter_enumtype ( p_enumtypename text, p_values text[] ) IS 'Modification des valeurs acceptées par un type enum, pour tous les champs qui l''utilisent (PostgreSQL >= 8.3)';

-- ----------------------------------------------------------------------------

-- Ajout de la position "traite" aux position du bilan de parcours du CG 66
SELECT public.alter_enumtype ( 'TYPE_POSITIONBILAN', ARRAY['eplaudit', 'eplparc', 'attcga', 'attct', 'ajourne', 'annule', 'traite'] );

-- ----------------------------------------------------------------------------

-- Ajout des valeurs emploi et professionnel vers professionnel pour les tables
-- saisinesbilansparcourseps66, decisionssaisinesbilansparcourseps66 et bilansparcours66
SELECT public.alter_enumtype ( 'TYPE_ORIENT', ARRAY['social','prepro','pro'] );
SELECT public.alter_enumtype ( 'TYPE_REORIENTATION', ARRAY['SP', 'PS', 'PP'] );

-- ----------------------------------------------------------------------------
-- 06/09/2011:
-- ----------------------------------------------------------------------------
-- Ajout de la position termine pour les CER du CG66. La position ressemble à
-- fincontrat sauf que fincontrat correspond à un contrat terminé avant terme
-- suite à un passage en EP, alors que termine correspond à un CER arrivé
-- simplement à terme.
-- SELECT public.alter_enumtype ( 'TYPE_POSITIONCER', ARRAY['encours', 'attvalid', 'annule', 'fincontrat', 'encoursbilan', 'attrenouv', 'perime', 'termine'] );

-- ----------------------------------------------------------------------------
-- 09/09/2011:
-- ----------------------------------------------------------------------------
ALTER TABLE actionscandidats_personnes ALTER COLUMN naturemobile TYPE TEXT;
DROP TYPE IF EXISTS TYPE_FICHELIAISONNATUREMOBILE;
CREATE TYPE TYPE_FICHELIAISONNATUREMOBILE AS ENUM ( 'commune', 'canton', 'dept', 'horsdept' );
UPDATE actionscandidats_personnes SET naturemobile = 'commune' WHERE naturemobile = '2501';
UPDATE actionscandidats_personnes SET naturemobile = 'dept' WHERE naturemobile = '2502';
UPDATE actionscandidats_personnes SET naturemobile = 'horsdept' WHERE naturemobile = '2503';
UPDATE actionscandidats_personnes SET naturemobile = null WHERE naturemobile = '2504';
ALTER TABLE actionscandidats_personnes ALTER COLUMN naturemobile TYPE TYPE_FICHELIAISONNATUREMOBILE USING CAST(naturemobile AS TYPE_FICHELIAISONNATUREMOBILE);

-- *****************************************************************************
COMMIT;
-- *****************************************************************************