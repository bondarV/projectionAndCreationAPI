<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/v1')]
class TestController extends AbstractController
{

    public const USERS_DATA = [
        [
            'id'    => '1',
            'email' => 'ipz234_bno@student.ztu.edu.ua',
            'name'  => 'Nina'
        ],
        [
            'id'    => '2',
            'email' => 'ipz234_buo@student.ztu.edu.ua',
            'name'  => 'Udiy'
        ],
        [
            'id'    => '3',
            'email' => 'ipz234_bso@student.ztu.edu.ua',
            'name'  => 'Savelii'
        ],
        [
            'id'    => '4',
            'email' => 'ipz234_bno@student.ztu.edu.ua',
            'name'  => 'Nano'
        ],
        [
            'id'    => '5',
            'email' => 'ipz234_blo@student.ztu.edu.ua',
            'name'  => 'Lydia'
        ],
        [
            'id'    => '6',
            'email' => 'ipz234_bko@student.ztu.edu.ua',
            'name'  => 'Kolya'
        ],
        [
            'id'    => '7',
            'email' => 'ipz234_bdo@student.ztu.edu.ua',
            'name'  => 'Daniil'
        ],
    ];
    #[Route('/users', name: 'app_collection_users', methods: ['GET'])]
    #[IsGranted("ROLE_ADMIN")]
    public function getCollection(): JsonResponse
    {
        return new JsonResponse([
            'data' => self::USERS_DATA
        ], Response::HTTP_OK);
    }

    #[Route('/users/{id}', name: 'app_item_users', methods: ['GET'])]
    public function getItem(string $id): JsonResponse
    {
        $userData = $this->findUserById($id);

        return new JsonResponse([
            'data' => $userData
        ], Response::HTTP_OK);
    }

    #[Route('/users', name: 'app_create_users', methods: ['POST'])]
    public function createItem(Request $request): JsonResponse
    {
        $requestPayload = json_decode($request->getContent(), true);

        if (!isset($requestPayload['email'], $requestPayload['name'])) {
            throw new UnprocessableEntityHttpException("both name and email are required");
        }

        // TODO check by regex

        $userCount = count(self::USERS_DATA);

        $newUser = [
            'id'    => $userCount + 1,
            'name'  => $requestPayload['name'],
            'email' => $requestPayload['email']
        ];

        // TODO add new user to collection

        return new JsonResponse([
            'data' => $newUser
        ], Response::HTTP_CREATED);
    }

    #[Route('/users/{id}', name: 'app_delete_users', methods: ['DELETE'])]
    public function deleteItem(string $id): JsonResponse
    {
        $this->findUserById($id);

        // TODO remove user from collection

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }

    #[Route('/users/{id}', name: 'app_update_users', methods: ['PATCH'])]
    public function updateItem(string $id, Request $request): JsonResponse
    {
        $requestPayload = json_decode($request->getContent(), true);

        if (!isset($requestPayload['name'])) {
            throw new UnprocessableEntityHttpException("name is necessarily required");
        }

        $userData = $this->findUserById($id);

        // TODO update user name

        $userData['name'] = $requestPayload['name'];

        return new JsonResponse(['data' => $userData], Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return string[]
     */
    public function findUserById(string $id): array
    {
        $userData = null;

        foreach (self::USERS_DATA as $user) {
            if (!isset($user['id'])) {
                continue;
            }

            if ($user['id'] == $id) {
                $userData = $user;

                break;
            }

        }

        if (!$userData) {
            throw new NotFoundHttpException("User with id " . $id . " not found");
        }

        return $userData;
    }

}
