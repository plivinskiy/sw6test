<?php declare(strict_types=1);

namespace Swpa\CustomOptions\DAL\Option;

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

class OptionEntityDefinition extends EntityDefinition
{

    public const ENTITY_NAME = 'product_custom_option';

    public function getEntityName(): string
    {

        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {

        return OptionEntityCollection::class;
    }

    public function getEntityClass(): string
    {

        return OptionEntity::class;
    }

    protected function defineFields(): FieldCollection
    {

        return new FieldCollection(
            [
                (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
                (new FkField('product_id', 'product_id', ProductDefinition::class))->addFlags(new Required()),
                (new FkField('parent_id', 'parent_id', ProductDefinition::class)),
                new StringField('type', 'type', 50),
                new BoolField('is_require', 'isRequire'),
                new BoolField('active', 'active'),
                new IntField('sort_order', 'sortOrder'),
                new TranslatedField('title'),
                //not inherited associations
                (new TranslationsAssociationField(OptionTranslationDefinition::class, 'product_custom_option_id'))->addFlags(new Inherited(), new Required()),
                (new OneToManyAssociationField('values', OptionValueEntityDefinition::class, 'option_id', 'id'))->addFlags(new CascadeDelete()),
                new ManyToOneAssociationField('product', 'product_id', ProductDefinition::class, 'id', false)
            ]
        );
    }
}
