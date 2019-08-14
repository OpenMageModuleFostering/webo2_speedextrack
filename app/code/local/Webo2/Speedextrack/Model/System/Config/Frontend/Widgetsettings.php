<?php

    class Webo2_Speedextrack_Model_System_Config_Frontend_Widgetsettings 
    {
        public function toOptionArray()
        {
            return array(                
                array('value' => 'left',
                      'label' => Mage::helper('speedextrack')->__('Left Column')),
                array('value' => 'right',
                      'label' => Mage::helper('speedextrack')->__('Right Column')),
            );
        }

}