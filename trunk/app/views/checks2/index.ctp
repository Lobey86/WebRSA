<h1><?php echo $this->pageTitle = 'Vérification de l\'application'; ?></h1>
<br />
<div id="tabbedWrapper" class="tabs">
	<div id="software">
		<h2 class="title">Environnement logiciel</h2>
		<div id="tabbedWrapperSoftware" class="tabs">
			<div id="apache">
				<h3 class="title">Apache</h3>
				<?php echo $checks->table( $results['Apache']['informations'] );?>
				<h4>Modules</h4>
				<?php echo $checks->table( $results['Apache']['modules'] );?>
			</div>
			<div id="binaries">
				<h3 class="title">Binaires</h3>
				<?php echo $checks->table( $results['Environment']['binaries'] );?>
			</div>
			<div id="cakephp">
				<h3 class="title">CakePHP</h3>
				<?php echo $checks->table( $results['Cakephp']['informations'] );?>
			</div>
			<div id="directories">
				<h3 class="title">Installation</h3>
				<h4>Répertoires</h4>
				<?php echo $checks->table( $results['Environment']['directories'] );?>
				<h4>Fichiers</h4>
				<?php echo $checks->table( $results['Environment']['files'] );?>
			</div>
			<div id="php">
				<h3 class="title">PHP</h3>
				<?php echo $checks->table( $results['Php']['informations'] );?>
				<h4>Configuration</h4>
				<?php echo $checks->table( $results['Php']['inis'] );?>
				<h4>Extensions</h4>
				<?php echo $checks->table( $results['Php']['extensions'] );?>
			</div>
			<div id="postgresql">
				<h3 class="title">PostgreSQL</h3>
				<?php echo $checks->table( $results['Postgresql'] );?>
			</div>
			<div id="webrsa">
				<h3 class="title">WebRSA</h3>
				<?php echo $checks->table( $results['Webrsa']['informations'] );?>
				<h4>Configuration</h4>
				<?php echo $checks->table( $results['Webrsa']['configure'] );?>
				<h4>Intervalles PostgreSQL</h4>
				<?php echo $checks->table( $results['Webrsa']['intervals'] );?>
			</div>
		</div>
	</div>
	<div id="modeles">
		<h2 class="title">Modèles de documents</h2>
		<h3>Paramétrables</h3>
		<?php echo $checks->table( $results['Modelesodt']['parametrables'] );?>
		<h3>Statiques</h3>
		<?php echo $checks->table( $results['Modelesodt']['statiques'] );?>
	</div>
	<div id="data">
		<h2 class="title">Données stockées en base</h2>
		<?php foreach( $results['Storeddata']['errors'] as $tablename => $errors ):?>
		<h3 class="storeddata <?php echo ( count( $errors ) > 0 ? 'error' : 'success' );?>"><?php echo h( $tablename );?></h3>
		<?php
			$fields = array();
			$controllerName = Inflector::camelize( $tablename );

			if( count( $errors ) > 0 ) {
				$fields = array_keys( Set::flatten( $errors[0] ) );
			}

			echo $default2->index(
				$errors,
				$fields,
				array(
					'actions' => array(
						"{$controllerName}::edit"
					)
				)
			);
		?>
		<?php endforeach;?>
	</div>
</div>
<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $javascript->link( 'prototype.livepipe.js' );
		echo $javascript->link( 'prototype.tabs.js' );
	}
?>
<script type="text/javascript">
	makeTabbed( 'tabbedWrapper', 2 );
	makeTabbed( 'tabbedWrapperSoftware', 3 );
	makeErrorTabs();
</script>