<?php

use PHPUnit\Framework\TestCase;

class MockTest extends TestCase {
    public function testMock() {
        $mailer = new Mailer;

        $result = $mailer->sendMessage('dave@example.com','Hello');

        var_dump($result);
    }
}