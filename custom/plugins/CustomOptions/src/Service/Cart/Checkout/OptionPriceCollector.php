<?php declare(strict_types=1);

namespace Swpa\CustomOptions\Service\Cart\Checkout;

use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\CartBehavior;
use Shopware\Core\Checkout\Cart\CartDataCollectorInterface;
use Shopware\Core\Checkout\Cart\CartProcessorInterface;
use Shopware\Core\Checkout\Cart\LineItem\CartDataCollection;
use Shopware\Core\Checkout\Cart\LineItem\LineItem;
use Shopware\Core\Checkout\Cart\Price\QuantityPriceCalculator;
use Shopware\Core\Checkout\Cart\Price\Struct\QuantityPriceDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Swpa\CustomOptions\DAL\Option\OptionEntity;
use Swpa\CustomOptions\DAL\Option\OptionStruct;
use Swpa\CustomOptions\DAL\OptionValue\OptionValueEntity;

class OptionPriceCollector implements CartDataCollectorInterface, CartProcessorInterface
{
    /**
     * @var EntityRepositoryInterface
     */
    private $optionRepository;

    /**
     * @var QuantityPriceCalculator
     */
    private $calculator;

    private $cache = [];

    public function __construct(
        EntityRepositoryInterface $optionRepository,
        QuantityPriceCalculator $calculator
    )
    {
        $this->optionRepository = $optionRepository;
        $this->calculator = $calculator;
    }

    public function collect(CartDataCollection $data, Cart $original, SalesChannelContext $context, CartBehavior $behavior): void
    {
    }

    public function process(CartDataCollection $data, Cart $original, Cart $toCalculate, SalesChannelContext $context, CartBehavior $behavior): void
    {
        // get all product line items
        $lineItemProducts = $toCalculate->getLineItems()->filterType(LineItem::PRODUCT_LINE_ITEM_TYPE);

        foreach ($lineItemProducts as $product) {
            $options = $product->getExtension('options');
            if (!$options) {
                continue;
            }
            $originalPrice = $price = $product->getPrice()->getUnitPrice();

            /** @var OptionStruct $item */
            foreach ($options->getCollection() as $item) {

                if (empty($item->getValueId())) {
                    continue;
                }

                $option = $this->getOptionById($item->getOptionId(), $context);
                $ids = json_decode($item->getValueId());
                foreach ($ids as $valueId) {
                    if (empty($valueId)) {
                        continue;
                    }
                    $selectedValue = $this->getValueById($option, $valueId);

                    if ($selectedValue->getType() == 'percent') {
                        $price += ($originalPrice * $selectedValue->getPrice()) / 100;
                    } else {
                        $price += $selectedValue->getPrice();
                    }
                }

            }

            // build new price definition
            $definition = new QuantityPriceDefinition(
                $price,
                $product->getPrice()->getTaxRules(),
                $context->getCurrency()->getDecimalPrecision(),
                $product->getPrice()->getQuantity(),
                true
            );

            // build CalculatedPrice over calculator class for overwitten price
            $calculated = $this->calculator->calculate($definition, $context);

            // set new price into line item
            $product->setPrice($calculated);
            $product->setPriceDefinition($definition);
        }
    }

    private function getOptionById(string $id, SalesChannelContext $context): OptionEntity
    {

        if (array_key_exists($id, $this->cache)) {

            return $this->cache[$id];
        }

        $criteria = new Criteria([$id]);
        $criteria->addAssociation('values');

        $result = $this->optionRepository->search($criteria, $context->getContext());

        if ($result->count() == 0) {
            throw new \Exception('can not found custom option with ID: ' . $id);
        }

        $this->cache[$id] = $result->first();

        return $this->cache[$id];
    }

    private function getValueById(OptionEntity $option, string $id): OptionValueEntity
    {
        if (empty($option->getValues())) {
            throw new \Exception('option has no values');
        }
        /** @var OptionValueEntity $value */
        foreach ($option->getValues() as $value) {
            if ($value->getId() === $id) {

                return $value;
            }
        }

        throw new \Exception('can not found value with ID: ' . $id);
    }

    private function filterAlreadyFetchedPrices(array $productIds, CartDataCollection $data): array
    {
        $filtered = [];

        foreach ($productIds as $id) {
            $key = $this->buildKey($id);

            // already fetched from database?
            if ($data->has($key)) {
                continue;
            }

            $filtered[] = $id;
        }

        return $filtered;
    }

    private function buildKey(string $id): string
    {
        return 'price-option-' . $id;
    }
}
