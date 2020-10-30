<?php

namespace App\Controller;

use App\Entity\Tournament;
use App\Form\UpdateTournamentFormType;
use App\Form\CreateTournamentFormType;
use App\Repository\TournamentRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TournamentController extends AbstractController
{
        protected function serializeJson($objet)
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
            foreach ($violation as $errors) {
                return new JsonResponse($errors->getMessage(), Response::HTTP_BAD_REQUEST);
            }
        }
        var_dump($data);
        $entityManager->persist($tournament);
        $entityManager->flush();
        $response = new Response("tournament created");
        return $response;
    }

    /**
     * @Route("/api/tournament/getAll", name="tournament_json", methods={"GET"})
     * @param TournamentRepository $tournamentRepository
     * @param Request $request
     * @return Response
     */
    public function TournamentJson(TournamentRepository $tournamentRepository, Request $request)
    {
        $filter = [];
        $em = $this->getDoctrine()->getManager();
        $metadata = $em->getClassMetadata(Tournament::class)->getFieldNames();
        foreach ($metadata as $value) {
            if ($request->query->get($value)) {
                $filter[$value] = $request->query->get($value);
            }
        }
        return JsonResponse::fromJsonString($this->serializeJson($tournamentRepository->findBy($filter)));
    }
    /**
     * @Route("/api/tournament/update/", name="tournamentUpdate", methods={"PATCH"})
     * @param Request $request
     * @param TournamentRepository $tournamentRepository
     * @return JsonResponse
     */
    public function userUpdate(Request $request, TournamentRepository $tournamentRepository, ValidatorInterface $validator)
    {

        $entityManager = $this->getDoctrine()->getManager();
        $data = json_decode($request->getContent(), true);
        $tournament = $tournamentRepository->findOneBy(['id' => $data['id']]);

        $form = $this->createForm(UpdateTournamentFormType::class, $tournament);
        $form->submit($data);
        $violation = $validator->validate($tournament);

        if (0 !== count($violation)) {
            foreach ($violation as $errors) {
                return new JsonResponse($errors->getMessage(), Response::HTTP_BAD_REQUEST);
            }
        }

        $entityManager->persist($tournament);
        $entityManager->flush();

        return JsonResponse::fromJsonString($this->serializeJson($tournament));
    }
     /**
     * @Route("/api/tournament/delete", name="commune_delete", methods={"DELETE"})
     * @param Request $request
     * @param TournamentRepository $tournamentRepository
     * @return Response
     */
    public function TournamentDelete(Request $request, TournamentRepository $tournamentRepository)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $response = new Response();
        $data = json_decode(
            $request->getContent(),
            true
        );
        if (isset($data["id"])) {
            $tournament = $tournamentRepository->find($data["id"]);
            if ($tournament === null) {
                $response->setContent("Ce tournoi n'existe pas");
                $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            } else {
                $entityManager->remove($tournament);
                $entityManager->flush();
                $response->setContent("Ce tournoi à était supprimé");
                $response->setStatusCode(Response::HTTP_OK);
            }
        } else {
            $response->setContent("L'id n'est pas renseigné");
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        return $response;
    }
}
