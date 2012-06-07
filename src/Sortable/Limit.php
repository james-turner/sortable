<?php

namespace Sortable;

class Limit extends Sortable {
    private $offset;
    private $limit;

    /**
     * @param Sortable $sortable
     * @param int $offset
     * @param int $limit
     */
    public function __construct(Sortable $sortable, $limit = 0, $offset = 1){
        parent::__construct($sortable);
        $this->offset = $offset;
        $this->limit = $limit;
    }

    /**
     * @return void
     */
    public function execute(){

        $offset = max(1, $this->offset);
        $sliced = array_slice($this->getArrayCopy(), $offset-1, $this->limit, true);
        $this->exchangeArray($sliced);
    }
}
