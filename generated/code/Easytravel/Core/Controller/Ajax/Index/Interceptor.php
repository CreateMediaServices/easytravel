<?php
namespace Easytravel\Core\Controller\Ajax\Index;

/**
 * Interceptor class for @see \Easytravel\Core\Controller\Ajax\Index
 */
class Interceptor extends \Easytravel\Core\Controller\Ajax\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Framework\Registry $coreRegistry, \Easytravel\Core\Helper\Functions $coreHelperFunction, \Magento\Catalog\Model\ProductFactory $_productLoader, \Magento\CatalogInventory\Api\StockStateInterface $stockState, \Magento\Checkout\Model\Cart $cart, \Magento\Checkout\Model\Session $checkoutSession, \Magento\Checkout\Model\Sidebar $sidebar, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory)
    {
        $this->___init();
        parent::__construct($context, $resultPageFactory, $coreRegistry, $coreHelperFunction, $_productLoader, $stockState, $cart, $checkoutSession, $sidebar, $resultJsonFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'execute');
        if (!$pluginInfo) {
            return parent::execute();
        } else {
            return $this->___callPlugins('execute', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getProductOptions()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getProductOptions');
        if (!$pluginInfo) {
            return parent::getProductOptions();
        } else {
            return $this->___callPlugins('getProductOptions', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function checkStockAndCart()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'checkStockAndCart');
        if (!$pluginInfo) {
            return parent::checkStockAndCart();
        } else {
            return $this->___callPlugins('checkStockAndCart', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function updateCart()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'updateCart');
        if (!$pluginInfo) {
            return parent::updateCart();
        } else {
            return $this->___callPlugins('updateCart', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getProductInAssociatedProducts($associatedProducts = array(), $attribute_set_id = 0, $params = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getProductInAssociatedProducts');
        if (!$pluginInfo) {
            return parent::getProductInAssociatedProducts($associatedProducts, $attribute_set_id, $params);
        } else {
            return $this->___callPlugins('getProductInAssociatedProducts', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function response($result = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'response');
        if (!$pluginInfo) {
            return parent::response($result);
        } else {
            return $this->___callPlugins('response', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getParams()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getParams');
        if (!$pluginInfo) {
            return parent::getParams();
        } else {
            return $this->___callPlugins('getParams', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getLastInsertedItemIdInCart()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getLastInsertedItemIdInCart');
        if (!$pluginInfo) {
            return parent::getLastInsertedItemIdInCart();
        } else {
            return $this->___callPlugins('getLastInsertedItemIdInCart', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isProductInCart($parentProductId, $departureDate, $productGroup)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isProductInCart');
        if (!$pluginInfo) {
            return parent::isProductInCart($parentProductId, $departureDate, $productGroup);
        } else {
            return $this->___callPlugins('isProductInCart', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function loadProduct($productId = 0)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'loadProduct');
        if (!$pluginInfo) {
            return parent::loadProduct($productId);
        } else {
            return $this->___callPlugins('loadProduct', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAssociatedProducts($product)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAssociatedProducts');
        if (!$pluginInfo) {
            return parent::getAssociatedProducts($product);
        } else {
            return $this->___callPlugins('getAssociatedProducts', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getProductAttributeValue($product, $attributeName = '')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getProductAttributeValue');
        if (!$pluginInfo) {
            return parent::getProductAttributeValue($product, $attributeName);
        } else {
            return $this->___callPlugins('getProductAttributeValue', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeItemFromCart($itemId = 0)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'removeItemFromCart');
        if (!$pluginInfo) {
            return parent::removeItemFromCart($itemId);
        } else {
            return $this->___callPlugins('removeItemFromCart', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'dispatch');
        if (!$pluginInfo) {
            return parent::dispatch($request);
        } else {
            return $this->___callPlugins('dispatch', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getActionFlag()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getActionFlag');
        if (!$pluginInfo) {
            return parent::getActionFlag();
        } else {
            return $this->___callPlugins('getActionFlag', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRequest()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getRequest');
        if (!$pluginInfo) {
            return parent::getRequest();
        } else {
            return $this->___callPlugins('getRequest', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getResponse');
        if (!$pluginInfo) {
            return parent::getResponse();
        } else {
            return $this->___callPlugins('getResponse', func_get_args(), $pluginInfo);
        }
    }
}
