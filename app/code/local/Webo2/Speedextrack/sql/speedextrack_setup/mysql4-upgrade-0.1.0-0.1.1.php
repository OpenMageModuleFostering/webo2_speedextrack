<?php
$installer = $this;

$installer->startSetup();

$installer->run("ALTER TABLE `{$this->getTable('sales/order')}` ADD `track_link` VARCHAR(255) NOT NULL;");



$widgetParameters = array(
    'param1' => 'track widget',
    'template' => 'speedextrack/trackwidget.phtml'
);

$instance = Mage::getModel('widget/widget_instance')->setData(array(
    'type' => 'speedextrack/trackwidget',
    'package_theme' => 'default/default', // according to template
    'title' => 'Track Widget',
    'store_ids' => '0', // or comma separated list of ids
    'sort_order' => '0',
    'widget_parameters' => serialize($widgetParameters),
    'page_groups' => array(array(
        'page_group' => 'all_pages',
        'all_pages' => array(
            'page_id' => null,
            'group' => 'all_pages',
            'layout_handle' => 'default',
            'block' => 'left',
            'for' => 'all',
            'template' => $widgetParameters['template'],
            
        )
    ))
))->save();

                

$installer->endSetup();
?>
