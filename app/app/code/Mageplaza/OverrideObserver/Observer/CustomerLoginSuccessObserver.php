<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Mageplaza\OverrideObserver\Observer;

use Magento\Customer\Model\AuthenticationInterface;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;

/**
 * Class CustomerLoginSuccessObserver
 */
class CustomerLoginSuccessObserver implements ObserverInterface
{
    /**
     * Authentication
     *
     * @var AuthenticationInterface
     */
    protected $authentication;
    /**
     * Authentication
     *
     * @var logger
     *
     */
    protected $logger;
    /**
     *
     * @var \Pom\ProductTracking\Logger\Customer
     *
     */
    protected $loggerCustomer;


    /**
     * @param AuthenticationInterface $authentication
     */
    
        // \Mageplaza\OverrideObserver\Logger\ProductTracking $loggerCustomer
    public function __construct(
        AuthenticationInterface $authentication,
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
        $this->authentication = $authentication;
        // $this->loggerCustomer = $loggerCustomer;
    }

    /**
     * Unlock customer on success login attempt.
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magento\Customer\Model\Customer $customer */

        $customer = $observer->getEvent()->getData('model');

        $this->authentication->unlock($customer->getId());

        $this->logger->info('Unlock customer with id ' . $customer->getId());

        // $this->loggerCustomer->info('Unlock customer with id ' . $customer->getId());


        // $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/product_tracking.log');
        // $logger = new \Zend\Log\Logger();
        // $logger->addWriter($writer);
        // $logger->info($customer->getId());


        return $this;
    }
}

