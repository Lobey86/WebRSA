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

--------------------------------------------------------------------------------
-- INFO: attention à ne pas passer ce morceau plusieurs fois!
--------------------------------------------------------------------------------
CREATE OR REPLACE FUNCTION public.update_duree_engag_integer( p_table TEXT ) RETURNS VOID AS
$$
	DECLARE
		v_query text;
	BEGIN
		v_query := 'UPDATE ' || p_table || '
			SET duree_engag =
				CASE
					WHEN duree_engag = 6 THEN 24
					WHEN duree_engag = 5 THEN 18
					WHEN duree_engag = 4 THEN 12
					WHEN duree_engag = 3 THEN 9
					WHEN duree_engag = 2 THEN 6
					WHEN duree_engag = 1 THEN 3
					ELSE 999
				END
			WHERE duree_engag IS NOT NULL;';

		RAISE NOTICE  '%', v_query;
		EXECUTE v_query;
	END;
$$
LANGUAGE plpgsql;

SELECT public.update_duree_engag_integer( 'bilansparcours66' );
SELECT public.update_duree_engag_integer( 'contratsinsertion' );
SELECT public.update_duree_engag_integer( 'proposcontratsinsertioncovs58' );
SELECT public.update_duree_engag_integer( 'decisionsproposcontratsinsertioncovs58' );

DROP FUNCTION public.update_duree_engag_integer( p_table TEXT );

-- *****************************************************************************
COMMIT;
-- *****************************************************************************
