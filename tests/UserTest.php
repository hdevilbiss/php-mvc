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

    public function testNotificationIsSent() {
        $user = new User;

        // Test Double: Mocked Class
        $mock_mailer = $this->createMock(Mailer::class);

        /**
         * Stubbed Method Assertions:
         * sendMessage method only gets called once
         * sendMessage arguments are equal to some hard-code values
         * sendMessage returns true
         */ 
        $mock_mailer->expects($this->once())->method('sendMessage')->with($this->equalTo('dave@example.com'),$this->equalTo('Hello'))->willReturn(true);

        $user->setMailer($mock_mailer);

        $user->email = 'dave@example.com';
        $this->assertTrue($user->notify("Hello"));
    }
}
?>