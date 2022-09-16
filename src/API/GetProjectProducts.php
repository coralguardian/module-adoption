<?php

namespace D4rk0snet\Adoption\API;

use D4rk0snet\Adoption\Models\ProjectProducts;
use Hyperion\RestAPI\APIEnpointAbstract;
use Hyperion\RestAPI\APIManagement;
use Hyperion\Stripe\Model\ProductSearchModel;
use Hyperion\Stripe\Service\StripeService;
use Stripe\Product;
use WP_REST_Request;
use WP_REST_Response;

class GetProjectProducts extends APIEnpointAbstract
{
    public static function callback(WP_REST_Request $request): WP_REST_Response
    {
        $project = $request->get_param("project");
        if ($project === null) {
            return APIManagement::APIError('Missing project GET parameter', 400);
        }

        // Récupération des produits depuis stripe
        $searchProductModel = new ProductSearchModel(
            active: true,
            metadata: ['project' => $project]
        );

        $stripeProducts = StripeService::getStripeClient()->products->search((string) $searchProductModel);
        $productModels = [];

        /** @var Product $product */
        foreach ($stripeProducts->data as $product) {
            $price = StripeService::getStripeClient()->prices->retrieve($product->default_price);

            $productModels[] = (new ProjectProducts())
                ->setKey($product->metadata['key'])
                ->setProject($product->metadata['project'])
                ->setPrice($price->unit_amount / 100)
                ->setVariant($product->metadata['variant']);
        }

        if (count($stripeProducts) === 0) {
            return APIManagement::APINotFound("");
        }

        usort($productModels, function (ProjectProducts $a, ProjectProducts $b) {
            return $a->getPrice() - $b->getPrice();
        });

        return APIManagement::APIOk($productModels);
    }

    public static function getMethods(): array
    {
        return ["GET"];
    }

    public static function getPermissions(): string
    {
        return "__return_true";
    }

    public static function getEndpoint(): string
    {
        return "adoption/products";
    }
}