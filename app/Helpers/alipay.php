<?php

if ( ! function_exists('makeAopClient') )
{
    function makeAopClient()
    {
        // 设置缓存路径
        define("AOP_SDK_WORK_DIR", storage_path('alipay'));
        // 根据环境设置 mode
        if (env('APP_DEBUG'))
            define("AOP_SDK_DEV_MODE", true);
        else
            define("AOP_SDK_DEV_MODE", false);

        // 用 authcode 换取用户 ID
        $aop = new \AopClient();
        $aop->gatewayUrl = env('ALIPAY_SANDBOX') ? 'https://openapi.alipaydev.com/gateway.do' : 'https://openapi.alipay.com/gateway.do';
        $aop->appId = env('ALIPAY_SANDBOX') ? env('ALIPAY_APP_ID_SANDBOX') : env('ALIPAY_APP_ID');
        $aop->rsaPrivateKey = env('ALIPAY_SANDBOX') ? env('ALIPAY_RSA_PRIVATE_KEY_SANDBOX') : env('ALIPAY_RSA_PRIVATE_KEY');
        $aop->alipayrsaPublicKey = env('ALIPAY_SANDBOX') ? env('ALIPAY_RSA_PUBLIC_KEY_SANDBOX') : env('ALIPAY_RSA_PUBLIC_KEY');
        $aop->apiVersion = '1.0';
        $aop->signType = 'RSA2';
        $aop->postCharset = 'UTF-8';
        $aop->format = 'json';

        return $aop;
    }
}
