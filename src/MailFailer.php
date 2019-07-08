<?php

namespace LaravelEmailFailer;

use Illuminate\Support\Testing\Fakes\MailFake;

class MailFailer extends MailFake
{
    public function send($view, array $data = [], $callback = null)
    {
        throw new \Exception(); // WIP
    }
}
