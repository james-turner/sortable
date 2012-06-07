<?php

namespace Sortable\Test;

class Model {

    private static $identity = 1;

    private $id;
    private $category;

    private $camelCaseName = 'camelCase';

    public $name;


    public function __construct($name, $category = 'cat1'){
        $this->id = self::$identity;
        self::$identity++;
        $this->name = $name;
        $this->category = $category;
    }

    public function getId(){
        return $this->id;
    }
}