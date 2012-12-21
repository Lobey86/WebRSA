<noscript>
	<p class="error">Cette fonctionnalité nécessite l'utilisation de javascript, mais javascript n'est pas activé dans votre navigateur.</p>
</noscript>
<?php
	$title_for_layout = '3. Validation Responsable';
	$this->set( compact( 'title_for_layout' ) );
	echo $this->Xhtml->tag( 'h1', $title_for_layout );

	require_once( dirname( __FILE__ ).DS.'filtre.ctp' );

	if( isset( $cers93 ) ) {
		if( empty( $cers93 ) ) {
			echo $this->Xhtml->tag( 'p', 'Aucun résultat', array( 'class' => 'notice' ) );
		}
		else {
			$pagination = $this->Xpaginator->paginationBlock( 'Personne', $this->passedArgs );
			echo $pagination;

			echo $this->Form->create( null, array( 'id' => 'Cohortescers93Saisie' ) );
			echo '<table id="searchResults" class="tooltips">';
			echo '<thead>
					<tr>
						<th>Commune</th>
						<th>Nom/Prénom allocataire</th>
						<th>Nom/Prénom référent</th>
						<th>Date d\'orientation</th>
						<th>Date de signature</th>
						<th class="action">Forme du CER</th>
						<th class="action">Commentaire</th>
						<th class="action">Actions</th>
						<th class="action">Détails</th>
					</tr>
				</thead>';
			echo '<tbody>';

			require_once( dirname( __FILE__ ).DS.'avalidercpdv_tbody_trs.ctp' );

			echo '</tbody>';
			echo '</table>';
			echo $this->Form->end();

			echo $pagination;
		}
	}
?>