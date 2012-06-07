<?php

namespace Sortable;

abstract class Sortable extends \ArrayObject {

    /**
     * @var array
     */
    private $queue = array();

    /**
     * @param $attribute
     * @param string $direction
     * @return Sortable
     */
    final public function sortBy($attribute, $direction = SortBy::UP){
        $this->queue[] = function($container)use($attribute, $direction){
            return new SortBy($container, $attribute, $direction);
        };
        return $this;
    }

    /**
     * @param $number
     * @param int $offset
     * @return Sortable
     */
    final public function limit($number, $offset = 1){
        $this->queue[] = function($container)use($number, $offset){
            return new Limit($container, $number, $offset);
        };
        return $this;
    }

    /**
     * @param $attribute
     * @param string $ordering
     * @return Sortable
     */
    final public function groupBy($attribute, $ordering = GroupBy::UP){
        $this->queue[] = function($container)use($attribute, $ordering){
            return new GroupBy($container, $attribute, $ordering);
        };
        return $this;
    }

    public function getIterator(){
        $this->sort();
        return parent::getIterator();
    }

    public function offsetGet($offset){
        $this->sort();
        return parent::offsetGet($offset);
    }

    public function count(){
        $this->sort();
        return parent::count();
    }

    /**
     * Perform the sorting requires prior to access.
     * @return void
     */
    protected function sort(){

        $last = $this;
        while($block = array_shift($this->queue)){
            $sortable = $block($last);
            $sortable->execute();
            $last = $sortable;
        }
        parent::exchangeArray($last->getArrayCopy());

    }

    abstract public function execute();
}
