<?php

namespace App\Controller;

use App\Entity\Report;
use OpenApi\Annotations as OA;
use App\Form\CreateReportFormType;
use App\Repository\ReportRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ReportController extends AbstractController
{
    /**
     * @Route("/createReport", name="createReport")
     * @OA\Tag(name="Report")
     * @param Request $request
     * @param UserRepository $userRepository
     * @param ValidatorInterface $validator
     * @return JsonResponse|Response
     */
    public function createReport(Request $request, UserRepository $userRepository, ValidatorInterface $validator)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $report = new report();
        $response = new Response();

        $data = json_decode($request->getContent(), true);
        $user = $userRepository->find($data['user_id']);
        $id_user_reported = $userRepository->findOneBy(["summoner_lol" => $data["summoner_lol"]]);
        if ($user === null || $id_user_reported === null) {
            $response->setContent("Cet utilisateur n'existe pas");
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        }else {
            $report->setUser($user)
                ->setIdUserReported($id_user_reported->getId())
                ->setReason($data['reason']);
            $form = $this->createForm(CreateReportFormType::class, [$user, $report]);
            $form->submit($data);
            $violation = $validator->validate($report);
            if (0 !== count($violation)) {
                foreach ($violation as $errors) {
                    return new JsonResponse($errors->getMessage(), Response::HTTP_BAD_REQUEST);
                }
            }
            $entityManager->persist($report);
            $entityManager->flush();
            $response->setStatusCode(Response::HTTP_OK);
            $response->setContent("Report send");
        }
        return $response;
    }

    /**
     * @Route("/getAllReport", name="getAllReport")
     * @OA\Tag(name="Report")
     * @param Request $request
     * @param ReportRepository $reportRepository
     * @return JsonResponse
     */
    public function getReportForSummoner(Request $request,ReportRepository $reportRepository){
        $filter = [];
        $em = $this->getDoctrine()->getManager();
        $metadata = $em->getClassMetadata(Report::class)->getFieldNames();
        foreach ($metadata as $value) {
            if ($request->query->get($value)) {
                $filter[$value] = $request->query->get($value);
            }
        }
        return JsonResponse::fromJsonString($reportRepository->findBy($filter));
    }
}
