<?php declare(strict_types=1);

namespace Swpa\CustomOptions\Setup;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Plugin\Context\DeactivateContext;

class Deactivate
{

    /**
     * @var Connection
     */
    private $connection;

    /**
     * Deactivate constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function deactivate(DeactivateContext $context): void
    {

    }
}
