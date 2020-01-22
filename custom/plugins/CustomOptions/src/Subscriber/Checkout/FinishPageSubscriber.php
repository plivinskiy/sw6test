<?php declare(strict_types=1);

namespace Swpa\CustomOptions\Subscriber\Checkout;

use Shopware\Storefront\Page\Checkout\Finish\CheckoutFinishPageLoadedEvent;
use Swpa\CustomOptions\Service\Cart\Checkout\LineItem\OptionCollector;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FinishPageSubscriber implements EventSubscriberInterface
{

    /**
     * @var OptionCollector
     */
    private $optionLineItemCollector;

    public function __construct(
        OptionCollector $optionLineItemCollector
    )
    {
        $this->optionLineItemCollector = $optionLineItemCollector;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CheckoutFinishPageLoadedEvent::class => 'finish'
        ];
    }

    public function finish(CheckoutFinishPageLoadedEvent $event)
    {
        $order = $event->getPage()->getOrder();
        $this->optionLineItemCollector->processOrderLineItems($order->getLineItems(), $event->getContext());
    }
}
