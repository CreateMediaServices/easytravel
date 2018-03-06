<?php 
namespace Easytravel\Tailor\Controller\Index; 

class Index extends \Magento\Framework\App\Action\Action {
   
   /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
	protected $_coreRegistry;

	/** @var  \Magento\Framework\View\Result\Page */
    protected $_resultPageFactory;

    protected $_coreHelperFunction;
    
	/**      
		* @param \Magento\Framework\App\Action\Context $context
	*/
    public function __construct
	(
		\Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Easytravel\Core\Helper\Functions $coreHelperFunction
	)
	{
		$this->_coreRegistry 		= $coreRegistry;
		$this->_resultPageFactory	= $resultPageFactory;
        $this->_coreHelperFunction  = $coreHelperFunction;

        parent::__construct($context);
    }

    /**
     * Blog Index, shows a list of recent blog posts.
     *
     * @return \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        if(isset($_POST['btn_submit']))
        {
            $full_name          = $_POST['full_name'];
            $natianality        = $_POST['natianality'];
            $email              = $_POST['email'];
            $phone              = $_POST['phone'];
            $trip_start_day     = $_POST['trip_start_day'];
            $trip_start_month   = $_POST['trip_start_month'];
            $trip_start_year    = $_POST['trip_start_year'];
            $trip_days          = $_POST['trip_days'];
            $trip_adults        = $_POST['trip_adults'];
            $trip_children      = $_POST['trip_children'];

            $aErrorMessage = array();
            $showErrorMessage = '';

            if(!$this->_coreHelperFunction->valueIsSet($full_name))
            {
                $aErrorMessage[] = 'Full name is required!';
            }

            if(!$this->_coreHelperFunction->valueIsSet($natianality))
            {
                $aErrorMessage[] = 'Natianality is required!';
            }

            if(!$this->_coreHelperFunction->valueIsSet($email))
            {
                $aErrorMessage[] = 'Email is required!';
            }

            if(!$this->_coreHelperFunction->valueIsSet($phone))
            {
                $aErrorMessage[] = 'Phone number is required!';
            }

            if(is_array($aErrorMessage) && count($aErrorMessage))
            {
                $showErrorMessage = $this->_coreHelperFunction->getFormValidationErrorMessage($aErrorMessage);
                $this->_coreRegistry->register('showErrorMessage', $showErrorMessage);
            }
            else
            {
                $this->messageManager->addSuccessMessage('Validation passed, move to next step for this form!');

                $urlToRedirect = $this->_coreHelperFunction->getUrlForTailorMadeTour();
                $this->_redirect($urlToRedirect);
            }
        }


		$resultPage = $this->_resultPageFactory->create();

		$resultPage->getConfig()->getTitle()->set(__('Tailor Made Tours'));

    	return $resultPage;
	}
}