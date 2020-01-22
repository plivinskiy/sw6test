<?php declare(strict_types=1);

namespace Swpa\CustomOptions\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1576336722 extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1576336722;
    }

    public function update(Connection $connection): void
    {
        $columns = $connection->fetchAll('SHOW COLUMNS FROM `product_custom_option`');
        $exist = false;
        foreach ($columns as $column) {
            if ($column['Field'] === 'active') {
                $exist = true;
            }
        }
        if (!$exist) {
            $connection->query("ALTER TABLE `product_custom_option` ADD COLUMN `active` TINYINT(1) NULL DEFAULT '0' COMMENT 'Status' AFTER `type`");
        }
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }

}
