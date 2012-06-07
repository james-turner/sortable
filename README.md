# Sortable

Ever wanted to sort a bunch of objects by accessor or attribute value?
Yeah! Me too!

## Begin



## Usage (Sorting)

    $model1 = new stdClass();
    $model1->id = 1;
    $model1->category = "cat1";
    $model2 = new stdClass();
    $model2->id = 2;
    $model1->category = "cat2";

    $models = array($model1, $model2);

    $container = new \Sortable\Container($models);

    $container->sortBy('id','down');


    foreach($container as $item){
       echo $item->id;
    }

## Usage (limiting)

    $container = new \Sortable\Container($models);
    $container->limit(1);

    foreach($container as $item){
       echo $item->id;
    }


## Usage (grouping)

    $container = new \Sortable\Container($models);
    $container->groupBy('category');

    foreach($container as $category => $items){
       echo $category;
       $items->limit(1);
       foreach($items as $item){
           echo $item->id;
       }
    }


## Limitations

Sorting does NOT occur until the container is utilised in a meaningful way.
i.e. until you count() or foreach() or array[offset]

## Catches

It does not handle more than 9998 objects at once. This seems to be a weird limitation
of ArrayObject causing a segfault in PHP. Perhaps this is just a bug I haven't figured out
yet, but it seems that way at present.