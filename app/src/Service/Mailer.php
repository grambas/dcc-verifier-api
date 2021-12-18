<?php

declare(strict_types=1);

namespace App\Service;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Trikoder\Bundle\OAuth2Bundle\Model\Client;

class Mailer implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /** @var MailerInterface */
    protected $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function dscListUpdateNotify(): void
    {
        $email = (new Email())
            ->to('milukasm@gmail.com')
            ->subject('DSC list updates successfully')
            ->text('DSC list updates successfully')
        ;

        $this->mailer->send($email);
    }

    public function sendCredentials(string $email, Client $client): void
    {
        $email = (new TemplatedEmail())
            ->to($email)
            ->subject('DCC Verifier API Credentials')
            ->htmlTemplate('emails/api_credentials.html.twig')
            ->context([
                'client' => $client,
            ])
        ;

        $this->mailer->send($email);
    }

    public function sendCredentialsRemind(string $email, Client $client): void
    {
        $email = (new TemplatedEmail())
            ->to($email)
            ->subject('DCC Verifier API remind your credentials')
            ->htmlTemplate('emails/api_credentials.html.twig')
            ->context([
                'client' => $client,
            ])
        ;

        $this->mailer->send($email);
    }
}
