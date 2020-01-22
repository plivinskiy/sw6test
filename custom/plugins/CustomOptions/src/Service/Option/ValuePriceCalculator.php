<?php declare(strict_types=1);

namespace Swpa\CustomOptions\Service\Option;

use Shopware\Core\Checkout\Cart\Price\Struct\CalculatedPrice;
use Shopware\Core\Checkout\Cart\Price\Struct\PriceCollection;
use Swpa\CustomOptions\DAL\Option\OptionEntity;
use Swpa\CustomOptions\DAL\OptionValue\OptionValueEntity;

class ValuePriceCalculator
{

    public function calculate(OptionEntity $option, PriceCollection $priceCollection, CalculatedPrice $calculatedPrice): void
    {
        $price = $calculatedPrice;
        if ($priceCollection->count() > 0) {
            $price = $priceCollection->first();
        }
        /** @var OptionValueEntity $value */
        foreach ($option->getValues() as $value) {
            if ($value->getType() === 'fixed') {
                continue;
            }
            $value->setPrice(($price->getUnitPrice() * $value->getPrice()) / 100);
        }
    }
}
