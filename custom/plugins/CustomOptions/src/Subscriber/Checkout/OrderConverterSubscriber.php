<?php declare(strict_types=1);

namespace Swpa\CustomOptions\Subscriber\Checkout;

use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\Order\CartConvertedEvent;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Swpa\CustomOptions\DAL\Option\OptionEntity;
use Swpa\CustomOptions\DAL\Option\OptionStruct;
use Swpa\CustomOptions\DAL\OptionValue\OptionValueEntity;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class OrderConverterSubscriber implements EventSubscriberInterface
{

    /**
     * @var EntityRepositoryInterface
     */
    private $optionRepository;

    private $cache = [];

    private $context;

    public function __construct(
        EntityRepositoryInterface $optionRepository
    )
    {
        $this->optionRepository = $optionRepository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CartConvertedEvent::class => 'converter'
        ];
    }

    public function converter(CartConvertedEvent $event)
    {
        $cart = $event->getCart();
        $data = $event->getConvertedCart();
        $this->context = $event->getContext();
        foreach ($data['lineItems'] as &$lineItem) {

            $lineItem['payload']['custom_options'] = $this->getCustomOptionsFromLineItem($lineItem['identifier'], $cart);
            $lineItem['payload']['backendCustomOptions'] = $this->getCustomOptionsForBackend($lineItem['identifier'], $cart);
        }

        $event->setConvertedCart($data);
    }

    private function getCustomOptionsFromLineItem($lineItemId, Cart $cart): ?array
    {
        $lineItems = $cart->getLineItems();

        if (!$lineItem = $lineItems->get($lineItemId)) {
            return null;
        }

        if (!$options = $lineItem->getExtension('options')) {
            return null;
        }

        $result = [];
        /** @var OptionStruct $item */
        foreach ($options->getCollection() as $item) {
            $result[$item->getOptionId()] = $item->getValueId();
        }

        return $result;
    }

    private function getCustomOptionsForBackend($lineItemId, Cart $cart): ?array
    {
        $lineItems = $cart->getLineItems();

        if (!$lineItem = $lineItems->get($lineItemId)) {
            return null;
        }

        if (!$options = $lineItem->getExtension('options')) {
            return null;
        }

        $result = [];
        /** @var OptionStruct $item */
        foreach ($options->getCollection() as $item) {
            /** @var OptionEntity $option */
            $option = $this->getOptionById($item->getOptionId(), $this->context);
            $result[$item->getOptionId()] = $option->getTranslation('title') . ': ';
            $valueIds = json_decode($item->getValueId());
            $values = [];
            foreach ($valueIds as $valueId) {
                $value = $this->getValueById($option, $valueId);
                if ($value) {
                    $values[] = $value->getTranslation('title') . ' [+' . $value->getPrice() . ']';
                }
            }
            $result[$item->getOptionId()] .= join(', ', $values);
        }

        return $result;
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
