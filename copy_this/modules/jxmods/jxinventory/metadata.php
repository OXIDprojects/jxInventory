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
                        'de'=>'Verwaltung und Auswertung des tats&auml;chlichen Lagerbestands.',
                        'en'=>'Administration and Analysis of the real inventory.'
                        ),
    'thumbnail'    => 'jxinventory.png',
    'version'      => '0.3',
    'author'       => 'Joachim Barthel',
    'url'          => 'https://github.com/job963/jxInventory',
    'email'        => 'jobarthel@gmail.com',
    'extend'       => array(
                            'oxorderarticle' => 'jxmods/jxinventory/models/jxinventory_packlist',
                            'order_overview' => 'jxmods/jxinventory/models/jxinventory_orderoverview',
                            'order_main'     => 'jxmods/jxinventory/models/jxinventory_ordermain',
                            'order_package'  => 'jxmods/jxinventory/extend/order_package_custom'
                        ),
    'files'        => array(
                            'jxinventory_events'   => 'jxmods/jxinventory/core/jxinventory_events.php',
                            'article_jxinventory'   => 'jxmods/jxinventory/application/controllers/admin/article_jxinventory.php',
                            'jxinventory_list'      => 'jxmods/jxinventory/application/controllers/admin/jxinventory_list.php',
                            'jxinventory_sendorder' => 'jxmods/jxinventory/application/controllers/admin/jxinventory_sendorder.php'
                        ),
    'templates'    => array(
                            'article_jxinventory.tpl' => 'jxmods/jxinventory/application/views/admin/tpl/article_jxinventory.tpl',
                            'jxinventory_list.tpl'    => 'jxmods/jxinventory/application/views/admin/tpl/jxinventory_list.tpl',
                            'jxinventory_summary.tpl' => 'jxmods/jxinventory/application/views/admin/tpl/jxinventory_summary.tpl',
                            'order_package_custom.tpl'=> 'jxmods/jxinventory/application/views/admin/tpl/order_package_custom.tpl'
                        ),
    'events'       => array(
                            'onActivate'   => 'jxinventory_events::onActivate', 
                            'onDeactivate' => 'jxinventory_events::onDeactivate'
                        ),
    'settings'     => array(
                        array(
                            'group' => 'JXINVENTORY_ACTIVATESETTINGS', 
                            'name'  => 'bJxInventoryCopyOnActivate', 
                            'type'  => 'bool', 
                            'value' => 'false'
                            ),
                        array(
                            'group' => 'JXINVENTORY_DEACTIVATESETTINGS', 
                            'name'  => 'bJxInventoryDropOnDeactivate', 
                            'type'  => 'bool', 
                            'value' => 'false'
                            )
                        )
    );

?>
