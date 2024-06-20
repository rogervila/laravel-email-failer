<?php

namespace Tests;

use Illuminate\Support\Facades\Mail;
use LaravelEmailFailer\MailFailer;
use Symfony\Component\Mailer\Exception\TransportException;

class FakeMailable extends \Illuminate\Mail\Mailable
{
    //
}

class MailFailureTest extends \Orchestra\Testbench\TestCase
{
    public function testMailFails()
    {
        $this->expectException(TransportException::class);
        $this->expectExceptionMessage('Connection could not be established with host "1.2.3.4:1234": stream_socket_client(): Unable to connect to 1.2.3.4:1234 (Connection refused)');

        $address = 'foo@foo.foo';
        $mailer = MailFailer::bind();

        $mailable = new FakeMailable;
        $mailable->subject('test')->to($address);

        $mailer->send($mailable);
    }

    public function testMailFailuresContainsAddress()
    {
        try {
            $address = 'foo@foo.foo';
            $mailer = MailFailer::bind();

            $mailable = new FakeMailable;
            $mailable->subject('test')->to($address);

            $mailer->send($mailable);
        } catch (TransportException) {
            $this->assertTrue(in_array($address, $mailer->failures()));
        }
    }

    public function testMailFailuresContainsMultipleAddresses()
    {
        try {
            $addresses = ['foo@foo.foo', 'bar@bar.bar'];
            $mailer = MailFailer::bind();

            $mailable = new FakeMailable;
            $mailable->subject('test')->to($addresses);

            $mailer->send($mailable);
        } catch (TransportException) {
            $this->assertCount(0, array_diff($addresses, $mailer->failures()));
        }
    }

    public function testFacadeSwap()
    {
        MailFailer::bind();

        $addresses = ['foo@foo.foo', 'bar@bar.bar'];
        $mailable = new FakeMailable;
        $mailable->subject('test')->to($addresses);

        try {
            Mail::send($mailable);
        } catch (TransportException) {
            $this->assertCount(0, array_diff($addresses, Mail::failures()));
        }
    }
}
