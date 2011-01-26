SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;
SET search_path = public, pg_catalog;
SET default_tablespace = '';
SET default_with_oids = false;

-- -----------------------------------------------------------------------------
BEGIN;
-- -----------------------------------------------------------------------------

DROP INDEX IF EXISTS bilansparcours66_accompagnement_idx;
DROP INDEX IF EXISTS bilansparcours66_accordprojet_idx;
DROP INDEX IF EXISTS bilansparcours66_changementrefeplocale_idx;
DROP INDEX IF EXISTS bilansparcours66_changementrefparcours_idx;
DROP INDEX IF EXISTS bilansparcours66_changementrefsansep_idx;
DROP INDEX IF EXISTS bilansparcours66_changereferent_idx;
DROP INDEX IF EXISTS bilansparcours66_choixparcours_idx;
DROP INDEX IF EXISTS bilansparcours66_datebilan_idx;
DROP INDEX IF EXISTS bilansparcours66_ddreconductoncontrat_idx;
DROP INDEX IF EXISTS bilansparcours66_decisioncga_idx;
DROP INDEX IF EXISTS bilansparcours66_decisioncommission_idx;
DROP INDEX IF EXISTS bilansparcours66_decisioncoordonnateur_idx;
DROP INDEX IF EXISTS bilansparcours66_dfreconductoncontrat_idx;
DROP INDEX IF EXISTS bilansparcours66_examenaudition_idx;
DROP INDEX IF EXISTS bilansparcours66_maintienorientation_idx;
DROP INDEX IF EXISTS bilansparcours66_maintienorientavisep_idx;
DROP INDEX IF EXISTS bilansparcours66_maintienorientparcours_idx;
DROP INDEX IF EXISTS bilansparcours66_maintienorientsansep_idx;
DROP INDEX IF EXISTS bilansparcours66_presenceallocataire_idx;
DROP INDEX IF EXISTS bilansparcours66_reorientation_idx;
DROP INDEX IF EXISTS bilansparcours66_reorientationeplocale_idx;
DROP INDEX IF EXISTS bilansparcours66_saisineepparcours_idx;
DROP INDEX IF EXISTS bilansparcours66_typeeplocale_idx;
DROP INDEX IF EXISTS bilansparcours66_typeformulaire_idx;
DROP INDEX IF EXISTS decisionsdefautsinsertionseps66_structurereferente_id_idx;
DROP INDEX IF EXISTS decisionsdefautsinsertionseps66_typeorient_id_idx;
DROP INDEX IF EXISTS decisionsnonrespectssanctionseps93_decision_idx;
DROP INDEX IF EXISTS decisionsnonrespectssanctionseps93_etape_idx;
DROP INDEX IF EXISTS descriptionspdos_dateactive_idx;
DROP INDEX IF EXISTS descriptionspdos_declencheep_idx;
DROP INDEX IF EXISTS dossierseps_seanceep_id_idx;
DROP INDEX IF EXISTS dossierseps_themeep_idx;
DROP INDEX IF EXISTS eps_defautinsertionep66_idx;
DROP INDEX IF EXISTS eps_nonrespectsanctionep93_idx;
DROP INDEX IF EXISTS eps_saisineepbilanparcours66_idx;
DROP INDEX IF EXISTS eps_saisineepdpdo66_idx;
DROP INDEX IF EXISTS eps_saisineepreorientsr93_idx;
DROP INDEX IF EXISTS membreseps_qual_idx;
DROP INDEX IF EXISTS membreseps_suppleant_id_idx;
DROP INDEX IF EXISTS membreseps_seanceseps_membreep_id_idx;
DROP INDEX IF EXISTS membreseps_seanceseps_presence_idx;
DROP INDEX IF EXISTS membreseps_seanceseps_reponse_idx;
DROP INDEX IF EXISTS membreseps_seanceseps_suppleant_idx;
DROP INDEX IF EXISTS membreseps_seanceseps_suppleant_id_idx;
DROP INDEX IF EXISTS nonrespectssanctionseps93_decision_idx;
DROP INDEX IF EXISTS nonrespectssanctionseps93_origine_idx;
DROP INDEX IF EXISTS nonrespectssanctionseps93_sortienvcontrat_idx;
DROP INDEX IF EXISTS nvsepdspdos66_datedecisionpdo_idx;
DROP INDEX IF EXISTS nvsepdspdos66_etape_idx;
DROP INDEX IF EXISTS nvsepdspdos66_nonadmis_idx;
DROP INDEX IF EXISTS nvsrsepsreorient66_decision_idx;
DROP INDEX IF EXISTS nvsrsepsreorient66_etape_idx;
DROP INDEX IF EXISTS nvsrsepsreorientsrs93_decision_idx;
DROP INDEX IF EXISTS nvsrsepsreorientsrs93_etape_idx;
DROP INDEX IF EXISTS propospdos_orgpayeur_idx;
DROP INDEX IF EXISTS relancesnonrespectssanctionseps93_dateimpression_idx;
DROP INDEX IF EXISTS relancesnonrespectssanctionseps93_daterelance_idx;
DROP INDEX IF EXISTS saisinesepsreorientsrs93_accordaccueil_idx;
DROP INDEX IF EXISTS saisinesepsreorientsrs93_accordallocataire_idx;
DROP INDEX IF EXISTS saisinesepsreorientsrs93_urgent_idx;
DROP INDEX IF EXISTS seanceseps_finalisee_idx;
DROP INDEX IF EXISTS traitementspdos_dateecheance_idx;
DROP INDEX IF EXISTS traitementspdos_daterevision_idx;

-- -----------------------------------------------------------------------------

CREATE INDEX bilansparcours66_accompagnement_idx ON bilansparcours66 (accompagnement);
CREATE INDEX bilansparcours66_accordprojet_idx ON bilansparcours66 (accordprojet);
CREATE INDEX bilansparcours66_changementrefeplocale_idx ON bilansparcours66 (changementrefeplocale);
CREATE INDEX bilansparcours66_changementrefparcours_idx ON bilansparcours66 (changementrefparcours);
CREATE INDEX bilansparcours66_changementrefsansep_idx ON bilansparcours66 (changementrefsansep);
CREATE INDEX bilansparcours66_changereferent_idx ON bilansparcours66 (changereferent);
CREATE INDEX bilansparcours66_choixparcours_idx ON bilansparcours66 (choixparcours);
CREATE INDEX bilansparcours66_datebilan_idx ON bilansparcours66 (datebilan);
CREATE INDEX bilansparcours66_ddreconductoncontrat_idx ON bilansparcours66 (ddreconductoncontrat);
CREATE INDEX bilansparcours66_decisioncga_idx ON bilansparcours66 (decisioncga);
CREATE INDEX bilansparcours66_decisioncommission_idx ON bilansparcours66 (decisioncommission);
CREATE INDEX bilansparcours66_decisioncoordonnateur_idx ON bilansparcours66 (decisioncoordonnateur);
CREATE INDEX bilansparcours66_dfreconductoncontrat_idx ON bilansparcours66 (dfreconductoncontrat);
CREATE INDEX bilansparcours66_examenaudition_idx ON bilansparcours66 (examenaudition);
CREATE INDEX bilansparcours66_maintienorientation_idx ON bilansparcours66 (maintienorientation);
CREATE INDEX bilansparcours66_maintienorientavisep_idx ON bilansparcours66 (maintienorientavisep);
CREATE INDEX bilansparcours66_maintienorientparcours_idx ON bilansparcours66 (maintienorientparcours);
CREATE INDEX bilansparcours66_maintienorientsansep_idx ON bilansparcours66 (maintienorientsansep);
CREATE INDEX bilansparcours66_presenceallocataire_idx ON bilansparcours66 (presenceallocataire);
CREATE INDEX bilansparcours66_reorientation_idx ON bilansparcours66 (reorientation);
CREATE INDEX bilansparcours66_reorientationeplocale_idx ON bilansparcours66 (reorientationeplocale);
CREATE INDEX bilansparcours66_saisineepparcours_idx ON bilansparcours66 (saisineepparcours);
CREATE INDEX bilansparcours66_typeeplocale_idx ON bilansparcours66 (typeeplocale);
CREATE INDEX bilansparcours66_typeformulaire_idx ON bilansparcours66 (typeformulaire);
CREATE INDEX decisionsdefautsinsertionseps66_structurereferente_id_idx ON decisionsdefautsinsertionseps66 (structurereferente_id);
CREATE INDEX decisionsdefautsinsertionseps66_typeorient_id_idx ON decisionsdefautsinsertionseps66 (typeorient_id);
CREATE INDEX decisionsnonrespectssanctionseps93_decision_idx ON decisionsnonrespectssanctionseps93 (decision);
CREATE INDEX decisionsnonrespectssanctionseps93_etape_idx ON decisionsnonrespectssanctionseps93 (etape);
CREATE INDEX descriptionspdos_dateactive_idx ON descriptionspdos (dateactive);
CREATE INDEX descriptionspdos_declencheep_idx ON descriptionspdos (declencheep);
CREATE INDEX dossierseps_seanceep_id_idx ON dossierseps (seanceep_id);
CREATE INDEX dossierseps_themeep_idx ON dossierseps (themeep);
CREATE INDEX eps_defautinsertionep66_idx ON eps (defautinsertionep66);
CREATE INDEX eps_nonrespectsanctionep93_idx ON eps (nonrespectsanctionep93);
CREATE INDEX eps_saisineepbilanparcours66_idx ON eps (saisineepbilanparcours66);
CREATE INDEX eps_saisineepdpdo66_idx ON eps (saisineepdpdo66);
CREATE INDEX eps_saisineepreorientsr93_idx ON eps (saisineepreorientsr93);
CREATE INDEX membreseps_qual_idx ON membreseps (qual);
CREATE INDEX membreseps_suppleant_id_idx ON membreseps (suppleant_id);
CREATE INDEX membreseps_seanceseps_membreep_id_idx ON membreseps_seanceseps (membreep_id);
CREATE INDEX membreseps_seanceseps_presence_idx ON membreseps_seanceseps (presence);
CREATE INDEX membreseps_seanceseps_reponse_idx ON membreseps_seanceseps (reponse);
CREATE INDEX membreseps_seanceseps_suppleant_idx ON membreseps_seanceseps (suppleant);
CREATE INDEX membreseps_seanceseps_suppleant_id_idx ON membreseps_seanceseps (suppleant_id);
CREATE INDEX nonrespectssanctionseps93_decision_idx ON nonrespectssanctionseps93 (decision);
CREATE INDEX nonrespectssanctionseps93_origine_idx ON nonrespectssanctionseps93 (origine);
CREATE INDEX nonrespectssanctionseps93_sortienvcontrat_idx ON nonrespectssanctionseps93 (sortienvcontrat);
CREATE INDEX nvsepdspdos66_datedecisionpdo_idx ON nvsepdspdos66 (datedecisionpdo);
CREATE INDEX nvsepdspdos66_etape_idx ON nvsepdspdos66 (etape);
CREATE INDEX nvsepdspdos66_nonadmis_idx ON nvsepdspdos66 (nonadmis);
CREATE INDEX nvsrsepsreorient66_decision_idx ON nvsrsepsreorient66 (decision);
CREATE INDEX nvsrsepsreorient66_etape_idx ON nvsrsepsreorient66 (etape);
CREATE INDEX nvsrsepsreorientsrs93_decision_idx ON nvsrsepsreorientsrs93 (decision);
CREATE INDEX nvsrsepsreorientsrs93_etape_idx ON nvsrsepsreorientsrs93 (etape);
CREATE INDEX propospdos_orgpayeur_idx ON propospdos (orgpayeur);
CREATE INDEX relancesnonrespectssanctionseps93_dateimpression_idx ON relancesnonrespectssanctionseps93 (dateimpression);
CREATE INDEX relancesnonrespectssanctionseps93_daterelance_idx ON relancesnonrespectssanctionseps93 (daterelance);
CREATE INDEX saisinesepsreorientsrs93_accordaccueil_idx ON saisinesepsreorientsrs93 (accordaccueil);
CREATE INDEX saisinesepsreorientsrs93_accordallocataire_idx ON saisinesepsreorientsrs93 (accordallocataire);
CREATE INDEX saisinesepsreorientsrs93_urgent_idx ON saisinesepsreorientsrs93 (urgent);
CREATE INDEX seanceseps_finalisee_idx ON seanceseps (finalisee);
CREATE INDEX traitementspdos_dateecheance_idx ON traitementspdos (dateecheance);
CREATE INDEX traitementspdos_daterevision_idx ON traitementspdos (daterevision);

-- -----------------------------------------------------------------------------
COMMIT;
-- -----------------------------------------------------------------------------