<?php declare(strict_types=1);

namespace Swpa\CustomOptions\DAL;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class CustomEntity extends Entity
{

    use EntityIdTrait;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var bool
     */
    protected $isRequired;

    /**
     * @var int
     */
    protected $sortOrder;

    /**
     * @var ProductEntity
     */
    protected $product;

    /**
     * @var OptionValueEntityCollection
     */
    protected $values;

    /**
     * @var OptionTranslationCollection
     */
    protected $translations;

}
