<?php
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase {

    public function testReturnsFullName() {
        
        $user = new User;
        $user->first_name = "Teresa";
        $user->last_name = "Green";

        $this->assertEquals('Teresa Green',$user->getFullName());
    }

    public function testFullNameIsEmptyByDefault() {
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

    public function testCannotNotifyUserWithNoEmail() {
        $user = new User;

        /**
         * If setMethods has no arguments or receives null, then none of the methods will be stubbed: original code will execute. You can also pass in array containing the names of the methods that will be stubbed.
         * Note that ...getMockBuilder...getMock is equivalent to ...createMock.
         */
        $mock_mailer = $this->getMockBuilder(Mailer::class)->setMethods(null)->getMock();

        //Inject dependency into User object
        $user->setMailer($mock_mailer);


        // Assertion: Expect Exception from notify
        $this->expectException(Exception::class);

        //Call notify method
        $user->notify("Hello");
    }
}
?>