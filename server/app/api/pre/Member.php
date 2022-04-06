<?php

// [ 前置检查 | 会员身份 ]

namespace app\api\pre;

class Member
{
    /*接口*/
    public $api;

    /*请求*/
    public $request;

    /*返回*/
    public $response = [];

    public function __construct($api, &$request) {
        $this->api     = $api;
        $this->request = $request;
    }

    public function run()
    {
        $role = $this->api->payload['role'] ?? '';
        if ('member' != $role) {
            $this->response = [9004,'Pre-check failed [role: member]'];
            return false;
        }

        return true;
    }
}