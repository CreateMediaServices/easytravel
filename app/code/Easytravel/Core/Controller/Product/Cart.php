<?php 
namespace Easytravel\Core\Controller\Product; 

class Cart extends \Magento\Framework\App\Action\Action {
   
  /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /** @var  \Magento\Framework\View\Result\Page */
    protected $_resultPageFactory;

    protected $_coreHelper;

    protected $_productLoader;
    
    protected $_stockState;

    protected $_cart;
    
    protected $_checkoutSession;

    protected $_objectmanager;
    
    protected $_eventManager;

    /**      
        * @param \Magento\Framework\App\Action\Context $context
    */

    public function __construct
	(
		\Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Easytravel\Core\Helper\Functions $coreHelperFunction,
        \Magento\Catalog\Model\ProductFactory $_productLoader,
        \Magento\CatalogInventory\Api\StockStateInterface $stockState,
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\ObjectManagerInterface $objectmanager,
        \Magento\Framework\Event\Manager $eventManager
        
	)
	{  
		$this->_coreRegistry        = $coreRegistry;
        $this->_resultPageFactory   = $resultPageFactory;
        $this->_coreHelper          = $coreHelperFunction;
        $this->_productLoader       = $_productLoader;
        $this->_stockState          = $stockState;
        $this->_cart                = $cart;
        $this->_checkoutSession     = $checkoutSession;
        $this->_objectmanager       = $objectmanager;
        $this->_eventManager        = $eventManager;


        parent::__construct($context);
    }

    /**
     * Blog Index, shows a list of recent blog posts.
     *
     * @return \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {   
        
       
        $allItems = $this->_cart->getQuote()->getAllItems();
        if(!empty($allItems))
        {   
           
            foreach($allItems as $item) 
            {   
                echo 'IID====: '.$item->getItemId().'<br />';
                echo 'PID====: '.$item->getParentItemId().'<br />';
                echo 'ID: '.$item->getProductId().'<br />';
                echo 'Name: '.$item->getName().'<br />';
                echo 'Sku: '.$item->getSku().'<br />';
                echo 'Quantity: '.$item->getQty().'<br />';
                echo 'Price: '.$item->getPrice().'<br />';
                echo "<br />";
                $this->_cart->removeItem($item->getItemId())->save();
                /*if($item->getItemId()==$itemId)
                {   
                    echo $item->getItemId()."<br>";
                   $this->_cart->removeItem($itemId)->save();
                }
                if($item->getParentItemId()==$itemId)
                {
                   $this->_cart->removeItem($itemId)->save();
                    echo $item->getItemId()."<br>";
                }*/
            }
        }
        die;
        foreach ($allItems as $item) 
        {   
            /*var_dump($item->getData());
            $optionData = $item->getQtyOptions();
            if($optionData)
            {


                foreach ($optionData as $key => $row) 
                {   
                    
                    //var_dump($row->getData());
                    if($row->getProductId()==$productId)
                    {
                        $itemData = [$item->getItemId() => ['qty' => 4]];
                        $this->_cart->updateItems($itemData)->save();
                    }
                }
            }*/
            echo 'IID====: '.$item->getItemId().'<br />';
            echo 'PID====: '.$item->getParentItemId().'<br />';
            echo 'ID: '.$item->getProductId().'<br />';
            echo 'Name: '.$item->getName().'<br />';
            echo 'Sku: '.$item->getSku().'<br />';
            echo 'Quantity: '.$item->getQty().'<br />';
            echo 'Price: '.$item->getPrice().'<br />';
            echo "<br />";
            if($optionData)
            {
                foreach ($optionData as $key => $row) 
                {   
                    
                    //var_dump($row->getData());
                    if($row->getProductId()==$productId)
                    {
                        $itemData = [$item->getItemId() => ['qty' => 4]];
                        $this->_cart->updateItems($itemData)->save();
                    }
                }
            }
            //$this->_coreHelper->dd($item->getProduct()->getData());
            //$itemId = $item->getItemId();
            //$this->_cart->removeItem($itemId)->save();
        }
        die;
        //$this->_customerSession->setCurrentStore($storeId);
        $productId = 653;//simple product id
        $product    = $this->_productLoader->create()->load($productId);

        // load child product
        $childId = 629;
        $childProduct = $this->_productLoader->create()->load($childId);
        $productTypeInstance    = $product->getTypeInstance();
        $productAttributeOptions= $productTypeInstance->getConfigurableAttributesAsArray($product);
        /*$this->_coreHelper->dd($productAttributeOptions);
        die;
        $params = [];
        $params['product'] = $product->getId();
        $params['qty'] = 1;
        $options = array(
                317 => 167,
                316 => 139
            );
        $params['super_attribute'] = $options;*/
        /*$this->_cart->addProduct($product, $params);
        $this->_cart->save();*/
       /* $qoute = $this->_checkoutSession->getQuote();
        var_dump($qoute->getItems()->getData());die;
        $qoute->updateItem($productId, array( 'qty' => 2));*/
        $items = $this->_cart->getQuote()->getAllItems();

        if(!empty($items))
        {   
            //$itemData = [22 => ['qty' => 3]];
            //$this->_cart->updateItems($itemData)->save();
            foreach($items as $item) 
            {   
                $optionData = $item->getQtyOptions();
                if($optionData)
                {
                    foreach ($optionData as $key => $row) 
                    {
                        if($row->getProductId()==629)
                        {
                            $itemData = [$item->getItemId() => ['qty' => 4]];
                            $this->_cart->updateItems($itemData)->save();
                        }
                    }
                }
                if($item->getProductId()==629)
                {
                    $itemData = [$item->getItemId() => ['qty' => 4]];
                    $this->_cart->updateItems($itemData)->save();
                }
               
                //var_dump();die;
                //$this->_coreHelper->dd($item->getData());die;
               
                echo 'Item ID: '.$item->getItemId().'<br />';
                echo 'ID: '.$item->getProductId().'<br />';
                echo 'Name: '.$item->getName().'<br />';
                echo 'Sku: '.$item->getSku().'<br />';
                echo 'Quantity: '.$item->getQty().'<br />';
                echo 'Price: '.$item->getPrice().'<br />';
                echo "<br />";            
            }
        }
        die;
        //$productId = 604;//simple product id
        ////$product = $this->_productLoader->create()->load($productId);
        $this->_coreHelper->dd($product);
        //var_dump($product->getTierPrice());die;
        /*

        // load child product
        $childId = 603;
        $childProduct = $this->_productLoader->create()->load($childId);

        // Prepare cart params 
        $params = [];
        $params['product'] = $product->getId();
        $params['qty'] = 1;
        $options = [];
        foreach($productAttributeOptions as $option){
            $options[$option['attribute_id']] =  $childProduct->getData($option['attribute_code']);
        }
        $params['super_attribute'] = $options;

        //Add product to cart 
        $this->_cart->addProduct($product, $params);
        $this->_cart->save();
        $items = $this->_cart->getQuote()->getAllItems();
        if(!empty($items))
        {

            foreach($items as $item) 
            {
                echo 'ID: '.$item->getProductId().'<br />';
                echo 'Name: '.$item->getName().'<br />';
                echo 'Sku: '.$item->getSku().'<br />';
                echo 'Quantity: '.$item->getQty().'<br />';
                echo 'Price: '.$item->getPrice().'<br />';
                echo "<br />";            
            }
        }
        var_dump($params);die;*/
        //$this->_coreHelper->dd($this->_cart->getQuote()->getAllItems());die;
        //echo "Cart Controller";

        exit;
	}
}