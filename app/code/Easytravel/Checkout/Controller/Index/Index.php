<?php 
namespace Easytravel\Checkout\Controller\Index; 

class Index extends \Magento\Framework\App\Action\Action {
   
   /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
	protected $_coreRegistry;

	/** @var  \Magento\Framework\View\Result\Page */
    protected $_resultPageFactory;

    protected $_cart;

    protected $_checkoutSession;
    
    protected $_customerSession;

    protected $_storeManager;
    protected $_product;
    protected $_formkey;
    protected $quote;
    protected $quoteManagement;
    protected $customerFactory;
    protected $customerRepository;
    protected $orderService;
    
	/**      
		* @param \Magento\Framework\App\Action\Context $context
	*/
    public function __construct
	(
		\Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Customer\Model\Session $customerSession,


        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Product $product,
        \Magento\Framework\Data\Form\FormKey $formkey,
        \Magento\Quote\Model\QuoteFactory $quote,
        \Magento\Quote\Model\QuoteManagement $quoteManagement,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Sales\Model\Service\OrderService $orderService 
	)
	{
		$this->_coreRegistry 		= $coreRegistry;
		$this->_resultPageFactory	= $resultPageFactory;

        $this->_cart                 = $cart;
        $this->_checkoutSession      = $checkoutSession;
        $this->_customerSession      = $customerSession;

        $this->_storeManager = $storeManager;
        $this->_product = $product;
        $this->_formkey = $formkey;
        $this->quote = $quote;
        $this->quoteManagement = $quoteManagement;
        $this->customerFactory = $customerFactory;
        $this->customerRepository = $customerRepository;
        $this->orderService = $orderService;

        parent::__construct($context);
    }

    /**
     * Blog Index, shows a list of recent blog posts.
     *
     * @return \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        /*
        // retrieve quote items collection
        $itemsCollection = $this->_cart->getQuote()->getItemsCollection();

        // get array of all items what can be display directly
        $itemsVisible = $this->_cart->getQuote()->getAllVisibleItems();
        */

        // retrieve quote items array
        $cartItems = $this->_cart->getQuote()->getAllItems();

        /* // below code is working for creating manual order via code!
        
        $orderData = [
             'currency_id'  => 'USD',
             'email'        => 'johndoe@example.com', //buyer email id
             'shipping_address' =>[
                    'firstname'    => 'John', //address Details
                    'lastname'     => 'Deo',
                            'street' => 'Main Street',
                            'city' => 'Pheonix',
                    'country_id' => 'US',
                    'region' => 'Arizona',
                    'region_id' => '4',
                    'postcode' => '85001',
                    'telephone' => '823322565',
                    'fax' => '3245845623',
                    'save_in_address_book' => 0
                         ],
           'items'=> [ 
                      ['product_id'=>'5','qty'=>1, 'price' => 100],
                      ['product_id'=>'6','qty'=>2, 'price' => 150]
                    ]
        ];

        $orderResult = $this->createMageOrder($orderData);

        echo '<pre>';
        print_r($orderResult);
        exit;
        */

		$resultPage = $this->_resultPageFactory->create();

		$resultPage->getConfig()->getTitle()->set(__('Easy Travel Checkout'));

        $this->_coreRegistry->register('cartItems', $cartItems);

    	return $resultPage;
	}

    public function createMageOrder($orderData)
    {
        $store=$this->_storeManager->getStore();
        $websiteId = $this->_storeManager->getStore()->getWebsiteId();
        $customer=$this->customerFactory->create();
        $customer->setWebsiteId($websiteId);
        $customer->loadByEmail($orderData['email']);// load customet by email address
        
        if(!$customer->getEntityId())
        {
            //If not avilable then create this customer 
            $customer->setWebsiteId($websiteId)
                    ->setStore($store)
                    ->setFirstname($orderData['shipping_address']['firstname'])
                    ->setLastname($orderData['shipping_address']['lastname'])
                    ->setEmail($orderData['email']) 
                    ->setPassword($orderData['email']);
            $customer->save();
        }

        $quote=$this->quote->create(); //Create object of quote
        $quote->setStore($store); //set store for which you create quote
        // if you have allready buyer id then you can load customer directly 
        $customer= $this->customerRepository->getById($customer->getEntityId());
        $quote->setCurrency();
        $quote->assignCustomer($customer); //Assign quote to customer
 
        //add items in quote
        foreach($orderData['items'] as $item){
            $product=$this->_product->load($item['product_id']);
            $product->setPrice($item['price']);
            $quote->addProduct(
                $product,
                intval($item['qty'])
            );
        }
 
        //Set Address to quote
        $quote->getBillingAddress()->addData($orderData['shipping_address']);
        $quote->getShippingAddress()->addData($orderData['shipping_address']);
 
        // Collect Rates and Set Shipping & Payment Method
 
        $shippingAddress=$quote->getShippingAddress();
        $shippingAddress->setCollectShippingRates(true)
                        ->collectShippingRates()
                        ->setShippingMethod('freeshipping_freeshipping'); //shipping method
        $quote->setPaymentMethod('cashondelivery'); //payment method
        $quote->setInventoryProcessed(false); //not effetc inventory
        $quote->save(); //Now Save quote and your quote is ready
 
        // Set Sales Order Payment
        $quote->getPayment()->importData(['method' => 'cashondelivery']);
 
        // Collect Totals & Save Quote
        $quote->collectTotals()->save();
 
        // Create Order From Quote
        $order = $this->quoteManagement->submit($quote);
 
        $order->setEmailSent(0);
        $increment_id = $order->getRealOrderId();

        $result = array();
        if($order->getEntityId()){
            $result['order_id']= $order->getRealOrderId();
        }else{
            $result=['error'=>1,'msg'=>'Your custom message'];
        }
        return $result;
    }
}
