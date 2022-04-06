<?php

use think\facade\Route;

Route::group('api', function() {
    Route::get('wiki$','Api/wiki');
    Route::rule('/:_path/[:_format]','Api/index')->allowCrossDomain([
        'Access-Control-Allow-Headers' => 'Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-Requested-With, X-Api-Token'
    ]);
});

Route::group('debug',function() {
    Route::rule('$','Debug/index');
});

Route::miss(function(){return miss();});
