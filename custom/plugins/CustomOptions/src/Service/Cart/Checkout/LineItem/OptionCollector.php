<?php declare(strict_types=1);

namespace Swpa\CustomOptions\Service\Cart\Checkout\LineItem;

use Shopware\Core\Checkout\Cart\LineItem\LineItem;
use Shopware\Core\Checkout\Order\Aggregate\OrderLineItem\OrderLineItemEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Swpa\CustomOptions\DAL\Option\OptionCollectionStruct;
use Swpa\CustomOptions\DAL\Option\OptionEntity;
use Swpa\CustomOptions\DAL\Option\OptionStruct;
use Swpa\CustomOptions\DAL\Option\ValueCollectionStruct;
use Swpa\CustomOptions\DAL\OptionValue\OptionValueEntity;

class OptionCollector
{

    /**
     * @var EntityRepositoryInterface
     */
    private $optionRepository;

    private $cache = [];

    public function __construct(
        EntityRepositoryInterface $optionRepository
    )
    {
        $this->optionRepository = $optionRepository;
    }


    // TODO why? save in the payload titles with prices and just display on form!!!
    public function processOrderLineItems($lineItems, Context $context)
    {
        /** @var OrderLineItemEntity $lineItem */
        foreach ($lineItems as $lineItem) {
            $payload = $lineItem->getPayload();
            if (!array_key_exists('custom_options', $payload) && empty($payload['custom_options'])) {
                continue;
            }
            $collection = new OptionCollectionStruct();
            $options = [];
            if (!is_array($payload['custom_options'])) {
                continue;
            }
            foreach ($payload['custom_options'] as $optionId => $selectedValueId) {
                if (empty($selectedValueId)) {
                    continue;
                }
                $optionStruct = new OptionStruct();
                $optionStruct->setOptionId($optionId);
                $optionStruct->setValueId($selectedValueId);
                $option = $this->getOptionById($optionId, $context);
                $optionStruct->addExtension('option', $option);
                $ids = json_decode($selectedValueId);
                $values = [];
                foreach ($ids as $valueId) {
                    $values[] = $this->getValueById($option, $valueId);
                }
                $valueCollection = new ValueCollectionStruct();
                $valueCollection->setCollection($values);
                $optionStruct->addExtension('value', $valueCollection);

                $options[] = $optionStruct;
            }
            $collection->setCollection($options);
            $lineItem->addExtension('options', $collection);
        }
    }

    public function processCartLineItems($lineItems, Context $context)
    {
        /** @var LineItem $lineItem */
        foreach ($lineItems as $lineItem) {
            $options = $lineItem->getExtension('options');
            if (!$options instanceof OptionCollectionStruct) {
                continue;
            }
            /** @var OptionStruct $optionStruct */
            foreach ($options->getCollection() as $optionStruct) {
                if (empty($optionStruct->getValueId())) {
                    continue;
                }
                $option = $this->getOptionById($optionStruct->getOptionId(), $context);

                $optionStruct->addExtension('option', $option);
                $ids = json_decode($optionStruct->getValueId());
                $values = [];
                foreach ($ids as $valueId) {
                    if (empty($valueId)) {
                        continue;
                    }
                    $values[] = $this->getValueById($option, $valueId);
                }
                $valueCollection = new ValueCollectionStruct();
                $valueCollection->setCollection($values);
                $optionStruct->addExtension('value', $valueCollection);
            }
        }
    }

    private function getOptionById(string $id, Context $context): OptionEntity
    {

        if (array_key_exists($id, $this->cache)) {

            return $this->cache[$id];
        }

        $criteria = new Criteria([$id]);
        $criteria->addAssociation('values');

        $result = $this->optionRepository->search($criteria, $context);

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
}
