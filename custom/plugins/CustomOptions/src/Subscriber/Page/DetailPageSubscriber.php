<?php declare(strict_types=1);

namespace Swpa\CustomOptions\Subscriber\Page;

use Shopware\Core\Checkout\Cart\Event\LineItemAddedEvent;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;
use Shopware\Storefront\Controller\CartLineItemController;
use Shopware\Storefront\Page\Checkout\Offcanvas\OffcanvasCartPageLoadedEvent;
use Shopware\Storefront\Page\Product\ProductPageLoadedEvent;
use Swpa\CustomOptions\DAL\Option\OptionCollectionStruct;
use Swpa\CustomOptions\DAL\Option\OptionStruct;
use Swpa\CustomOptions\Service\Cart\Checkout\LineItem\OptionCollector;
use Swpa\CustomOptions\Service\Option\ValuePriceCalculator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * subscriber on events of details page
 *
 * @package Swpa\CustomOptions\Subscriber\Page
 * @license See COPYING.txt for license details
 * @author  Magidev Team <support@magidev.com>
 */
class DetailPageSubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityRepositoryInterface
     */
    private $optionsRepository;

    /**
     * @var OptionCollector
     */
    private $optionLineItemCollector;

    /**
     * @var ValuePriceCalculator
     */
    private $valuePriceCalculator;

    /**
     * selected options ids
     * @var array | null
     */
    private $options;

    public function __construct(
        EntityRepositoryInterface $optionsRepository,
        OptionCollector $optionLineItemCollector,
        ValuePriceCalculator $valuePriceCalculator)
    {
        $this->optionsRepository = $optionsRepository;
        $this->optionLineItemCollector = $optionLineItemCollector;
        $this->valuePriceCalculator = $valuePriceCalculator;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ProductPageLoadedEvent::class => 'addOptionsToProductPage',
            LineItemAddedEvent::class => 'addOptionsToCart',
            KernelEvents::CONTROLLER => 'getOptionsBeforeSaveCart',
            OffcanvasCartPageLoadedEvent::class => 'offCanvasCartExtension'
        ];
    }

    /**
     * retrieve selected options from request and save they in the object,
     * to add they to the cart item on event LineItemAddedEvent
     *
     * @param ControllerEvent $event
     */
    public function getOptionsBeforeSaveCart(ControllerEvent $event): void
    {
        $controller = $event->getController();

        if (is_array($controller)) {
            $controller = array_shift($controller);
        }

        if ($controller instanceof CartLineItemController) {
            /** @var  CartLineItemController $controller */
            $options = $event->getRequest()->get('options');
            $this->options = $options;
        }
    }

    /**
     * add saved in the method getOptionsBeforeSaveCart options to the cart item
     *
     * @param LineItemAddedEvent $event
     */
    public function addOptionsToCart(LineItemAddedEvent $event): void
    {
        /** @var \Shopware\Core\Checkout\Cart\LineItem\LineItem $item */
        $item = $event->getLineItem();
        if (is_array($this->options)) {
            $options = [];
            foreach ($this->options as $optionID => $option) {
                if (!array_key_exists('value', $option) || empty($option['value'])) {
                    continue;
                }
                $values = [];
                foreach ($option['value'] as $value) {
                    if (empty($value)) {
                        continue;
                    }
                    $values[] = $value;
                }
                if (empty($values)) {
                    continue;
                }
                $values = json_encode($values);
                $optionStruct = new OptionStruct();
                $optionStruct->setOptionId($optionID);
                $optionStruct->setValueId($values);
                $options[] = $optionStruct;
            }
            $collection = new OptionCollectionStruct();
            $collection->setCollection($options);

            $item->addExtension('options', $collection);
        } else {
            $item->addExtension('options', null);
        }
    }

    /**
     * add array with options to product detail page(template),
     * see template #storefront/page/product-detail/buy-widget-form.html.twig for details
     *
     * @param ProductPageLoadedEvent $event
     * @throws \Shopware\Core\Framework\DataAbstractionLayer\Exception\InconsistentCriteriaIdsException
     */
    public function addOptionsToProductPage(ProductPageLoadedEvent $event): void
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('product_id', $event->getPage()->getProduct()->getId()));
        $criteria->addFilter(new EqualsFilter('active', true));
        $criteria->addAssociation('values');
        $criteria->addAssociation('product');
        $criteria->addSorting(new FieldSorting('sortOrder'));
        /** @var EntitySearchResult $options */
        $options = $this->optionsRepository->search($criteria, $event->getContext());
        foreach ($options as $option) {
            $this->valuePriceCalculator->calculate(
                $option,
                $event->getPage()->getProduct()->getCalculatedPrices(),
                $event->getPage()->getProduct()->getCalculatedPrice()
            );
        }
        $event->getPage()->addExtension('customOptions', $options);
    }

    /**
     * add custom options to canvas cart
     *
     * @param OffcanvasCartPageLoadedEvent $event
     */
    public function offCanvasCartExtension(OffcanvasCartPageLoadedEvent $event): void
    {
        $this->optionLineItemCollector->processCartLineItems($event->getPage()->getCart()->getLineItems(), $event->getContext());
    }

}
