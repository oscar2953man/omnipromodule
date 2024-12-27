<?php
namespace Omnipro\QuickProductPositioning\Controller\Adminhtml\Position;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    protected $resultPageFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Omnipro_QuickProductPositioning::main_menu'); 
        $resultPage->addBreadcrumb(__('Quick Product Positioning'), __('Quick Product Positioning'));
        $resultPage->addBreadcrumb(__('Manage Positions'), __('Manage Positions'));
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Positions'));
        return $resultPage;
    }
}