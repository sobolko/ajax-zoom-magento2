<?php
namespace Ax\Zoom\Model;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Helper\Image;
use Magento\Framework\Data\Collection;

class Ax360set extends \Magento\Framework\Model\AbstractModel
{
	protected $_res;
	protected $_objectManager;
	protected $_storeManager;

	public function __construct(
			\Magento\Framework\ObjectManagerInterface $objectManager,
			\Magento\Store\Model\StoreManager $storeManager,
			\Magento\Framework\App\ResourceConnection $res,
			\Magento\Framework\Model\Context $context,
			\Magento\Framework\Registry $registry,
			\Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
			\Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
			
			array $data = []
		) {
		$this->_res = $res;
		$this->_objectManager = $objectManager;
		$this->_storeManager = $storeManager;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
	}

    protected function _construct()
    {
        $this->_init('Ax\Zoom\Model\Resource\Ax360set');
    }

    public function getSets($productId)
    {
		$setsCollection = $this->getCollection();
		$tbl_set_group = $this->_res->getTableName('ajaxzoom360');

		$setsCollection->getSelect()->join(array('t2' => $tbl_set_group), 'main_table.id_360 = t2.id_360 AND t2.id_product = ' . $productId, array('t2.name', 't2.status'));
		$sets = $setsCollection->getData();

		$baseDir = BP;
		$baseUrlJs = $this->getBaseUrl();
				
		foreach ($sets as &$set) {
			if (file_exists($baseDir . '/axzoom/pic/360/' . $productId . '/' . $set['id_360'] . '/' . $set['id_360set'])) {
				$set['path'] = $baseUrlJs . 'axzoom/axZm/zoomLoad.php?qq=1&azImg360=' . $this->rootFolder() . 'axzoom/pic/360/' . $productId . '/' . $set['id_360'] . '/' . $set['id_360set'] . '&width=100&height=100&thumbMode=contain';
			} else {
				$set['path'] = $baseUrlJs . 'axzoom/no_image-100x100.jpg';
			}
		}

		return $sets;
    }

    public function rootFolder()
    {
        $p = parse_url($this->getBaseUrl());
        return str_replace('index.php/', '', $p['path']);
    }

    public function getBaseUrl()
    {
    	return $this->_storeManager->getStore()->getBaseUrl();
    }    

    public function getBaseDir()
    {
    	return BP;
    }   
}