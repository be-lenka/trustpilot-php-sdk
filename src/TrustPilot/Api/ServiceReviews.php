<?php

namespace TrustPilot\Api;

class ServiceReviews extends AbstractApi
{

    // Incomplete
    /**
     * Get private service reviews
     * https://developers.trustpilot.com/business-units-api#get-a-business-unit's-reviews
     *
     *
     * @param  string, array
     * @return \stdClass
     */
    public function getPrivateReviews($businessUnitId, $data)
    {
        return json_decode(
            $this->api->get(
                'private/business-units/' . $businessUnitId . '/reviews',
                [
                    'query' =>
                    [
                        'page' => $data['page'] ?? '',
                        'perPage' => $data['perPage'] ?? '',
                        'language' => $data['language'] ?? '',
                    ]
                ]
            )
        );
    }
}
