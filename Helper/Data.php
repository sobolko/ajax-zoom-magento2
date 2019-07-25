<?php
namespace Ax\Zoom\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    public function getConfig($config_path)
    {
        return $this->scopeConfig->getValue(
            $config_path,
            'default'  /*\Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE*/ /*SCOPE_STORE*/
        );
    }
}
