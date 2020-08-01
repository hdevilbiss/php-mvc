<?php
use PHPUnit\Framework\TestCase;

/**
 * ClassTest example : ExampleTest
 */

class ExampleTest extends TestCase {
    
    /**
     * ASSERTION: AddingTwoPlusTwoResultsInFour
     */
    public function testAddingTwoPlusTwoResultsInFour() {
        
        /**
         * Call the assertEquals method, which is a TestCase parent method, on the current object
         * 4    = Expected Value
         * 2+2  = Actual Value to Compare
         */
        $this->assertEquals(4, 2+2);
    }
}
?>