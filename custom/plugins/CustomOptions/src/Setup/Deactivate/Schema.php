<?php declare(strict_types=1);

namespace Swpa\CustomOptions\Setup\Deactivate;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Plugin\Context\DeactivateContext;

class Schema
{

    /**
     * @var DeactivateContext
     */
    protected $context;

    /**
     * @var Connection
     */
    protected $connection;

    public function __construct(DeactivateContext $context, Connection $connection)
    {
        $this->context = $context;
        $this->connection = $connection;
    }

    public function deactivate(): void
    {

    }
}
