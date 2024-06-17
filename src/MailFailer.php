<?php

namespace LaravelEmailFailer;

use Illuminate\Container\Container;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Mail\MailManager;
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

            throw new TransportException(
                'Email "error" does not comply with addr-spec of RFC 2822.'
            );
        } catch (Throwable $e) {
            throw new TransportException(
                $e->getMessage()
            );
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
}
