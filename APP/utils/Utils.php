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
           http_response_code(400);
           throw new Exception("The body is invalid");
       }
       return $data;
    }

}