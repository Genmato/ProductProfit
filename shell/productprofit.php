<?php

require_once 'abstract.php';

class Genmato_Shell_ProductProduct extends Mage_Shell_Abstract
{

    /**
     * Run script
     *
     */
    public function run()
    {
        Mage::app()->setCurrentStore(1);
        $collection = Mage::getResourceModel('catalog/product_collection')
            ->addAttributeToFilter('cost', array('gt'=>0))
        ;

        $productModel = Mage::getModel('catalog/product');
        foreach ($collection as $prod) {
            $product = $productModel->load($prod->getId());
            printf('Updating product: %s'.PHP_EOL, $product->getSku());
            $product->setData('force_profit_calculation', true);
            $product->save();
            $product->clearInstance();
        }
    }

    /**
     * Retrieve Usage Help Message
     *
     */
    public function usageHelp()
    {
        return <<<USAGE
Usage:  php -f productprofit.php -- [options]

  help          This help

USAGE;
    }
}

$shell = new Genmato_Shell_ProductProduct();
$shell->run();
