<?php declare(strict_types=1);

namespace Swpa\CustomOptions\DAL\OptionValue\Aggregate\OptionValueTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Swpa\CustomOptions\DAL\Option\Aggregate\OptionPrice\OptionPriceEntityDefinition;
use Swpa\CustomOptions\DAL\Option\Aggregate\OptionTitle\OptionTitleEntityDefinition;
use Swpa\CustomOptions\DAL\OptionValue\OptionValueEntityDefinition;

class OptionValueTranslationDefinition extends EntityTranslationDefinition
{

    public const ENTITY_NAME = 'product_custom_option_type_value_translation';

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

        return OptionValueTranslationCollection::class;
    }

    public function getEntityClass(): string
    {

        return OptionValueTranslationEntity::class;
    }

    protected function getParentDefinitionClass(): string
    {
        return OptionValueEntityDefinition::class;
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
