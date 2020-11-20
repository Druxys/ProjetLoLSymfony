<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\RegisterFormType;
use App\Form\UpdateUserFormType;
use OpenApi\Annotations as OA;
use App\Repository\UserRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    private function serializeJson($objet)
    {
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getNom();
            },
        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $serializer = new Serializer([$normalizer], [new JsonEncoder()]);

        return $serializer->serialize($objet, 'json');
    }

    /**
     * @Route("/sign-up", name="register", methods={"POST"})
     * @OA\Tag(name="User")
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function register(UserPasswordEncoderInterface $passwordEncoder, Request $request, ValidatorInterface $validator)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = new User();
        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(RegisterFormType::class, $user);
        $form->submit($data);
        $violation = $validator->validate($user);
        dump($violation);
        if (0 !== count($violation)) {
            foreach ($violation as $errors) {
                return new JsonResponse($errors->getMessage(), Response::HTTP_BAD_REQUEST);
            }
        }
        $password = $passwordEncoder->encodePassword($user, $user->getPassword());
        $user->setPassword($password);
        $entityManager->persist($user);
        $entityManager->flush();
        $response = new Response("user created");
        return $response;
    }


    /**
     * @Route("/logout", name="app_logout",  methods={"POST"})
     * @OA\Tag(name="User")
     * @param Request $request
     * @return Response
     */
    public function logout()
    {
        $response = new Response("disconect");
        return $response;
    }
    /**
     * @Route("/api/user/update", name="userUpdate", methods={"PATCH"})
     * @OA\Tag(name="User")
     * @param Request $request
     * @param UserRepository $userRepository
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return JsonResponse
     */
    public function userUpdate(Request $request, UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder,ValidatorInterface $validator)
    {
        
        $entityManager = $this->getDoctrine()->getManager();
        $data = json_decode($request->getContent(), true);
        $user = $userRepository->findOneBy(['id' => $data['id']]);
  
        $form = $this->createForm(UpdateUserFormType::class, $user);
        $form->submit($data);
        $violation = $validator->validate($user);
        if (0 !== count($violation)) {
            foreach ($violation as $errors) {
                return new JsonResponse($errors->getMessage(), Response::HTTP_BAD_REQUEST);
            }
        }
        $password = $passwordEncoder->encodePassword($user, $user->getPassword());
        $user->setPassword($password);

        $entityManager->persist($user);
        $entityManager->flush();

        return JsonResponse::fromJsonString($this->serializeJson($user));
    }
    /**
     * @Route("/api/user/delete", name="userDelete", methods={"DELETE"})
     * @OA\Tag(name="User")
     * @param Request $request
     * @param UserRepository $userRepository
     * @return Response
     */
    public function userDelete(Request $request, UserRepository $userRepository)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $item = json_decode($request->getContent(), true);
        $user = $userRepository->find($item['id']);
        $response = new Response();
        if ($user) {
            $entityManager->remove($user);
            $entityManager->flush();
            $response
                ->setContent("l'utilisateur avec l'id " . $item['id'] . " est supprimé")
                ->setStatusCode(Response::HTTP_OK);
        } else {
            $response
                ->setContent('bad request')
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        return $response;
    }
}
