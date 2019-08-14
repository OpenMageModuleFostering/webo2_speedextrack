<?php

    class Webo2_Speedextrack_Helper_Data extends Mage_Core_Helper_Abstract
    {
        public function getSpeedextrackUrl()
        {
            return $this->_getUrl('speedextrack/index');
        }
}