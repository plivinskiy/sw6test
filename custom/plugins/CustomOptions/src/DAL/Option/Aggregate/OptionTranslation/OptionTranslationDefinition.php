<?php declare(strict_types=1);

namespace Swpa\CustomOptions\DAL\Option\Aggregate\OptionTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Swpa\CustomOptions\DAL\Option\Aggregate\OptionPrice\OptionPriceEntityDefinition;
use Swpa\CustomOptions\DAL\Option\Aggregate\OptionTitle\OptionTitleEntityDefinition;
use Swpa\CustomOptions\DAL\Option\OptionEntityDefinition;

class OptionTranslationDefinition extends EntityTranslationDefinition
{

    public const ENTITY_NAME = 'product_custom_option_translation';

    public function getEntityName(): string
    {

        return self::ENTITY_NAME;
    }

    public function isVersionAware(): bool
    {
        return false;
    }

    public function getCollectionClass(): string
    {

        return OptionTranslationCollection::class;
    }

    public function getEntityClass(): string
    {

        return OptionTranslationEntity::class;
    }

    protected function getParentDefinitionClass(): string
    {
        return OptionEntityDefinition::class;
    }

    protected function defineFields(): FieldCollection
    {

        return new FieldCollection(
            [
                new StringField('title', 'title', 255),
            ]
        );
    }
}
