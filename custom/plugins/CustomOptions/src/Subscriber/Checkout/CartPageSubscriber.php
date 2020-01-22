<?php declare(strict_types=1);

namespace Swpa\CustomOptions\Subscriber\Checkout;

use Shopware\Storefront\Page\Checkout\Cart\CheckoutCartPageLoadedEvent;
use Swpa\CustomOptions\Service\Cart\Checkout\LineItem\OptionCollector;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CartPageSubscriber implements EventSubscriberInterface
{

    /**
     * @var OptionLineItemCollector
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
            CheckoutCartPageLoadedEvent::class => 'cart'
        ];
    }

    public function cart(CheckoutCartPageLoadedEvent $event)
    {

        $cart = $event->getPage()->getCart();

        $this->optionLineItemCollector->processCartLineItems($cart->getLineItems(), $event->getContext());
    }
}
