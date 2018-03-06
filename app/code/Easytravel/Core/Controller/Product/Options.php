<?php 
namespace Easytravel\Core\Controller\Product; 

class Options extends \Magento\Framework\App\Action\Action {
   
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

        //$productId = 623;//simple product id
       // $date = "2017-11-13";
        $productId              = $this->getRequest()->getParam('product_id');
        $departureDate          = $this->getRequest()->getParam('departure_dates');
        $productGroup           = $this->getRequest()->getParam('product_group');
        $noOfTravelers          = @$this->getRequest()->getParam('no_of_travellers');
        /*$productId      = 559;
        $departureDate  = "2018-01-23";
        $productGroup   =  12; 
        $noOfTravelers  =  2; */
        if($productId)
        {

            $product = $this->_productLoader->create()->load($productId);
            $productTypeInstance    = $product->getTypeInstance();
            $associatedProducts     = $productTypeInstance->getUsedProducts($product);
            $noOfTravelersOptions = array();
            $noOfKidsOptions = array();
            if($productGroup   == $this->_coreHelper->getSmallGroupId())
            { 
                foreach ($associatedProducts as $childProduct)
                {
                    if($childProduct->getResource()->getAttribute('departure_dates')->getFrontend()->getValue($childProduct)==$departureDate)
                    {
                        if($childProduct->getStatus()==$this->_coreHelper->getEnableProductStatus())
                        {
                            $noOfTravelersOptions[] = $childProduct->getResource()->getAttribute('no_of_travellers')->getFrontend()->getValue($childProduct);
                        }
                    }
                }

                $result['status']           = 1;
                $result['noOfTravelers']    = $noOfTravelersOptions;
                
                echo json_encode($result);die;
            }

            if($productGroup   == $this->_coreHelper->getPrivateGroupId())
            {   
                $result['status'] = 1;
                if(strtotime($departureDate) > strtotime(date('Y-m-d')))
                {
                    foreach ($associatedProducts  as $childProduct) 
                    {   
                        $_noOfTravels   = $childProduct->getResource()->getAttribute('no_of_travellers')->getFrontend()->getValue($childProduct);
                        $_departureDate = $childProduct->getResource()->getAttribute('departure_dates')->getFrontend()->getValue($childProduct);
                        $_noOfKids      = $childProduct->getResource()->getAttribute('child')->getFrontend()->getValue($childProduct);
                        
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
                    /*if($noOfTravels == $_noOfTravels && $departureDate==$_departureDate && $_noOfKids ==  $noOfKids)
                    {   
                        $quantity = $this->_stockState->getStockQty($childProduct->getId(), $childProduct->getStore()->getWebsiteId());
                        if($quantity==0)
                        {   
                            $result['status']   = 0;
                            $result['message']  = "Out of Stock";
                            echo json_encode($result);die; 
                        }
                    }*/

                if($noOfTravelers)
                {
                    $result['noOfKids'] = $noOfKidsOptions;
                }
                else
                {
                    $result['noOfTravelers'] = array_unique($noOfTravelersOptions);
                }
                echo json_encode($result);die;
            }
        }
        exit;
	}
}