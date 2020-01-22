<?php declare(strict_types=1);

namespace Swpa\CustomOptions\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1576348086 extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1576348086;
    }

    public function update(Connection $connection): void
    {
        $connection->query("ALTER TABLE `product_custom_option`
        CHANGE COLUMN `is_require` `is_require` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Is Required' COLLATE 'utf8mb4_unicode_ci' AFTER `active`;");
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
