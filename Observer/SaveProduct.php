<?php
namespace Ax\Zoom\Observer;

use Magento\Framework\App\RequestInterface;

class SaveProduct implements \Magento\Framework\Event\ObserverInterface
{
    protected $request;
    protected $Ax360;
    protected $Ax360set;
    protected $Axproducts;
    protected $_resource;

    /**
     * @param RequestInterface $request
     */
    public function __construct(
        RequestInterface $request,
        \Ax\Zoom\Model\Ax360 $Ax360,
        \Ax\Zoom\Model\Ax360set $Ax360set,
        \Ax\Zoom\Model\Axproducts $Axproducts,
        \Magento\Framework\App\ResourceConnection $resource
    ) {
        $this->request = $request;
        $this->_resource = $resource;
        $this->Ax360 = $Ax360;
        $this->Ax360set = $Ax360set;
        $this->Axproducts = $Axproducts;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        try {
            $this->save360($observer);
        } catch (\Exception $e) {
            //$this->_helper->debug((string)$e, array());
            $a = 1;
        }
    }

    public function save360($observer)
    {
        $object = $observer->getEvent()->getDataObject();
        $productId = $object->getId();
        
        $postData = $this->request->getPost();
        //$post = $this->request->getParams();

        /*
        // remove images from Ax cache if image checked as remove
        $images = Mage::helper('core')->jsonDecode($postData['product']['media_gallery']['images']);
        foreach ($images as $image) {
            if(isset($image['removed']) && $image['removed'] == 1) {
                Mage::getModel('axzoom/ax360')->deleteImageAZcache(basename($image['file']));
            }
        }
        */

        /* AZ: this part moved to the ajax action as post variables does not passed
        // save status
        if (isset($postData['az_active'])) {
            if ($postData['az_active'] == 1) {
                $this->activateAx($productId);
            } else {
                $this->Axproducts->setData(array('id_product' => $productId))->save();
            }
        }
        */

        // save settings
        if (isset($postData['settings'])) {
            foreach ($postData['settings'] as $id_360 => $string) {
                $this->Ax360->load($id_360)->addData([
                'settings' => urldecode($string),
                'combinations' => urldecode($postData['comb'][$id_360])
                ])->setId($id_360)->save();
            }
        }
    }

    public function activateAx($productId)
    {
        $con = $this->_resource->getConnection();
        $table = 'ajaxzoomproducts';
        $query = "DELETE FROM {$table} WHERE id_product = " . (int)$productId;
        $con->query($query);
    }
}
