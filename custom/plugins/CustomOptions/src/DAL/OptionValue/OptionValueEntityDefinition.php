<?php declare(strict_types=1);

namespace Swpa\CustomOptions\DAL\OptionValue;

use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\CustomFields;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Inherited;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FloatField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IntField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\JsonField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Swpa\CustomOptions\DAL\Option\OptionEntityDefinition;
use Swpa\CustomOptions\DAL\OptionValue\Aggregate\OptionValueTranslation\OptionValueTranslationDefinition;

class OptionValueEntityDefinition extends EntityDefinition
{

    public const ENTITY_NAME = 'product_custom_option_type_value';

    public function getEntityName(): string
    {

        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {

        return OptionValueEntityCollection::class;
    }

    public function getEntityClass(): string
    {

        return OptionValueEntity::class;
    }


    protected function defineFields(): FieldCollection
    {

        return new FieldCollection(
            [
                (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
                (new FkField('option_id', 'option_id', OptionEntityDefinition::class))->addFlags(new Required()),
                new FloatField('price', 'price'),
                new StringField('sku', 'sku', 255),
                new StringField('type', 'type', 10),
                new StringField('color', 'color', 7),
                new StringField('dependent','dependent'),
                new JsonField('dependence','dependence'),
                new IntField('sort_order', 'sortOrder'),
                new TranslatedField('title'),
                (new TranslationsAssociationField(OptionValueTranslationDefinition::class, 'product_custom_option_type_value_id'))->addFlags(new Inherited(), new Required()),
            ]
        );
    }
}
