<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Trikoder\Bundle\OAuth2Bundle\Model\Client;

/**
 * @ORM\Entity()
 */
class ApiUser
{
    public function __construct(Client $client, string $email)
    {
        $this->oAuthClient = $client;
        $this->email = $email;
    }

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var ?int
     */
    protected $id;
    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true, nullable=false)
     */
    protected $email;

    /**
     * @ORM\OneToOne(targetEntity="Trikoder\Bundle\OAuth2Bundle\Model\Client")
     * @ORM\JoinColumn(name="oauth_client", referencedColumnName="identifier")
     *
     * @var Client
     */
    protected $oAuthClient;

    /**
     * @ORM\Column(type="integer", options={"default": 0})
     *
     * @var int
     */
    protected $requestsAmount = 0;

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getOAuthClient(): Client
    {
        return $this->oAuthClient;
    }

    public function getRequestsAmount(): int
    {
        return $this->requestsAmount;
    }
}
