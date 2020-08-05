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
}