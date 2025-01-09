<?php

namespace TrustPilot\Api;

use TrustPilot\TrustPilot;
use Carbon\Carbon;

class Invitation extends AbstractApi
{

    /**
     * This API endpoint triggers an email invitation. Use the redirect parameter
     * to pass in a product review invitation link. preferredSendTime
     * must be in UTC if specified.
     *
     * @param
     * @return \stdClass
     */
    public function createEmailInvitation($businessUnitId, $data)
    {
        $data['locale'] = isset($data['locale']) ? $data['locale'] : 'en-US';
        $data['preferredSendTime'] = isset($data['preferredSendTime']) ? Carbon::parse($data['preferredSendTime'])->toAtomString() : '';

        return json_decode(
            $this->api->post('private/business-units/' . $businessUnitId . '/email-invitations', array('json' => $data))
        );
    }

    /**
     * Get list of invitation templates
     * Retiurns a list of ID and Names of the templates available to be used in invitations.
     * Includes both standard and custom templates.
     *
     * @param
     * @return \stdClass
     */
    public function getInvitationTemplates($businessUnitId)
    {
        return json_decode(
            $this->api->get('private/business-units/' . $businessUnitId . '/templates')
        );
    }

    /**
     * Renders preview of custom template
     *
     * @param
     * @return \stdClass
     */
    public function renderPreview($businessUnitId, $templateId, $data)
    {
        return json_decode(
            $this->api->get(
                'private/business-units/' . $businessUnitId . '/templates/custom/' . $templateId . '/preview',
                [
                    'query' =>
                    [
                        'customerName' => $data['customerName'] ?? '',
                        'customerEmail' => $data['customerEmail'] ?? '',
                        'tld' => $data['tld'] ?? '',
                        'domainName' => $data['domainName'] ?? '',
                        'language' => $data['language'] ?? '',
                        'orderref' => $data['orderref'] ?? ''
                    ]
                ]
            )
        );
    }

    /**
     * Generate service review invitation link
     * Generate a unique invitation link that can be sent to a consumer by email or website.
     * Use the request parameter called redirectURI to take the user
     * to a product review link after the user has left a service review.
     *
     * @param
     * @return string
     */
    public function generateInvitationLink($businessUnitId, $data)
    {
        $data['locale'] = isset($data['locale']) ? $data['locale'] : 'en-US';
        $response = json_decode(
            $this->api->post('private/business-units/' . $businessUnitId . '/invitation-links', array('json' => $data))
        );
        return $response->url;
    }
}
