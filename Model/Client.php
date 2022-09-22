<?php
declare(strict_types=1);

namespace Emergento\PonyU\Model;

use Emergento\PonyUShippingMethod\Model\Config as PonyUConfig;
use GuzzleHttp\ClientFactory;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Store\Api\StoreRepositoryInterface;
use Psr\Log\LoggerInterface;

/**
 * Implementation of a PonyU Client
 * @api
 */
class Client
{

    public function __construct(
        private readonly ClientFactory            $clientFactory,
        private readonly Json                     $json,
        private readonly PonyUConfig              $ponyUConfig,
        private readonly LoggerInterface          $logger,
        private readonly StoreRepositoryInterface $storeRepository
    ) {
    }

    /**
     * @param string $method
     * @param string $path
     * @param array $options
     * @param int|null $storeId
     * @return array|bool|float|int|mixed|string|null
     * @throws GuzzleException
     */
    public function call(string $method, string $path, array $options, ?int $storeId)
    {
        $client = $this->clientFactory->create([
            'config' => ['base_uri' => $this->ponyUConfig->getEndpoint($storeId)]
        ]);

        try {
            $websiteId = $this->storeRepository->getById($storeId)->getWebsiteId();
        } catch (NoSuchEntityException $e) {
            $this->logger->debug($e->getMessage());
            $websiteId = null;
        }

        $defaultOptions = [
            'headers' => [
                'Content-Type' => 'application/json',
                'accept' => 'application/json',
                'api_key' => $this->ponyUConfig->getApiKey((int)$websiteId)
            ]
        ];
        $options = array_replace_recursive($defaultOptions, $options);
        $response = $client->request($method, $path, $options);
        $body = $response->getBody();
        $this->logger->debug(sprintf('%s - %s: %s', $method, $path, var_export($options, true)));
        return $this->json->unserialize($body->getContents());
    }
}
