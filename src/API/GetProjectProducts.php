<?php

namespace D4rk0snet\Adoption\API;

use D4rk0snet\Adoption\Models\ProjectProducts;
use Hyperion\RestAPI\APIEnpointAbstract;
use Hyperion\RestAPI\APIManagement;
use Hyperion\Stripe\Model\ProductSearchModel;
use Hyperion\Stripe\Service\SearchService;
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
        $searchProductModel = (new ProductSearchModel())->addMetadata(['project' => $project]);
        $stripeProducts = SearchService::searchProduct($searchProductModel);
        $productModels = [];

        /** @var Product $product */
        foreach($stripeProducts->toArray() as $product) {
            $productModels[] = (new ProjectProducts())
                ->setKey($product->metadata['key'])
                ->setPrice($product->default_price->unit_amount);
        }

        if(count($stripeProducts) === 0) {
            return APIManagement::APINotFound("");
        }

        return APIManagement::APIOk(json_encode($productModels, JSON_THROW_ON_ERROR));
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