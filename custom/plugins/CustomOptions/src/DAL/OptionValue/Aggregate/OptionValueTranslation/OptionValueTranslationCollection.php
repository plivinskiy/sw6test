<?php declare(strict_types=1);

namespace Swpa\CustomOptions\DAL\OptionValue\Aggregate\OptionValueTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;
use Swpa\CustomOptions\DAL\Option\Aggregate\OptionTranslation\OptionTranslationEntity;

class OptionValueTranslationCollection extends EntityCollection
{

    public function filterByLanguageId(string $id): self
    {
        return $this->filter(function (OptionValueTranslationEntity $value) use ($id) {
            return $value->getLanguageId() === $id;
        });
    }

    protected function getExpectedClass(): string
    {
        return OptionTranslationEntity::class;
    }
}
