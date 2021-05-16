<?php

namespace App\Service;

class OpenSslEncryptionService implements EncryptionServiceInterface
{
    private const SERVICE_NAME = 'openssl_encryption_service';

    /**
     * @var string
     */
    private $passphrase;

    /**
     * @var string
     */
    private $iv;

    public function __construct(string $passphrase, string $iv)
    {
        $this->passphrase = $passphrase;
        $this->iv = $iv;
    }

    public function encrypt(string $data): string
    {
        return openssl_encrypt($data, 'aes-128-cbc', $this->passphrase, 0, $this->iv);
    }

    public function decrypt(string $data): string
    {
        return openssl_decrypt($data, 'aes-128-cbc', $this->passphrase, 0, $this->iv);
    }

    public function getServiceName(): string
    {
        return self::SERVICE_NAME;
    }

    public function supports(?string $encryptionServiceName): bool
    {
        return self::SERVICE_NAME === $encryptionServiceName;
    }
}
