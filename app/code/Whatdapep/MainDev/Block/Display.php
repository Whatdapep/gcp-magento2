<?php

namespace Whatdapep\MainDev\Block;

class Display extends \Magento\Framework\View\Element\Template
{

    protected $_postFactory;
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Whatdapep\MainDev\Model\PostFactory $postFactory
    )
    {
        $this->_postFactory = $postFactory;
        parent::__construct($context);
    }
//    ________________________________________________________
//    public function __construct(
//        \Magento\Framework\View\Element\Template\Context $context
//    )
//    {
//        parent::__construct($context);
//    }
//    ____________________________________________
    public function sayHello()
    {
        return __('Hello World');
    }

    public function timeNow(){
        return __("time is ".Date('Y-m-d H:i:s'));
    }
    public function getPostCollection(){
        $post = $this->_postFactory->create();
        return $post->getCollection();
    }


}
