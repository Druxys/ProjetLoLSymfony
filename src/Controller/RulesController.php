<?php

namespace App\Controller;

use App\Repository\RulesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Rules;
use OpenApi\Annotations as OA;
use App\Entity\Tournament;
use App\Form\UpdateRulesTournamentFormType;
use App\Form\CreateRulesTournamentFormType;
use App\Repository\TournamentRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class RulesController extends AbstractController
{
    protected function serializeJson($objet)
    {
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getId();
            },
        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $serializer = new Serializer([$normalizer], [new JsonEncoder()]);
        return $serializer->serialize($objet, 'json');
    }
    /**
     * @Route("api/rules/create", name="rules", methods={"POST"})
     * @OA\Tag(name="Rules")
     * @param TournamentRepository $tournamentRepository
     */
    public function CreateRulesTournament(Request $request, ValidatorInterface $validator,TournamentRepository $tournamentRepository )
    {
        $entityManager = $this->getDoctrine()->getManager();
        $rules = new Rules();
        $tournament = new Tournament();
        $response = new Response();
        $data = json_decode($request->getContent(), true);
        var_dump($data);
        if (isset($data["tournament_id"])) {
            $id = $data["tournament_id"];
            $tournament = $tournamentRepository->find($id);
            if ($tournament === null) {
                $response->setContent("Ce tournoi n'existe pas");
                $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            } else {
                $rules->setTournament($tournament);
                $form = $this->createForm(CreateRulesTournamentFormType::class, $rules);
                $form->submit($data);
                $violation = $validator->validate($rules);
                if (0 !== count($violation)) {
                    foreach ($violation as $$errors) {
                        return new JsonResponse($errors->getMessage(), Response::HTTP_BAD_REQUEST);
                    }
                }
                $entityManager->persist($rules);
                $entityManager->flush();
                $response->setStatusCode(Response::HTTP_OK);
                $response->setContent("creation regle pour le tournoi =  " . $id);
            }
        } else {
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        return $response;
    }
     /**
     * @Route("api/rules/getRules", name="rules_json", methods={"GET"})
     * @OA\Tag(name="Rules")
     * @param RulesRepository $rulesRepository
     * @param Request $request
     * @return Response
     */
    public function RulesJson(RulesRepository $rulesRepository, Request $request)
    {
        $filter = [];
        $em = $this->getDoctrine()->getManager();
        $metadata = $em->getClassMetadata(Rules::class)->getFieldNames();
        foreach ($metadata as $value) {
            if ($request->query->get($value)) {
                $filter[$value] = $request->query->get($value);
            }
        }
        return JsonResponse::fromJsonString($this->serializeJson($rulesRepository->findBy($filter)));
    }
        /**
     * @Route("api/rules/update/", name="RulesUpdate", methods={"PATCH"})
     * @OA\Tag(name="Rules")
     * @param Request $request
     * @param TournamentRepository $ournamentRepository
     * @param RulesRepository $rulesRepository
     * @return JsonResponse
     */
    public function RulesUpdate(Request $request, RulesRepository $rulesRepository, ValidatorInterface $validator, TournamentRepository $tournamentRepository)
    {


        $entityManager = $this->getDoctrine()->getManager();
        $data = json_decode($request->getContent(), true);
        $rules = $rulesRepository->findOneBy(['id' => $data['id']]);

        $form = $this->createForm(UpdateRulesTournamentFormType::class, $rules);
        $form->submit($data);
        $violation = $validator->validate($rules);

        if (0 !== count($violation)) {
            foreach ($violation as $errors) {
                return new JsonResponse($errors->getMessage(), Response::HTTP_BAD_REQUEST);
            }
        }
        var_dump($data);
        $entityManager->persist($rules);
        $entityManager->flush();

        return JsonResponse::fromJsonString($this->serializeJson($rules));
    }
     /**
     * @Route("api/rules/delete", name="rules_delete", methods={"DELETE"})
     * @OA\Tag(name="Rules")
     * @param Request $request
     * @param RulesRepository $rulesRepository
     * @return Response
     */
    public function RulesDelete(Request $request, RulesRepository $rulesRepository)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $response = new Response();
        $data = json_decode(
            $request->getContent(),
            true
        );
        if (isset($data["id"])) {
            $rules = $rulesRepository->find($data["id"]);

            if ($rules === null) {
                $response->setContent("Les règles du tournoi n'existe pas" + $rules);
                $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            } else {
                $entityManager->remove($rules);
                $entityManager->flush();
                $response->setContent("Les règles du tournoi ont étaient supprimé");
                $response->setStatusCode(Response::HTTP_OK);
            }
        } else {
            $response->setContent("L'id n'est pas renseigné");
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        return $response;
    }
}
