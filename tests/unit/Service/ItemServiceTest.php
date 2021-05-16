<?php

namespace App\Tests\Unit;

use App\Entity\Item;
use App\Entity\User;
use App\Exception\ItemNotFoundException;
use App\Repository\ItemRepository;
use App\Service\ItemService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ItemServiceTest extends TestCase
{
    /**
     * @var EntityManagerInterface|MockObject
     */
    private $itemRepository;

    /**
     * @var ItemService
     */
    private $itemService;

    public function setUp(): void
    {
        /** @var ItemRepository */
        $this->itemRepository = $this->createMock(ItemRepository::class);

        $this->itemService = new ItemService($this->itemRepository);
    }

    public function testCreate(): void
    {
        /** @var User */
        $user = $this->createMock(User::class);
        $data = 'secret data';

        $expectedObject = new Item($user, $data);

        $this->itemRepository->expects(self::once())->method('save')->with($expectedObject);

        $this->itemService->create($user, $data);
    }

    public function testDelete(): void
    {
        /** @var User */
        $user = $this->createMock(User::class);
        $data = 'secret data';

        $expectedObject = new Item($user, $data);

        $this->itemRepository->expects(self::once())->method('findByIdAndUser')->with(1,
            $user)->willReturn($expectedObject);
        $this->itemRepository->expects(self::once())->method('delete')->with($expectedObject);

        $this->itemService->delete($user, 1);
    }

    public function testDeleteNonExistingItem(): void
    {
        $this->expectException(ItemNotFoundException::class);

        /** @var User */
        $user = $this->createMock(User::class);

        $this->itemRepository->expects(self::once())->method('findByIdAndUser')->with(1, $user)->willReturn(null);
        $this->itemRepository->expects(self::never())->method('delete');

        $this->itemService->delete($user, 1);
    }
}
