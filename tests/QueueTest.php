<?php
use PHPUnit\Framework\TestCase;

class QueueTest extends TestCase {
    /**
     * @test whether the count in an empty queue returns 0
     */
    public function new_queue_is_empty() {
        //This main test is called a producer because other tests depend on it
        
        $queue = new Queue;

        $this->assertEquals(0,$queue->getCount());

        return $queue;
    }

    /**
     * @test whether an $item was added to the queue
     * @depends new_queue_is_empty
     */
    public function an_item_is_added_to_the_queue(Queue $queue) {
        // This "dependent" test is called a consumer

        $queue->push('green');
        $this->assertEquals(1,$queue->getCount());

        return $queue;
    }

    /**
     * @test whether an $item was removed from the queue
     * @depends an_item_is_added_to_the_queue
     */
    public function an_item_is_removed_from_the_queue(Queue $queue) {

        $item = $queue->pop();

        $this->assertEquals(0,$queue->getCount());

        $this->assertEquals('green',$item);
    }
}