<?php declare(strict_types=1);

namespace Swpa\CustomOptions\DAL\OptionValue;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use Swpa\CustomOptions\DAL\OptionValue\Aggregate\OptionValueTranslation\OptionValueTranslationCollection;

class OptionValueEntity extends Entity
{

    use EntityIdTrait;

    /**
     * @var string
     */
    protected $sku;

    /**
     * @var float
     */
    protected $price;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var OptionValueTranslationCollection
     */
    protected $translations;

    /**
     * @var string
     */
    protected $color;

    /**
     * @var array
     */
    protected $dependence;

    /**
     * @var int
     */
    protected $dependent;


    public function getTranslations(): ?OptionValueTranslationCollection
    {

        return $this->translations;
    }

    public function setTranslations(?OptionValueTranslationCollection $translations)
    {

        $this->translations = $translations;
    }

    /**
     * @return string
     */
    public function getSku(): ?string
    {

        return $this->sku;
    }

    /**
     * @param string $sku
     */
    public function setSku(string $sku): void
    {

        $this->sku = $sku;
    }

    /**
     * @return string
     */
    public function getColor(): ?string
    {

        return $this->color;
    }

    /**
     * @param string $color
     */
    public function setColor(string $color): void
    {

        $this->color = $color;
    }

    /**
     * @return float
     */
    public function getPrice(): ?float
    {

        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price): void
    {

        $this->price = $price;
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

    public function getDependence(): ?array
    {
        return $this->dependence;
    }

    public function setDependence(?array $dependence): void
    {
        $this->dependence = $dependence;
    }

    public function getDependent(): ?int
    {
        return $this->dependent;
    }

    public function setDependent(?int $dependent): void
    {
        $this->dependent = $dependent;
    }
}
