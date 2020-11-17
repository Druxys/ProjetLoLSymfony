<?php

namespace App\Controller;

use App\Entity\Team;
use App\Entity\UsersTeams;
use App\Form\CreateTeamFormType;
use App\Form\SendInvitationFormType;
use App\Form\UpdateTeamFormType;
use App\Repository\TeamRepository;
use App\Repository\UserRepository;
use App\Repository\UsersTeamsRepository;
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
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param UserRepository $userRepository
     * @return JsonResponse|Response
     */
    public function createTeam(Request $request, ValidatorInterface $validator,UserRepository $userRepository)
    {

        $entityManager = $this->getDoctrine()->getManager();
        $team = new Team();
        $usersTeams = new UsersTeams();
        $response = new Response();

        $data = json_decode($request->getContent(), true);
        $user = $userRepository->find($data['user_id']);
        if ($user === null){
            $response->setContent("Cet utilisateur n'existe pas");
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        }else {
            $usersTeams->setUser($user);
            $usersTeams->setTeam($team);
            $usersTeams->setInvitation(false);
            $form = $this->createForm(CreateTeamFormType::class, $team);
            $form->submit($data);
            $violation = $validator->validate($team);
            if (0 !== count($violation)) {
                foreach ($violation as $errors) {
                    return new JsonResponse($errors->getMessage(), Response::HTTP_BAD_REQUEST);
                }
            }
            $entityManager->persist($usersTeams);
            $entityManager->persist($team);
            $entityManager->flush();
            $response->setStatusCode(Response::HTTP_OK);
            $response->setContent("team created");

        }

        return $response;

    }

    /**
     * @Route("/sendInvitation", name="sendInvitation")
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param TeamRepository $teamRepository
     * @param UserRepository $userRepository
     * @return JsonResponse|Response
     */
    public function sendInvitation(Request $request, ValidatorInterface $validator, TeamRepository $teamRepository,UserRepository $userRepository){

        $entityManager = $this->getDoctrine()->getManager();
        $usersTeams = new UsersTeams();
        $response = new Response();
        $data = json_decode($request->getContent(), true);
        $user = $userRepository->findOneBy(["summoner_lol" => $data["summoner_lol"]]);
        $team = $teamRepository->find($data['team_id']);
        if ($user === null) {
            $response->setContent("Cet utilisateur n'existe pas");
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        }else {
            $usersTeams->setUser($user);
            $usersTeams->setTeam($team);
            $usersTeams->setInvitation(true);
            $form = $this->createForm(SendInvitationFormType::class, $user);
            $form->submit($data);
            $violation = $validator->validate($team);
            if (0 !== count($violation)) {
                foreach ($violation as $errors) {
                    return new JsonResponse($errors->getMessage(), Response::HTTP_BAD_REQUEST);
                }
            }
            $entityManager->persist($usersTeams);
            $entityManager->flush();
            $response->setStatusCode(Response::HTTP_OK);
            $response->setContent("invitation send");
        }
        return $response;
    }

    /**
     * @Route("/responseInvitation", name="responseInvitation")
     * @param Request $request
     * @param UsersTeamsRepository $usersTeamsRepository
     * @return JsonResponse|Response
     */
    public function responseInvitation(Request $request, UsersTeamsRepository $usersTeamsRepository){
        $entityManager = $this->getDoctrine()->getManager();
        $response = new Response();
        $data = json_decode($request->getContent(), true);
        $usersTeams = $usersTeamsRepository->findOneBy(["user" => $data['user_id'], "team" =>$data['team_id']]);

        if ($usersTeams === null){
            $response->setContent("Cet utilisateur n'existe pas");
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        }else {
            if ($data['response'] === true ){
                $usersTeams->setInvitation(false);
                $entityManager->persist($usersTeams);
                $entityManager->flush();
                $response->setStatusCode(Response::HTTP_OK);
                $response->setContent("invitation acceptée");
            }else{
                $entityManager->remove($usersTeams);
                $entityManager->flush();
                $response->setContent("Invitation refusé");
                $response->setStatusCode(Response::HTTP_OK);
            }

        }
        return $response;
    }

    /**
     * @Route("/updateTeam", name="updateTeam")
     * @param Request $request
     * @param TeamRepository $teamRepository
     * @param ValidatorInterface $validator
     * @return JsonResponse|Response
     */
    public function updateTeam(Request $request, TeamRepository $teamRepository ,ValidatorInterface $validator) {
        $entityManager = $this->getDoctrine()->getManager();
        $response = new Response();
        $data = json_decode($request->getContent(), true);

        $team = $teamRepository->find([$data['id']]);

        $form = $this->createForm(UpdateTeamFormType::class, $team);
        $form->submit($data);
        $violation = $validator->validate($team);

        if (0 !== count($violation)) {
            foreach ($violation as $errors) {
                return new JsonResponse($errors->getMessage(), Response::HTTP_BAD_REQUEST);
            }
        }

        $entityManager->persist($team);
        $entityManager->flush();
        $response->setContent("Team update");
        $response->setStatusCode(Response::HTTP_OK);
        return $response;
    }

    /**
     * @Route("/deleteTeam", name="deleteTeam")
     * @param Request $request
     * @param TeamRepository $teamRepository
     * @return Response
     */
    public function deleteTeam(Request $request, TeamRepository $teamRepository) {
        $entityManager = $this->getDoctrine()->getManager();
        $response = new Response();
        $data = json_decode($request->getContent(), true);
        $team = $teamRepository->find([$data['id']]);
        $entityManager->persist($team);
        $entityManager->flush();
        $response->setContent("Team deleted");
        $response->setStatusCode(Response::HTTP_OK);
        return $response;
    }
}
