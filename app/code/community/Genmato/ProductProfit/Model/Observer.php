<?php

/**
 * @category    Genmato
 * @package     Genmato_ProductProfit
 * @copyright   Copyright (c) 2013 Genmato BV (http://genmato.com)
 */
class Genmato_ProductProfit_Model_Observer
{

    public function beforeBlockToHtml(Varien_Event_Observer $observer)
    {
        $grid = $observer->getBlock();

        /**
         * Mage_Adminhtml_Block_Catalog_Product_Grid
         */
        if ($grid instanceof Mage_Adminhtml_Block_Catalog_Product_Grid) {
            $grid->addColumnAfter(
                'product_profit',
                array(
                    'header' => Mage::helper('genmato_productprofit')->__('Profit'),
                    'index' => 'product_profit',
                    'type' => 'number'
                ),
                'price'
            );

            $grid->addColumnAfter(
                'product_profit_ratio',
                array(
                    'header' => Mage::helper('genmato_productprofit')->__('Profit Ratio'),
                    'index' => 'product_profit_ratio',
                    'type' => 'number'
                ),
                'product_profit'
            );
        }
    }

    public function beforeCollectionLoad(Varien_Event_Observer $observer)
    {
        $collection = $observer->getCollection();
        if (!isset($collection)) {
            return;
        }

        /**
         * Mage_Catalog_Model_Resource_Product_Collection
         */
        if ($collection instanceof Mage_Catalog_Model_Resource_Product_Collection) {
            /* @var $collection Mage_Catalog_Model_Resource_Product_Collection */
            $collection->addAttributeToSelect('product_profit');
            $collection->addAttributeToSelect('product_profit_ratio');
        }
    }

    public function onProductCollectionLoadAfter(Varien_Event_Observer $observer)
    {
        $collection = $observer->getEvent()->getCollection();
        if (!isset ($collection)) {
            return;
        }
        return;
        foreach ($collection as $object) {
            $cost = $object->getCost();
            $price = $object->getFinalPrice();

            if ($cost > 0) {
                $profit = $price - $cost;
                $object->setData('product_profit', $profit);
                $ratio = ($profit / $price) * 100;
                $object->setData('product_profit_ratio', $ratio);
                Mage::log($object->getData());
            }
        }

        return $this;
    }

}