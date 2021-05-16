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

    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
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
        $item = new Item($user, $data);
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
