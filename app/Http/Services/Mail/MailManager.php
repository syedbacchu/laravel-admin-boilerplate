<?php

namespace App\Http\Services\Mail;

class MailManager
{
    public function make(): MailerInterface
    {
        $settings = settings(); // fetch from DB

        $config = [
            'driver'        => strtolower($settings['mail_driver'] ?? env('MAIL_DRIVER', 'smtp')),
            'host'          => $settings['email_host'] ?? env('MAIL_HOST'),
            'port'          => $settings['email_port'] ?? env('MAIL_PORT'),
            'username'      => $settings['email_username'] ?? env('MAIL_USERNAME'),
            'password'      => $settings['email_password'] ?? env('MAIL_PASSWORD'),
            'encryption'    => $settings['email_encryption'] ?? env('MAIL_ENCRYPTION'),
            'from_address'  => $settings['mail_from_address'] ?? env('MAIL_FROM_ADDRESS'),
            'from_name'     => $settings['app_title'] ?? env('MAIL_FROM_NAME', config('app.name')),
        ];

        return new MailService($config);
    }

    /**
     * Check if mail is properly configured
     */
    public function isConfigured(): bool
    {
        $settings = settings();

        $driver = strtolower($settings['mail_driver'] ?? env('MAIL_DRIVER', 'smtp'));
        $host = $settings['email_host'] ?? env('MAIL_HOST');
        $port = $settings['email_port'] ?? env('MAIL_PORT');
        $username = $settings['email_username'] ?? env('MAIL_USERNAME');
        $password = $settings['email_password'] ?? env('MAIL_PASSWORD');
        $fromAddress = $settings['mail_from_address'] ?? env('MAIL_FROM_ADDRESS');

        // For SMTP, require host, port, username, and from address
        if ($driver === 'smtp') {
            return !empty($host) && !empty($port) && !empty($username) && !empty($fromAddress);
        }

        // For other drivers, just check if from address is set
        return !empty($fromAddress);
    }
}
