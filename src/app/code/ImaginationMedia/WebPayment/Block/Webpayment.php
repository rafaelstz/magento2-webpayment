<?php

namespace ImaginationMedia\WebPayment\Block;

use Magento\Framework\View\Element\Template;
use Magento\Checkout\Block\Cart\AbstractCart;

/**
 * Webpayment block
 */
class Webpayment extends Template
{

    private $_cart;

    public function __construct(
        Template\Context $context,
        AbstractCart $cart,
        array $data = [])
    {
        parent::__construct(
            $context,
            $data
        );
        $this->_cart = $cart;
    }

    public function getCurrency(){
//        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of Object Manager
//        $abstractCart = $objectManager->create('Magento\Checkout\Block\Cart\AbstractCart'); // Instance of Pricing Helper
        $currency = $this->_cart->getQuote()->getQuoteCurrencyCode();
        return $currency;
    }

    public function getAllItems(){
        $allItems = $this->_cart->getQuote()->getAllItems();
        return $allItems;
    }

    public function getTotal(){
        $quote = $this->_cart->getTotalsCache();
        $getGrandTotal = $quote['grand_total']->getData('value');

        return $getGrandTotal;
    }

    public function getShippingRate(){
        $quote = $this->_cart->getTotalsCache();
        $getShippingRate = $quote['shipping']->getData('value');

        return $getShippingRate;
    }

    public function getSubTotal(){
        $quote = $this->_cart->getTotalsCache();
        $getSubTotal = $quote['subtotal']->getData('value');

        return $getSubTotal;
    }


}