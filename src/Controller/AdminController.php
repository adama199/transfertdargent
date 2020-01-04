<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * @Route("/showadmin/{id}", name="admin", methods={"GET"})
     */
    public function showadmin(User $user, UserRepository $userrepo, SerializerInterface $serializer)
    {
        $user = $userrepo->find($user->getId());
        $data = $serializer->serialize($user, 'json', [
            'groups' => ['show']
        ]);
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]); 
    }


     /**
     * @Route("/addadmin", name="add_admin", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function addadmin(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');
        $errors = $validator->validate($user);
        if(count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, [
                'Content-Type' => 'application/json'
            ]);
        }
        $entityManager->persist($user);
        $entityManager->flush();
        $data = [
            'status' => 201,
            'message' => 'Le téléphone a bien été ajouté'
        ];
        return new JsonResponse($data, 201);
    }
}
