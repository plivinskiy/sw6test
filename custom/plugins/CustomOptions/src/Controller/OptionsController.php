<?php declare(strict_types=1);


namespace Swpa\CustomOptions\Controller;

use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Swpa\CustomOptions\DAL\Option\OptionEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OptionsController extends AbstractController
{
    private $repo;

    private $product;

    public function __construct(EntityRepositoryInterface $repository, EntityRepositoryInterface $product)
    {
        $this->repo = $repository;
        $this->product = $product;
    }

    /**
     * BankTransfer Pending Notification
     *
     * @RouteScope(scopes={"storefront"})
     * @Route("/custom/notification/bank-transfer", name="custom.notification.bankTransfer", methods={"GET"})
     */
    public function bankTransfer(Request $request, Context $context): JsonResponse
    {

        try {
            $criteria = new Criteria();
            $criteria->addAssociation('values');
            $result = $this->repo->search($criteria, $context);
        } catch (\Exception $e) {
            echo($e->getMessage());
            die();
        }

        /** @var OptionEntity $option */
        $option = $result->first();
        /** @var ProductEntity $product */
        //$product = $res->first();
        p($option);

        return new JsonResponse(['notificationResponse' => '[accepted]']);
    }


}
