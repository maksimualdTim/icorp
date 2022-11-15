<?php
namespace App\services;

use AmoCRM\OAuth2\Client\Provider\AmoCRM;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Grant\RefreshToken;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use AmoCRM\OAuth2\Client\Provider\AmoCRMResourceOwner;
class ApiService
{
    /**
     * @var AmoCRM
     */
    public AmoCRM $provider;

    /**
     * @var Db
     */
    private Db $db;

    /**
     * @var AccessToken
     */
    public AccessToken $accessToken;

    /**
     * @var ResourceOwnerInterface
     */
    public ResourceOwnerInterface $user;

    /**
     * @param string $clientId
     * @param string $clientSecret
     * @param string $redirectUri
     */
    public function __construct(string $clientId, string $clientSecret, string $redirectUri)
    {
        $this->db = new Db(__DIR__. '/../db/tokens.sql', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
        $this->provider = new AmoCRM([
            'clientId' => $clientId,
            'clientSecret' => $clientSecret,
            'redirectUri' => $redirectUri,
        ]);
        $this->setReferer();
        $this->setToken();
    }

    /**
     * Sets default base domain
     * @return void
     */
    public function setReferer(): void
    {
        if (isset($_GET['referer'])) {
            $this->provider->setBaseDomain($_GET['referer']);
        }else
            throw new \Exception('Referer required!');
    }

    /**
     * Get stored token by client_uid if exists
     * @return void
     */
    public function setToken(): void
    {
        if (isset($_GET['client_id']) && $_GET['client_id']){
            $client_id = $_GET['client_id'];

            $statement = $this->db->prepare('SELECT * FROM users WHERE "client_id"=:client_id AND "baseDomain"=:base_domain');
            $statement->bindValue(':client_id', $client_id);
            $statement->bindValue(':base_domain', $this->provider->getBaseDomain());
            $result = $statement->execute();
            $data = $result->fetchArray(SQLITE3_ASSOC);
            if (!$data){
                throw new \Exception('user not found');
            }

            $this->accessToken = new AccessToken([
                'access_token' => $data['access_token'],
                'refresh_token' => $data['refresh_token'],
                'resource_owner_id' => $data['client_id'],
                'expires' => $data['access_token_expires_at'],
            ]);

            if ($this->accessToken->hasExpired())
                $this->refreshToken();
        }else
            $this->fetchToken();
    }

    /**
     * fetch token from AMO
     * @return void
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     */
    public function fetchToken(): void
    {
        if (isset($_GET['authorization_code']) && $_GET['authorization_code']){
            $this->accessToken = $this->provider->getAccessToken('authorization_code', [
                'code' => $_GET['authorization_code']
            ]);
            $user = $this->provider->getResourceOwner($this->accessToken);
            $this->saveToken($user);
        }
        else
            throw new \Exception('authorization_code required!');
    }

    /**
     * Save token to DB
     * @param AmoCRMResourceOwner $user
     * @return void
     */
    public function saveToken(AmoCRMResourceOwner $user): void
    {
        $statement = $this->db->prepare('INSERT INTO 
        users ("client_id", "access_token", "refresh_token", "baseDomain", "access_token_expires_at", "refresh_token_expires_at") 
        VALUES (:client_id, :access_token, :refresh_token, :baseDomain, :access_token_expires_at, :refresh_token_expires_at)');
        $statement->bindValue(':access_token', $this->accessToken->getToken());
        $statement->bindValue(':client_id', $user->getId());
        $statement->bindValue(':refresh_token', $this->accessToken->getRefreshToken());
        $statement->bindValue(':baseDomain', $this->provider->getBaseDomain());
        $statement->bindValue(':access_token_expires_at', $this->accessToken->getExpires());
        $statement->bindValue(':refresh_token_expires_at', time() + 3*30*24*60*60); //expires after 3 month
        $statement->execute();
    }

    /**
     * @param int $client_id
     * @return void
     */
    public function updateToken(int $client_id)
    {
        $statement = $this->db->prepare(' UPDATE users SET "access_token"=:access_token, 
                  "refresh_token"=:refresh_token, "access_token_expires_at"=:access_token_expires_at, 
                  "refresh_token_expires_at"=:refresh_token_expires_at WHERE "client_id"=:client_id AND "baseDomain"=:baseDomain');
        $statement->bindValue(':access_token', $this->accessToken->getToken());
        $statement->bindValue(':client_id', $client_id);
        $statement->bindValue(':refresh_token', $this->accessToken->getRefreshToken());
        $statement->bindValue(':baseDomain', $this->provider->getBaseDomain());
        $statement->bindValue(':access_token_expires_at', $this->accessToken->getExpires());
        $statement->bindValue(':refresh_token_expires_at', time() + 3*30*24*60*60); //expires after 3 month
        $statement->execute();
    }

    /**
     * @return void
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     */
    public function refreshToken(): void
    {
        $client_id = $this->accessToken->getResourceOwnerId();
        $this->accessToken = $this->provider->getAccessToken(new RefreshToken(), [
            'refresh_token' => $this->accessToken->getRefreshToken(),
        ]);

        $this->updateToken($client_id);
    }

    /**
     * @return AmoCRM
     */
    public function getProvider(): AmoCRM
    {
        return $this->provider;
    }
}