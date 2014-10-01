<?php

$installer = $this;
$installer->startSetup();

$installer->addAttribute(
    'catalog_product',
    'product_profit',
    array(
        'group' => 'Prices',
        'type' => 'decimal',
        'label' => 'Product Profit',
        'class' => '',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'backend' => 'genmato_productprofit/entity_attribute_backend_profit',
        'visible' => true,
        'required' => false,
        'user_defined' => false,
        // this is what determines if it will be a system attribute or not
        'default' => '',
        'searchable' => false,
        'filterable' => false,
        'comparable' => false,
        'visible_on_front' => false,
        'unique' => false
    )
);

$installer->addAttribute(
    'catalog_product',
    'product_profit_ratio',
    array(
        'group' => 'Prices',
        'type' => 'decimal',
        'label' => 'Product Profit Ratio',
        'class' => '',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'backend' => 'genmato_productprofit/entity_attribute_backend_profit_ratio',
        'visible' => true,
        'required' => false,
        'user_defined' => false,
        // this is what determines if it will be a system attribute or not
        'default' => '',
        'searchable' => false,
        'filterable' => false,
        'comparable' => false,
        'visible_on_front' => false,
        'unique' => false
    )
);

$installer->endSetup();
