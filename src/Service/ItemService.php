<?php

namespace App\Service;

use App\Entity\Item;
use App\Entity\User;
use App\Exception\ItemNotFoundException;
use App\Repository\ItemRepository;

class ItemService
{
    /**
     * @var ItemRepository
     */
    private $itemRepository;

    /**
     * @var EncryptionServiceInterface
     */
    private $encryptionService;

    public function __construct(ItemRepository $itemRepository, EncryptionServiceInterface $encryptionService)
    {
        $this->itemRepository = $itemRepository;
        $this->encryptionService = $encryptionService;
    }

    /**
     * @return Item[]
     */
    public function findByUser(User $user): array
    {
        return $this->itemRepository->findBy(['user' => $user]);
    }

    public function create(User $user, string $data): void
    {
        $item = new Item($user, $this->encryptionService->encrypt($data), $this->encryptionService->getServiceName());
        $this->itemRepository->save($item);
    }

    /**
     * @throws ItemNotFoundException
     */
    public function update(User $user, int $id, string $data): void
    {
        $item = $this->itemRepository->findByIdAndUser($id, $user);

        if (!$item) {
            throw new ItemNotFoundException();
        }

        $item->setData($this->encryptionService->encrypt($data));
        $item->setEncryptionServiceName($this->encryptionService->getServiceName());
        $this->itemRepository->save($item);
    }

    /**
     * @throws ItemNotFoundException
     */
    public function delete(User $user, int $id): void
    {
        $item = $this->itemRepository->findByIdAndUser($id, $user);

        if (!$item) {
            throw new ItemNotFoundException();
        }

        $this->itemRepository->delete($item);
    }
} 
