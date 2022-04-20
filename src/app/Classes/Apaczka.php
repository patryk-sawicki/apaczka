<?php

namespace PatrykSawicki\Apaczka\app\Classes;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class Apaczka
{
    protected string $appId, $secret;
    protected int $expiresTime;

    public function __construct() {
        $this->appId = config('apaczka.app_id');
        $this->secret = config('apaczka.app_secret');
        $this->expiresTime = config('apaczka.expires_time');
    }

    /**
     * Get a list of the latest orders.
     *
     * @param int $page
     * @param int $limit
     * @return string
     */
    public function orders(int $page = 0, int $limit = 10): string
    {
        $route = 'orders/';
        $expires = $this->expires();
        $data = json_encode([
            'page'  => $page,
            'limit' => $limit
        ]);

        $requestData = $this->requestData($data, $route, $expires);

        $response = Http::asForm()->post(config('apaczka.app_url').$route, $requestData);

        return $response->body();
    }

    /**
     * Get order details.
     *
     * @param int $orderId
     * @return string
     */
    public function order(int $orderId): string
    {
        $route = "order/$orderId/";
        $expires = $this->expires();
        $data = json_encode([]);

        $requestData = $this->requestData($data, $route, $expires);

        $response = Http::asForm()->post(config('apaczka.app_url').$route, $requestData);

        return $response->body();
    }

    /**
     * Download waybill.
     *
     * @param int $orderId
     * @return BinaryFileResponse
     */
    public function downloadWaybill(int $orderId): BinaryFileResponse
    {
        $route = "waybill/$orderId/";
        $expires = $this->expires();
        $data = json_encode([]);

        $requestData = $this->requestData($data, $route, $expires);

        $response = Http::asForm()->post(config('apaczka.app_url').$route, $requestData);

        $path = "waybill_$orderId.pdf";

        Storage::disk('public')->put($path, base64_decode(json_decode($response->body())->response->waybill));

        return response()->download(storage_path('app/public/'.$path))->deleteFileAfterSend(true);
    }

    /**
     * Store waybill.
     *
     * @param int $orderId
     * @param $path //Path with file name.
     * @return bool
     */
    public function storeWaybill(int $orderId, $path): bool
    {
        $route = "waybill/$orderId/";
        $expires = $this->expires();
        $data = json_encode([]);

        $requestData = $this->requestData($data, $route, $expires);

        $response = Http::asForm()->post(config('apaczka.app_url').$route, $requestData);

        file_put_contents($path, base64_decode(json_decode($response->body())->response->waybill));

        return true;
    }

    /**
     * Get service structure.
     *
     * @return string
     */
    public function serviceStructure(): string
    {
        return Cache::remember('apaczkaServiceStructure', config('apaczka.cache_time'), function(){
            $route = "service_structure/";
            $expires = $this->expires();
            $data = json_encode([]);

            $requestData = $this->requestData($data, $route, $expires);

            $response = Http::asForm()->post(config('apaczka.app_url').$route, $requestData);

            return $response->body();
        });
    }

    /**
     * Get list of postage points.
     *
     * @param string $type
     * @return string
     */
    public function points(string $type): string
    {
        return Cache::remember('apaczkaPoints_'.$type, config('apaczka.cache_time'), function() use ($type) {
            $route = "points/$type/";
            $expires = $this->expires();
            $data = json_encode([]);

            $requestData = $this->requestData($data, $route, $expires);

            $response = Http::asForm()->post(config('apaczka.app_url') . $route, $requestData);

            return $response->body();
        });
    }

    /**
     * Download turn in.
     *
     * @param array $orderIds
     * @return BinaryFileResponse
     */
    public function downloadTurnIn(array $orderIds): BinaryFileResponse
    {
        $route = 'turn_in/';
        $expires = $this->expires();
        $data = json_encode([
            'order_ids' => $orderIds
        ]);

        $requestData = $this->requestData($data, $route, $expires);

        $response = Http::asForm()->post(config('apaczka.app_url').$route, $requestData);

        $path = 'turn_in_'.date('Y-m-d H:i:s').'.pdf';

        Storage::disk('public')->put($path, base64_decode(json_decode($response->body())->response->waybill));

        return response()->download(storage_path('app/public/'.$path))->deleteFileAfterSend(true);
    }

    /**
     * Store turn in.
     *
     * @param array $orderIds
     * @param $path //Path with file name.
     * @return bool
     */
    public function storeTurnIn(array $orderIds, $path): bool
    {
        $route = 'turn_in/';
        $expires = $this->expires();
        $data = json_encode([
            'order_ids' => $orderIds
        ]);

        $requestData = $this->requestData($data, $route, $expires);

        $response = Http::asForm()->post(config('apaczka.app_url').$route, $requestData);

        file_put_contents($path, base64_decode(json_decode($response->body())->response->waybill));

        return true;
    }

    /**
     * Get list of pickup hours.
     *
     * @param string $postalCode
     * @param int|null $serviceId
     * @param bool $removeIndex
     * @return string
     */
    public function pickupHours(string $postalCode, int $serviceId = null, bool $removeIndex = false): string
    {
        return Cache::remember('apaczkaPickupHours_'.$postalCode.'_'.$serviceId.'_'.($removeIndex ? 1 : 0), config('apaczka.cache_time'), function() use ($postalCode, $serviceId, $removeIndex) {
            $route = 'pickup_hours/';
            $expires = $this->expires();
            $data = json_encode([
                'postal_code' => $postalCode,
                'service_id' => $serviceId,
                'remove_index' => $removeIndex
            ]);

            $requestData = $this->requestData($data, $route, $expires);

            $response = Http::asForm()->post(config('apaczka.app_url') . $route, $requestData);

            return $response->body();
        });
    }

    /**
     * Get order valuation.
     *
     * @param array $order
     * @return string
     */
    public function orderValuation(array $order): string
    {
        $route = 'order_valuation/';
        $expires = $this->expires();
        $data = json_encode([
            'order' => $order,
        ]);

        $requestData = $this->requestData($data, $route, $expires);

        $response = Http::asForm()->post(config('apaczka.app_url').$route, $requestData);

        return $response->body();
    }

    /**
     * Send order.
     *
     * @param array $order
     * @return string
     */
    public function sendOrder(array $order): string
    {
        $route = 'order_send/';
        $expires = $this->expires();
        $data = json_encode([
            'order' => $order,
        ]);

        $requestData = $this->requestData($data, $route, $expires);

        $response = Http::asForm()->post(config('apaczka.app_url').$route, $requestData);

        return $response->body();
    }

    /**
     * Cancel order.
     *
     * @param int $orderId
     * @return string
     */
    public function cancelOrder(int $orderId): string
    {
        $route = "cancel_order/$orderId/";
        $expires = $this->expires();
        $data = json_encode([]);

        $requestData = $this->requestData($data, $route, $expires);

        $response = Http::asForm()->post(config('apaczka.app_url').$route, $requestData);

        return $response->body();
    }

    #[ArrayShape(['app_id' => "mixed", 'request' => "string", 'expires' => "int", 'signature' => "string"])]
    protected function requestData(string $data, string $route, int $expires): array{
        return [
            'app_id' => $this->appId,
            'request' => $data,
            'expires'   => $expires,
            'signature' => $this->getSignature($this->stringToSign($route, $data, $expires))
        ];
    }

    protected function getSignature( $string ):string {
        return hash_hmac( 'sha256', $string, $this->secret );
    }

    protected function stringToSign($route, $data, $expires):string {
        return sprintf( "%s:%s:%s:%s", $this->appId, $route, $data, $expires);
    }

    protected function expires(): bool|int
    {
        return strtotime('+'.$this->expiresTime.' seconds');
    }
}