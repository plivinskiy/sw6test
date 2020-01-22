<?php declare(strict_types=1);

namespace Swpa\CustomOptions\Subscriber\Checkout;

use Shopware\Storefront\Page\Checkout\Confirm\CheckoutConfirmPageLoadedEvent;
use Swpa\CustomOptions\Service\Cart\Checkout\LineItem\OptionCollector;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ConfirmPageSubscriber implements EventSubscriberInterface
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
            CheckoutConfirmPageLoadedEvent::class => 'confirm'
        ];
    }

    public function confirm(CheckoutConfirmPageLoadedEvent $event)
    {

        $cart = $event->getPage()->getCart();
        $this->optionLineItemCollector->processCartLineItems($cart->getLineItems(), $event->getContext());
    }
}
