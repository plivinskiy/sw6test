<?php declare(strict_types=1);

namespace Swpa\CustomOptions\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1571524609 extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1571524609;
    }

    /**
     * Create database structure
     *
     * @param Connection $connection
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function update(Connection $connection): void
    {
        $connection->query("CREATE TABLE IF NOT EXISTS `product_custom_option` (
        	`id` BINARY(16) NOT NULL COMMENT 'Option ID',
        	`product_id` BINARY(16) NOT NULL COMMENT 'Product ID',
        	`type` VARCHAR(50) NULL DEFAULT NULL COMMENT 'Type',
        	`is_require` VARCHAR(1) NOT NULL DEFAULT '1' COMMENT 'Is Required',
        	`sort_order` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Sort Order',
            `created_at` DATETIME(3) NULL,
            `updated_at` DATETIME(3) NULL,
        	PRIMARY KEY (`id`),
        	INDEX `IDX_OPTION_PRODUCT_ID` (`product_id`),
        	CONSTRAINT `FK_OPT_PRD_ID_CAT_PRD_ID` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
        ) COMMENT='Option Table' COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB");

        $connection->query("CREATE TABLE IF NOT EXISTS `product_custom_option_translation` (
        	`product_custom_option_id` BINARY(16) NOT NULL COMMENT 'Option ID',
        	`language_id` BINARY(16) NOT NULL COMMENT 'Language ID',
        	`title` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Title',
            `created_at` DATETIME(3) NULL,
            `updated_at` DATETIME(3) NULL,
        	PRIMARY KEY (`product_custom_option_id`,`language_id`),
        	CONSTRAINT `FK_OPT_TRANSLATION_ID_OPTION_ID` FOREIGN KEY (`product_custom_option_id`) REFERENCES `product_custom_option` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
        	CONSTRAINT `FK_OPT_TRANSLATION_ID_LANGUAGE_ID` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
        ) COMMENT='Option Translation Table' COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB");

        $connection->query("CREATE TABLE IF NOT EXISTS `product_custom_option_type_value` (
        	`id` BINARY(16) NOT NULL COMMENT 'Option Type ID',
        	`option_id` BINARY(16) NOT NULL COMMENT 'Option ID',
        	`price` DECIMAL(12,4) NOT NULL DEFAULT '0.0000' COMMENT 'Price',
        	`type` VARCHAR(10) NOT NULL DEFAULT 'fixed' COMMENT 'Price Type',
        	`sku` VARCHAR(64) NULL DEFAULT NULL COMMENT 'SKU',
        	`sort_order` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Sort Order',
            `created_at` DATETIME(3) NULL,
            `updated_at` DATETIME(3) NULL,
        	PRIMARY KEY (`id`),
        	INDEX `IDX_PRODUCT_OPTION_TYPE_VALUE_OPTION_ID` (`option_id`),
        	CONSTRAINT `FK_PRD_OPT_TYPE_VAL_OPT_ID_PRD_OPT_OPT_ID` FOREIGN KEY (`option_id`) REFERENCES `product_custom_option` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
        ) COMMENT='Product Option Value Table' COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB");

        $connection->query("CREATE TABLE IF NOT EXISTS `product_custom_option_type_value_translation` (
        	`product_custom_option_type_value_id` BINARY(16) NOT NULL COMMENT 'Value ID',
        	`language_id` BINARY(16) NOT NULL COMMENT 'Language ID',
        	`title` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Title',
            `created_at` DATETIME(3) NULL,
            `updated_at` DATETIME(3) NULL,
        	PRIMARY KEY (`product_custom_option_type_value_id`,`language_id`),
        	CONSTRAINT `FK_VALUE_TRANSLATION_ID_OPTION_ID` FOREIGN KEY (`product_custom_option_type_value_id`) REFERENCES `product_custom_option_type_value` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
        	CONSTRAINT `FK_VALUE_TRANSLATION_ID_LANGUAGE_ID` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
        ) COMMENT='Product Option Value Translation' COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB");

    }

    public function updateDestructive(Connection $connection): void
    {

    }
}
