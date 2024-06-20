<?php

namespace LaravelEmailFailer;

use Illuminate\Container\Container;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Mail\MailManager;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Testing\Fakes\MailFake;
use Symfony\Component\Mailer\Exception\TransportException;
use Throwable;

class MailFailer extends MailFake
{
    /**
     * Array of failed recipients.
     *
     * @var array
     */
    protected $failedRecipients = [];

    public function __construct(protected ?Application $app = null)
    {
        $app ??= Container::getInstance();
        parent::__construct($app->make(MailManager::class));
    }

    /**
     * Send a new message using a view.
     *
     * @param  Mailable|string|array  $view
     * @param  \Closure|string|null  $callback
     * @return void
     *
     * @throws TransportException
     */
    public function send($view, array $data = [], $callback = null)
    {
        try {
            if ($view instanceof Mailable) {
                foreach ($view->to as $to) {
                    array_push($this->failedRecipients, $to['address']);
                }
            }

            throw new TransportException('Connection could not be established with host "1.2.3.4:1234": stream_socket_client(): Unable to connect to 1.2.3.4:1234 (Connection refused)');
        } catch (Throwable $e) {
            throw new TransportException($e->getMessage());
        }
    }

    /**
     * Get the array of failed recipients.
     *
     * @return array
     */
    public function failures()
    {
        return $this->failedRecipients;
    }

    public static function bind(?Application $app = null): self
    {
        Mail::swap($instance = new self($app));

        return $instance;
    }
}
