<?php
declare(strict_types=1);

namespace Emergento\PonyU\Model;

use GuzzleHttp\Exception\GuzzleException;

/**
 * Retrieve available PonyU delivery slots
 * @api
 */
class GetDeliverySlots
{
    private const ENDPOINT = 'v1/secured/delivery-slots';

    public function __construct(private readonly Client $client)
    {
    }

    /**
     * @throws GuzzleException
     */
    public function execute(
        float $senderLatitude,
        float $senderLongitude,
        float $receiverLatitude,
        float $receiverLongitude,
        string $date,
        int $days,
        int $storeId
    ) {
        return $this->client->call(
            'GET',
            self::ENDPOINT,
            [
                'query' => [
                    'senderLatitude' => $senderLatitude,
                    'senderLongitude' => $senderLongitude,
                    'receiverLatitude' => $receiverLatitude,
                    'receiverLongitude' => $receiverLongitude,
                    'date' => $date,
                    'days' => $days
                ]
            ],
            $storeId
        );
    }
}
