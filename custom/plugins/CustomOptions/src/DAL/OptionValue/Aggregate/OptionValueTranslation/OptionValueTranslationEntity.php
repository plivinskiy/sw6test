<?php declare(strict_types=1);

namespace Swpa\CustomOptions\DAL\OptionValue\Aggregate\OptionValueTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use Shopware\Core\Framework\DataAbstractionLayer\TranslationEntity;

class OptionValueTranslationEntity extends TranslationEntity
{

    use EntityIdTrait;

    protected $title;


    public function getTitle(): ?string
    {

        return $this->title;
    }


    public function setTitle(string $title): void
    {

        $this->title = $title;
    }

}
