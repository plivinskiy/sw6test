<?php declare(strict_types=1);

namespace Swpa\CustomOptions\DAL;

use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\CascadeDelete;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Inherited;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IntField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Swpa\CustomOptions\DAL\Option\Aggregate\OptionPrice\OptionPriceEntityDefinition;
use Swpa\CustomOptions\DAL\Option\Aggregate\OptionTitle\OptionTitleEntityDefinition;
use Swpa\CustomOptions\DAL\Option\Aggregate\OptionTranslation\OptionTranslationDefinition;
use Swpa\CustomOptions\DAL\OptionValue\OptionValueEntityDefinition;

class CustomEntityDefinition extends EntityDefinition
{

    public const ENTITY_NAME = 'product_custom';

    public function getEntityName(): string
    {

        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {

        return CustomEntityCollection::class;
    }

    public function getEntityClass(): string
    {

        return CustomEntity::class;
    }

    protected function defineFields(): FieldCollection
    {

        return new FieldCollection(
            [
                (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
                new TranslatedField('title'),
                //not inherited associations
                (new TranslationsAssociationField(OptionTranslationDefinition::class, 'product_custom_option_id'))->addFlags(new Inherited(), new Required()),
                new ManyToOneAssociationField('parent', 'parent_id', OptionValueEntityDefinition::class, 'id', false)
            ]
        );
    }
}
