<?php declare(strict_types=1);

namespace Swpa\CustomOptions\Setup;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;

class Uninstall
{

    /**
     * @var Connection
     */
    private $connection;

    /**
     * Uninstall constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function uninstall(UninstallContext $context): void
    {

    }
}
