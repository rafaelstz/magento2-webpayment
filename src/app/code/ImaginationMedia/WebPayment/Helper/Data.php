<?php
namespace ImaginationMedia\WebPayment\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    private $customerRepository;
    private $_storeManager;
    private $_productFactory;
    private $quoteManagement;
    private $shippingRate;
    private $cartManagementInterface;
    private $cartRepositoryInterface;
    private $orderService;
    private $customerFactory;

    /**
     * Data constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Quote\Model\QuoteManagement $quoteManagement
     * @param \Magento\Customer\Model\CustomerFactory $customerFactory
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Magento\Sales\Model\Service\OrderService $orderService
     * @param \Magento\Quote\Api\CartRepositoryInterface $cartRepositoryInterface
     * @param \Magento\Quote\Api\CartManagementInterface $cartManagementInterface
     * @param \Magento\Quote\Model\Quote\Address\Rate $shippingRate
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Quote\Model\QuoteManagement $quoteManagement,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Sales\Model\Service\OrderService $orderService,
        \Magento\Quote\Api\CartRepositoryInterface $cartRepositoryInterface,
        \Magento\Quote\Api\CartManagementInterface $cartManagementInterface,
        \Magento\Quote\Model\Quote\Address\Rate $shippingRate
    ) {
        $this->_storeManager = $storeManager;
        $this->_productFactory = $productFactory;
        $this->quoteManagement = $quoteManagement;
        $this->customerFactory = $customerFactory;
        $this->customerRepository = $customerRepository;
        $this->orderService = $orderService;
        $this->cartRepositoryInterface = $cartRepositoryInterface;
        $this->cartManagementInterface = $cartManagementInterface;
        $this->shippingRate = $shippingRate;
    }

    /**
     * Create Order On Your Store
     *
     * @param array $orderData
     * @return int $orderId
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function createWebPaymentOrder($orderData) {

        //init the store id and website id @todo pass from array
        $store = $this->_storeManager->getStore();
        $websiteId = $this->_storeManager->getStore()->getWebsiteId();
        //init the customer
        $customer=$this->customerFactory->create();
        $customer->setWebsiteId($websiteId);
        $customer->loadByEmail($orderData['email']);// load customet by email address
        //check the customer
        if(!$customer->getEntityId()){
            //If not avilable then create this customer
            $customer->setWebsiteId($websiteId)
                ->setStore($store)
                ->setFirstname($orderData['shipping_address']['firstname'])
                ->setLastname($orderData['shipping_address']['lastname'])
                ->setEmail($orderData['email'])
                ->setPassword($orderData['email']);
            $customer->save();
        }
        //init the quote
        $cart_id = $this->cartManagementInterface->createEmptyCart();
        $cart = $this->cartRepositoryInterface->get($cart_id);
        $cart->setStore($store);
        // if you have already buyer id then you can load customer directly
        $customer= $this->customerRepository->getById($customer->getEntityId());
        $cart->setCurrency();
        $cart->assignCustomer($customer); //Assign quote to customer
        //add items in quote
        foreach($orderData['items'] as $item){
            $product = $this->_productFactory->create()->load($item['product_id']);
            $cart->addProduct(
                $product,
                intval($item['qty'])
            );
        }
        //Set Address to quote @todo add section in order data for seperate billing and handle it
        $cart->getBillingAddress()->addData($orderData['shipping_address']);
        $cart->getShippingAddress()->addData($orderData['shipping_address']);
        // Collect Rates and Set Shipping & Payment Method
        $this->shippingRate
            ->setCode('freeshipping_freeshipping')
            ->getPrice(1);
        $shippingAddress = $cart->getShippingAddress();
        //@todo set in order data
        $shippingAddress->setCollectShippingRates(true)
            ->collectShippingRates()
            ->setShippingMethod('flatrate_flatrate'); //shipping method
        $cart->getShippingAddress()->addShippingRate($this->shippingRate);
        $cart->setPaymentMethod('checkmo'); //payment method
        //@todo insert a variable to affect the invetory
        $cart->setInventoryProcessed(false);
        // Set sales order payment
        $cart->getPayment()->importData(['method' => 'checkmo']);
        // Collect total and saeve
        $cart->collectTotals();
        // Submit the quote and create the order
        $cart->save();
        $cart = $this->cartRepositoryInterface->get($cart->getId());
        $order_id = $this->cartManagementInterface->placeOrder($cart->getId());
//        return $order_id;
        var_dump($order_id);exit;
    }
}