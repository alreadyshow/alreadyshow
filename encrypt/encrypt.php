<?php

function encrypt($data, $key)
{
    return base64_encode(openssl_encrypt($data, 'aes-128-ecb', $key, OPENSSL_PKCS1_PADDING));//OPENSSL_PKCS1_PADDING 不知道为什么可以与PKCS5通用,未深究
}


function decrypt($data, $key)
{
    return openssl_decrypt(base64_decode($data), 'aes-128-ecb', $key, OPENSSL_PKCS1_PADDING);//OPENSSL_PKCS1_PADDING 不知道为什么可以与PKCS5通用,未深究
}

//生成 sha1WithRSA 签名
function genSign($toSign, $privateKey){
    $privateKey = "-----BEGIN RSA PRIVATE KEY-----\n" .
        wordwrap($privateKey, 64, "\n", true) .
        "\n-----END RSA PRIVATE KEY-----";

    $key = openssl_get_privatekey($privateKey);
    openssl_sign($toSign, $signature, $key);
    openssl_free_key($key);
    $sign = base64_encode($signature);
    return $sign;
}

//校验 sha1WithRSA 签名
function verifySign($data, $sign, $pubKey){
    $sign = base64_decode($sign);

    $pubKey = "-----BEGIN PUBLIC KEY-----\n" .
                wordwrap($pubKey, 64, "\n", true) .
                "\n-----END PUBLIC KEY-----";

    $key = openssl_pkey_get_public($pubKey);
    $result = openssl_verify($data, $sign, $key, OPENSSL_ALGO_SHA1) === 1;
    return $result;
}
//MIICeAIBADANBgkqhkiG9w0BAQEFAASCAmIwggJeAgEAAoGBAOTYIqkjyCrIHdIeOAvTwaggG6mAhXU6byrW5SIqAXE3znaiBeOeDVNWJzs/pQtXuTn6fB1LoU3Q93hPcLkh7kdoH3+BJDzoPWZ5tPyzgua2nad9xMNNphfRYDVTiEoAxOnFc3aNI22gse+wPS0Ll29/LGp+z3e/p+e1cRP/ibFJAgMBAAECgYEA3pVbISisiPAcEUNTQC23LtAMF9Hp/RvZBNIADDrPLFAbgUgWck5Ip8YkYnyFC4NHphz8m4H0Yrvd+CdMfMWD/BkPRf3eafhnJlHGKyGqsAXLmGh/mvJbleE3NH9LS1N/0+pPam58mAjvkujxoPQ0v5BxHyS7r14lBMkvxiXN9AECQQD8B2zTpvsXDWJFwjKYmKRkWCs3JOaOJmWX6MTY3qPSE6mFW/93blDAs1kEioB01ZsbKiE3fIubZVcFEzI90nCXAkEA6HMxd+GYWA7+UdeOklhz/XhBdtlsOeHZDG8glOFhsHJguURcnov2TG4G5L1t+qdnpZzTeNKVrSyT2ECE4gVJHwJAVwiZZF39x/AvR7fQkTHlU2G/SsPLert3ygXwNJRuLlXr7MngZvYJnQJSc2cBBVfewHrEDc1MyNUuP+ppJ0BM8QJBALdi6gwiNwaCDbKT1S8wCZJXZY5WSkQAIjTlF1dd2KxUEGsZu9h5o3747wdXS4UMvYCzEUOpH9zX5mwdurh2YxECQQDuPsVpoJlevwbIuRymGzvYvVZvDP2N+O4rN0lrJnlhTXkYdsRLSw92QcBX0jRqjwl/LwEMPt8EaK25xJ6rEc07
//MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCfRRqiTyiDRvgPwAnHm+odB6kEY1O51Zh5rlr3iSYEgDKfO00yD6ZCAh6MlKfYT0DD+WKN91lt6t9g/u0Cw2WJwGeUiOEWUDso/MiOGmdGYrfsarEzGCTSRmu1tIdwFKNi9HThcMTs7aU99lBtoGIYu2mxsXoWnLbdExZ9TaOBgwIDAQAB
//测试
$privateKey = "MIICdQIBADANBgkqhkiG9w0BAQEFAASCAl8wggJbAgEAAoGBAILFLdmFwfNHCGk3vpwj/FR9XpE9jjVreLlOaIEkSAOWOfzxCF7pMeSy0UDroRLgfLD4nQbUcK5AT7gg8PEM6H99SEHcpBkTKhfK1iI5iU9sN/WGL2Ft5T/uwo+KD7X3CsqMD0HsMQtv/YHJeAz/SPf5zyD5KttwohXXzO8a3P1pAgMBAAECgYAchxl2f6iNAu0Bzyhk9bDBWcw8kRop6zUd7836hkizh51E4ew6kFLTGnNt3zl3XcO187aF2+htCxiZCY6md3NstJod1zshoXf2slxxPWRUK92sS0XzlVT6ahVTdq2tAS3hOY3ldtnfOXMmMQPNn9OqlALQGOH/hJatBV6ZQxpp0QJBAMGKsxJkg3M6sZEO4zUgWgnGN+uS6nwAWpCfBY7eDvYSN78BxGGLgn+t40c0R8M/n85IM8OUmfHxTGWN3hYyGuUCQQCs+Kg1ypul9XLTWrVApyvZxY4ZxjAWG4yW4xqbUtHWUkGyle7Dy8xiYezl0kWN81lJNTz3qrJ4ZvM825+7tPw1AkBDPNxZV3ITZiCqNHHa0xJ0sthajv/HdJgCBjz4FU09T7buNL705HLByLdc1VzZCBGMqKjTGZ0h4KKZ5V9ydpXBAkABpZx0Zql0uxGM0aBILU9Nk4P22tw6WajNBvyJ3hABamcVvDe5xYb8qNIInifrYhXHjKo38XghjVljivPKZb7BAkAt4pCUYLI7LzLeSh/lnVxA7jOaRI3UsngCXn9VA4f+dkNcKAftRQqe7ytudqG07GKoInnDiyjrYe4kTLym8zoc";
$pubKey = "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCCxS3ZhcHzRwhpN76cI/xUfV6RPY41a3i5TmiBJEgDljn88Qhe6THkstFA66ES4Hyw+J0G1HCuQE+4IPDxDOh/fUhB3KQZEyoXytYiOYlPbDf1hi9hbeU/7sKPig+19wrKjA9B7DELb/2ByXgM/0j3+c8g+SrbcKIV18zvGtz9aQIDAQAB";

//生成签名
$sign = genSign("test", $privateKey);
//校验签名
$rs = verifySign("test", $sign, $pubKey);

$str = 'zmqVAky/ssUARbDUidliJR8SxSASPGFbY2hNwDtOFSOlyLkWqfumWO/no5idcb8fw90Mb5ZarnXmPc/MLhvfGobEuoqQRaEbaOVCwcxbdbFiwqzFm4AbOkHKdWEMAVVlqtedIwLL/N3MdPzlLaNJvbRt84sltRuqMfpx48FAlf6n4ppQmpe/o5pO9RlZKNlSP9snqvw8RN4amkNvA3YhpcOXETEbMbCmpVf6H4+tthddFP4RLSiNLhSTo51EH8xc4fkGBfpMwidfcGNh8NLwW4lo2kigphg8VzdN/s9cq5xauPe9F9XVGtaxoIzeUp0GVy5FFeNJU891Tu8moYHPY3bRfjS49PSDgFAy7AG2ajhbVSrJ7dJUXsZLotxWtGbhXednxbR7ksZ0wR//P0j4vzeQj2+QkZlSqsnuc2uWToCQPu1fJ2BzC0B0WFJt8elHT6xlCp4VzPdaYISTG0JO0OpagM5NYE4d6sgYN136W54=';
var_dump(json_decode(decrypt($str,'v3fotW5LXw5AED5QxRswBQ=='),true));
//echo encrypt('{"outTradeNo":"20190313100920629","totalAmount":"1.00","payeeBankName":"\u62db\u5546\u94f6\u884c","payeeSubBranchName":"\u6df1\u5733\u5206\u884c\u79d1\u53d1\u652f\u884c","city":"\u6df1\u5733","payeeAcc":"6214837884634589","payeeName":"\u738b\u5947","privateFlag":"S","currency":"CNY","chargeType":1,"notifyUrl":"http:\/\/spaytest.8dsk.com\/call\/bghdaifu"}','v3fotW5LXw5AED5QxRswBQ==');