<?php

abstract class Controller
{
    protected $model;
    public function __construct()
    {
        $classModel = str_replace("Controller", "Model", get_class($this));
        $this->model= new $classModel();
    }
    abstract protected function processRequest(string $method, ?string $actions);
    protected function fieldExists(array $json, string $field)
    {
        if(isset($json[$field]))
            return $json[$field];
        Utils::throwError(422, ["message"=>"Missing field: ".$field]);
        exit(0);
    }
}