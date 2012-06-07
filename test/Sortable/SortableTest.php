<?php

namespace Sortable\Test;

require_once 'Model.php';

class SortableContainerTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var array
     */
    private $models;

    public function __construct(){
        $this->models = array(
            "bob"     => new Model("bob"),
            "fran"    => new Model("fran"),
            "wilfred" => new Model("wilfred", "cat2"),
            "jim"     => new Model("jim"),
            "grant"   => new Model("grant", "cat2"),
        );
    }

    /**
     * @test
     */
    public function countingAContainerInvokesSorting(){

        $actual = new \Sortable\Container($this->models);
        $actual->sortBy("id", "down")->limit(2); //->sortBy("published_start_time", "up")->limit(1,10);

        $this->assertEquals(2, count($actual));

    }

    /**
     * @test
     */
    public function offsetAccessInvokesSorting(){

        $actual = new \Sortable\Container($this->models);
        $actual->sortBy('id', 'down');
        $actual->execute();

        // Check keys to make sure we've not whittled down.
        $arrayCopy = array_values($actual->getArrayCopy());

        $this->assertEquals($this->models["grant"], $arrayCopy[0]);
        $this->assertEquals($this->models["jim"], $arrayCopy[1]);
        $this->assertEquals($this->models["wilfred"], $arrayCopy[2]);
        $this->assertEquals($this->models["fran"], $arrayCopy[3]);
        $this->assertEquals($this->models["bob"], $arrayCopy[4]);
    }

    /**
     * @test
     */
    public function addAnElementAfterInitialCreationStillGetsSorted(){

        $sortableContainer = new \Sortable\Container($this->models);
        $sortableContainer->sortBy("id", "down")->limit(2);

        $sortableContainer[] = $jim = new Model("jim");
        $sortableContainer->execute();

        $this->assertEquals($jim, $sortableContainer[0]);
        $this->assertEquals($this->models["grant"], $sortableContainer["grant"]);
    }


    /**
     * @test
     */
    public function groupByAttributeReturnsGroupedItems(){

        $container = new \Sortable\Container($this->models);
        $container->groupBy('category', 'down');

        // Assert
        $this->assertEquals(2, count($container));

        // Grouped containers have the corret number of items.
        $this->assertEquals(3, count($container['cat1']));
        $this->assertEquals(2, count($container['cat2']));

        // The grouped items are keyed correctly!
        $array = $container->getArrayCopy();
        $keys = array_keys($array);

        $this->assertEquals("cat2", $keys[0]);
        $this->assertEquals("cat1", $keys[1]);

    }

    /**
     * @test
     */
    public function groupByWithLimitReturnsLimitedGroupedResults(){

        $container = new \Sortable\Container($this->models);
        $container->groupBy('category', 'up')->limit(1);

        $this->assertEquals(1, count($container));

        $this->assertEquals(3, count($container["cat1"]));

        $this->assertEquals($this->models["bob"], $container["cat1"]["bob"]);
        $this->assertEquals($this->models["fran"], $container["cat1"]["fran"]);
        $this->assertEquals($this->models["jim"], $container["cat1"]["jim"]);

    }

    /**
     * @test
     */
    public function groupByWithSortByReturnsSortedGroupedResults(){

        $models = $this->models;

        $container = new \Sortable\Container($models);
        $container->sortBy('name')->groupBy('category', 'down');

        $this->assertEquals(array($models['grant'], $models["wilfred"]), array_values($container["cat2"]->getArrayCopy()));

    }

    /**
     * @test
     */
    public function sortingByCamelCaseAttribute(){

        $container = new \Sortable\Container($this->models);

        $container->sortBy('camel_case_name');

        $this->assertEquals($this->models['bob'], $container['bob']);

    }

    /**
     * @test
     */
    public function sortingByInvalidAttributeCausesInvalidArgumentExceptionToBeThrown(){

        $this->setExpectedException('InvalidArgumentException');

        // Execution
        $container = new \Sortable\Container($this->models);

        $container->sortBy('random_attribute_name');

        // Assertion (causes invocation).
        $this->assertEquals($this->models['bob'], $container['bob']);


    }



}