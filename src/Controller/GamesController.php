<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Tournament;
use App\Form\UpdateGameFormType;
use App\Form\CreateGameFormType;
use App\Repository\GameRepository;
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

class GamesController extends AbstractController
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
     * @Route("/game/create", name="game", methods={"POST"})
     * @param TournamentRepository $ournamentRepository
     */
    public function GameCreate(Request $request, ValidatorInterface $validator, TournamentRepository $tournamentRepository)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $game = new Game();
        $tournament = new Tournament();
        $response = new Response();
        $data = json_decode($request->getContent(), true);
        if (isset($data["tournament_id"])) {
            $id = $data["tournament_id"];
            $tournament = $tournamentRepository->find($id);
            if ($tournament === null) {
                $response->setContent("Ce tournoi n'existe pas");
                $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            } else {
                $game->setTournament($tournament);
                $form = $this->createForm(CreateGameFormType::class, $game);
                $form->submit($data);
                $violation = $validator->validate($game);
                if (0 !== count($violation)) {
                    foreach ($violation as $$errors) {
                        return new JsonResponse($errors->getMessage(), Response::HTTP_BAD_REQUEST);
                    }
                }
                $entityManager->persist($game);
                $entityManager->flush();
                $response->setStatusCode(Response::HTTP_OK);
                $response->setContent("creation tournoi numéro =  " . $id);
            }
        } else {
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        return $response;
    }

    /**
     * @Route("/game/getAll", name="game_json", methods={"GET"})
     * @param GameRepository $gameRepository
     * @param Request $request
     * @return Response
     */
    public function GameJson(GameRepository $gameRepository, Request $request)
    {
        $filter = [];
        $em = $this->getDoctrine()->getManager();
        $metadata = $em->getClassMetadata(Game::class)->getFieldNames();
        foreach ($metadata as $value) {
            if ($request->query->get($value)) {
                $filter[$value] = $request->query->get($value);
            }
        }
        return JsonResponse::fromJsonString($this->serializeJson($gameRepository->findBy($filter)));
    }
    /**
     * @Route("/game/update/", name="gameUpdate", methods={"PATCH"})
     * @param Request $request
     * @param TournamentRepository $ournamentRepository
     * @param GameRepository $gameRepository
     * @return JsonResponse
     */
    public function GameUpdate(Request $request, GameRepository $gameRepository, ValidatorInterface $validator, TournamentRepository $tournamentRepository)
    {


        $entityManager = $this->getDoctrine()->getManager();
        $data = json_decode($request->getContent(), true);
        $game = $gameRepository->findOneBy(['id' => $data['id']]);

        $form = $this->createForm(UpdateGameFormType::class, $game);
        $form->submit($data);
        $violation = $validator->validate($game);

        if (0 !== count($violation)) {
            foreach ($violation as $errors) {
                return new JsonResponse($errors->getMessage(), Response::HTTP_BAD_REQUEST);
            }
        }
        var_dump($data);
        $entityManager->persist($game);
        $entityManager->flush();

        return JsonResponse::fromJsonString($this->serializeJson($game));
    }
    /**
     * @Route("/game/delete", name="game_delete", methods={"DELETE"})
     * @param Request $request
     * @param GameRepository $gameRepository
     * @return Response
     */
    public function GameDelete(Request $request, GameRepository $gameRepository)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $response = new Response();
        $data = json_decode(
            $request->getContent(),
            true
        );
        if (isset($data["id"])) {
            $game = $gameRepository->find($data["id"]);
            if ($game === null) {
                $response->setContent("Cet game n'existe pas" + $game);
                $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            } else {
                $entityManager->remove($game);
                $entityManager->flush();
                $response->setContent("Cet game à était supprimé" + $game);
                $response->setStatusCode(Response::HTTP_OK);
            }
        } else {
            $response->setContent("L'id n'est pas renseigné");
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        return $response;
    }
}
