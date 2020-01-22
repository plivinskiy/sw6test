<?php declare(strict_types=1);

namespace Swpa\CustomOptions\Setup\Activate;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Plugin\Context\ActivateContext;

class Schema
{

    /**
     * @var ActivateContext
     */
    protected $context;

    /**
     * @var Connection
     */
    protected $connection;

    public function __construct(ActivateContext $context, Connection $connection)
    {
        $this->context = $context;
        $this->connection = $connection;
    }

    public function activate(): void
    {

    }
}
