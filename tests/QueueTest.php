<?php
use PHPUnit\Framework\TestCase;

class QueueTest extends TestCase {
    
    /**
     * Test Fixture: set_up
     */
    protected function set_up(): void {
        // This empty Queue instance is called the test "fixture"
        $this->queue = new Queue;
    }

    /**
     * Test Fixture: tear_down
     */
    protected function tear_down() : void {
        
        //Use unset if you have memory constraints on your system
        unset($this->queue);

    }
    
    /**
     * @test whether the count in an empty queue returns 0
     */
    public function new_queue_is_empty() {
       
        $this->assertEquals(0,$this->queue->getCount());
    }

    /**
     * @test whether an $item was added to the queue
     */
    public function an_item_is_added_to_the_queue() {
        
        $this->queue->push('green');
        
        $this->assertEquals(1,$this->queue->getCount());
    }

    /**
     * @test whether an $item was removed from the queue
     */
    public function an_item_is_removed_from_the_queue() {

        $this->queue->push('green');
        
        $item = $this->queue->pop();
        
        $this->assertEquals(0,$this->queue->getCount());
        
        $this->assertEquals('green',$item);
    }
}