<?php

namespace EMP\Emailplatform\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $upgrade = $setup;

        $upgrade->startSetup();
        $upgrade->getConnection()->addColumn(
            $upgrade->getTable('quote'),
            'newsletter_subscribe',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                'unsigned' => true,
                'nullable' => false,
                'default' => '0',
                'comment' => 'Newsletter Subscribe'
            ]
        );

        $upgrade->endSetup();
    }
}
