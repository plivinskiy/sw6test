<?php declare(strict_types=1);

namespace Swpa\CustomOptions\Setup\Install;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Plugin\Context\InstallContext;

class Schema
{

    /**
     * @var InstallContext
     */
    protected $context;

    /**
     * @var Connection
     */
    protected $connection;

    public function __construct(InstallContext $context, Connection $connection)
    {
        $this->context = $context;
        $this->connection = $connection;
    }

    public function install(): void
    {

    }

}
