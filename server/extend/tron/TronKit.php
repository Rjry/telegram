<?php

namespace tron;

class TronKit
{
    protected $tronkit;
    protected $net     = 'main';
    protected $netlist = [
        'main'   => [
            'fullNode'     => 'https://api.trongrid.io',
            'solidityNode' => 'https://api.trongrid.io',
            'eventServer'  => 'https://api.trongrid.io',
        ],
        'shasta' => [
            'fullNode'     => 'https://api.shasta.trongrid.io',
            'solidityNode' => 'https://api.shasta.trongrid.io',
            'eventServer'  => 'https://api.shasta.trongrid.io',
        ],
    ];
    protected $contract = [
        'TRX'  => '',
        'USDT' => 'TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t',
    ];

    public function __construct(){
        $net           = $this->netlist[$this->net];
        $fullNode      = new \IEXBase\TronAPI\Provider\HttpProvider($net['fullNode']);
        $solidityNode  = new \IEXBase\TronAPI\Provider\HttpProvider($net['solidityNode']);
        $eventServer   = new \IEXBase\TronAPI\Provider\HttpProvider($net['eventServer']);
        $this->tronkit = new \IEXBase\TronAPI\Tron($fullNode, $solidityNode, $eventServer);
        return $this;
    }

    public function hexToBase58($address)
    {
        return $this->tronkit->hexString2Address($address);
    }

    public function isAddress($address)
    {
        return $this->tronkit->isAddress($address);
    }

    public function createAccount()
    {
        $wallet = $this->tronkit->generateAddress();
        return  [
            'public_key'     => $wallet->getPublicKey(),
            'private_key'    => $wallet->getPrivateKey(),
            'address_hex'    => $wallet->getAddress(),
            'address_base58' => $wallet->getAddress(true),
        ];
    }

    public function getTrx($address)
    {
        $assets = $this->tronkit->getBalance($address, true);
        $assets = floatval($assets);
        return $assets;
    }

    public function getContractToken($address, $symbol)
    {
        $contract = $this->contract[$symbol];
        $contract = $this->tronkit->contract($contract);
        $assets   = $contract->balanceOf($address);
        $assets   = floatval($assets);
        return $assets;
    }

    public function transferTrx($fromAddress, $fromPrivateKey, $toAddress, $quantity)
    {
        $this->tronkit->setAddress($fromAddress);
        $this->tronkit->setPrivateKey($fromPrivateKey);
        $result = $this->tronkit->send($toAddress, $quantity);
        return $result;
    }

    public function transferToken($fromAddress, $fromPrivateKey, $toAddress, $symbol, $quantity)
    {
        $this->tronkit->setAddress($fromAddress);
        $this->tronkit->setPrivateKey($fromPrivateKey);
        $contract = $this->contract[$symbol];
        $contract = $this->tronkit->contract($contract);
        $result   = $contract->transfer($toAddress, $quantity);
        return $result;
    }

    public function transferQuery($txid)
    {
        $result = $this->tronkit->getTransaction($txid);
        return $result;
    }




    // public function getTransactionsToAddress($address)
    // {
    //     $result = $this->tronkit->getTransactionsToAddress($address, 30, 0);
    //     return $result;
    // }

    // public function getTransactionsFromAddress($address)
    // {
    //     $result = $this->tronkit->getTransactionsFromAddress($address, 30, 0);
    //     return $result;
    // }
}