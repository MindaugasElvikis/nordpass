<?php

namespace App\Service;

interface EncryptionServiceInterface
{
    public function encrypt(string $data): string;
    public function decrypt(string $data): string;
    public function getServiceName(): string;
    public function supports(?string $encryptionServiceName): bool;
}
