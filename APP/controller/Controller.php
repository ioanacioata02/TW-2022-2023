<?php

abstract class Controller
{
    protected $model;
    public function __construct()
    {
        $classModel = str_replace("Controller", "Model", get_class($this));
        $this->model= new $classModel();
    }
}