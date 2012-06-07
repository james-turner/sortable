<?php

namespace Sortable;

class Container extends Sortable {

    public function execute(){
        $this->sort();
        return $this;
    }

}