<?php 
namespace Easytravel\Core\Controller\Index; 

class Index extends \Magento\Framework\App\Action\Action {
   
   /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
	protected $_coreRegistry;

	/** @var  \Magento\Framework\View\Result\Page */
    protected $_resultPageFactory;
    
	/**      
		* @param \Magento\Framework\App\Action\Context $context
	*/
    public function __construct
	(
		\Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $coreRegistry
	)
	{
		$this->_coreRegistry 		= $coreRegistry;
		$this->_resultPageFactory	= $resultPageFactory;

        parent::__construct($context);
    }

    /**
     * Blog Index, shows a list of recent blog posts.
     *
     * @return \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        echo 'EasyTravel Core!';
        exit;
	}
}