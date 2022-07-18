<?php

namespace Whatdapep\MainDev\Controller\Home;

class Test extends \Magento\Framework\App\Action\Action
{
    public function execute()
    {
        $textDisplay = new \Magento\Framework\DataObject(array('text' => 'Home Whatdapep'));
        $this->_eventManager->dispatch('whatdapep_maindev_display_text', ['mp_text' => $textDisplay]);
        echo $textDisplay->getText();
        exit;
    }
}
