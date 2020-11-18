<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use App\Entity\History;
use App\Entity\MatchHistory;
use Doctrine\ORM\EntityManagerInterface;

class RiotApiController extends AbstractController
{
    private $client;
    private $https = "https://";
    private $baseurl = ".api.riotgames.com/lol/";
    private $endPointGetUserInfoBySummonerName = "summoner/v4/summoners/by-name/";
    private $endPointGetRankAccount = "league/v4/entries/by-summoner/"; 
    private $token = "";

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
        $this->token = $_ENV['RIOT_API_KEY'];
    }
    /**
     * @Route("/{region}/riot/GetRankAccount/{summonerName}", name="GetRankAccount", methods={"GET"} )
     */
    public function getRankAcount($region, $summonerName)
    {
        $accountID = $this->encryptedSummonerId($region, $summonerName);
        $url = $this->https . $region . $this->baseurl . $this->endPointGetRankAccount . $accountID;
        $cb = $this->client->request(
            'GET',
            $url,
            [
                'headers' => [
                    'X-Riot-Token' => $this->token
                ]
            ]
        );
        $lastMatch = json_decode($cb->getContent(), true);
        $result = $lastMatch;
        // var_dump($result);


        $response = new JsonResponse();
        $response->setContent(json_encode($result));
        return $response;
    }
    /**
     * @Route("/{region}/riot/getInfoAcount/{summonerName}", name="getInfoAcount", methods={"GET"} )
     */
    public function getInfoAcount($region, $summonerName)
    {
        $url = $this->https . $region . $this->baseurl . $this->endPointGetUserInfoBySummonerName . $summonerName;
        $cb = $this->client->request(
            'GET',
            $url,
            [
                'headers' => [
                    'X-Riot-Token' => $this->token
                ]
            ]
        );
        $lastMatch = json_decode($cb->getContent(), true);
        $result = $lastMatch;
        $response = new JsonResponse();
        $response->setContent(json_encode($result));
        return $response;
    }
    

    private function encryptedSummonerId($region, $summonerName) {
        $url = $this->https.$region. $this->baseurl.$this->endPointGetUserInfoBySummonerName.$summonerName;
        $response = $this->client->request(
            'GET',
            $url,
            [
                'headers' => [
                    'X-Riot-Token' => $this->token
                ]
            ]
        );
        $responseContent =json_decode($response->getContent());

        return $responseContent->id;
    }
}