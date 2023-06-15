<?php

class Solutions extends Controller
{
    public function __consstruct()
    {
        parent::__construct();
    }

    public function processRequest(string $method, ?string $actions)
    {
        $params = $actions ? $this->processAction($actions) : null;
        switch ($method)
        {
            case "POST":
                break;
            case "GET":
                break;
        }
    }
}