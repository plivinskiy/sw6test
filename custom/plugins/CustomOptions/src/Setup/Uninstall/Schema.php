<?php declare(strict_types=1);

namespace Swpa\CustomOptions\Setup\Uninstall;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;

class Schema
{

    /**
     * @var UninstallContext
     */
    protected $context;

    /**
     * @var Connection
     */
    protected $connection;

    public function __construct(UninstallContext $context, Connection $connection)
    {
        $this->context = $context;
        $this->connection = $connection;
    }

    public function uninstall(): void
    {

    }
}
