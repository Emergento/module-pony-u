<?php
declare(strict_types=1);

namespace Emergento\PonyU\Model;

use GuzzleHttp\Exception\GuzzleException;

/**
 * Check whether the coordinates of a delivery point fall within a served area
 * @api
 */
class GetZonesByLatitudeLongitude
{
    private const ENDPOINT_PATH = 'secured/sender-service-zones';

    public function __construct(private readonly Client $client)
    {
    }

    /**
     * @param $latitude
     * @param $longitude
     * @param $storeId
     * @return array
     * @throws GuzzleException
     */
    public function execute($latitude, $longitude, $storeId): array
    {
        return $this->client->call('GET', self::ENDPOINT_PATH, ['query' => ['latitude' => $latitude, 'longitude' => $longitude]], $storeId);
    }
}
