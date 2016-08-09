<?php

/**
 * Code source de la classe Dossierpcg66.
 *
 * PHP 5.3
 *
 * @package app.Model
 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
 */

/**
 * La classe Dossierpcg66 ...
 *
 * @package app.Model
 */
class Dossierpcg66 extends AppModel {

    public $name = 'Dossierpcg66';
    public $recursive = -1;
    public $virtualFields = array(
        'nbpropositions' => array(
            'type' => 'integer',
            'postgres' => '(
					SELECT COUNT(*)
						FROM decisionsdossierspcgs66
						WHERE
							decisionsdossierspcgs66.dossierpcg66_id = "%s"."id"
				)',
        ),
    );
    public $actsAs = array(
        'Pgsqlcake.PgsqlAutovalidate',
        'Formattable' => array(
            'suffix' => array('user_id')
        ),
        'Enumerable' => array(
            'fields' => array(
                'orgpayeur',
                'iscomplet',
                'haspiecejointe',
                'istransmis'
            )
        )
    );
    public $belongsTo = array(
        'Bilanparcours66' => array(
            'className' => 'Bilanparcours66',
            'foreignKey' => 'bilanparcours66_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Contratinsertion' => array(
            'className' => 'Contratinsertion',
            'foreignKey' => 'contratinsertion_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Decisiondefautinsertionep66' => array(
            'className' => 'Decisiondefautinsertionep66',
            'foreignKey' => 'decisiondefautinsertionep66_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Dossierpcg66pcd' => array(
            'className' => 'Dossierpcg66',
            'foreignKey' => 'dossierpcg66pcd_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Foyer' => array(
            'className' => 'Foyer',
            'foreignKey' => 'foyer_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Originepdo' => array(
            'className' => 'Originepdo',
            'foreignKey' => 'originepdo_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Poledossierpcg66' => array(
            'className' => 'Poledossierpcg66',
            'foreignKey' => 'poledossierpcg66_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Serviceinstructeur' => array(
            'className' => 'Serviceinstructeur',
            'foreignKey' => 'serviceinstructeur_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Typepdo' => array(
            'className' => 'Typepdo',
            'foreignKey' => 'typepdo_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    public $hasOne = array(
        'Dossierpcg66svt' => array(
            'className' => 'Dossierpcg66',
            'foreignKey' => 'dossierpcg66pcd_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
        'Primoanalyse' => array(
            'className' => 'Primoanalyse',
            'foreignKey' => 'dossierpcg66_id',
            'dependent' => false,
        ),
    );
    public $hasMany = array(
        'Decisiondossierpcg66' => array(
            'className' => 'Decisiondossierpcg66',
            'foreignKey' => 'dossierpcg66_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
        'Personnepcg66' => array(
            'className' => 'Personnepcg66',
            'foreignKey' => 'dossierpcg66_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
        'Fichiermodule' => array(
            'className' => 'Fichiermodule',
            'foreignKey' => false,
            'dependent' => false,
            'conditions' => array(
                'Fichiermodule.modele = \'Dossierpcg66\'',
                'Fichiermodule.fk_value = {$__cakeID__$}'
            ),
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )
    );
    public $validate = array(
        'orgpayeur' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire',
            )
        ),
// 			'iscomplet' => array(
// 				'notEmptyIf' => array(
// 					'rule' => array( 'notEmptyIf', 'user_id', false, array( null ) ),
// 					'message' => 'Champ obligatoire',
// 				)
// 			)
    );

    /**
     *
     */
    public function etatPcg66($dossierpcg66) {
        $dossierpcg66 = Hash::expand(Hash::filter((array) ( $dossierpcg66 )));

        $typepdo_id = Set::classicExtract($dossierpcg66, 'Dossierpcg66.typepdo_id');
    }

    /**
     * Function qui retourne l'état du dossierpcg66 dont les différents champs nécessaires à son calcul sont passés en paramètres
	 * 
	 * @deprecated
	 * @link updatePositionsPcgsById()
     * */
    public function etatDossierPcg66($typepdo_id = null, $user_id = null, $decisionpdoId = null, $instrencours = null, $avistechnique = null, $validationavis = null, $retouravistechnique = null, $vuavistechnique = null, /* $iscomplet = null, */ $etatdossierpcg = null) {
		throw new Exception(sprintf("Utilisation d'une fonction obsolète : %s %s %s", __CLASS__, __FUNCTION__, __LINE__));
        $etat = '';

        if (!empty($typepdo_id) && empty($user_id)) {
            $etat = 'attaffect';
        } elseif (!empty($typepdo_id) && !empty($user_id) && $etatdossierpcg == 'attaffect') {
            $etat = 'attinstr';
        } elseif (!empty($typepdo_id) && !empty($user_id) && !empty($decisionpdoId) && !empty($instrencours) && empty($avistechnique)) {
            $etat = 'instrencours';
        } elseif (!empty($typepdo_id) && !empty($user_id) && !empty($decisionpdoId) && empty($instrencours) && empty($avistechnique)) {
            $etat = 'attavistech';
        } elseif (!empty($typepdo_id) && !empty($user_id) && !empty($decisionpdoId) && empty($instrencours) && !empty($avistechnique) && empty($validationavis)) {
            $etat = 'attval';
        } elseif (!empty($typepdo_id) && !empty($user_id) && !empty($decisionpdoId) && empty($instrencours) && !empty($avistechnique) && !empty($validationavis) && $validationavis == 'N' && $retouravistechnique == '0') {
            $etat = 'decisionnonvalid';
        } elseif (!empty($typepdo_id) && !empty($user_id) && !empty($decisionpdoId) && empty($instrencours) && !empty($avistechnique) && !empty($validationavis) && $validationavis == 'N' && $retouravistechnique == '1' && $vuavistechnique == '0') {
            $etat = 'decisionnonvalidretouravis';
        } elseif (!empty($typepdo_id) && !empty($user_id) && !empty($decisionpdoId) && empty($instrencours) && !empty($avistechnique) && !empty($validationavis) && $validationavis == 'N' && $retouravistechnique == '1' && $vuavistechnique == '1') {
            $etat = 'decisionnonvalid';
        } elseif (!empty($typepdo_id) && !empty($user_id) && empty($instrencours) && !empty($avistechnique) && !empty($validationavis) && $validationavis == 'O' && $retouravistechnique == '1' && $vuavistechnique == '0') {
            $etat = 'decisionvalidretouravis';
        } elseif (!empty($typepdo_id) && !empty($user_id) && empty($instrencours) && !empty($avistechnique) && !empty($validationavis) && $validationavis == 'O' && $retouravistechnique == '1' && $vuavistechnique == '1') {
            $etat = 'decisionvalid';
        } elseif (!empty($typepdo_id) && !empty($user_id) && empty($instrencours) && !empty($avistechnique) && !empty($validationavis) && $validationavis == 'O' && $retouravistechnique == '0' && $vuavistechnique == '0') {
            $etat = 'decisionvalid';
        } elseif (!empty($etatdossierpcg)) {
            $etat = $etatdossierpcg;
        }
// debug( $etat );
// die();
        return $etat;
    }

// 		/**
// 		* Mise à jour de l'état du dossierpcg66 du traitementpcg66 passé en paramètre.
// 		* Si le nouveau traitement nécessite une décision, le dossier repasse en instruction en cours
// 		**/
//
// 		public function updateEtatViaTraitement( $traitementpcg66_id ) {
// 			$traitementpcg66 = $this->Personnepcg66->Traitementpcg66->find(
// 				'first',
// 				array(
// 					'conditions' => array(
// 						'Traitementpcg66.id' => $traitementpcg66_id
// 					),
// 					'contain' => array(
// 						'Descriptionpdo',
// 						'Personnepcg66'
// 					)
// 				)
// 			);
// 			$return = true;
// 			if ( $traitementpcg66['Descriptionpdo']['decisionpcg'] == 'O' ) {
// 				$this->id = $traitementpcg66['Personnepcg66']['dossierpcg66_id'];
// 				$return = $this->saveField( 'etatdossierpcg', 'instrencours' );
// 			}
// 			return $return;
// 		}

    /**
     * Mise à jour de l'état du dossierpcg66. On vérifie qu'il existe au moins un traitement
     * nécessitant une décision en ai une active.
	 * 
	 * @deprecated
	 * @link updatePositionsPcgsById()
     * */
    public function updateEtatViaDecisionTraitement($dossierpcg66_id) {
		throw new Exception(sprintf("Utilisation d'une fonction obsolète : %s %s %s", __CLASS__, __FUNCTION__, __LINE__));
        $dossierpcg66 = $this->find(
                'first', array(
            'conditions' => array(
                'Dossierpcg66.id' => $dossierpcg66_id
            ),
            'contain' => false
                )
        );

        if ($dossierpcg66['Dossierpcg66']['etatdossierpcg'] != 'attval') {
            $checkDecisionsTraitements = $this->query(
                    'SELECT
						EVERY(necessaires.decisionok)
						FROM (
							SELECT
									(
										EXISTS(
											SELECT
													*
												FROM decisionstraitementspcgs66
												WHERE
													decisionstraitementspcgs66.traitementpcg66_id = traitementspcgs66.id
												ORDER BY decisionstraitementspcgs66.created DESC
												LIMIT 1
										)
										AND
										EXISTS(
											SELECT
													(
														/*decisionstraitementspcgs66.valide = \'O\'
														AND*/ decisionstraitementspcgs66.actif = \'1\'
													)
												FROM decisionstraitementspcgs66
												WHERE
													decisionstraitementspcgs66.traitementpcg66_id = traitementspcgs66.id
												ORDER BY decisionstraitementspcgs66.created DESC
												LIMIT 1
										)
									)AS decisionok
								FROM dossierspcgs66
									INNER JOIN personnespcgs66 ON (
										dossierspcgs66.id = personnespcgs66.dossierpcg66_id
									)
									INNER JOIN traitementspcgs66 ON (
										personnespcgs66.id = traitementspcgs66.personnepcg66_id
										AND traitementspcgs66.clos = \'N\'
										AND traitementspcgs66.annule = \'N\'
									)
									INNER JOIN descriptionspdos ON (
										descriptionspdos.id = traitementspcgs66.descriptionpdo_id
										AND descriptionspdos.decisionpcg = \'O\'
									)
					) AS necessaires'
            );

            if ($checkDecisionsTraitements[0][0]['every'] == true) {
                $this->id = $dossierpcg66_id;
                $return = $this->saveField('etatdossierpcg', 'attavistech');
                return $return;
            }
        }
        return true;
    }

    /**
     * Mise à jour de l'état du dossierpcg66. On vérifie qu'il existe au moins un traitement
     * nécessitant une décision en ai une active.
	 * 
	 * @deprecated
	 * @link updatePositionsPcgsById()
     * */
    public function updateEtatViaDecisionPersonnepcg($dossierpcg66_id) {
		throw new Exception(sprintf("Utilisation d'une fonction obsolète : %s %s %s", __CLASS__, __FUNCTION__, __LINE__));
        $dossierpcg66 = $this->find(
                'first', array(
            'conditions' => array(
                'Dossierpcg66.id' => $dossierpcg66_id
            ),
            'contain' => false
                )
        );

        if ($dossierpcg66['Dossierpcg66']['etatdossierpcg'] != 'attval') {
            $checkDecisionsPersonnes = $this->query(
                    'SELECT
						EVERY(necessaires.decisionok)
						FROM (
							SELECT
									(
										EXISTS(
											SELECT
													*
												FROM decisionspersonnespcgs66
												WHERE
													decisionspersonnespcgs66.personnepcg66_situationpdo_id = personnespcgs66_situationspdos.id
												ORDER BY decisionspersonnespcgs66.created DESC
												LIMIT 1
										)
									)
									AS decisionok
								FROM dossierspcgs66
									INNER JOIN personnespcgs66 ON (
										dossierspcgs66.id = personnespcgs66.dossierpcg66_id
									)
									INNER JOIN traitementspcgs66 ON (
										personnespcgs66.id = traitementspcgs66.personnepcg66_id
										AND traitementspcgs66.clos = \'N\'
										AND traitementspcgs66.annule = \'N\'
									)
									INNER JOIN descriptionspdos ON (
										descriptionspdos.id = traitementspcgs66.descriptionpdo_id
										AND descriptionspdos.decisionpcg = \'O\'
									)
									INNER JOIN personnespcgs66_situationspdos ON (
										personnespcgs66_situationspdos.personnepcg66_id = personnespcgs66.id
									)
					) AS necessaires'
            );
// debug($checkDecisionsPersonnes);
            if ($checkDecisionsPersonnes[0][0]['every'] == true) {
                $this->id = $dossierpcg66_id;
                $return = $this->saveField('etatdossierpcg', 'attavistech');
                return $return;
            }
        }
        return true;
    }

	/**
	 * 
	 * @deprecated
	 * @link updatePositionsPcgsById()
	 * @param type $dossierpcg66_id
	 * @return type
	 */
    public function updateEtatViaPersonne($dossierpcg66_id) {
		throw new Exception(sprintf("Utilisation d'une fonction obsolète : %s %s %s", __CLASS__, __FUNCTION__, __LINE__));

        if ($this->existePropoParMotif($dossierpcg66_id)) {
            $etat = 'attavistech';
        } else {
            $etat = 'attinstr';
        }

        $this->id = $dossierpcg66_id;
        $return = $this->saveField('etatdossierpcg', $etat);
        return $return;
    }

    public function existePropoParMotif($dossierpcg66_id) {
        $existe = true;
        $personnespcgs66 = $this->Personnepcg66->find(
                'all', array(
            'conditions' => array(
                'Personnepcg66.dossierpcg66_id' => $dossierpcg66_id
            ),
            'contain' => false
                )
        );

        foreach ($personnespcgs66 as $personnepcg66) {
            $situationsParPersonne = $this->Personnepcg66->Personnepcg66Situationpdo->find(
                    'all', array(
                'conditions' => array(
                    'Personnepcg66Situationpdo.personnepcg66_id' => $personnepcg66['Personnepcg66']['id']
                ),
                'contain' => array(
                    'Decisionpersonnepcg66'
                )
                    )
            );

// debug($situationsParPersonne);
// die();
// 				foreach( $situationsParPersonne as $situationParPersonne ) {
// 					if ( empty( $situationParPersonne['Decisionpersonnepcg66'] ) ) {
// 						$existe = false;
// 					}
// 				}
        }
        return ( $existe && !empty($personnespcgs66) );
    }

	/**
	 * @deprecated
	 * @link updatePositionsPcgsById()
	 * @param type $decisiondossierpcg66_id
	 * @return type
	 */
    public function updateEtatViaDecisionFoyer($decisiondossierpcg66_id) {
		throw new Exception(sprintf("Utilisation d'une fonction obsolète : %s %s %s", __CLASS__, __FUNCTION__, __LINE__));
        $decisiondossierpcg66 = $this->Decisiondossierpcg66->find(
                'first', array(
            'conditions' => array(
                'Decisiondossierpcg66.id' => $decisiondossierpcg66_id
            ),
            'contain' => array(
                'Dossierpcg66'
            )
                )
        );

// debug( $decisiondossierpcg66 );
// die();
        $etat = $this->etatDossierPcg66($decisiondossierpcg66['Dossierpcg66']['typepdo_id'], $decisiondossierpcg66['Dossierpcg66']['user_id'], $decisiondossierpcg66['Decisiondossierpcg66']['decisionpdo_id'], $decisiondossierpcg66['Decisiondossierpcg66']['instrencours'], $decisiondossierpcg66['Decisiondossierpcg66']['avistechnique'], $decisiondossierpcg66['Decisiondossierpcg66']['validationproposition'], $decisiondossierpcg66['Decisiondossierpcg66']['retouravistechnique'], $decisiondossierpcg66['Decisiondossierpcg66']['vuavistechnique'], /* , $decisiondossierpcg66['Dossierpcg66']['iscomplet'] */ $decisiondossierpcg66['Dossierpcg66']['etatdossierpcg']);
// debug($etat);
// die();
        $this->id = $decisiondossierpcg66['Dossierpcg66']['id'];
        $return = $this->saveField('etatdossierpcg', $etat);

        return $return;
    }

    /**
     * @deprecated since version 3.0
     */
    public function updateEtatViaTransmissionop($decisiondossierpcg66_id) {
		throw new Exception(sprintf("Utilisation d'une fonction obsolète : %s %s %s", __CLASS__, __FUNCTION__, __LINE__));
        $decisiondossierpcg66 = $this->Decisiondossierpcg66->find(
                'first', array(
            'conditions' => array(
                'Decisiondossierpcg66.id' => $decisiondossierpcg66_id
            ),
            'contain' => array(
                'Dossierpcg66'
            )
                )
        );


        if ($decisiondossierpcg66['Decisiondossierpcg66']['etatop'] == 'transmis') {
            $etat = 'transmisop';

            //Mise à jour de l'état du bilan de parcours
            if (!empty($decisiondossierpcg66['Dossierpcg66']['bilanparcours66_id'])) {
                $this->Bilanparcours66->updateAllUnbound(
                        array('Bilanparcours66.positionbilan' => '\'traite\''), array(
                    '"Bilanparcours66"."positionbilan"' => 'attcga',
                    '"Bilanparcours66"."id"' => $decisiondossierpcg66['Dossierpcg66']['bilanparcours66_id']
                        )
                );
            }
        } else if ($decisiondossierpcg66['Decisiondossierpcg66']['etatop'] == 'atransmettre') {
            $etat = 'atttransmisop';
        }

        $this->id = $decisiondossierpcg66['Dossierpcg66']['id'];
        $return = $this->saveField('etatdossierpcg', $etat);

        return $return;
    }

    /**
     *   AfterSave
     */
    public function afterSave($created) {
        $return = parent::afterSave($created);

		$this->updatePositionsPcgsById($this->id);
        $return = $this->_updateDecisionCerParticulier($created) && $return;

        return $return;
    }

    protected function _updateDecisionCerParticulier($created) {
        $success = true;

        $decisiondossierpcg66 = $this->Decisiondossierpcg66->find(
                'first', array(
            'conditions' => array(
                'Decisiondossierpcg66.dossierpcg66_id' => $this->id
            ),
            'contain' => array(
                'Decisionpdo'
            ),
            'order' => array('Decisiondossierpcg66.datevalidation DESC')
                )
        );

        $dossierpcg66 = $this->find(
                'first', array(
            'conditions' => array(
                'Dossierpcg66.id' => $this->id
            ),
            'contain' => false
                )
        );


// debug($decisiondossierpcg66);
// die();

        if (!empty($decisiondossierpcg66) && isset($decisiondossierpcg66['Decisiondossierpcg66']['validationproposition'])) {
            $dateDecision = $decisiondossierpcg66['Decisiondossierpcg66']['datevalidation'];
            $propositiondecision = $decisiondossierpcg66['Decisionpdo']['decisioncerparticulier'];
            if (( $decisiondossierpcg66['Decisiondossierpcg66']['validationproposition'] == 'O' ) && ( ( ( $decisiondossierpcg66['Decisiondossierpcg66']['retouravistechnique'] == '0' ) && ( $decisiondossierpcg66['Decisiondossierpcg66']['vuavistechnique'] == '0' ) ) || ( ( $decisiondossierpcg66['Decisiondossierpcg66']['retouravistechnique'] == '1' ) && ( $decisiondossierpcg66['Decisiondossierpcg66']['vuavistechnique'] == '1' ) ) )) {

                if ($propositiondecision == 'N') {
                    $success = $this->Contratinsertion->updateAllUnBound(
                                    array(
                                'Contratinsertion.decision_ci' => "'" . $propositiondecision . "'",
                                'Contratinsertion.datevalidation_ci' => null,
                                'Contratinsertion.datedecision' => "'" . $dateDecision . "'",
                                'Contratinsertion.positioncer' => '\'nonvalid\'',
                                    ), array(
                                'Contratinsertion.id' => $dossierpcg66['Dossierpcg66']['contratinsertion_id']
                                    )
                            ) && $success;
                } else {
                    $success = $this->Contratinsertion->updateAllUnBound(
                                    array(
                                'Contratinsertion.decision_ci' => "'" . $propositiondecision . "'",
                                'Contratinsertion.datevalidation_ci' => "'" . $dateDecision . "'",
                                'Contratinsertion.datedecision' => "'" . $dateDecision . "'",
                                'Contratinsertion.positioncer' => '\'encours\'',
                                    ), array(
                                'Contratinsertion.id' => $dossierpcg66['Dossierpcg66']['contratinsertion_id']
                                    )
                            ) && $success;
                }

				if( !empty( $dossierpcg66['Dossierpcg66']['contratinsertion_id'] ) ) {
					$success = $success && $this->Contratinsertion->updatePositionsCersById( $dossierpcg66['Dossierpcg66']['contratinsertion_id'] );
				}
            }
        }

// 			debug($decisiondossierpcg66);
// 			die();

        return $success;
    }

    /**
     * Retourne l'id du dossier à partir de l'id du dosiserpcg66
     *
     * @param integer $dossierpcg66_id
     * @return integer
     */
    public function dossierId($dossierpcg66_id) {
        $querydata = array(
            'fields' => array('Foyer.dossier_id'),
            'joins' => array(
                $this->join('Foyer', array('type' => 'INNER'))
            ),
            'conditions' => array(
                'Dossierpcg66.id' => $dossierpcg66_id
            ),
            'recursive' => -1
        );

        $dossierpcg66 = $this->find('first', $querydata);

        if (!empty($dossierpcg66)) {
            return $dossierpcg66['Foyer']['dossier_id'];
        } else {
            return null;
        }
    }

    /**
     * 	Liste des courriers envoyés aux personnes PCG liés au dossier sur lequel on travaille
     * 	@params	integer
     * 	@return array
     *
     */
    public function listeCourriersEnvoyes($personneId = 'Personne.id', $data = array()) {
        $traitementsNonClos = array();

        $personnespcgs66 = $this->Personnepcg66->find(
                'all', array(
            'fields' => array(
                'Personnepcg66.id',
                'Personnepcg66.dossierpcg66_id',
            ),
            'conditions' => array(
                'Personnepcg66.personne_id' => $personneId,
                'Personnepcg66.dossierpcg66_id' => $data['Dossierpcg66']['id']
            ),
            'contain' => false
                )
        );
        $infosDossierpcg66 = (array) Set::extract($personnespcgs66, '{n}.Personnepcg66.dossierpcg66_id');
        $listPersonnespcgs66 = (array) Set::extract($personnespcgs66, '{n}.Personnepcg66.id');

        $traitementspcgs66 = array();
        if (!empty($infosDossierpcg66)) {
            $conditions = array(
                'Traitementpcg66.personnepcg66_id' => $listPersonnespcgs66,
                'Traitementpcg66.dateenvoicourrier IS NOT NULL'
            );

            $traitementspcgs66 = $this->Personnepcg66->Traitementpcg66->find(
                    'all', array(
                'fields' => array(
                    'Traitementpcg66.id',
                    'Traitementpcg66.datedepart',
                    'Traitementpcg66.dateenvoicourrier',
                    'Personnepcg66.dossierpcg66_id',
                    'Personnepcg66.id',
                    'Personnepcg66.personne_id',
                    $this->Personnepcg66->Personne->sqVirtualField('nom_complet'),
                    'Descriptionpdo.name',
                    'Situationpdo.libelle',
                    'Dossierpcg66.datereceptionpdo',
                    'Typepdo.libelle',
                    $this->User->sqVirtualField('nom_complet')
                ),
                'conditions' => $conditions,
                'joins' => array(
                    $this->Personnepcg66->Traitementpcg66->join('Personnepcg66', array('type' => 'INNER')),
                    $this->Personnepcg66->Traitementpcg66->Personnepcg66->join('Dossierpcg66', array('type' => 'INNER')),
                    $this->Personnepcg66->Traitementpcg66->Personnepcg66->Dossierpcg66->join('Typepdo', array('type' => 'INNER')),
                    $this->Personnepcg66->Traitementpcg66->Personnepcg66->Dossierpcg66->join('User', array('type' => 'INNER')),
                    $this->Personnepcg66->join('Personne', array('type' => 'INNER')),
                    $this->Personnepcg66->Traitementpcg66->join('Descriptionpdo', array('type' => 'INNER')),
                    $this->Personnepcg66->Traitementpcg66->join('Situationpdo', array('type' => 'LEFT OUTER'))
                ),
                'contain' => false
                    )
            );
        }

        return $traitementspcgs66;
    }

    /**
     * Préparation des données du formulaire d'ajout ou de modification d'un
     * Dossier PCG
     *
     * @param integer $foyer_id
     * @param integer $dossierpcg66_id
     * @return array
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function prepareFormDataAddEdit($foyer_id, $dossierpcg66_id) {
        if (!empty($dossierpcg66_id)) {
            $querydataDossierpcg66Actuel['conditions'] = array(
                'Dossierpcg66.id' => $dossierpcg66_id
            );
            $dataDossierpcg66Actuel = $this->find('first', $querydataDossierpcg66Actuel);

            // Il faut que l'enregistrement à modifier existe
            if (empty($dataDossierpcg66Actuel)) {
                throw new NotFoundException();
            }

            $data = $dataDossierpcg66Actuel;
        } else {
            $data = array(
                'Dossierpcg66' => array(
                    'id' => null,
                    'foyer_id' => $foyer_id,
                    'user_id' => null
                )
            );

            $dossierpcg66Pcd = $this->find(
                    'first', array(
                'conditions' => array(
                    'Dossierpcg66.foyer_id' => $foyer_id
                ),
                'recursive' => -1,
                'order' => array('Dossierpcg66.created DESC'),
                'limit' => 1
                    )
            );

            $data['Dossierpcg66']['user_id'] = $dossierpcg66Pcd['Dossierpcg66']['user_id'];
        }

        return $data;
    }

    /**
     * Mise à jour de l'état du dossierpcg66. On vérifie qu'il existe au moins un traitement
     * nécessitant une décision en ai une active.
	 * 
	 * @deprecated
	 * @link updatePositionsPcgsById()
     * */
    public function updateEtatDossierViaTraitement($traitementpcg66_id) {
		throw new Exception(sprintf("Utilisation d'une fonction obsolète : %s %s %s", __CLASS__, __FUNCTION__, __LINE__));
        $traitementpcg66 = $this->Personnepcg66->Traitementpcg66->find(
            'first',
            array(
                'fields' => array_merge(
                        $this->Personnepcg66->Traitementpcg66->fields(), $this->Personnepcg66->Traitementpcg66->Personnepcg66->fields(), $this->Personnepcg66->Traitementpcg66->Personnepcg66->Dossierpcg66->fields(), $this->Personnepcg66->Traitementpcg66->Personnepcg66->Dossierpcg66->Decisiondossierpcg66->fields()
                ),
                'conditions' => array(
                    'Traitementpcg66.id' => $traitementpcg66_id
                ),
                'joins' => array(
                    $this->Personnepcg66->Traitementpcg66->join('Personnepcg66', array('type' => 'INNER')),
                    $this->Personnepcg66->Traitementpcg66->Personnepcg66->join('Dossierpcg66', array('type' => 'INNER')),
                    $this->Personnepcg66->Traitementpcg66->Personnepcg66->Dossierpcg66->join('Decisiondossierpcg66', array('type' => 'LEFT OUTER'))
                ),
                'recursive' => -1
            )
        );

        // A l'enregistrement du traitement,
        //  le traitement est non clos
        //  le traitement n'est pas annulé
        //  le type de traitement est dossier à revoir
        // aucune décision n'a été émise
        $corbeillepcgDescriptionId = Configure::read('Corbeillepcg.descriptionpdoId'); // Traiteement de description courrier à l'allocataire
        if (empty($traitementpcg66['Decisiondossierpcg66']['id'])) {

            if (in_array($traitementpcg66['Dossierpcg66']['etatdossierpcg'], array('attaffect', 'attinstr', 'instrencours', 'attinstrattpiece'))) {
                if (in_array($traitementpcg66['Traitementpcg66']['descriptionpdo_id'], $corbeillepcgDescriptionId)) {
                    $return = $this->updateAllUnBound(
                            array(
                        'Dossierpcg66.etatdossierpcg' => '\'attinstrattpiece\''
                            ), array(
                        '"Dossierpcg66"."id"' => $traitementpcg66['Dossierpcg66']['id']
                            )
                    );
                } else if ($traitementpcg66['Traitementpcg66']['typetraitement'] == 'documentarrive') {
                    $return = $this->updateAllUnBound(
                            array(
                        'Dossierpcg66.etatdossierpcg' => '\'attinstrdocarrive\''
                            ), array(
                        '"Dossierpcg66"."id"' => $traitementpcg66['Dossierpcg66']['id']
                            )
                    );
                }

                if ($traitementpcg66['Traitementpcg66']['typetraitement'] == 'dossierarevoir') {
                    $return = $this->updateAllUnBound(
                        array(
                            'Dossierpcg66.etatdossierpcg' => '\'arevoir\''
                        ),
                        array(
                            '"Dossierpcg66"."id"' => $traitementpcg66['Dossierpcg66']['id']
                        )
                    );
                }
                return $return;
            }
        }
//            debug($return);
        return true;
    }

    /*
     * Mise à jour de l'état du passage en comission EP du dossier EP pour
     * un défaut d'insertion (issu d'une EP Audition)
     *
     * @param integer $decisiondefautinsertionep66_id
     * @return array
     */
    public function updateEtatPassagecommissionep($decisiondefautinsertionep66_id) {
        if (empty($decisiondefautinsertionep66_id)) {
            return false;
        }

        $decisiondefautinsertionep66 = $this->Decisiondefautinsertionep66->find(
			'first', 
			array(
				'fields' => array_merge(
					$this->Decisiondefautinsertionep66->fields(), 
					$this->Decisiondefautinsertionep66->Passagecommissionep->fields()
				),
				'conditions' => array(
					'Decisiondefautinsertionep66.id' => $decisiondefautinsertionep66_id,
					'Passagecommissionep.id IN ('.$this->Decisiondefautinsertionep66->Passagecommissionep->sqDernier().' )'
				),
				'joins' => array(
					$this->Decisiondefautinsertionep66->join('Passagecommissionep', array('type' => 'INNER')),
					$this->Decisiondefautinsertionep66->Passagecommissionep->join('Dossierep', array('type' => 'INNER'))
				),
				'contain' => false
			)
        );

		$dec = Hash::get($decisiondefautinsertionep66, 'Passagecommissionep');
        if ( Hash::get($dec, 'etatdossierep') !== 'traite' ) {
            return $this->Decisiondefautinsertionep66->Passagecommissionep->updateAllUnBound(
				array(
					'Passagecommissionep.etatdossierep' => '\'traite\''
				), 
				array(
					'"Passagecommissionep"."dossierep_id"' => Hash::get($dec, 'dossierep_id'),
					'"Passagecommissionep"."id"' => Hash::get($dec, 'id')
				)
			);
        }

        return true;
    }

    /**
     * Fonction permettatn la génération automatique d'un dossier PCG
     * une fois que le dossier PCG initial s'est vu transmis à un organisme
     * de type PDU-MMR, PDAMGA ou PDU-MJP
     * @param type $dossierpcg66_id
     * @return boolean
     */
    public function generateDossierPCG66Transmis($dossierpcg66_id) {

        if (empty($dossierpcg66_id)) {
            return false;
        }
        $success = true;

        $dossierpcg66EnCours = $this->find(
                'first', array(
            'fields' => array_merge(
                    $this->fields(), $this->Decisiondossierpcg66->fields(), $this->Decisiondossierpcg66->Orgtransmisdossierpcg66->fields(), $this->Poledossierpcg66->fields()
            ),
            'conditions' => array(
                'Dossierpcg66.id' => $dossierpcg66_id
            ),
            'joins' => array(
                $this->join('Decisiondossierpcg66', array(
                    'order' => array('Decisiondossierpcg66.created DESC'),
                    'type' => 'INNER')
                ),
                $this->join('Poledossierpcg66', array('type' => 'INNER')),
                $this->Decisiondossierpcg66->join('Orgtransmisdossierpcg66', array('type' => 'LEFT OUTER'))
            ),
            'contain' => false
                )
        );

        // Récupération de la liste des organismes auxquels la décision est transmise
//            $orgstransmisdossierspcgs66 = $this->Decisiondossierpcg66->Decdospcg66Orgdospcg66->find(
//                'all',
//                array(
//                    'fields' => array(
////                        "Decdospcg66Orgdospcg66.id",
//                        "Decdospcg66Orgdospcg66.orgtransmisdossierpcg66_id"
//                    ),
//                    'conditions' => array(
//                        "Decdospcg66Orgdospcg66.decisiondossierpcg66_id" => $dossierpcg66EnCours['Decisiondossierpcg66']['id']
//                    )
//                )
//            );
//            $orgsIds = Hash::extract( $orgstransmisdossierspcgs66, '{n}.Decdospcg66Orgdospcg66.orgtransmisdossierpcg66_id' );
//            $organismesConcernes = Configure::read( 'Generationdossierpcg.Orgtransmisdossierpcg66.id' );
//
//            $dossierPCGGenerable = false;
//            foreach( $orgsIds as $key => $value ) {
//                if( in_array( $value, $organismesConcernes ) ){
//                    $dossierPCGGenerable = true;
//
//                    $poleOrg = $this->Decisiondossierpcg66->Decdospcg66Orgdospcg66->Orgtransmisdossierpcg66->find(
//                        'first',
//                        array(
//                            'conditions' => array(
//                                'Orgtransmisdossierpcg66.id' => $value
//                            )
//                        )
//                    );
//                    $poledossierpcg66_id = $poleOrg['Orgtransmisdossierpcg66']['poledossierpcg66_id'];
//                }
//            }
//debug($dossierpcg66EnCours);
//die();
        $dossierPCGGenerable = false;
        $organismesConcernes = Configure::read('Generationdossierpcg.Orgtransmisdossierpcg66.id');
        $orgId = $dossierpcg66EnCours['Decisiondossierpcg66']['orgtransmisdossierpcg66_id'];

        // Pôle chargé de traiter le dossier PCG en cours
        $poledossierpcg66IdDossierpcg66 = $dossierpcg66EnCours['Dossierpcg66']['poledossierpcg66_id'];
        // Pôle lié à l'organisme auquel on a tranmis l'information (PDA-MGA ou PDU-MMR)
        $poledossierpcg66IdOrg66 = $dossierpcg66EnCours['Orgtransmisdossierpcg66']['poledossierpcg66_id'];

        if (!empty($orgId) && ( $poledossierpcg66IdDossierpcg66 != $poledossierpcg66IdOrg66 )) {
            if (!empty($organismesConcernes)) {
                if (in_array($orgId, $organismesConcernes)) {
                    $dossierPCGGenerable = true;

                    $poleOrg = $this->Decisiondossierpcg66->Orgtransmisdossierpcg66->find(
                            'first', array(
                        'conditions' => array(
                            'Orgtransmisdossierpcg66.id' => $orgId
                        )
                            )
                    );
                    $poledossierpcg66_id = $poleOrg['Orgtransmisdossierpcg66']['poledossierpcg66_id'];
                }
            }

//debug($poleOrg);
//debug($poledossierpcg66_id);
//die();

            $success = true;
            $nouveauDossierpcg66 = array(
                'Dossierpcg66' => array(
                    'foyer_id' => $dossierpcg66EnCours['Dossierpcg66']['foyer_id'],
                    'originepdo_id' => $dossierpcg66EnCours['Poledossierpcg66']['originepdo_id'],
                    'typepdo_id' => $dossierpcg66EnCours['Poledossierpcg66']['typepdo_id'], // FIXME
                    'orgpayeur' => $dossierpcg66EnCours['Dossierpcg66']['orgpayeur'],
                    'datereceptionpdo' => $dossierpcg66EnCours['Decisiondossierpcg66']['datevalidation'],
                    'commentairepiecejointe' => $dossierpcg66EnCours['Decisiondossierpcg66']['infotransmise'],
                    'haspiecejointe' => 0,
                    'poledossierpcg66_id' => $poledossierpcg66_id,
                    'etatdossierpcg' => 'attaffect',
                    'dossierpcg66pcd_id' => $dossierpcg66EnCours['Dossierpcg66']['id']
                )
            );

            $dossierpcg66pcd_id = $dossierpcg66EnCours['Dossierpcg66']['dossierpcg66pcd_id'];

            if ($dossierPCGGenerable && empty($dossierpcg66pcd_id)) {
                $this->create($nouveauDossierpcg66);
                $success = $this->save() && $success;
            }
        }
        return $success;
    }

	/**************************************************************************************************************/
		
	/**
	 * Retourne les positions et les conditions CakePHP/SQL dans l'ordre dans
	 * lequel elles doivent être traitées pour récupérer la position actuelle.
	 *
	 * @return array
	 */
	protected function _getConditionsPositionsPcgs() {
		$sqArevoir = 'EXISTS(
			SELECT traitementspcgs66.id
			FROM traitementspcgs66
			INNER JOIN personnespcgs66 ON (personnespcgs66.id = traitementspcgs66.personnepcg66_id)
			WHERE personnespcgs66.dossierpcg66_id = "Dossierpcg66"."id"
			AND traitementspcgs66.typetraitement IS NOT NULL
			AND traitementspcgs66.typetraitement = \'dossierarevoir\'
			LIMIT 1
		)';

		$sqAttinstrdocarrive = 'EXISTS(
			SELECT traitementspcgs66.id
			FROM traitementspcgs66
			INNER JOIN personnespcgs66 ON (personnespcgs66.id = traitementspcgs66.personnepcg66_id)
			WHERE personnespcgs66.dossierpcg66_id = "Dossierpcg66"."id"
			AND traitementspcgs66.typetraitement IS NOT NULL
			AND traitementspcgs66.typetraitement = \'documentarrive\'
			AND NOT EXISTS(
				SELECT traitementspcgs66_sq.id
				FROM traitementspcgs66 AS traitementspcgs66_sq
				WHERE personnespcgs66.id = traitementspcgs66_sq.personnepcg66_id
				AND traitementspcgs66.descriptionpdo_id IS NOT NULL
				AND traitementspcgs66.descriptionpdo_id IN (' . implode(', ', (array) Configure::read('Corbeillepcg.descriptionpdoId') ) . ')
				AND traitementspcgs66_sq.created > traitementspcgs66.created
				LIMIT 1
			)
			ORDER BY traitementspcgs66.id DESC
			LIMIT 1
		)';

		$sqAttinstrattpiece = 'EXISTS(
			SELECT traitementspcgs66.id
			FROM traitementspcgs66
			INNER JOIN personnespcgs66 ON (personnespcgs66.id = traitementspcgs66.personnepcg66_id)
			WHERE personnespcgs66.dossierpcg66_id = "Dossierpcg66"."id"
			AND traitementspcgs66.descriptionpdo_id IN (' . implode(', ', (array) Configure::read('Corbeillepcg.descriptionpdoId') ) . ')
			AND NOT EXISTS(
				SELECT traitementspcgs66_sq.id
				FROM traitementspcgs66 AS traitementspcgs66_sq
				WHERE personnespcgs66.id = traitementspcgs66_sq.personnepcg66_id
				AND traitementspcgs66.typetraitement IS NOT NULL
				AND traitementspcgs66.typetraitement = \'documentarrive\'
				AND traitementspcgs66_sq.created > traitementspcgs66.created
				LIMIT 1
			)
			ORDER BY traitementspcgs66.id DESC
			LIMIT 1
		)';

		$return = array(
			'annule' => array(
				array(
					$this->alias.'.etatdossierpcg' => 'annule',
				)
			),
			'attaffect' => array(
				array(
					$this->alias.'.user_id IS NULL',
				)
			),
			'instrencours' => array(
				array(
					$this->alias.'.user_id IS NOT NULL',
					$this->Decisiondossierpcg66->alias . '.decisionpdo_id IS NOT NULL',
					$this->Decisiondossierpcg66->alias . '.instrencours IS NOT NULL',
					$this->Decisiondossierpcg66->alias . '.instrencours' => '1',
					$this->Decisiondossierpcg66->alias . '.etatdossierpcg IS NULL',
				),
			),
			'attavistech' => array(
				array(
					$this->alias.'.user_id IS NOT NULL',
					$this->Decisiondossierpcg66->alias . '.decisionpdo_id IS NOT NULL',
					'OR' => array(
						$this->Decisiondossierpcg66->alias . '.instrencours IS NULL',
						$this->Decisiondossierpcg66->alias . '.instrencours' => '0',
					),
					$this->Decisiondossierpcg66->alias . '.avistechnique IS NULL',
					$this->Decisiondossierpcg66->alias . '.etatdossierpcg IS NULL',
				),
			),
			'attval' => array(
				array(
					$this->alias.'.user_id IS NOT NULL',
					$this->Decisiondossierpcg66->alias . '.decisionpdo_id IS NOT NULL',
					'OR' => array(
						$this->Decisiondossierpcg66->alias . '.instrencours IS NULL',
						$this->Decisiondossierpcg66->alias . '.instrencours' => '0',
					),
					$this->Decisiondossierpcg66->alias . '.avistechnique IS NOT NULL',
					$this->Decisiondossierpcg66->alias . '.validationproposition IS NULL',
					$this->Decisiondossierpcg66->alias . '.etatdossierpcg IS NULL',
				),
			),
			'transmisop' => array(
				$this->Decisiondossierpcg66->alias . '.etatop IS NOT NULL',
				$this->Decisiondossierpcg66->alias . '.etatop' => 'transmis',
			),
			'atttransmisop' => array(
				$this->alias.'.etatdossierpcg' => array( 'decisionvalid', 'atttransmisop' ),
				$this->alias.'.dateimpression IS NOT NULL',
				$this->Decisiondossierpcg66->alias . '.validationproposition' => 'O',
				$this->Decisiondossierpcg66->alias . '.datevalidation <= '.$this->alias.'.dateimpression',
				$this->Decisiondossierpcg66->alias . '.etatdossierpcg IS NULL',
				'OR' => array(
					array(
						$this->Decisiondossierpcg66->alias . '.etatop IS NOT NULL',
						$this->Decisiondossierpcg66->alias . '.etatop' => 'atransmettre',
					),
					$this->Decisiondossierpcg66->alias . '.etatop IS NULL'
				)
			),
			'decisionnonvalid' => array(
				array(
					$this->alias.'.user_id IS NOT NULL',
					$this->Decisiondossierpcg66->alias . '.decisionpdo_id IS NOT NULL',
					array(
						'OR' => array(
							$this->Decisiondossierpcg66->alias . '.instrencours IS NULL',
							$this->Decisiondossierpcg66->alias . '.instrencours' => '0',
						),
					),
					$this->Decisiondossierpcg66->alias . '.avistechnique IS NOT NULL',
					$this->Decisiondossierpcg66->alias . '.validationproposition IS NOT NULL',
					$this->Decisiondossierpcg66->alias . '.validationproposition' => 'N',
					$this->Decisiondossierpcg66->alias . '.etatdossierpcg IS NULL',
					array(
						'OR' => array(
							$this->Decisiondossierpcg66->alias . '.retouravistechnique' => '0',
							array(
								$this->Decisiondossierpcg66->alias . '.retouravistechnique' => '1',
								$this->Decisiondossierpcg66->alias . '.vuavistechnique' => '1',
							)
						)
					)
				),
			),
			'decisionnonvalidretouravis' => array(
				array(
					$this->alias.'.user_id IS NOT NULL',
					$this->Decisiondossierpcg66->alias . '.decisionpdo_id IS NOT NULL',
					array(
						'OR' => array(
							$this->Decisiondossierpcg66->alias . '.instrencours IS NULL',
							$this->Decisiondossierpcg66->alias . '.instrencours' => '0',
						),
					),
					$this->Decisiondossierpcg66->alias . '.avistechnique IS NOT NULL',
					$this->Decisiondossierpcg66->alias . '.validationproposition IS NOT NULL',
					$this->Decisiondossierpcg66->alias . '.validationproposition' => 'N',
					$this->Decisiondossierpcg66->alias . '.retouravistechnique' => '1',
					$this->Decisiondossierpcg66->alias . '.vuavistechnique' => '0',
					$this->Decisiondossierpcg66->alias . '.etatdossierpcg IS NULL',
				),
			),
			'decisionvalidretouravis' => array(
				array(
					$this->alias.'.user_id IS NOT NULL',
					$this->Decisiondossierpcg66->alias . '.decisionpdo_id IS NOT NULL',
					array(
						'OR' => array(
							$this->Decisiondossierpcg66->alias . '.instrencours IS NULL',
							$this->Decisiondossierpcg66->alias . '.instrencours' => '0',
						),
					),
					$this->Decisiondossierpcg66->alias . '.avistechnique IS NOT NULL',
					$this->Decisiondossierpcg66->alias . '.validationproposition IS NOT NULL',
					$this->Decisiondossierpcg66->alias . '.validationproposition' => 'O',
					$this->Decisiondossierpcg66->alias . '.retouravistechnique' => '1',
					$this->Decisiondossierpcg66->alias . '.vuavistechnique' => '0',
					$this->Decisiondossierpcg66->alias . '.etatdossierpcg IS NULL',
				),
			),
			'decisionvalid' => array(
				array(
					$this->alias.'.user_id IS NOT NULL',
					$this->Decisiondossierpcg66->alias . '.decisionpdo_id IS NOT NULL',
					array(
						'OR' => array(
							$this->Decisiondossierpcg66->alias . '.instrencours IS NULL',
							$this->Decisiondossierpcg66->alias . '.instrencours' => '0',
						),
					),
					$this->Decisiondossierpcg66->alias . '.avistechnique IS NOT NULL',
					$this->Decisiondossierpcg66->alias . '.validationproposition IS NOT NULL',
					$this->Decisiondossierpcg66->alias . '.validationproposition' => 'O',
					$this->Decisiondossierpcg66->alias . '.etatdossierpcg IS NULL',
					array(
						'OR' => array(
							array(
								$this->Decisiondossierpcg66->alias . '.retouravistechnique' => '1',
								$this->Decisiondossierpcg66->alias . '.vuavistechnique' => '1',
							),
							array(
								$this->Decisiondossierpcg66->alias . '.retouravistechnique' => '0',
								$this->Decisiondossierpcg66->alias . '.vuavistechnique' => '0',
							),
						),
					),
				),
			),
			'arevoir' => array(
				$sqArevoir
			),
			'attinstrdocarrive' => array(
				$sqAttinstrdocarrive
			),
			'attinstrattpiece' => array(
				$sqAttinstrattpiece
			),
			'attinstr' => array(
				array(
					$this->alias.'.user_id IS NOT NULL',
				)
			),
		);

		return $return;
	}

	/**
	 * Retourne les conditions permettant de cibler les PCG qui devraient être
	 * dans une certaine position.
	 *
	 * @param string $etatdossierpcg66
	 * @return array
	 */
	public function getConditionsPositionpcg( $etatdossierpcg66 ) {
		$conditions = array();

		foreach( $this->_getConditionsPositionsPcgs() as $keyPosition => $conditionsPosition ) {
			if ( $keyPosition === $etatdossierpcg66 ) {
				$conditions[] = array( $conditionsPosition );
				break;
			}
		}

		return $conditions;
	}

	/**
	 * Retourne une CASE (PostgreSQL) pemettant de connaître la position que
	 * devrait avoir un PCG (au CG 66).
	 *
	 * @return string
	 */
	public function getCasePositionPcg() {
		$return = '';
		$Dbo = $this->getDataSource();

		foreach( array_keys( $this->_getConditionsPositionsPcgs() ) as $etatdossierpcg66 ) {
			$conditions = $this->getConditionsPositionpcg( $etatdossierpcg66 );
			$conditions = $Dbo->conditions( $conditions, true, false, $this );
			$return .= "WHEN {$conditions} \nTHEN '{$etatdossierpcg66}' \n";
		}

		$sq = $Dbo->startQuote;
		$eq = $Dbo->endQuote;
		// Position par defaut : En attente d'envoi de l'e-mail pour l'employeur
		$return = "( CASE {$return} ELSE {$sq}{$this->alias}{$eq}.etatdossierpcg END )";

		return $return;
	}

	/**
	 * Mise à jour des positions des PCG suivant des conditions données.
	 *
	 * @param array $conditions
	 * @return boolean
	 */
	public function updatePositionsPcgsByConditions( array $conditions ) {
		// On vérifi qu'au moins un cas existe selon les conditions
		$query = array( 
			'fields' => array( "{$this->alias}.{$this->primaryKey}", "{$this->alias}.etatdossierpcg" ), 
			'conditions' => $conditions,
		);
		$datas = $this->find( 'first', $query );

		if ( empty( $datas ) ){
			return true;
		}

		$Dbo = $this->getDataSource();
		$DboDecisiondossierpcg66 = $this->Decisiondossierpcg66->getDataSource();
		$DboPersonnepcg66 = $this->Personnepcg66->getDataSource();
		$DboTraitementpcg66 = $this->Personnepcg66->Traitementpcg66->getDataSource();

		$tableName = $Dbo->fullTableName( $this, true, true );
		$tableNameDecisiondossierpcg66 = $DboDecisiondossierpcg66->fullTableName( $this->Decisiondossierpcg66, true, true );
		$tableNamePersonnepcg66 = $DboPersonnepcg66->fullTableName( $this->Personnepcg66, true, true );
		$tableNameTraitementpcg66 = $DboTraitementpcg66->fullTableName( $this->Personnepcg66->Traitementpcg66, true, true );

		$case = $this->getCasePositionPcg();

		$sq = $Dbo->startQuote;
		$eq = $Dbo->endQuote;

		$conditionsSql = $Dbo->conditions( $conditions, true, true, $this );

		$jointureConditionDecision = "
			SELECT decisionsdossierspcgs66.id 
			FROM decisionsdossierspcgs66 
			WHERE decisionsdossierspcgs66.dossierpcg66_id = {$sq}{$this->alias}_sq{$eq}.{$sq}id{$eq}
			ORDER BY decisionsdossierspcgs66.id DESC 
			LIMIT 1
		";

		$query = array(
			'update' => "UPDATE {$tableName} AS {$sq}{$this->alias}{$eq}",
			'set' => "SET {$sq}etatdossierpcg{$eq} = {$case}",
			'from' => "FROM {$tableName} AS {$sq}{$this->alias}_sq{$eq}",
			'join1' => "LEFT JOIN {$tableNameDecisiondossierpcg66} AS {$sq}{$this->Decisiondossierpcg66->alias}{$eq}",
			'condition_join1' => "ON ({$sq}{$this->Decisiondossierpcg66->alias}{$eq}.{$sq}id{$eq} IN ({$jointureConditionDecision}))",
			'condition' => "{$conditionsSql}",
			'condition2' => "AND ({$sq}{$this->alias}_sq{$eq}.{$sq}etatdossierpcg{$eq} IS NULL OR {$sq}{$this->alias}_sq{$eq}.{$sq}etatdossierpcg{$eq} != 'transmisop')",
			'finalisation jointure from' => "AND {$sq}{$this->alias}_sq{$eq}.{$sq}id{$eq} = {$sq}{$this->alias}{$eq}.{$sq}id{$eq}",
			'fin' => "RETURNING {$sq}{$this->alias}{$eq}.{$sq}etatdossierpcg{$eq};"
		);

		$sql = implode( ' ', $query );
		$result = $Dbo->query( $sql );

		return $result !== false;
	}

	/**
	 * Mise à jour des positions des PCG qui devraient se trouver dans une
	 * position donnée.
	 *
	 * @param integer $etatdossierpcg66
	 * @return boolean
	 */
	public function updatePositionsPcgsByPosition( $etatdossierpcg66 ) {
		$conditions = $this->getConditionsPositionpcg( $etatdossierpcg66 );

		$query = array( 
			'fields' => array( "{$this->alias}.{$this->primaryKey}" ), 
			'conditions' => $conditions,
		);
		$sample = $this->find( 'first', $query );

		return (
			empty( $sample )
			|| $this->updateAllUnBound(
				array( "{$this->alias}.etatdossierpcg66" => "'{$etatdossierpcg66}'" ),
				$conditions
			)
		);
	}

	/**
	 * Permet de mettre à jour les positions des PCG d'un allocataire retrouvé
	 * grâce à la clé primaire d'un PCG en particulier.
	 *
	 * @param integer $id La clé primaire d'un PCG.
	 * @return boolean
	 */
	public function updatePositionsPcgsById( $id ) {
		$return = $this->updatePositionsPcgsByConditions(
			array( $this->alias . ".id" => $id )
		);

		return $return;
	}

	/**
	 * Renvoi la quete de base pour les impressions liés au dossier pcg (pour obtenir les informations nécéssaires)
	 * 
	 * @param mixed $dossierpcg66_id
	 * @return array
	 */
	public function getImpressionBaseQuery( $dossierpcg66_id ) {
		return array(
			'fields' => array(
				'Decisiondossierpcg66.id',
				'Dossierpcg66.id',
				'Dossierpcg66.etatdossierpcg',
				'Personne.nom',
				'Personne.prenom',
				'Foyer.dossier_id'
			),
			'joins' => array(
				$this->join( 'Decisiondossierpcg66', 
					array(
						'type' => 'LEFT OUTER',
						'conditions' => array(
							'Decisiondossierpcg66.id IN ('
							. 'SELECT a.id FROM decisionsdossierspcgs66 AS a '
							. 'WHERE a.dossierpcg66_id = "Dossierpcg66"."id" '
							. 'AND a.etatdossierpcg IS NULL ' // N'est pas annulé
							. 'ORDER BY a.datevalidation DESC, a.created DESC '
							. 'LIMIT 1)'
						)
					) 
				),
				$this->join( 'Foyer', array( 'type' => 'INNER' ) ),
				$this->Foyer->join( 'Personne', array( 'type' => 'INNER' ) ),
				$this->Foyer->Personne->join( 'Prestation', 
					array( 
						'type' => 'INNER',
						'conditions' => array( 'Prestation.rolepers' => 'DEM' )
					)
				)
			),
			'contain' => false,
			'conditions' => array(
				'Dossierpcg66.id' => $dossierpcg66_id,
			),
			'order' => array(
				'Dossierpcg66.id' => 'DESC'
			)
		);
	}
}

?>