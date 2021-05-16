<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\ItemNotFoundException;
use App\Service\ItemService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ItemController extends AbstractController
{
    /**
     * @Route("/item", name="item_list", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function list(ItemService $itemService): JsonResponse
    {
        $items = $itemService->findByUser($this->getUser());

        $allItems = [];
        foreach ($items as $item) {
            $oneItem['id'] = $item->getId();
            $oneItem['data'] = $item->getData();
            $oneItem['created_at'] = $item->getCreatedAt();
            $oneItem['updated_at'] = $item->getUpdatedAt();
            $allItems[] = $oneItem;
        }

        return $this->json($allItems);
    }

    /**
     * @Route("/item", name="item_create", methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function create(Request $request, ItemService $itemService): JsonResponse
    {
        $data = $request->get('data');

        if (empty($data)) {
            return $this->json(['error' => 'No data parameter']);
        }

        $itemService->create($this->getUser(), $data);

        return $this->json([]);
    }

    /**
     * @Route("/item", name="item_update", methods={"PUT"})
     * @IsGranted("ROLE_USER")
     */
    public function update(Request $request, ItemService $itemService): JsonResponse
    {
        $id = $request->request->getInt('id');
        if (empty($id)) {
            return $this->json(['error' => 'No id parameter'], Response::HTTP_BAD_REQUEST);
        }

        $data = $request->get('data');
        if (empty($data)) {
            return $this->json(['error' => 'No data parameter']);
        }

        try {
            $itemService->update($this->getUser(), $id, $data);
        } catch (ItemNotFoundException $exception) {
            return $this->json(['error' => 'No item'], Response::HTTP_BAD_REQUEST);
        }

        return $this->json([]);
    }

    /**
     * @Route("/item/{id}", name="items_delete", methods={"DELETE"})
     * @IsGranted("ROLE_USER")
     */
    public function delete(int $id, ItemService $itemService): JsonResponse
    {
        if (empty($id)) {
            return $this->json(['error' => 'No data parameter'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $itemService->delete($this->getUser(), $id);
        } catch (ItemNotFoundException $exception) {
            return $this->json(['error' => 'No item'], Response::HTTP_BAD_REQUEST);
        }

        return $this->json([]);
    }
}
