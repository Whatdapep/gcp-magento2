<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Mageplaza\OverrideObserver\Observer;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Wishlist\Model\WishlistFactory;
// **** Odd Ons ****************
use Psr\Log\LoggerInterface;
// use Magento\Checkout\Model\Cart\RequestQuantityProcessor;
// use Magento\Framework\App\ObjectManager;
// ***********************
/**
 * Class AddToCart
 * @deprecated 101.0.0
 * @package Magento\Wishlist\Observer
 */
class AddToCart implements ObserverInterface
{
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Magento\Wishlist\Model\WishlistFactory
     */
    protected $wishlistFactory;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;
    // **** Odd Ons ******
    /**
     * Authentication
     *
     * @var logger
     *
     */
    protected $logger;
    // /**
    //  * @var RequestQuantityProcessor
    //  */
    // protected $quantityProcessor;
    // ***********************
    /**
     * @param CheckoutSession $checkoutSession
     * @param CustomerSession $customerSession
     * @param WishlistFactory $wishlistFactory
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        CheckoutSession $checkoutSession,
        CustomerSession $customerSession,
        WishlistFactory $wishlistFactory,
        ManagerInterface $messageManager,
        LoggerInterface $logger

    ) {
        // ?RequestQuantityProcessor $quantityProcessor = null
        $this->checkoutSession = $checkoutSession;
        $this->customerSession = $customerSession;
        $this->wishlistFactory = $wishlistFactory;
        $this->messageManager = $messageManager;
        $this->logger = $logger;
        // $this->quantityProcessor = $quantityProcessor
        //     ?? ObjectManager::getInstance()->get(RequestQuantityProcessor::class);
    }

    /**
     * @param Observer $observer
     * @return void
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function execute(Observer $observer)
    {
        $request = $observer->getEvent()->getRequest();
        $sharedWishlist = $this->checkoutSession->getSharedWishlist();
        $messages = $this->checkoutSession->getWishlistPendingMessages();
        $urls = $this->checkoutSession->getWishlistPendingUrls();
        $wishlistIds = $this->checkoutSession->getWishlistIds();
        $singleWishlistId = $this->checkoutSession->getSingleWishlistId();

        if ($singleWishlistId) {
            $wishlistIds = [$singleWishlistId];
        }
        // ********* Add Ons *********
        $custom_name = "Guest User";
        $product = $observer->getEvent()->getData('product');
        $request = $observer->getEvent()->getData('request');
        $params = $request->getParams();
        if (isset($params['qty'])) {
            // $filter = new \Zend_Filter_LocalizedToNormalized(
            //     ['locale' => $this->_objectManager->get(
            //         \Magento\Framework\Locale\ResolverInterface::class
            //     )->getLocale()]
            // );
            // $params['qty'] = $this->quantityProcessor->prepareQuantity($params['qty']);
            // $params['qty'] = $filter->filter($params['qty']);
            $qty = $params['qty'];
        } else {
            $qty = "Unset";
        }



        $product_name = $product->getName();
        $wordlog = 'NONE';
        if ($this->customerSession->isLoggedIn()) {
            $custom_name = $this->customerSession->getCustomerName();
        }

        $wordlog = $custom_name . " " . $product_name . " " . $qty . " Qty";

          
     
        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/product_tracking.log');
        $logger = new \Zend_Log();
        $logger->addWriter($writer);
        $logger->info($wordlog);

        $this->logger->info($wordlog);

        // ****************************
        if (is_array($wishlistIds) && count($wishlistIds) && $request->getParam('wishlist_next')) {
            $wishlistId = array_shift($wishlistIds);

            if ($this->customerSession->isLoggedIn()) {
                $wishlist = $this->wishlistFactory->create()
                    ->loadByCustomerId($this->customerSession->getCustomerId(), true);
                // ********** Add Ons ********************************
                $custom_name = $this->customerSession->getCustomerName();
                $wordlog = $custom_name . " " . $product_name . " " . " 1 Qty";

                // ***************************
            } elseif ($sharedWishlist) {
                $wishlist = $this->wishlistFactory->create()->loadByCode($sharedWishlist);

                // ************** Add Ons ********************
                $wordlog = $custom_name . " " . $product_name . " " . " 1 Qty";

                // *********************************
            } else {
                return;
            }

            $wishlists = $wishlist->getItemCollection()->load();
            foreach ($wishlists as $wishlistItem) {
                if ($wishlistItem->getId() == $wishlistId) {
                    $wishlistItem->delete();
                }
            }
            $this->checkoutSession->setWishlistIds($wishlistIds);
            $this->checkoutSession->setSingleWishlistId(null);
        }

        if ($request->getParam('wishlist_next') && count($urls)) {
            $url = array_shift($urls);
            $message = array_shift($messages);

            $this->checkoutSession->setWishlistPendingUrls($urls);
            $this->checkoutSession->setWishlistPendingMessages($messages);

            $this->messageManager->addError($message);

            $observer->getEvent()->getResponse()->setRedirect($url);
            $this->checkoutSession->setNoCartRedirect(true);
        }
    }
}

