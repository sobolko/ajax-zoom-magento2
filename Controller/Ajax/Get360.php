<?php
namespace Ax\Zoom\Controller\Ajax;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Catalog\Model\Product;
use Ax\Zoom\Model\Ax360;

class Get360 extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Swatches\Helper\Data
     */
    protected $swatchHelper;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $productModelFactory;

    protected $Ax360;
    protected $Ax360set;

    /**
     * @param Context $context
     * @param \Magento\Swatches\Helper\Data $swatchHelper
     * @param \Magento\Catalog\Model\ProductFactory $productModelFactory
     * @param \Ax\Zoom\Model\Ax360 $Ax360
     * @param \Ax\Zoom\Model\Ax360set $Ax360set
     */
    public function __construct(
        Context $context,
        \Magento\Swatches\Helper\Data $swatchHelper,
        \Magento\Catalog\Model\ProductFactory $productModelFactory,
        \Ax\Zoom\Model\Ax360 $Ax360,
        \Ax\Zoom\Model\Ax360set $Ax360set
    ) {
        $this->swatchHelper = $swatchHelper;
        $this->productModelFactory = $productModelFactory;
        $this->Ax360 = $Ax360;
        $this->Ax360set = $Ax360set;

        parent::__construct($context);
    }

    public function execute()
    {
        $result = [];

        if ($productId = (int)$this->getRequest()->getParam('product_id')) {
            $attributes = (array)$this->getRequest()->getParam('attributes');

            $combination_id = $this->getCombinationIdByAttributes($productId, $attributes);
            
            $extraGroups = array();
            $setsGroups = $this->Ax360->getSetsGroups($combination_id);
            if($setsGroups) foreach($setsGroups as $group) array_push($extraGroups, $group['id_360']);

            $str = $this->Ax360->images360Json($productId, $extraGroups, $combination_id);
            $result = array(
                'id' => $combination_id,
                'images360json' => str_replace("'", '"', $str)
                );
        }

        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setData($result);
        return $resultJson;
    }

    public function getCombinationIdByAttributes($productId, $attributes)
    {
        $currentConfigurable = $this->productModelFactory->create()->load($productId);

        $resultAttributes = $attributes;
        $product = $this->swatchHelper->loadVariationByFallback($currentConfigurable, $resultAttributes);
        if (!$product || (!$product->getImage() || $product->getImage() == 'no_selection')) {
            $product = $this->swatchHelper->loadFirstVariationWithImage(
                $currentConfigurable,
                $resultAttributes
            );
        }        
        $data = $product->getData();
        return $data['entity_id'];
    }
}