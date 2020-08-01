<?php
use PHPUnit\Framework\TestCase;

class FunctionsTest extends TestCase {
    /**
     * @test
     */
    public function returns_the_correct_sum() {
        require 'themes/functions.php';

        $this->assertEquals(4,add(2,2));
        $this->assertEquals(8,add(3,5));
    }

    /**
     * @test
     */
    public function does_not_return_the_incorrect_sum() {
        $this->assertNotEquals(5,add(2,2));
    }
}
?>