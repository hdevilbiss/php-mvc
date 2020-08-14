<?php

/**
 * Queue
 * A first-in, first-out data object
 */
class Queue {
    /**
     * @var array   : Items in the queue
     */
    protected $items = [];

    /**
     * METHOD: push
     * @param mixed : $item in the queue
     * @return void : Add the $item to the $items array
     */
    public function push($item) {
        $this->items[] = $item;
    }

    /**
     * METHOD: pop
     * @param void
     * @return mixed    : Return the value of the last element, shortening the $items array by 1
     */
    public function pop() {
        return array_shift($this->items);
    }

    /**
     * METHOD: getCount
     * @param void
     * @return integer  : Quantity of $item in $items array
    */
    public function getCount() {
        return count($this->items);
    }
}