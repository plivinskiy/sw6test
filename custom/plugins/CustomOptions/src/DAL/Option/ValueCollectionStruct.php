<?php declare(strict_types=1);

namespace Swpa\CustomOptions\DAL\Option;

use Shopware\Core\Framework\Struct\Struct;

class ValueCollectionStruct extends Struct
{

    /**
     * @var array
     */
    private $collection;

    public function setCollection(array $options): void
    {
        $this->collection = $options;
    }

    public function getCollection(): ?array
    {

        return $this->collection;
    }
}
