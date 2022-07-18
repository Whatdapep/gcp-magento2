<?php

namespace Whatdapep\MainDev\Model;

class Post  extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'whatdapep_helloworld_post';

    protected $_cacheTag = 'whatdapep_helloworld_post';

    protected $_eventPrefix = 'whatdapep_helloworld_post';

    protected function _construct()
    {
        $this->_init('Whatdapep\MainDev\Model\ResourceModel\Post');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }
}
