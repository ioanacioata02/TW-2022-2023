<?php

//creditds to https://stackoverflow.com/questions/5631387/php-array-to-postgres-array


class Utils
{

    static public function  notAllowed():void
    {
        http_response_code(405);
        echo json_encode(["message"=>"Action not allowed"]);
    }
    static public function getBody(): array
    {
       $data = json_decode(file_get_contents('php://input'), true);
       if($data==null)
       {


           self::throwError(400, ["message"=>"Body invalid"]);
           exit(0);
       }
       return $data;
    }
    static public function throwError(int $errorCode, ?array $additionalInfo):void
    {
            http_response_code($errorCode);
            echo json_encode($additionalInfo);
    }

}