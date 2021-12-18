<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\ApiUser;
use Doctrine\ORM\EntityManagerInterface;
use Trikoder\Bundle\OAuth2Bundle\Model\Client;
use Trikoder\Bundle\OAuth2Bundle\Model\Grant;
use Trikoder\Bundle\OAuth2Bundle\Model\RedirectUri;
use Trikoder\Bundle\OAuth2Bundle\Model\Scope;

class OAuthService
{
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createClient(string $email): Client
    {
        $identifier = hash('md5', random_bytes(16));
        $secret = hash('sha512', random_bytes(32));

        $client = new Client($identifier, $secret);
        $client->setActive(true);
        $client->setAllowPlainTextPkce(false);

        $client->setGrants(...[new Grant('client_credentials')]);
        $client->setScopes(...[new Scope('verify')]);
        $client->setRedirectUris(...[new RedirectUri('http://localhost')]);

        $apiUser = new ApiUser($client, $email);

        $this->entityManager->persist($client);
        $this->entityManager->persist($apiUser);
        $this->entityManager->flush();

        return $client;
    }

    public function getExistingApiUser(string $email): ?ApiUser
    {
        return $this->entityManager->getRepository(ApiUser::class)->findOneBy(['email' => $email]);
    }
}
