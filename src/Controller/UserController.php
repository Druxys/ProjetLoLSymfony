<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\RegisterFormType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
 

    /**
     * @Route("/sign-up", name="register", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function register(Request $request,ValidatorInterface $validator)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = new User();
        $data = json_decode($request->getContent(), true);
        $form =$this->createForm(RegisterFormType::class, $user);
        $form->submit($data);
        $violation = $validator->validate($user);
        if(0 !== count($violation)){
            foreach($violation as $errors)
            {
                return new JsonResponse($errors->getMessage(),Response::HTTP_BAD_REQUEST);
            }

        }
        $entityManager->persist($user);
        $entityManager->flush();
   }
}