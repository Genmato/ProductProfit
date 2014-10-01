<?php

/**
 * @category    Genmato
 * @package     Genmato_ProductProfit
 * @copyright   Copyright (c) 2013 Genmato BV (http://genmato.com)
 */
class Genmato_ProductProfit_Model_Observer
{

    public function addColumn(Mage_Adminhtml_Block_Catalog_Product_Grid $block, $column_id, $title, $after)
    {
        $block->addColumnAfter(
            $column_id,
            array(
                'header' => Mage::helper('genmato_productprofit')->__($title),
                'width' => '50px',
                'type' => 'number',
                'index' => $column_id,
            ),
            $after
        );
        $block->sortColumnsByOrder();

        if ($block->getCollection()) {
            $block->getCollection()->addAttributeToSelect($column_id);
        }

        $filter = $block->getParam($block->getVarNameFilter(), null);
        $column = $block->getColumn($column_id);
        if (is_string($filter)) {
            $filter = $block->helper('adminhtml')->prepareFilterString($filter);
        } else {
            if ($filter && is_array($filter)) {
            } else {
                //   if (0 !== sizeof($this->_defaultFilter)) {
                //       $filter = $this->_defaultFilter;
                //   }
            }
        }

        if (isset ($filter [$column_id]) && (!empty ($filter [$column_id]) || strlen(
                    $filter [$column_id]
                ) > 0) && $column->getFilter()
        ) {

            $column->getFilter()->setValue($filter [$column_id]);

            if ($block->getCollection()) {
                $field = ($column->getFilterIndex()) ? $column->getFilterIndex() : $column->getIndex();
                if ($column->getFilterConditionCallback()) {
                    call_user_func($column->getFilterConditionCallback(), $block->getCollection(), $column);
                } else {
                    $cond = $column->getFilter()->getCondition();
                    if ($field && isset ($cond)) {
                        $block->getCollection()->addFieldToFilter($field, $cond);
                    }
                }
            }
        }
    }

    public function onEavLoadBefore(Varien_Event_Observer $observer)
    {
        $collection = $observer->getCollection();
        if (!isset ($collection)) {
            return;
        }

        if (is_a($collection, 'Mage_Catalog_Model_Resource_Product_Collection')) {

            if (($productListBlock = Mage::app()->getLayout()
                    ->getBlock(
                        'products_list'
                    )) != false && ($productListBlock instanceof Mage_Adminhtml_Block_Catalog_Product)
            ) {
                $this->addColumn($productListBlock->getChild('grid'), 'product_profit', 'Profit', 'price');
                $this->addColumn(
                    $productListBlock->getChild('grid'),
                    'product_profit_ratio',
                    'Profit Ratio',
                    'product_profit'
                );
            } else {
                if (($block = Mage::app()->getLayout()->getBlock('admin.product.grid')) != false) {
                    $this->addColumn($block, 'product_profit', 'Profit', 'price');
                    $this->addColumn($block, 'product_profit_ratio', 'Profit Ratio', 'product_profit');
                }
            }
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