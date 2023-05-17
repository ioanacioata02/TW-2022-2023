<?php

class Jwt
{

    /**
     * @param array $payload -> Is the data that we want to include in the token in our cause the data should be the username,
     * the creation date, the expiration date, and the status of the user (normal user, professor, admin)
     * @param string $secretKey
     * SHOULD BE THE name of the env that contains the secret key DO NOT PUT THE SECRET KEY IN THE CODE
     * @param int $hoursUntilExpiration how many hours the token is valid the default value is 24
     * @return string
     */
        static public function generateToken(array $payload, string $secretKey, int $hoursUntilExpiration=24) : string
        {
                $secretKey =  getenv($secretKey);
                $header =  base64_encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
                $payload["creationDate"]=time();
                $payload["expirationDate"]= $payload["creationDate"]  + $hoursUntilExpiration*3600;
                $payload = base64_encode(json_encode($payload));
                $signature =  hash_hmac('sha256',  "$header.$payload", $secretKey, true);
                $signature = base64_encode($signature);
                return "$header.$payload.$signature";
        }
        //the function will return null if the jwt was tempered or the decoded data
        static public function validateToken(string $token, string $secretKey) : ?array
        {
            $secretKey =  getenv($secretKey);
            list($header, $payload, $signature) = explode('.', $token);
            $decodedSignature = base64_decode($signature);
            $expectedSignature=  hash_hmac('sha256', "$header.$payload", $secretKey, true);
            if (!hash_equals($decodedSignature, $expectedSignature)) {
                //the token was modified
                return null;
            }
            $decodedPayload = json_decode(base64_decode($payload), true);
            if(time()>$decodedPayload["expirationDate"])
            {
                //the token expired
                return null;
            }
            return $decodedPayload;
        }
}