<?php
namespace Easytravel\Core\Helper;

class Functions extends \Magento\Framework\Url\Helper\Data
{
    const MODULE_NAME                               = 'Easytravel_Core';
    const URL_TailorMadeTour                        = 'tailor-made-tour';
    const CONST_SMALL_GROUP_ID                      = 11;
    const CONST_PRIVATE_GROUP_ID                    = 12;
    const CONST_PRODUCT_ENABLE_STATUS               = 1;
    const CONST_ATTRIBUTE_ID_FOR_DEPARTURE_DATE     = 317;
    const CONST_ATTRIBUTE_ID_FOR_NO_OF_TRAVALLERS   = 316;
    const CONST_ATTRIBUTE_ID_FOR_NO_OF_KIDS         = 312;

    protected $_objectManager;
    protected $_attributeSet;
    protected $_storeManager;
    protected $_priceCurrencyObject;
    protected $_stockItemRepository;

    public function __construct
    (
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectmanager,
        \Magento\Eav\Api\AttributeSetRepositoryInterface $attributeSet,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrencyObject,
        \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository
    )
    {
        parent::__construct($context);
        $this->_objectManager       = $objectmanager;
        $this->_attributeSet        = $attributeSet;
        $this->_storeManager        = $storeManager;
        $this->_priceCurrencyObject = $priceCurrencyObject;
        $this->_stockItemRepository = $stockItemRepository;
    }

    function getABC()
    {
        return 'xyz1';
    }

    function valueIsSet($value='')
    {
        if( (!empty($value)) || ($value == '0') )
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    function validateEmailAddress($sEmailAddress)
    {
        if(!filter_var($sEmailAddress, FILTER_VALIDATE_EMAIL))
        {
            return false;
        }
        
        return true;
    }

    function validateUrl($sUrl)
    {
        if(!filter_var($sUrl, FILTER_VALIDATE_URL))
        {
            return false;
        }

        return true;
    }

    function getFieldValueFromPostedData($postedData=array(), $fieldName='')
    {
        $value = '';
        if($fieldName)
        {
            if(is_array($postedData) && count($postedData) > 0);
            {
                if(isset($postedData[$fieldName]))
                {
                    $value = $postedData[$fieldName];
                }
            }
        }
        
        return $value;
    }

    function beforeDBSave($value='')
    {
        if($value)
        {
            $value = addslashes($value);
        }
        
        return $value;
    }

    function getFormValidationErrorMessage($aErrors)
    {
        $htmlErrorMessages = '';
        if(is_array($aErrors) && count($aErrors) > 0)
        {
            foreach($aErrors as $errorKey => $errorMessage)
            {
                $htmlErrorMessages .=  $errorMessage .'<br />';
            }

            if($htmlErrorMessages)
            {
                $htmlErrorMessages = '<div class="text-danger">'. $htmlErrorMessages .'</div>';
            }
        }
        else
        {
            $htmlErrorMessages = '<div class="text-danger">'. $aErrors .'</div>';
        }


        return $htmlErrorMessages;
    }

    public function getUrlForTailorMadeTour()
    {   
        return $this->_getUrl(self::URL_TailorMadeTour);
    }   

    public function getSmallGroupId()
    {
        return self::CONST_SMALL_GROUP_ID;
    }

    public function getNoOfTravellersAttributeId()
    {
        return self::CONST_ATTRIBUTE_ID_FOR_NO_OF_TRAVALLERS;
    }

    public function getNoOfKidsAttributeId()
    {
        return self::CONST_ATTRIBUTE_ID_FOR_NO_OF_KIDS;
    }

    public function getDepartureDateAttributeId()
    {
        return self::CONST_ATTRIBUTE_ID_FOR_DEPARTURE_DATE;
    }

    public function getPrivateGroupId()
    {
        return self::CONST_PRIVATE_GROUP_ID;
    }

    public function dd($array,$die=0)
    {
        echo "<pre>";
        print_r($array);
        if($die)
        {
            die;
        }
    }

    public function getProdutAttributeGroupId($product)
    {   
       
        //$attributeSet           = $this->_objectManager->create('Magento\Eav\Api\AttributeSetRepositoryInterface');
        $attributeSetRepository = $this->_attributeSet->get($product->getAttributeSetId());
        $attribute_set_id       = $attributeSetRepository->getAttributeSetId(); // get Attribute ID
        return $attribute_set_id;
    }

    public function getProdutAttributeGroupName($product)
    {   

        //$attributeSet           = $this->_objectManager->create('Magento\Eav\Api\AttributeSetRepositoryInterface');
        $attributeSetRepository = $this->_attributeSet->get($product->getAttributeSetId());
        $attribute_set_name     = $attributeSetRepository->getAttributeSetName(); // get Attribute Name
        return $attribute_set_name;
    }

    public function getCurrentCurrencyPrice($amount = 0, $store = null, $currency = null)
    {   
        $store  =  $this->getCurrentStoreId();//get current store id if store id not get passed
        $rate   = $this->_priceCurrencyObject->convert($amount, $store, $currency); //it return price according to current store from base currency
        return  round($rate);//You can round off to it or you can return it in its original form
    }

    public function getCurrentStoreId()
    {
        return $this->_storeManager->getStore()->getStoreId();
    }
    public function getCurrentCurrencySymbol()
    {
       return $this->_priceCurrencyObject->getCurrencySymbol();
    }

    public function getStockItem($productId)
    {   
        //var_dump($this->_stockItemRepository->get($productId));die;
        //return $this->_stockItemRepository->get($productId);
    }

    public function getFormattedStartAndEndDates($date="", $noOfDays = 0)
    {
        $endDate        = date('D, d M',strtotime($date."$noOfDays days"));
        $startDate      = date('D, d M',strtotime($date));

        return $startDate." - ".$endDate;
    }

    public function getBaseUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl();
    }

    public function getEnableProductStatus()
    {
        return self::CONST_PRODUCT_ENABLE_STATUS;
    }
}