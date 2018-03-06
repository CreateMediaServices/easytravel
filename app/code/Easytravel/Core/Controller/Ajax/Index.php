<?php 
namespace Easytravel\Core\Controller\Ajax; 

class Index extends \Magento\Framework\App\Action\Action {
   
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

    protected $_sidebar;

    protected $_resultJsonFactory;

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
        \Magento\Checkout\Model\Sidebar $sidebar,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
        
	)
	{  
		$this->_coreRegistry        = $coreRegistry;
        $this->_resultPageFactory   = $resultPageFactory;
        $this->_coreHelper          = $coreHelperFunction;
        $this->_productLoader       = $_productLoader;
        $this->_stockState          = $stockState;
        $this->_cart                = $cart;
        $this->_checkoutSession     = $checkoutSession;
        $this->_sidebar             = $sidebar;
        $this->_resultJsonFactory   = $resultJsonFactory;


        parent::__construct($context);
    }

    /**
     * Blog Index, shows a list of recent blog posts.
     *
     * @return \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {   
       
        $type    = $this->getRequest()->getParam('type');

        if($type)
        {   
            if($type=='getProductOptions')
            {   
                return $this->response($this->getProductOptions());
            }

            if($type=='checkStockAndCart')
            {
                return $this->response($this->checkStockAndCart());
            }
            if($type=='addToCart')
            {   
               return $this->response($this->addToCart());
            }
        }

        exit;
	}

   

    public function getProductOptions()
    {   

        $params                 = $this->getParams();
        $productId              = $params['productId'];
        $departureDate          = $params['departureDate'];
        $noOfTravelers          = $params['noOfTravels'];
        $productGroup           = $params['productGroup'];
        $product                = $this->loadProduct($productId);
        $associatedProducts     = $this->getAssociatedProducts($product);
        $attribute_set_id       = $this->_coreHelper->getProdutAttributeGroupId($product);
        $noOfTravelersOptions   = array();
        $noOfKidsOptions        = array();
        $childProduct           = $this->getProductInAssociatedProducts($associatedProducts,$attribute_set_id,$params);

        if($productGroup   == $this->_coreHelper->getSmallGroupId())
        { 
            foreach ($associatedProducts as $childProduct)
            {
                if($this->getProductAttributeValue($childProduct,'departure_dates')==$departureDate)
                {
                    if($childProduct->getStatus()==$this->_coreHelper->getEnableProductStatus())
                    {
                        $noOfTravelersOptions[] = $this->getProductAttributeValue($childProduct,'no_of_travellers');
                    }
                }
            }

            $result['status']           = 1;
            $result['noOfTravelers']    = $noOfTravelersOptions;
            return $result;
        }

        if($productGroup   == $this->_coreHelper->getPrivateGroupId())
        {   
            $result['status'] = 1;
            if(strtotime($departureDate) > strtotime(date('Y-m-d')))
            {
                foreach ($associatedProducts  as $childProduct) 
                {   
                    $_noOfTravels       = $this->getProductAttributeValue($childProduct,'no_of_travellers');
                    $_departureDate     = $this->getProductAttributeValue($childProduct,'departure_dates');
                    $_noOfKids          = $this->getProductAttributeValue($childProduct,'child');
                    
                    $_departureDate = substr($_departureDate, 0,7);
                    $departureDate  = substr($departureDate, 0,7);
 
                    if($departureDate==$_departureDate && $childProduct->getStatus()==$this->_coreHelper->getEnableProductStatus())
                    {   

                        if($noOfTravelers)
                        {
                            if($noOfTravelers==$_noOfTravels)
                            {    
                                $noOfKidsOptions[] = $childProduct->getResource()->getAttribute('child')->getFrontend()->getValue($childProduct);
                            }
                        }
                        else
                        {
                            $noOfTravelersOptions[] = $_noOfTravels;
                        }
                    }
                }
            }

            if($noOfTravelers)
            {
                $result['noOfKids'] = $noOfKidsOptions;
            }
            else
            {
                $result['noOfTravelers'] = array_unique($noOfTravelersOptions);
            }
            return $result;
        }
    }

    public function checkStockAndCart()
    {   
        $params                 = $this->getParams();
        $productId              = $params['productId'];
        $departureDate          = $params['departureDate'];
        $productGroup           = $params['productGroup'];
        /*
        $noOfTravels            = $params['noOfTravels'];
        $noOfKids               = $params['noOfKids'];*/

        $product                = $this->loadProduct($productId);
        $associatedProducts     = $this->getAssociatedProducts($product);
        $attribute_set_id       = $this->_coreHelper->getProdutAttributeGroupId($product);
        $result                 = array();
        $result['status']       = 1;
        $result['stock']        = 1;
        $result['cart']         = 0;

        
        $childProduct   = $this->getProductInAssociatedProducts($associatedProducts,$attribute_set_id,$params);
        $quantity       = $this->_stockState->getStockQty($childProduct->getId(), $childProduct->getStore()->getWebsiteId());

        if($quantity==0)
        {   
            $result['stock']    = 0;
        }

        $result['cart'] = $this->isProductInCart($productId,$departureDate,$productGroup);
        return $result;
        /*if($attribute_set_id   == $this->_coreHelper->getSmallGroupId())
        {     
            foreach ($associatedProducts  as $childProduct) 
            {   
                $_noOfTravels                    = $this->getProductAttributeValue($childProduct,'no_of_travellers');
                $_departureDate                  = $this->getProductAttributeValue($childProduct,'departure_dates');
               
                if($noOfTravels == $_noOfTravels && $departureDate==$_departureDate)
                {   
                    $quantity = $this->_stockState->getStockQty($childProduct->getId(), $childProduct->getStore()->getWebsiteId());
                    if($quantity==0)
                    {   
                        $result['stock']    = 0;
                    }

                    $result['cart']  = $this->isProductInCart($productId,$departureDate, $productGroup);
                }
            }

        }*/

        /*** 
            Check Private Group Product Stock 
        **/

       /* if($attribute_set_id   == $this->_coreHelper->getPrivateGroupId())
        {   
            $childProduct   = $this->getProductInAssociatedProducts($associatedProducts,$attribute_set_id,$params);
            $quantity       = $this->_stockState->getStockQty($childProduct->getId(), $childProduct->getStore()->getWebsiteId());
           
            if($quantity==0)
            {   
                $result['stock']    = 0;
            }

            $result['cart'] = $this->isProductInCart($productId,$departureDate,$productGroup);


            foreach ($associatedProducts  as $childProduct) 
            {   
                $_noOfTravels                    = $this->getProductAttributeValue($childProduct,'no_of_travellers');
                $_departureDate                  = $this->getProductAttributeValue($childProduct,'departure_dates');
                $_noOfKids                       = $this->getProductAttributeValue($childProduct,'child');
               
                $_departureDate = substr($_departureDate, 0,7);
                $departureDate  = substr($departureDate, 0,7);
                
                if($noOfTravels == $_noOfTravels && $departureDate==$_departureDate && $_noOfKids ==  $noOfKids)
                {   
                    $quantity = $this->_stockState->getStockQty($childProduct->getId(), $childProduct->getStore()->getWebsiteId());
                    if($quantity==0)
                    {   
                        $result['stock']    = 0;
                    }

                    $result['cart'] = $this->isProductInCart($productId,$departureDate,$productGroup);
                }
            }
            return $result;
        }*/
    }

    public function addToCart()
    {   
        $params                 = $this->getParams();
        
        $productId              = $params['productId'];
        $departureDate          = $params['departureDate'];
        $noOfTravels            = $params['noOfTravels'];
        $productGroup           = $params['productGroup'];
        $noOfKids               = $params['noOfKids'];
        $itemId                 = $this->getRequest()->getParam('item_id');
        $product                = $this->loadProduct($productId);
        $associatedProducts     = $this->getAssociatedProducts($product);
        $attribute_set_id       = $this->_coreHelper->getProdutAttributeGroupId($product);
        $result                 = array();
        $result['status']       = 0;
        
        $childProduct           = $this->getProductInAssociatedProducts($associatedProducts,$attribute_set_id,$params);
        $quantity               = $this->_stockState->getStockQty($childProduct->getId(), $childProduct->getStore()->getWebsiteId());
        if($quantity > 0)
        {   
             
            $departureDateId    = $childProduct->getDepartureDates();
            $noOfTravelsId      = $childProduct->getNoOfTravellers();
            $params             = [];

            $params['product']  = $productId;
            $params['qty']      = 1;

            $options            = array(
                    $this->_coreHelper->getDepartureDateAttributeId() => $departureDateId,
                    $this->_coreHelper->getNoOfTravellersAttributeId() => $noOfTravelsId
                );
            if($attribute_set_id == $this->_coreHelper->getPrivateGroupId())
            {   
                $noOfKidsId         = $childProduct->getchild();
                $options[$this->_coreHelper->getNoOfKidsAttributeId()] = $noOfKidsId;
            }
            $params['super_attribute'] = $options;
            if($itemId)
            {   
                $this->_cart->removeItem($itemId);
                $this->_cart->addProduct($product, $params);
                $this->_cart->save();
            }
            else
            {
                $this->_cart->addProduct($product, $params);
                $this->_cart->save();
            }
            $result['status']   = 1;
            $result['item_id']  = $this->getLastInsertedItemIdInCart();
            return $result;
        }
        else
        {
            return $result;
        }
        /*if($attribute_set_id   == $this->_coreHelper->getSmallGroupId())
        {     
            foreach ($associatedProducts  as $childProduct) 
            {   
                $_noOfTravels                    = $this->getProductAttributeValue($childProduct,'no_of_travellers');
                $_departureDate                  = $this->getProductAttributeValue($childProduct,'departure_dates');
               
                if($noOfTravels == $_noOfTravels && $departureDate==$_departureDate)
                {   
                    $quantity = $this->_stockState->getStockQty($childProduct->getId(), $childProduct->getStore()->getWebsiteId());
                    if($quantity > 0)
                    {   
                        $departureDateId    = $childProduct->getDepartureDates();
                        $noOfTravelsId      = $childProduct->getNoOfTravellers();
                        $params             = [];
                        $params['product']  = $productId;
                        $params['qty']      = 1;

                        $options            = array(
                                $this->_coreHelper->getDepartureDateAttributeId() => $departureDateId,
                                $this->_coreHelper->getNoOfTravellersAttributeId() => $noOfTravelsId
                            );
                        $params['super_attribute'] = $options;
                      
                        if($itemId)
                        {   
                            $this->_cart->removeItem($itemId);
                            $this->_cart->addProduct($product, $params);
                            $this->_cart->save();
                            $result['status']   = 1;
                            $result['item_id']  = $this->getLastInsertedItemIdInCart();
                        }
                        else
                        {
                            $this->_cart->addProduct($product, $params);
                            $this->_cart->save();
                            $result['status'] = 1;
                            $result['item_id']  = $this->getLastInsertedItemIdInCart();
                        }
                        return $result;
                    }
                }
            }

            return $result;
        }
        if($attribute_set_id   == $this->_coreHelper->getPrivateGroupId())
        {     
            foreach ($associatedProducts  as $childProduct) 
            {   
                $_noOfTravels   = $this->getProductAttributeValue($childProduct,'no_of_travellers');
                $_departureDate = $this->getProductAttributeValue($childProduct,'departure_dates');
                $_noOfKids      = $this->getProductAttributeValue($childProduct,'child');
                $_departureDate = substr($_departureDate, 0,7);
                $departureDate  = substr($departureDate, 0,7);
               
                if($noOfTravels == $_noOfTravels && $departureDate==$_departureDate && $_noOfKids ==  $noOfKids)
                {   
                    $quantity = $this->_stockState->getStockQty($childProduct->getId(), $childProduct->getStore()->getWebsiteId());
                    if($quantity > 0)
                    {   
                        $departureDateId    = $childProduct->getDepartureDates();
                        $noOfTravelsId      = $childProduct->getNoOfTravellers();
                        $noOfKidsId         = $childProduct->getchild();
                        
                        $params             = [];
                        $params['product']  = $productId;
                        $params['qty']      = 1;

                        $options            = array(
                                $this->_coreHelper->getDepartureDateAttributeId() => $departureDateId,
                                $this->_coreHelper->getNoOfTravellersAttributeId() => $noOfTravelsId,
                                $this->_coreHelper->getNoOfKidsAttributeId() => $noOfKidsId
                            );
                        $params['super_attribute'] = $options;
                        if($itemId)
                        {   
                            $this->_cart->removeItem($itemId);
                            $this->_cart->addProduct($product, $params);
                            $this->_cart->save();
                            $result['status']   = 1;
                            $result['item_id']  = $this->getLastInsertedItemIdInCart();
                        }
                        else
                        {
                            $this->_cart->addProduct($product, $params);
                            $this->_cart->save();
                            $result['status'] = 1;
                            $result['item_id']  = $this->getLastInsertedItemIdInCart();
                        }
                        return $result;
                    }
                }
            }

            return $result;
        }*/
    }

    public function getProductInAssociatedProducts($associatedProducts = array(), $attribute_set_id = 0, $params = array())
    {
        $noOfTravels    = $params['noOfTravels'];
        $departureDate  = $params['departureDate'];
        $noOfKids       = $params['noOfKids'];
        $productGroup   = $params['productGroup'];

        foreach ($associatedProducts  as $childProduct) 
        {   
            $_noOfTravels                    = $this->getProductAttributeValue($childProduct,'no_of_travellers');
            $_departureDate                  = $this->getProductAttributeValue($childProduct,'departure_dates');
            
            if($attribute_set_id   == $this->_coreHelper->getPrivateGroupId())
            {
                $_noOfKids                       = $this->getProductAttributeValue($childProduct,'child');
                $_departureDate = substr($_departureDate, 0,7);
                $departureDate  = substr($departureDate, 0,7);
                
                if($noOfTravels == $_noOfTravels && $departureDate==$_departureDate && $_noOfKids ==  $noOfKids)
                {
                    return $childProduct;
                }
            }
            else
            {
                if($noOfTravels == $_noOfTravels && $departureDate==$_departureDate)
                {
                    return $childProduct;
                }
            }
        }
    }

    public function response($result = array())
    {
        $response = $this->_resultJsonFactory->create();
        return $response->setData($result);
    }

    public function getParams()
    {
        $params['productId']        = $this->getRequest()->getParam('product_id');
        $params['productGroup']     = $this->getRequest()->getParam('product_group');
        $params['noOfTravels']      = $this->getRequest()->getParam('no_of_travellers');
        $params['departureDate']    = $this->getRequest()->getParam('departure_dates');
        $params['noOfKids']         = @$this->getRequest()->getParam('no_of_kids');

        return $params;
    }

    public function getLastInsertedItemIdInCart()
    {
        $items = $this->_cart->getQuote()->getAllItems();
        $itemIds = [];
        if($items)
        {   
            foreach($items as $item) 
            {   
                $optionData = $item->getQtyOptions();
                if($optionData)
                {
                    $itemIds[] = $item->getItemId();
                }
            }
        }
        rsort($itemIds);
        return $itemIds[0];
    }

    public function isProductInCart($parentProductId = 0 , $departureDate , $productGroup)
    {
        $items = $this->_cart->getQuote()->getAllItems();

        if(!empty($items))
        {   
            //$itemData = [22 => ['qty' => 3]];
            //$this->_cart->updateItems($itemData)->save();
            foreach($items as $item) 
            {   
                $optionData = $item->getQtyOptions();
                if($optionData && $item->getProductId()==$parentProductId)
                {   
                    foreach ($optionData as $key => $row) 
                    {
                        $product        = $this->loadProduct($row->getProductId());
                        $_departureDate = $this->getProductAttributeValue($product,'departure_dates');
                        
                        if($productGroup   == $this->_coreHelper->getPrivateGroupId())
                        { 
                            $departureDate  = substr($departureDate, 0,7);
                            $_departureDate = substr($_departureDate, 0,7);
                        }

                        if(strtotime($_departureDate)==strtotime($departureDate))
                        {
                            return $item->getItemId();
                        }
                    }
                }
            }
        }

        return 0;
    }

    public function loadProduct($productId = 0)
    {
       return $this->_productLoader->create()->load($productId);
    }

    public function getAssociatedProducts($product)
    {
        $productTypeInstance    = $product->getTypeInstance();
        $associatedProducts     = $productTypeInstance->getUsedProducts($product);
        return $associatedProducts;
    }

    public function getProductAttributeValue($product,$attributeName = "" )
    {
        return $product->getResource()->getAttribute($attributeName)->getFrontend()->getValue($product);
    }

    public function removeItemFromCart($itemId = 0)
    {   

        $items = $this->_cart->getQuote()->getAllItems();
        if(!empty($items))
        {   
            foreach($items as $item) 
            {   
                if($item->getItemId()==$itemId)
                {   
                   $this->_cart->removeItem($itemId)->save();
                }
                if($item->getParentItemId()==$itemId)
                {
                   $this->_cart->removeItem($itemId)->save();
                }
            }
        }
        return true;
    }
}