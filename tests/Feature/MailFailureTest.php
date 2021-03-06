<?php

namespace Tests\Feature;

class FakeMailable extends \Illuminate\Mail\Mailable
{
    //
}

class MailFailureTest extends \Orchestra\Testbench\TestCase
{
    public function testMailFails()
    {
        $this->expectException(\Swift_TransportException::class);

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
        } catch (\Exception $e) {
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
        } catch (\Exception $e) {
            $this->assertCount(0, array_diff($addresses, $mailer->failures()));
        }
    }
}
