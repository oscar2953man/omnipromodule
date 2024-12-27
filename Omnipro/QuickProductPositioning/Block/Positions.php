<?php
namespace Omnipro\QuickProductPositioning\Block;

use Magento\Framework\View\Element\Template;
use Omnipro\QuickProductPositioning\Helper\Data as HelperData;

class Positions extends Template
{
    protected $helperData;

    public function __construct(
        Template\Context $context,
        HelperData $helperData,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->helperData = $helperData;
    }

    public function getPositions()
    {
        return $this->helperData->getPositions();
    }
}