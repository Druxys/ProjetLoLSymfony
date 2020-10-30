<?php

namespace App\Controller;

use App\Entity\Team;
use App\Form\CreateTeamFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TeamController extends AbstractController
{
    /**
     * @Route("/createTeam", name="createTeam")
     */
    public function createTeam(Request $request, ValidatorInterface $validator)
    {

        $entityManager = $this->getDoctrine()->getManager();
        $team = new Team();
        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(CreateTeamFormType::class, $team);
        $form->submit($data);
        $violation = $validator->validate($team);
        if (0 !== count($violation)) {
            foreach ($violation as $errors) {
                return new JsonResponse($errors->getMessage(), Response::HTTP_BAD_REQUEST);
            }
        }
        var_dump($data);
        $entityManager->persist($team);
        $entityManager->flush();
        $response = new Response("tournament created");
        return $response;

    }
}
