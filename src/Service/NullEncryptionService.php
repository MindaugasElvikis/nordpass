<?php

namespace App\Service;

class NullEncryptionService implements EncryptionServiceInterface
{
    private const SERVICE_NAME = 'null_encryption_service';

    public function encrypt(string $data): string
    {
        return $data;
    }

    public function decrypt(string $data): string
    {
        return $data;
    }

    public function getServiceName(): string
    {
        return self::SERVICE_NAME;
    }

    public function supports(?string $encryptionServiceName): bool
    {
        return null === $encryptionServiceName || self::SERVICE_NAME === $encryptionServiceName;
    }
}
