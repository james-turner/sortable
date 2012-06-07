<?php

namespace Sortable;

class GroupBy extends Sortable {

    const UP    = 'up';
    const DOWN  = 'down';

    private $groupBy;
    private $order;

    public function __construct(Sortable $container, $groupBy, $order = self::UP){
        parent::__construct($container);
        $this->groupBy = $groupBy;
        $this->order = $order;
    }

    public function execute(){

        $containers = array();

        foreach($this as $key => $item){
            $reflect = new \ReflectionObject($item);

            $reflectProp = $reflect->getProperty($this->groupBy);
            $reflectProp->setAccessible(true);
            $groupKey = $reflectProp->getValue($item);
            if(array_key_exists($groupKey, $containers)){
                $containers[$groupKey][$key] = $item;
            } else {
                $containers[$groupKey] = new Container(array($key=>$item));
            }
        }
        // Cleardown memory usage.
        unset($reflect, $reflectProp);

        // Reset internal array with new container array.
        $this->exchangeArray($containers);

        // Perform ordering up/down.
        $order = $this->order;
        $this->uksort(function($lKey, $rKey)use($order){
            if($order === GroupBy::UP){
                return strnatcasecmp($lKey, $rKey);
            } else {
                return strnatcasecmp($rKey, $lKey);
            }
        });

    }

}