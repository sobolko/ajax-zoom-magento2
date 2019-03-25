<?php
namespace Ax\Zoom\Block\Adminhtml\Product;

class Resizefix extends \Magento\Backend\Block\Template
{

    public function __construct(\Magento\Backend\Block\Template\Context $context, array $data = [])
    {
        parent::__construct($context, $data);
        
        if($this->_scopeConfig->getValue('axzoom_options/magento/magentoNoScale', \Magento\Store\Model\ScopeInterface::SCOPE_STORE) == 'true') {
            $this->setTemplate('resizefix.phtml');
        }
    }    
    
}