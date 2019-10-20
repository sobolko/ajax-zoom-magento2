<?php
namespace Ax\Zoom\Block\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class Update extends Field
{
    /**
     * @var string
     */
    protected $_template = 'Ax_Zoom::system/config/update.phtml';

    /**
     * Remove scope label
     *
     * @param  AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }

    /**
     * Return element html
     *
     * @param  AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        return $this->_toHtml();
    }

    /**
     * Return ajax url for collect button
     *
     * @return string
     */
    public function getDownloadAxZmUrl()
    {
        return $this->getUrl('axzoom/Ajax/DownloadAxZm');
    }

    /**
     * Return ajax url for collect button
     *
     * @return string
     */
    public function getAzAvailVersionUrl()
    {
        return $this->getUrl('axzoom/Ajax/GetAzAvailVersion');
    }

    /**
     * Return ajax url for collect button
     *
     * @return string
     */
    public function getAjaxUrl()
    {
        return $this->getUrl('axzoom/Ajax/ActionUpdate');
    }

    /**
     * Generate collect button html
     *
     * @return string
     */
    public function getButtonHtml()
    {
        $button = $this->getLayout()->createBlock(
            \Magento\Backend\Block\Widget\Button::class
        )->setData(
            [
                'id' => 'axzoom_updateaz',
                'label' => __('Check for available updates'),
            ]
        );

        return $button->toHtml();
    }
}
