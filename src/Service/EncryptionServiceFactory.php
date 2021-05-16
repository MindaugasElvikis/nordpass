<?php

namespace App\Service;

class EncryptionServiceFactory
{
    /**
     * @var EncryptionServiceInterface[]
     */
    private $encrypters;

    /**
     * @var EncryptionServiceInterface
     */
    private $defaultEncrypter;

    /**
     * @param EncryptionServiceInterface[] $encrypters
     */
    public function __construct(array $encrypters, EncryptionServiceInterface $defaultEncrypter)
    {
        $this->encrypters = $encrypters;
        $this->defaultEncrypter = $defaultEncrypter;
    }

    public function getEncrypter(?string $encrypterName): EncryptionServiceInterface
    {
        foreach ($this->encrypters as $encrypter) {
            if ($encrypter->supports($encrypterName)) {
                return $encrypter;
            }
        }

        return $this->defaultEncrypter;
    }
}
