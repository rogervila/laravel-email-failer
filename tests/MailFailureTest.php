<?php

namespace Tests;

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

        $address = 'foo@foo.foo';
        $mailer = new \LaravelEmailFailer\MailFailer();
        $this->app->instance('mailer', $mailer);

        $mailable = new FakeMailable;
        $mailable->subject('test')->to($address);

        $mailer->send($mailable);
    }

    public function testMailFailuresContainsAddress()
    {
        try {
            $address = 'foo@foo.foo';
            $mailer = new \LaravelEmailFailer\MailFailer();
            $this->app->instance('mailer', $mailer);

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
            $mailer = new \LaravelEmailFailer\MailFailer();
            $this->app->instance('mailer', $mailer);

            $mailable = new FakeMailable;
            $mailable->subject('test')->to($addresses);

            $mailer->send($mailable);
        } catch (TransportException) {
            $this->assertCount(0, array_diff($addresses, $mailer->failures()));
        }
    }
}
