<?php

namespace Whatdapep\MainDev\Model\ResourceModel\Post;

class Collection extends \Magento\Catalog\Model\ResourceModel\Collection\AbstractCollection
{
    protected $_idFieldName = 'post_id';
    protected $_eventPrefix = 'whatdapep_helloworld_post_collection';
    protected $_eventObject = 'post_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Whatdapep\MainDev\Model\Post', 'Whatdapep\MainDev\Model\ResourceModel\Post');
    }
}
