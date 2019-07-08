<?php

namespace LaravelEmailFailer;

use Illuminate\Support\Testing\Fakes\MailFake;

class MailFailer extends MailFake
{
    /**
     * Array of failed recipients.
     *
     * @var array
     */
    protected $failedRecipients = [];

    /**
     * Send a new message using a view.
     *
     * @param  string|array  $view
     * @param  array  $data
     * @param  \Closure|string  $callback
     * @return void
     * @throws \Swift_TransportException
     */
    public function send($view, array $data = [], $callback = null)
    {
        try {
            foreach ($view->to as $to) {
                array_push($this->failedRecipients, $to['address']);
            }

            throw new \Swift_TransportException(
                'Expected response code 354 but got code "554", with message "554 5.5.1 Error: no valid recipients'
            );
        } catch (\Exception $e) {
            throw new \Swift_TransportException(
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
