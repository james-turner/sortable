<?php

namespace Sortable;

class SortBy extends Sortable {

    const UP = "up";
    const DOWN = "down";

    private $sortBy;
    private $direction;

    public function __construct(Sortable $container, $attribute, $direction = self::UP){
        parent::__construct($container);
        $this->sortBy = $attribute;
        $this->direction = $direction;
    }

    public function execute(){

        // Determine comparison ordering.
        if($this->direction == self::UP){
            $comparisons = array(1,-1);
        } else {
            $comparisons = array(-1,1);
        }

        // Convert attribute into expected internal attribute in the model.
        $propName = lcfirst(str_replace(" ", "", ucwords(str_replace("_", " ", $this->sortBy))));
        $methodName = "get".ucfirst($propName);

        $leftRightCompare = function($l, $r)use($comparisons){
            if($l === $r){
                return 0;
            }
            return $l > $r ? $comparisons[0]: $comparisons[1];
        };

        $comparison = function($left, $right)use($methodName, $propName, $comparisons, $leftRightCompare){
            if(method_exists($left, $methodName) && method_exists($right, $methodName)){
                $method = new \ReflectionMethod($left, $methodName);
                $method->setAccessible(true);
                $res = $leftRightCompare($method->invoke($left), $method->invoke($right));
                unset($method);
            } elseif(property_exists($left, $propName) && property_exists($right, $propName)){
                $property = new \ReflectionProperty($left, $propName);
                $property->setAccessible(true);
                $res = $leftRightCompare($property->getValue($left), $property->getValue($right));
                unset($property);
            } else {
                throw new \InvalidArgumentException("The objects do not conform to having the same property '{$propName}' or method '{$methodName}()'.");
            }
            /**
             * @note Memory consumption conern:
             * clear down reflection objects to
             * stop them hanging around in memory
             * unnecessarily.
             */
            return $res;
        };

        // Custom user function sorting.
        $this->uasort($comparison);

    }
}