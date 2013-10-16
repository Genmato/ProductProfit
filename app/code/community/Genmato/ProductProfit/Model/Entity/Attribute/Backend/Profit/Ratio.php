<?php
/**
 * @category    Genmato
 * @package     Genmato_ProductProfit
 * @copyright   Copyright (c) 2013 Genmato BV (http://genmato.com)
 */

class Genmato_ProductProfit_Model_Entity_Attribute_Backend_Profit_Ratio extends Mage_Eav_Model_Entity_Attribute_Backend_Abstract {

    public function beforeSave($object) {
        $cost = $object->getCost();

        $price = $object->getFinalPrice();

        if ($cost > 0) {
            $profit = $price - $cost;
            $ratio  = ($profit / $price) * 100;
            $object->setProductProfitRatio($ratio);
        }

        return $this;
    }

}