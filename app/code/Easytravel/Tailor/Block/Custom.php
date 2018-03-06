<?php 
namespace Easytravel\Tailor\Block;

use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template\Context;

class Custom extends \Magento\Framework\View\Element\Template
{
	 /**
     * @var Registry
     */
    protected $coreRegistry;
	
	public $blockData;

	/**
     * @param Context $context
     * @param Registry $registry
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        array $data = []
    )
	{
        $this->coreRegistry 	   = $registry;

        parent::__construct($context, $data);
    }
	
	public function _prepareLayout()
	{  
        parent::_prepareLayout();
    
        return $this;
	}

	public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
    
	public function setBlockData($blockData)
	{
		$this->blockData = $blockData;
	}
	
	public function getBlockData()
    {
		return $this->blockData;
    }

    public function getErrorMessage()
    {
        return $this->coreRegistry->registry('showErrorMessage');
    }
}