<?php

namespace App\Tests;

use App\Entity\Item;
use App\Repository\ItemRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ItemControllerTest extends WebTestCase
{
    public function testCreate(): void
    {
        $client = static::createClient();

        /** @var UserRepository $userRepository */
        $userRepository = static::$container->get(UserRepository::class);
        /** @var ItemRepository $itemRepository */
        $itemRepository = static::$container->get(ItemRepository::class);

        $user = $userRepository->findOneByUsername('john');

        $client->loginUser($user);

        $data = 'very secure new item data ' . mt_rand();

        $newItemData = ['data' => $data];

        $client->request('POST', '/item', $newItemData);
        $client->request('GET', '/item');

        self::assertResponseIsSuccessful();
        self::assertStringContainsString($data, $client->getResponse()->getContent());

        $item = $itemRepository->findOneBy(['data' => $data]);
        self::assertEquals($data, $item->getData());
        self::assertEquals($user->getId(), $item->getUser()->getId());
    }

    public function testDelete(): void
    {
        $client = static::createClient();

        /** @var EntityManagerInterface $em */
        $em = static::$container->get(EntityManagerInterface::class);
        /** @var UserRepository $userRepository */
        $userRepository = static::$container->get(UserRepository::class);
        /** @var ItemRepository $itemRepository */
        $itemRepository = static::$container->get(ItemRepository::class);

        $user = $userRepository->findOneByUsername('john');

        $client->loginUser($user);

        $item = new Item($user, 'very secure new item data');
        $em->persist($item);
        $em->flush();

        $itemId = $item->getId();
        $client->request('DELETE', '/item/' . $itemId);

        self::assertNull($itemRepository->find($itemId));
    }

    public function testIHaveToProvideValidItemIdToDelete(): void
    {
        $client = static::createClient();

        /** @var EntityManagerInterface $em */
        $em = static::$container->get(EntityManagerInterface::class);
        /** @var UserRepository $userRepository */
        $userRepository = static::$container->get(UserRepository::class);
        /** @var ItemRepository $itemRepository */
        $itemRepository = static::$container->get(ItemRepository::class);

        $user = $userRepository->findOneByUsername('john');

        $client->loginUser($user);

        $item = new Item($user, 'very secure new item data');
        $em->persist($item);
        $em->flush();

        $itemId = $item->getId();
        $client->request('DELETE', '/item/0');

        self::assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        self::assertStringContainsString('No data parameter', $client->getResponse()->getContent());
        self::assertNotNull($itemRepository->find($itemId));
    }

    public function testICantDeleteOtherUserItem(): void
    {
        $client = static::createClient();

        /** @var EntityManagerInterface $em */
        $em = static::$container->get(EntityManagerInterface::class);
        /** @var UserRepository $userRepository */
        $userRepository = static::$container->get(UserRepository::class);
        /** @var ItemRepository $itemRepository */
        $itemRepository = static::$container->get(ItemRepository::class);

        $user = $userRepository->findOneByUsername('john');

        $client->loginUser($user);

        $item = new Item($userRepository->findOneByUsername('chuck'), 'very secure new item data');
        $em->persist($item);
        $em->flush();

        $itemId = $item->getId();
        $client->request('DELETE', '/item/' . $itemId);

        self::assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        self::assertStringContainsString('No item', $client->getResponse()->getContent());
        self::assertNotNull($itemRepository->find($itemId));
    }
}
