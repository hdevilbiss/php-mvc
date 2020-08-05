<?php
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase {
    /**
     * @test
     */
    public function returns_full_name() {
        
        $user = new User;
        $user->first_name = "Teresa";
        $user->last_name = "Green";

        $this->assertEquals('Teresa Green',$user->getFullName());
    }

    /**
     * @test
     */
    public function full_name_is_empty_by_default() {
        $user = new User;
        $this->assertEquals('',$user->getFullName());
    }
}
?>