<?php

namespace App\Controller;

use App\Entity\Tournament;
use App\Form\CreateTournamentFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TournamentController extends AbstractController
{
    /**
     * @Route("/api/tournament/create", name="tournament", methods={"POST"})
     */
    public function TournamentCreate(Request $request, ValidatorInterface $validator)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $tournament = new Tournament();
        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(CreateTournamentFormType::class, $tournament);
        
        $form->submit($data);
        $violation = $validator->validate($tournament);
        if (0 !== count($violation)) {
            foreach ($violation as $$errors) {
                return new JsonResponse($errors->getMessage(), Response::HTTP_BAD_REQUEST);
            }
        }
        var_dump($data);
        $entityManager->persist($tournament);
        $entityManager->flush();
        $response = new Response("tournament created");
        return $response;
    }
}
