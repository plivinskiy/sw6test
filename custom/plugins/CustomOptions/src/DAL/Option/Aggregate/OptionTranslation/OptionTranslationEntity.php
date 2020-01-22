<?php declare(strict_types=1);

namespace Swpa\CustomOptions\DAL\Option\Aggregate\OptionTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use Shopware\Core\Framework\DataAbstractionLayer\TranslationEntity;
use Shopware\Core\Framework\Language\LanguageEntity;

class OptionTranslationEntity extends TranslationEntity
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
