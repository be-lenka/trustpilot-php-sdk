<?php

namespace TrustPilot\Api;

use Carbon\Carbon;
use TrustPilot\TrustPilot;

class ProductReviews extends AbstractApi
{

    /**
     * Create product review invitation link
     * https://developers.trustpilot.com/product-reviews-api#create-product-review-invitation-link
     *
     *
     * @param  string, array
     * @return \stdClass
     */
    public function createInvitationLink($businessUnitId, $data)
    {
        $data['locale'] = isset($data['locale']) ? $data['locale'] : 'en-US';

        return json_decode(
            $this->api->post('private/product-reviews/business-units/' . $businessUnitId . '/invitation-links', array('json' => $data))
        );
    }

    /**
     * Get private product reviews
     * https://developers.trustpilot.com/product-reviews-api#get-private-product-reviewsnk
     *
     *
     * @param  string, array
     * @return \stdClass
     */
    public function getPrivateReviews($businessUnitId, $data)
    {
        return json_decode(
            $this->api->get(
                'private/product-reviews/business-units/' . $businessUnitId . '/reviews',
                [
                    'query' =>
                    [
                        'page' => $data['page'] ?? '',
                        'perPage' => $data['perPage'] ?? '',
                        'sku' => $data['sku'] ?? '',
                        'language' => $data['language'] ?? '',
                        'state' => $data['state'] ?? ''
                    ]
                ]
            )
        );
    }

    /**
     * Get imported product reviews
     * https://developers.trustpilot.com/product-reviews-api#get-imported-product-reviews
     *
     *
     * @param  string, array
     * @return \stdClass
     */
    public function getImportedReviews($businessUnitId, $apiKey, $data)
    {
        return json_decode(
            $this->api->get(
                'product-reviews/business-units/' . $businessUnitId . '/imported-reviews',
                [
                    'query' =>
                    [
                        'page' => $data['page'] ?? '',
                        'perPage' => $data['perPage'] ?? '',
                        'sku' => $data['sku'] ?? '',
                        'language' => $data['language'] ?? '',
                        'apikey' => $apiKey
                    ],
                ]
            )
        );
    }

    /**
     * Get private product reviews
     * https://developers.trustpilot.com/product-reviews-api#get-product-reviews-summaries-list
     *
     *
     * @param  string, array
     * @return \stdClass
     */
    public function getReviewsSummaries($businessUnitId, $data)
    {
        return json_decode(
            $this->api->get(
                'private/product-reviews/business-units/' . $businessUnitId . '/summaries',
                [
                    'query' =>
                    [
                        'page' => $data['page'] ?? '',
                        'perPage' => $data['perPage'] ?? '',
                    ]
                ]
            )
        );
    }

    /**
     * Get private product review
     * https://developers.trustpilot.com/product-reviews-api#get-private-product-reviews-list
     *
     *
     * @param string
     * @return \stdClass
     */
    public function getPrivateReview($reviewId)
    {
        return json_decode(
            $this->api->get('private/product-reviews/' . $reviewId)
        );
    }

    /**
     * Create product review conversation
     * https://developers.trustpilot.com/product-reviews-api#create-product-review-conversation
     *
     *
     * @param string
     * @return string
     */
    public function createReviewConversation($reviewId)
    {
        $response = json_decode(
            $this->api->post('private/product-reviews/' . $reviewId . '/create-conversation')
        );

        return $response['conversationId'];
    }

    /**
     * Get product reviews summary
     * https://developers.trustpilot.com/product-reviews-api#get-product-reviews-summaryist
     *
     *
     * @param string|array
     * @return \stdClass
     */
    public function getReviewsSummary($businessUnitId, $data)
    {
        return json_decode(
            $this->api->get(
                'private/product-reviews/business-units/' . $businessUnitId,
                [
                    'query' =>
                    [
                        'page' => $data['page'] ?? '',
                        'perPage' => $data['perPage'] ?? '',
                    ]
                ]
            )
        );
    }

}
