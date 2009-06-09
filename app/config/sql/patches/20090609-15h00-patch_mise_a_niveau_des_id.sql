SELECT pg_catalog.setval('users_id_seq', ( SELECT max(users.id) + 1 FROM users ), false);
SELECT pg_catalog.setval('zonesgeographiques_id_seq', ( SELECT max(zonesgeographiques.id) + 1 FROM zonesgeographiques ), false);
SELECT pg_catalog.setval('typesorients_id_seq', ( SELECT max(typesorients.id) + 1 FROM typesorients ), false);
SELECT pg_catalog.setval('structuresreferentes_id_seq', ( SELECT max(structuresreferentes.id) + 1 FROM structuresreferentes ), false);
SELECT pg_catalog.setval('typoscontrats_id_seq', ( SELECT max(typoscontrats.id) + 1 FROM typoscontrats ), false);

SELECT pg_catalog.setval('accoemplois_id_seq', ( SELECT max(accoemplois.id) + 1 FROM accoemplois ), false);
SELECT pg_catalog.setval('acos_id_seq', ( SELECT max(acos.id) + 1 FROM acos ), false);
SELECT pg_catalog.setval('actions_id_seq', ( SELECT max(actions.id) + 1 FROM actions ), false);
SELECT pg_catalog.setval('actionsinsertion_id_seq', ( SELECT max(actionsinsertion.id) + 1 FROM actionsinsertion ), false);
SELECT pg_catalog.setval('activites_id_seq', ( SELECT max(activites.id) + 1 FROM activites ), false);
SELECT pg_catalog.setval('adresses_id_seq', ( SELECT max(adresses.id) + 1 FROM adresses ), false);
SELECT pg_catalog.setval('adresses_foyers_id_seq', ( SELECT max(adresses_foyers.id) + 1 FROM adresses_foyers ), false);
SELECT pg_catalog.setval('aidesagricoles_id_seq', ( SELECT max(aidesagricoles.id) + 1 FROM aidesagricoles ), false);
SELECT pg_catalog.setval('aidesdirectes_id_seq', ( SELECT max(aidesdirectes.id) + 1 FROM aidesdirectes ), false);
SELECT pg_catalog.setval('allocationssoutienfamilial_id_seq', ( SELECT max(allocationssoutienfamilial.id) + 1 FROM allocationssoutienfamilial ), false);
SELECT pg_catalog.setval('aros_acos_id_seq', ( SELECT max(aros_acos.id) + 1 FROM aros_acos ), false);
SELECT pg_catalog.setval('avispcgdroitrsa_id_seq', ( SELECT max(avispcgdroitrsa.id) + 1 FROM avispcgdroitrsa ), false);
SELECT pg_catalog.setval('avispcgpersonnes_id_seq', ( SELECT max(avispcgpersonnes.id) + 1 FROM avispcgpersonnes ), false);
SELECT pg_catalog.setval('condsadmins_id_seq', ( SELECT max(condsadmins.id) + 1 FROM condsadmins ), false);
SELECT pg_catalog.setval('contratsinsertion_id_seq', ( SELECT max(contratsinsertion.id) + 1 FROM contratsinsertion ), false);
SELECT pg_catalog.setval('creances_id_seq', ( SELECT max(creances.id) + 1 FROM creances ), false);
SELECT pg_catalog.setval('creancesalimentaires_id_seq', ( SELECT max(creancesalimentaires.id) + 1 FROM creancesalimentaires ), false);
SELECT pg_catalog.setval('derogations_id_seq', ( SELECT max(derogations.id) + 1 FROM derogations ), false);
SELECT pg_catalog.setval('detailscalculsdroitsrsa_id_seq', ( SELECT max(detailscalculsdroitsrsa.id) + 1 FROM detailscalculsdroitsrsa ), false);
SELECT pg_catalog.setval('detailsdroitsrsa_id_seq', ( SELECT max(detailsdroitsrsa.id) + 1 FROM detailsdroitsrsa ), false);
SELECT pg_catalog.setval('detailsressourcesmensuelles_id_seq', ( SELECT max(detailsressourcesmensuelles.id) + 1 FROM detailsressourcesmensuelles ), false);
SELECT pg_catalog.setval('difdisps_id_seq', ( SELECT max(difdisps.id) + 1 FROM difdisps ), false);
SELECT pg_catalog.setval('diflogs_id_seq', ( SELECT max(diflogs.id) + 1 FROM diflogs ), false);
SELECT pg_catalog.setval('difsocs_id_seq', ( SELECT max(difsocs.id) + 1 FROM difsocs ), false);
SELECT pg_catalog.setval('dossiers_rsa_id_seq', ( SELECT max(dossiers_rsa.id) + 1 FROM dossiers_rsa ), false);
SELECT pg_catalog.setval('dossierscaf_id_seq', ( SELECT max(dossierscaf.id) + 1 FROM dossierscaf ), false);
SELECT pg_catalog.setval('dspfs_id_seq', ( SELECT max(dspfs.id) + 1 FROM dspfs ), false);
SELECT pg_catalog.setval('dspps_id_seq', ( SELECT max(dspps.id) + 1 FROM dspps ), false);
SELECT pg_catalog.setval('evenements_id_seq', ( SELECT max(evenements.id) + 1 FROM evenements ), false);
SELECT pg_catalog.setval('foyers_id_seq', ( SELECT max(foyers.id) + 1 FROM foyers ), false);
SELECT pg_catalog.setval('grossesses_id_seq', ( SELECT max(grossesses.id) + 1 FROM grossesses ), false);
SELECT pg_catalog.setval('groups_id_seq', ( SELECT max(groups.id) + 1 FROM groups ), false);
SELECT pg_catalog.setval('identificationsflux_id_seq', ( SELECT max(identificationsflux.id) + 1 FROM identificationsflux ), false);
SELECT pg_catalog.setval('informationseti_id_seq', ( SELECT max(informationseti.id) + 1 FROM informationseti ), false);
SELECT pg_catalog.setval('informationseti_id_seq', ( SELECT max(informationseti.id) + 1 FROM informationseti ), false);
SELECT pg_catalog.setval('infosagricoles_id_seq', ( SELECT max(infosagricoles.id) + 1 FROM infosagricoles ), false);
SELECT pg_catalog.setval('infosfinancieres_id_seq', ( SELECT max(infosfinancieres.id) + 1 FROM infosfinancieres ), false);
SELECT pg_catalog.setval('jetons_id_seq', ( SELECT max(jetons.id) + 1 FROM jetons ), false);
SELECT pg_catalog.setval('liberalites_id_seq', ( SELECT max(liberalites.id) + 1 FROM liberalites ), false);
SELECT pg_catalog.setval('modes_contact_id_seq', ( SELECT max(modes_contact.id) + 1 FROM modes_contact ), false);
SELECT pg_catalog.setval('nataccosocfams_id_seq', ( SELECT max(nataccosocfams.id) + 1 FROM nataccosocfams ), false);
SELECT pg_catalog.setval('nataccosocindis_id_seq', ( SELECT max(nataccosocindis.id) + 1 FROM nataccosocindis ), false);
SELECT pg_catalog.setval('natmobs_id_seq', ( SELECT max(natmobs.id) + 1 FROM natmobs ), false);
SELECT pg_catalog.setval('nivetus_id_seq', ( SELECT max(nivetus.id) + 1 FROM nivetus ), false);
SELECT pg_catalog.setval('orientsstructs_id_seq', ( SELECT max(orientsstructs.id) + 1 FROM orientsstructs ), false);
SELECT pg_catalog.setval('paiementsfoyers_id_seq', ( SELECT max(paiementsfoyers.id) + 1 FROM paiementsfoyers ), false);
SELECT pg_catalog.setval('personnes_id_seq', ( SELECT max(personnes.id) + 1 FROM personnes ), false);
SELECT pg_catalog.setval('prestsform_id_seq', ( SELECT max(prestsform.id) + 1 FROM prestsform ), false);