<?php declare(strict_types=1);

namespace Swpa\CustomOptions\DAL\Option;

use Shopware\Core\Framework\Struct\Struct;

class OptionStruct extends Struct
{

    /**
     * @var string
     */
    private $optionId;

    /**
     * @var string
     */
    private $valueId;

    public function setOptionId(string $optionId): void
    {
        $this->optionId = $optionId;
    }

    public function getOptionId(): ?string
    {

        return $this->optionId;
    }

    public function setValueId(string $valueId): void
    {
        $this->valueId = $valueId;
    }

    public function getValueId(): ?string
    {

        return $this->valueId;
    }
}
