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

    public function send($view, array $data = [], $callback = null)
    {
        try {
            array_push($this->failedRecipients, $view->to[0]['address']);

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
