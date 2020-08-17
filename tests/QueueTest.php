<?php
use PHPUnit\Framework\TestCase;

class QueueTest extends TestCase {
    
    protected static $queue;
    
    /**
     * METHOD: setUp
     * This method gets run before each test method to empty the $queue before each test method
     */
    protected function setUp(): void
    {
        static::$queue->clear();        
    }
    
    /**
     * METHOD: setUpBeforeClass
     * This method gets run only once, before the first test method
     */
    public static function setUpBeforeClass(): void
    {
        static::$queue = new Queue;        
    }
    
    /**
     * METHOD: tearDownAfterClass
     * This method gets run only once, after the last est method
     */
    public static function tearDownAfterClass(): void
    {
        static::$queue = null;        
    }    
        
    public function testNewQueueIsEmpty()
    {
        $this->assertEquals(0, static::$queue->getCount());
    }

    public function testAnItemIsAddedToTheQueue()
    {
        static::$queue->push('green');
        
        $this->assertEquals(1, static::$queue->getCount());
    }

    public function testAnItemIsRemovedFromTheQueue()
    {
        static::$queue->push('green');
                
        $item = static::$queue->pop();
        
        $this->assertEquals(0, static::$queue->getCount());

        $this->assertEquals('green', $item);
    }
    
    public function testAnItemIsRemovedFromTheFrontOfTheQueue()
    {
        static::$queue->push('first');
        static::$queue->push('second');
        
        $this->assertEquals('first', static::$queue->pop());
    }

    /**
     * @test whether adding the max number of $items to the $queue equals the MAX_ITEMS constant
     */
    public function testMaxNumberOfItemsCanBeAdded() {
        
        for ($i = 0; $i < Queue::MAX_ITEMS; $i++) {
            static::$queue->push($i);
        }
        $this->assertEquals(Queue::MAX_ITEMS, static::$queue->getCount());
    }

    /**
     * @test whether an Exception gets thrown when more $items than MAX_ITEMS gets added to the $queue, and whether the Exception message matches our expectation
     */
    public function testExceptionThrownWhenAddingItemToFullQueue() {
        for ($i = 0; $i < Queue::MAX_ITEMS; $i++) {
            static::$queue->push($i);
        }
        
        /**
         * PHPUnit has a few built-in methods to deal with expecting Exceptions:
         * expectException
         * expectExceptionCode
         * expectExceptionMessage
         * expectExceptionMessageRegExp
         */
        $this->expectException(QueueException::class);
        
        $this->expectExceptionMessage("Queue is full");

        static::$queue->push("Broke the camel's back");
    }
}