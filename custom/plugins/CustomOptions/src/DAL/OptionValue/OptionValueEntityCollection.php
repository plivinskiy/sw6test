<?php declare(strict_types=1);

namespace Swpa\CustomOptions\DAL\OptionValue;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void              add(CustomEntity $entity)
 * @method void              set(string $key, CustomEntity $entity)
 * @method NotificationEntity[]    getIterator()
 * @method NotificationEntity[]    getElements()
 * @method NotificationEntity|null get(string $key)
 * @method NotificationEntity|null first()
 * @method NotificationEntity|null last()
 */
class OptionValueEntityCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return OptionValueEntity::class;
    }
}
