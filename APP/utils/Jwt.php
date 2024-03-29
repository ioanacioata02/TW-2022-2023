<?php

declare(strict_types=1);
class Jwt
{

    private static $payload;
    /**Will return true if the token is valid, false otherwise and will send a error message to the client
     * @param $status
     * @return bool
     */
    static public function validateAuthorizationToken(string $secret, int $status=-1): bool
    {
        if(!isset(getallheaders()["Authorization"])) {
            /*
            http_response_code(401);
            echo json_encode(["message"=>"Please provide a token"]);*/
            return false;
        }
        $token= getallheaders()["Authorization"];
        if($token === null || empty($token)){
            http_response_code(401);
            echo json_encode(["message"=>"Missing token"]);
            return false;
        }
        $payload =  Jwt::validateToken($token, $secret);
        if (!isset($payload))
        {
            http_response_code(401);
            echo json_encode(["message"=>"Invalid token"]);
            return false;
        }
        if($payload["status"]<$status)
        {
            http_response_code(403);
            echo json_encode(["message"=>"You don't have the permission for this action"]);
            return false;
        }
        return true;
    }
    /**
     * @param array $payload -> Is the data that we want to include in the token in our cause the data should be the username,
     * the creation date, the expiration date, and the status of the user (normal user, professor, admin)
     * @param string $secretKey
     * SHOULD BE THE name of the env that contains the secret key DO NOT PUT THE SECRET KEY IN THE CODE
     * @param int $hoursUntilExpiration how many hours the token is valid the default value is 24
     * @return string
     */
    static public function generateToken(array $payload, string $secretKey, int $hoursUntilExpiration = 24): string
    {
        $secretKey = getenv($secretKey);
        $header = base64_encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        $payload["creationDate"] = time();
        $payload["expirationDate"] = $payload["creationDate"] + $hoursUntilExpiration * 3600;
        $payload = base64_encode(json_encode($payload));
        $signature = hash_hmac('sha256', "$header.$payload", $secretKey, true);
        $signature = base64_encode($signature);
        return "$header.$payload.$signature";
    }

    /**This function will get the token from the header and if the token is valid will return is value
     * @param string $token
     * @param string $secretKey
     * @return array|null
     */
    static private function validateToken(string $token, string $secretKey): ?array
    {
        $secretKey = getenv($secretKey);
        list($header, $payload, $signature) = explode('.', $token);
        $decodedSignature = base64_decode($signature);

        $expectedSignature = hash_hmac('sha256', "$header.$payload", $secretKey, true);
        if (!hash_equals($decodedSignature, $expectedSignature)) {
            //the token was modified
            return null;
        }
        $decodedPayload = json_decode(base64_decode($payload), true);
        if (time() > $decodedPayload["expirationDate"]) {
            return null;
        }
        Jwt::$payload=$decodedPayload;
        return $decodedPayload;
    }
    static public function getPayload()
    {
        return Jwt::$payload;
    }

    static public function getIdFromToken(): int{
        $token= getallheaders()["Authorization"];
        $tokenParts = explode('.', $token);
        $decodedPayload = json_decode(base64_decode($tokenParts[1]), true);
        return $decodedPayload["id"];
    }
}