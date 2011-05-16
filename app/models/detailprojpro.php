<?php
    class Detailprojpro extends AppModel
    {
        var $name = 'Detailprojpro';

        var $actsAs = array(
            'Enumerable' => array(
                'fields' => array(
                    'projpro' => array(
						'type' => 'projpro', 'domain' => 'dsp'
					),
				)
			),
			'Revision' => array('auto'=>false),
			'Autovalidate'
		);

        var $belongsTo = array( 'Dsp' );
    }
?>
