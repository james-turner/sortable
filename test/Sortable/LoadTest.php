<?php

namespace Sortable\Test;

require_once 'Model.php';

/**
 * This load test has been known to trigger exit 139 (segfault).
 * Thus huge container test is limited to 10,000 records.
 */

class LoadTest extends \PHPUnit_Framework_TestCase {

    /**
     * @test
     * Largest container
     */
    public function maxSizeContainer(){
        $models = array();
        for($i=0; $i<9997; $i++){
            $num = ($i%2 == 0) ? $i+1 : $i;
            $models[] = new Model("name");
        }

        $container = new \Sortable\Container($models);

        // Forces a sort to reverse the whole set!
        $container->sortBy('name','down');

        $this->withTiming(function()use($container){
            // Implicit invocation
            count($container);
            return "Max size container run";
        });

        unset($container, $models);
    }

    /**
     * Large normal sorting
     * @test
     */
    public function normalUASortLimit(){

        $models = array();
        for($i=0; $i<10000; $i++){
            $models[] = new Model("name:$i");
        }

        $this->withTiming(function()use($models){
            uasort($models, function($l, $r){
                return strnatcasecmp($r->name, $l->name);
            });
            return "Normal uasort comparison";
        });

    }

    /**
     * @param Closure $yield
     * @return void
     */
    private function withTiming($yield){
        $start = microtime(true);
        $prefix = $yield();
        $time = microtime(true) - $start;
        echo sprintf("%s took %.5f seconds\n", $prefix, $time);
    }



}