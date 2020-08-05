<?php
use PHPUnit\Framework\TestCase;

class QueueTest extends TestCase {
    /**
     * @test whether the count in an empty queue returns 0
     */
    public function new_queue_is_empty() {
        $queue = new Queue;

        $this->assertEquals(0,$queue->getCount());
    }

    /**
     * @test whether an $item was added to the queue
     */
    public function an_item_is_added_to_the_queue() {
        $queue = new Queue;

        $queue->push('green');
        $this->assertEquals(1,$queue->getCount());
    }

    /**
     * @test whether an $item was removed from the queue
     */
    public function an_item_is_removed_from_the_queue() {
        $queue = new Queue;

        $queue->push('green');

        $item = $queue->pop();

        $this->assertEquals(0,$queue->getCount());

        $this->assertEquals('green',$item);
    }
}