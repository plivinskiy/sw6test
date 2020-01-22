<?php declare(strict_types=1);

namespace Swpa\CustomOptions\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1576348573 extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1576348573;
    }

    public function update(Connection $connection): void
    {
        $connection->query("ALTER TABLE `product_custom_option`
        	CHANGE COLUMN `sort_order` `sort_order` INT(10) UNSIGNED NULL COMMENT 'Sort Order' AFTER `is_require`;");
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
