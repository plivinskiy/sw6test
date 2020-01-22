<?php declare(strict_types=1);

namespace Swpa\CustomOptions\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1578259415 extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1578259415;
    }

    public function update(Connection $connection): void
    {
        $columns = $connection->fetchAll('SHOW COLUMNS FROM `product_custom_option_type_value`');
        $exist = false;
        foreach ($columns as $column) {
            if ($column['Field'] === 'dependent') {
                $exist = true;
            }
        }
        if (!$exist) {
            $connection->query("ALTER TABLE `product_custom_option_type_value` ADD COLUMN `dependent` TINYINT(1) NULL DEFAULT '0' COMMENT 'dependent?' AFTER `sku`");
            $connection->query("ALTER TABLE `product_custom_option_type_value` ADD COLUMN `dependence` JSON NULL DEFAULT NULL COMMENT 'dependence' AFTER `dependent`");
        }
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
