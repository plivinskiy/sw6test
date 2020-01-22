<?php declare(strict_types=1);

namespace Swpa\CustomOptions\DAL\Option\Aggregate\OptionTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

class OptionTranslationCollection extends EntityCollection
{

    public function filterByLanguageId(string $id): self
    {
        return $this->filter(function (OptionTranslationEntity $option) use ($id) {
            return $option->getLanguageId() === $id;
        });
    }

    protected function getExpectedClass(): string
    {
        return OptionTranslationEntity::class;
    }
}
