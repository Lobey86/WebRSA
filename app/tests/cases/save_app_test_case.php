<?php

	if( !defined( 'CAKEPHP_UNIT_TEST_EXECUTION' ) ) {
		define( 'CAKEPHP_UNIT_TEST_EXECUTION', 1 );
	}

	ClassRegistry::config(array('ds' => 'test_suite'));

	class CakeAppTestCase extends CakeTestCase {

		/**
		* Tables de données à utiliser
		* TODO: certaines tables ont été supprimées, d'autres renommées -> le répercuter
		* sur les fixtures, les cas pour les modèles et pour les contrôleurs.
		*/

		public $fixtures = array (
			'app.acccreaentr',
			'app.acccreaentr_pieceacccreaentr',
			//'app.accoemploi',
			'app.aco',
			'app.acqmatprof',
			'app.acqmatprof_pieceacqmatprof',
			'app.action',
			'app.actioncandidat',
			'app.actioncandidat_partenaire',
			'app.actioncandidat_personne',
			'app.actioninsertion',
			'app.activite',
			'app.actprof',
			'app.actprof_pieceactprof',
			'app.adresse',
			'app.adressefoyer',
			'app.aideagricole',
			'app.aideapre66',
			'app.aideapre66_pieceaide66',
			'app.aideapre66_piececomptable66',
			'app.aidedirecte',
			'app.allocationsoutienfamilial',
			'app.amenaglogt',
			'app.amenaglogt_pieceamenaglogt',
			'app.anomaly',
			'app.apre_comiteapre',
			'app.apre_etatliquidatif',
			'app.apre',
			'app.apre_pieceapre',
			'app.aro_aco',
			'app.aro',
			'app.avispcgdroitrsa',
			'app.avispcgpersonne',
			'app.bilanparcours',
			'app.budgetapre',
			'app.calculdroitrsa',
			'app.canton',
			'app.comiteapre',
			'app.comiteapre_participantcomite',
			'app.condadmin',
			'app.connection',
			'app.contactpartenaire',
			'app.contactpartenaire_partenaire',
			'app.contratinsertion',
			'app.controlesadministratif',
			'app.creance',
			'app.creancealimentaire',
			'app.cui',
			'app.contratinsertion_user',
			'app.decisionpdo',
			'app.decisionreorient',
			'app.decisionsparcour',
			'app.demandereorient',
			'app.departement',
			'app.derogation',
			'app.descriptionpdo',
			'app.detailaccosocfam',
			'app.detailaccosocindi',
			'app.detailcalculdroitrsa',
			'app.detaildifdisp',
			'app.detaildiflog',
			'app.detaildifsoc',
			'app.detaildroitrsa',
			'app.detailnatmob',
			'app.detailressourcemensuelle',
// 			'app.difdisp',
// 			'app.diflog',
// 			'app.difsoc',
			'app.domiciliationbancaire',
			'app.dossier',
			'app.dossiercaf',
			'app.dsp',
			'app.dspf_diflog',
			'app.dspf',
			'app.dspf_nataccosocfam',
			'app.dspp_accoemploi',
			'app.dspp_difdisp',
			'app.dspp_difsoc',
			'app.dspp',
			'app.dspp_nataccosocindi',
			'app.dspp_natmob',
			'app.dspp_nivetu',
			'app.entretien',
// 			'app.ep',
// 			'app.ep_zonegeographique',
// 			'app.eps_partsep',
			'app.etatliquidatif',
			'app.evenement',
			'app.fonctionpartep',
			'app.formpermfimo',
			'app.formpermfimo_pieceformpermfimo',
			'app.formqualif',
			'app.formqualif_pieceformqualif',
			'app.foyer',
			'app.fraisdeplacement66',
			'app.frenchfloat',
			'app.grossesse',
			'app.group',
			'app.identificationflux',
			'app.infoagricole',
			'app.infofinanciere',
			'app.infopoleemploi',
			'app.informationeti',
			'app.integrationfichierapre',
			'app.item',
			'app.jeton',
			'app.jetonfonction',
			'app.liberalite',
			'app.locvehicinsert',
			'app.locvehicinsert_piecelocvehicinsert',
			'app.memo',
			'app.modecontact',
			'app.montantconsomme',
			'app.motifdemreorient',
// 			'app.nataccosocfam',
// 			'app.nataccosocindi',
// 			'app.natmob',
// 			'app.nivetus',
			'app.orientation',
			'app.orientstruct',
			'app.orientstruct_serviceinstructeur',
			'app.originepdo',
			'app.paiementfoyer',
			'app.parametrefinancier',
			'app.parcours',
			'app.parcoursdetecte',
			'app.partenaire',
			'app.partep',
			'app.partep_seanceep',
			'app.participantcomite',
			'app.partitem',
			'app.pdf',
			'app.periodeimmersion',
			'app.permanence',
			'app.permisb',
			'app.permisb_piecepermisb',
			'app.personne',
			'app.personne_referent',
			'app.pieceacccreaentr',
			'app.pieceacqmatprof',
			'app.pieceactprof',
			'app.pieceaide66',
			'app.pieceamenaglogt',
			'app.pieceapre',
			'app.piececomptable66',
			'app.pieceformpermfimo',
			'app.pieceformqualif',
			'app.piecelocvehicinsert',
			'app.piecepdo',
			'app.piecepermisb',
			'app.precosreorient',
			'app.prestation',
			'app.prestform',
			'app.propopdo',
			'app.propopdo_situationpdo',
			'app.propopdo_statutdecisionpdo',
			'app.propopdo_statutpdo',
			'app.rattachement',
			'app.reducrsa',
			'app.referent',
			'app.refpresta',
			'app.regroupementzonegeo',
			'app.relanceapre',
			'app.rendezvous',
			'app.ressource',
			'app.ressource_ressourcemensuelle',
			'app.ressourcemensuelle_detailressourcemensuelle',
			'app.ressourcemensuelle',
			'app.rolespartsep',
			'app.seanceep',
			'app.serviceinstructeur',
			'app.situationdossierrsa',
			'app.situationpdo',
			'app.statutdecisionpdo',
			'app.statutpdo',
			'app.statutrdv',
			'app.structurereferente',
			'app.structurereferente_zonegeographique',
			'app.suiviaideapre',
			'app.suiviaideapretypeaide',
			'app.suiviappuiorientation',
			'app.suiviinstruction',
			'app.suspensiondroit',
			'app.suspensionversement',
			'app.tempcessation',
			'app.tempinscription',
			'app.tempradiation',
			'app.themeapre66',
			'app.tiersprestataireapre',
			'app.titre_sejour',
			'app.totalisationacompte',
			'app.traitementpdo',
			'app.traitementtypepdo',
			'app.transmissionflux',
			'app.typeaction',
			'app.typeaideapre66',
			'app.pieceaide66_typeaideapre66',
			'app.piececomptable66_typeaideapre66',
			'app.typenotifpdo',
			'app.typeorient',
			'app.typepdo',
			'app.typerdv',
			'app.typocontrat',
			//'app.user_contratinsertion',
			'app.user',
			'app.user_zonegeographique',
			'app.zonegeographique',
			'app.zonegeographique_regroupementzonegeo',
		);

		function startCase() { Cache::clear(); clearCache(); }
	}
?>