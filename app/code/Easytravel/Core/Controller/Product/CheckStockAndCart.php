<?php 
namespace Easytravel\Core\Controller\Product; 

class CheckStockAndCart extends \Magento\Framework\App\Action\Action {
   
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
        \Magento\Checkout\Model\Cart $cart
        
	)
	{  
		$this->_coreRegistry        = $coreRegistry;
        $this->_resultPageFactory   = $resultPageFactory;
        $this->_coreHelper          = $coreHelperFunction;
        $this->_productLoader       = $_productLoader;
        $this->_stockState          = $stockState;
        $this->_cart                = $cart;


        parent::__construct($context);
    }

    /**
     * Blog Index, shows a list of recent blog posts.
     *
     * @return \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {   
        
        $productId              = $this->getRequest()->getParam('product_id');
        $productGroup           = $this->getRequest()->getParam('product_group');
        $noOfTravels            = $this->getRequest()->getParam('no_of_travellers');
        $departureDate          = $this->getRequest()->getParam('departure_dates');
        $noOfKids               = $this->getRequest()->getParam('no_of_kids');
        if($productId)
        {
            $product                = $this->_productLoader->create()->load($productId);
            $productTypeInstance    = $product->getTypeInstance();
            $associatedProducts     = $productTypeInstance->getUsedProducts($product);
            $attribute_set_id       = $this->_coreHelper->getProdutAttributeGroupId($product);
            $result                 = array();
            $result['status']       = 1;
            $result['stock']        = 1;
            $result['cart']          = 0;
            /*** 
                Check Small Group Product Stock 
            **/

            if($attribute_set_id   == $this->_coreHelper->getSmallGroupId())
            {     
                foreach ($associatedProducts  as $childProduct) 
                {   
                    $_noOfTravels                    = $childProduct->getResource()->getAttribute('no_of_travellers')->getFrontend()->getValue($childProduct);
                    $_departureDate                  = $childProduct->getResource()->getAttribute('departure_dates')->getFrontend()->getValue($childProduct);
                   
                    if($noOfTravels == $_noOfTravels && $departureDate==$_departureDate)
                    {   
                        $quantity = $this->_stockState->getStockQty($childProduct->getId(), $childProduct->getStore()->getWebsiteId());
                        if($quantity==0)
                        {   
                            $result['stock']    = 0;
                        }
                        $isProductInCart = $this->isProductInCart($productId,$childProduct->getId());
                        if($isProductInCart)
                        {
                            $result['cart']    = 1;
                        }
                    }
                }

                echo json_encode($result);die;
            }

            /*** 
                Check Private Group Product Stock 
            **/

            if($attribute_set_id   == $this->_coreHelper->getPrivateGroupId())
            {   
                foreach ($associatedProducts  as $childProduct) 
                {   
                    $_noOfTravels                    = $childProduct->getResource()->getAttribute('no_of_travellers')->getFrontend()->getValue($childProduct);
                    $_departureDate                  = $childProduct->getResource()->getAttribute('departure_dates')->getFrontend()->getValue($childProduct);
                    $_noOfKids                       = $childProduct->getResource()->getAttribute('child')->getFrontend()->getValue($childProduct);
                   
                    $_departureDate = substr($_departureDate, 0,7);
                    $departureDate  = substr($departureDate, 0,7);
                    
                    if($noOfTravels == $_noOfTravels && $departureDate==$_departureDate && $_noOfKids ==  $noOfKids)
                    {   
                        $quantity = $this->_stockState->getStockQty($childProduct->getId(), $childProduct->getStore()->getWebsiteId());
                        if($quantity==0)
                        {   
                            $result['status']   = 0;
                            $result['message']  = "Out of Stock";
                            echo json_encode($result);die; 
                        }
                    }
                }
                echo json_encode($result);die;
            }

        }
        exit;
	}

    function isProductInCart($parentProductId = 0 , $childProductId = 0)
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
                        if($row->getProductId()==$childProductId)
                        {
                           return true;
                        }
                    }
                }
            }
        }

        return false;
    }
}