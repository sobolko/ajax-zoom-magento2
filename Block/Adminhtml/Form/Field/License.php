<?php
namespace Ax\Zoom\Block\Adminhtml\Form\Field;

class License extends \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
{
    /**
     * Prepare to render
     *
     * @return void
     */
    protected function _prepareToRender()
    {
        $this->addColumn('domain', [
            'label' => __('Domain'),
            'style' => 'width:200px'
        ]);
        $this->addColumn('type', [
            'label' => __('License Type'),
            'style' => 'width:200px'
        ]);

        $this->addColumn('license', [
            'label' => __('License Key'),
            'style' => 'width:200px'
        ]);

        $this->addColumn('error200', [
            'label' => __('Error200'),
            'style' => 'width:100px'
        ]);

        $this->addColumn('error300', [
            'label' => __('Error300'),
            'style' => 'width:100px'
        ]);
 
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }

    public function renderCellTemplate($columnName)
    {
        if ($columnName == 'type') {
            $el = $this->getElement();
            
            $inputName = $this->_getCellInputElementName($columnName);
            $inputId = $this->_getCellInputElementId('<%- _id %>', $columnName);
            $rendered = '<select id="' . $inputId . '" name="' . $inputName . '">';
            $rendered .= '<option value="evaluation" #{option_extra_attr_evaluation}>evaluation</option>';
            $rendered .= '<option value="developer" #{option_extra_attr_developer}>developer</option>';
            $rendered .= '<option value="basic" #{option_extra_attr_basic}>basic</option>';
            $rendered .= '<option value="simple" #{option_extra_attr_simple}>simple</option>';
            $rendered .= '<option value="standard" #{option_extra_attr_standard}>standard</option>';
            $rendered .= '<option value="business" #{option_extra_attr_business}>business</option>';
            $rendered .= '<option value="corporate" #{option_extra_attr_corporate}>corporate</option>';
            $rendered .= '<option value="enterprise" #{option_extra_attr_enterprise}>enterprise</option>';
            $rendered .= '<option value="unlimited" #{option_extra_attr_unlimited}>unlimited</option>';
            $rendered .= '</select>';
            
            return $rendered;
        }
        return parent::renderCellTemplate($columnName);
    }

    /**
     * Prepare existing row data object
     *
     * @param \Magento\Framework\DataObject $row
     * @return void
     */
    protected function _prepareArrayRow(\Magento\Framework\DataObject $row)
    {
        $row->setData(
            'option_extra_attr_' . $row->getData('type'),
            'selected="selected"'
        );
    }
}
