<?php declare(strict_types=1);

namespace Swpa\CustomOptions\DAL\Option;

use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use Swpa\CustomOptions\DAL\Option\Aggregate\OptionPrice\OptionPriceEntityCollection;
use Swpa\CustomOptions\DAL\Option\Aggregate\OptionTranslation\OptionTranslationCollection;
use Swpa\CustomOptions\DAL\OptionValue\OptionValueEntityCollection;

class OptionEntity extends Entity
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

    public function getProductId()
    {

        return $this->product->getId();
    }

    public function getValues(): ?OptionValueEntityCollection
    {

        return $this->values;
    }

    public function setValues(OptionValueEntityCollection $values): void
    {

        $this->values = $values;
    }

    public function getTranslations(): ?OptionTranslationCollection
    {

        return $this->translations;
    }

    public function setTranslations(?OptionTranslationCollection $translations)
    {

        $this->translations = $translations;
    }

    /**
     * @return string
     */
    public function getType(): ?string
    {

        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return bool
     */
    public function isRequired(): ?bool
    {

        return $this->isRequired;
    }

    /**
     * @param bool $isRequired
     */
    public function setIsRequired(bool $isRequired): void
    {
        $this->isRequired = $isRequired;
    }

    /**
     * @return int
     */
    public function getSortOrder(): ?int
    {

        return $this->sortOrder;
    }

    /**
     * @param int $sortOrder
     */
    public function setSortOrder(int $sortOrder): void
    {
        $this->sortOrder = $sortOrder;
    }

    /**
     * @return ProductEntity
     */
    public function getProduct(): ?ProductEntity
    {

        return $this->product;
    }

    /**
     * @param ProductEntity $product
     */
    public function setProduct(ProductEntity $product): void
    {
        $this->product = $product;
    }

}
