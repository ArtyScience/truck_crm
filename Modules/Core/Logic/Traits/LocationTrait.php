<?php

namespace Modules\Core\Logic\Traits;

trait LocationTrait
{
    const string FROM = 'from';
    const string TO = 'to';

    private function locationParser(array $location): array
    {
        $address_components = $location['address_components'];

        $address_allowed_parts = ['country', 'street_number', 'postal_code', 'natural_feature',
            'locality', 'administrative_area_level_1', 'route'];
        $parsed_address = [];

        foreach ($address_components as $address_component) {
            $address_type = reset($address_component['types']);


            if (in_array($address_type, $address_allowed_parts)) {
                if (in_array('country', $address_component['types'])) {
                    $parsed_address['country_code'] = $address_component['short_name'];
                }
                $parsed_address[$address_type] = $address_component['long_name'];
            }
        }

        $city = '';

        if (isset($parsed_address['locality'])) {
            $city = $parsed_address['locality'];
        } elseif (isset($parsed_address['natural_feature'])) {
            $city = $parsed_address['natural_feature'];
        }

        return [
            'full_location' => $location['formatted_address'],
            'country' => $parsed_address['country'],
            'country_code' => $parsed_address['country_code'],
            'city' => $city,
            'state' => $parsed_address['administrative_area_level_1'],
            'postcode' => $parsed_address['postal_code'] ?? '',
            'latitude' => $location['geometry']['location']['lat'],
            'longitude' => $location['geometry']['location']['lng'],
        ];
    }

}
