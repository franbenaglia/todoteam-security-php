<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserController extends AbstractController
{
    
    #[Route('/users', name: 'user_index', methods:['get'] )]
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
        
        $users = $entityManager
            ->getRepository(User::class)
            ->findAll();
    
        $data = [];
    
        foreach ($users as $user) {
           $data[] = [
               'id' => $user->getId(),
               'name' => $user->getName(),
               'username' => $user->getUsername(),
               'roles' => $user->getRoles(),
           ];
        }
    
        return $this->json($data);
    }
  
  
    #[Route('/user', name: 'user_create', methods:['post'] )]
    public function create(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        $user = new User();
        $user->setName($request->request->get('name'));
        $user->setUsername($request->request->get('username'));
    
        $entityManager->persist($user);
        $entityManager->flush();
    
        $data =  [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'username' => $user->getUsername(),
        ];
            
        return $this->json($data);
    }
  
  
    #[Route('/user/{id}', name: 'user_show', methods:['get'] )]
    public function show(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $user = $entityManager->getRepository(User::class)->find($id);
    
        if (!$user) {
    
            return $this->json('No user found for id ' . $id, 404);
        }
    
        $data =  [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'username' => $user->getUsername(),
        ];
            
        return $this->json($data);
    }
  
    #[Route('/user/{id}', name: 'user_update', methods:['put', 'patch'] )]
    public function update(EntityManagerInterface $entityManager, Request $request, int $id): JsonResponse
    {
        $user = $entityManager->getRepository(User::class)->find($id);
    
        if (!$user) {
            return $this->json('No user found for id ' . $id, 404);
        }
    
        $user->setName($request->request->get('name'));
        $user->setUsername($request->request->get('username'));
        $entityManager->flush();
    
        $data =  [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'username' => $user->getUsername(),
        ];
            
        return $this->json($data);
    }
  
    #[Route('/user/{id}', name: 'user_delete', methods:['delete'] )]
    public function delete(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $user = $entityManager->getRepository(User::class)->find($id);
    
        if (!$user) {
            return $this->json('No user found for id ' . $id, 404);
        }
    
        $entityManager->remove($user);
        $entityManager->flush();
    
        return $this->json('Deleted a user successfully with id ' . $id);
    }
    
    
    
   
    
}
