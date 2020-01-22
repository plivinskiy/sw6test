<?php declare(strict_types=1);

namespace Swpa\CustomOptions\Setup;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Swpa\CustomOptions\Setup\Install\Schema;

class Install
{

    /**
     * @var Connection
     */
    private $connection;

    /**
     * Install constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function install(InstallContext $context): void
    {

        $schema = new Schema($context, $this->connection);
        $schema->install();
    }
}
