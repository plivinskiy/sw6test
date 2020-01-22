<?php declare(strict_types=1);

namespace Swpa\CustomOptions\Setup;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Plugin\Context\ActivateContext;

class Activate
{

    /**
     * @var Connection
     */
    private $connection;

    /**
     * Activate constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function activate(ActivateContext $context): void
    {

    }
}
