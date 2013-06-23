<?php

/**
 * Metadata version
 */
$sMetadataVersion = '1.1';
 
/**
 * Module information
 */
$aModule = array(
    'id'           => 'jxinventory',
    'title'        => 'jxInventory - Real Inventory Administration',
    'description'  => array(
                        'de'=>'Verwaltung des tats&auml;chlichen Lagerbestands.',
                        'en'=>'Administration of the real inventory.'
                        ),
    'thumbnail'    => 'jxinventory.png',
    'version'      => '0.2',
    'author'       => 'Joachim Barthel',
    'url'          => 'https://github.com/job963/jxInventory',
    'email'        => 'jbarthel@qualifire.de',
    'extend'       => array(
        'oxorderarticle' => 'jxinventory/models/jxinventory_packlist',
        'order_overview' => 'jxinventory/models/jxinventory_orderoverview',
        'order_main' => 'jxinventory/models/jxinventory_ordermain'
                        ),
    'files'        => array(
        'install_jxinventory'  => 'jxinventory/application/controllers/admin/install_jxinventory.php',
        'article_jxinventory'  => 'jxinventory/application/controllers/admin/article_jxinventory.php',
        'jxinventory_list'     => 'jxinventory/application/controllers/admin/jxinventory_list.php',
        'jxinventory_sendorder'     => 'jxinventory/application/controllers/admin/jxinventory_sendorder.php'
                        ),
    'templates'    => array(
        'article_jxinventory.tpl' => 'jxinventory/views/admin/tpl/article_jxinventory.tpl',
        'jxinventory_list.tpl'    => 'jxinventory/views/admin/tpl/jxinventory_list.tpl',
        'jxinventory_summary.tpl' => 'jxinventory/views/admin/tpl/jxinventory_summary.tpl'
                        ),
    'events'       => array(
        'onActivate'   => 'install_jxinventory::onActivate', 
        'onDeactivate' => 'install_jxinventory::onDeactivate'
                        ),
    'settings'     => array(
                        array(
                            'group' => 'JXINVENTORY_DEACTIVATESETTINGS', 
                            'name'  => 'bJxInventoryDropOnDeactivate', 
                            'type'  => 'bool', 
                            'value' => 'false'
                            )
                        )
    );

?>
