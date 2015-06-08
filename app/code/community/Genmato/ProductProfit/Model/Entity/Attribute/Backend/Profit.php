<?php

/**
 * @category    Genmato
 * @package     Genmato_ProductProfit
 * @copyright   Copyright (c) 2013 Genmato BV (http://genmato.com)
 */
class Genmato_ProductProfit_Model_Entity_Attribute_Backend_Profit extends Mage_Eav_Model_Entity_Attribute_Backend_Abstract
{

    public function afterLoad($object)
    {
        $cost = $object->getCost();

        if ($cost > 0) {
            $finalPrice = $object->getFinalPrice();
            $taxHelper = Mage::helper('tax');
            $price = $taxHelper->getPrice($object, $finalPrice, false);

            $profit = $price - $cost;
            $object->setProductProfit($profit);
        }

        return $this;
    }

    public function beforeSave($object)
    {
        $cost = $object->getCost();

        if ($cost > 0) {
            $finalPrice = $object->getFinalPrice();
            $taxHelper = Mage::helper('tax');
            $price = $taxHelper->getPrice($object, $finalPrice, false);

            $profit = $price - $cost;
            $object->setProductProfit($profit);
        }

        return $this;
    }

}