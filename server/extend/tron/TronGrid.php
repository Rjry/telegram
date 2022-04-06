<?php
/*
use tron\facade\TronGrid;

$path = '';
$data = [];
$keys = \app\logic\ConfigTronNet::get()['api_key'];
$rets = TronGrid::apiKey($keys)->request($path, $method, $data);
$rets = json_decode($rets,true);
dump($rets);
*/

namespace tron;

class TronGrid
{
    private $apiUrl = 'https://api.trongrid.io';

    private $apiKey = '590831f8-69e1-4d07-8add-91d237b9903b';

    public function __construct() {}

    public function apiKey($apiKey)
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    public function request($path, $method, $data)
    {
        return ( 'GET' == strtoupper($method) ) ? $this->get($path, $data) : $this->post($path, $data);
    }

    // ------------------------------------------------------------------------------------------------
    
    // [ 工具方法 ]

    private function get($path, $data)
    {
        $ch  = curl_init();
        $url = $this->apiUrl . $path;
        if ( !empty($data) ) {
            $url .= '?' . http_build_query($data);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $header = [
            'Accept: application/json',
            'Content-Type: application/json',
            'TRON-PRO-API-KEY: ' . $this->apiKey,
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    private function post($path, $data)
    {
        $ch  = curl_init();
        $url = $this->apiUrl . $path;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $header = [
            'Accept: application/json',
            'Content-Type: application/json',
            'TRON-PRO-API-KEY: ' . $this->apiKey,
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        
        curl_setopt($ch, CURLOPT_POST, true);
        $data = json_encode($data);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}