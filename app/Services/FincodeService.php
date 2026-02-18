<?php
namespace App\Services;

use GuzzleHttp\Client;
/*
| =============================================
|  fincode サービス
| =============================================
*/
class FincodeService
{
    private string $apiKey;
    private string $baseUrl;
    private Client $client;

    public function __construct()
    {
        $this->apiKey  = 'Bearer ' . config('fincode.secret_key');
        $this->baseUrl = config('fincode.base_url');
        $this->client  = new Client();
    }

    /**
     * 3Dセキュア必須セッション作成
     */
    public function createSession(array $data): array
    {
        $response = $this->client->post(
            $this->baseUrl . '/v1/sessions',
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => $this->apiKey,
                ],
                'json' => $data,
            ]
        );

        return json_decode($response->getBody(), true);
    }

    /**
     * 決済トランザクション取得
     */
    public function getTransaction(string $transactionId): array
    {
        $response = $this->client->get(
            $this->baseUrl . "/v1/transactions/{$transactionId}",
            [
                'headers' => [
                    'Authorization' => $this->apiKey,
                ],
            ]
        );

        return json_decode($response->getBody(), true);
    }

}
