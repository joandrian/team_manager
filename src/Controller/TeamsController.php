<?php

namespace App\Controller;

use App\Entity\Teams;
use App\Entity\Players;
use App\Form\TeamsType;
use App\Form\MarketplaceType;
use App\Repository\TeamsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeamsController extends AbstractController
{
    #[Route('/', name: 'app_teams_index')]
    public function index(TeamsRepository $teamsRepository, Request $request): Response
    {
        //Retrieve the page number from the url
        $page = $request->query->getInt('page', 1);
        return $this->render('index.html.twig', [
            'teams' => $teamsRepository->findTeamsPaginated($page, 3),
        ]);
    }

    #[Route('/new', name: 'app_teams_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $team = new Teams();
        //Add a dummy player to avoid an empty Collection
        $player1 = new Players();
        $player1->setName(' ');
        $player1->setSurname(' ');
        $team->getPlayers()->add($player1);

        $form = $this->createForm(TeamsType::class, $team);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $newTeam = new Teams();
            $newTeam->setName($team->getName());
            $newTeam->setCountry($team->getCountry());
            $newTeam->setMoneyBalance($team->getMoneybalance());
            $entityManager->persist($newTeam);
            $listPlayers = $team->getPlayers();
            foreach ($listPlayers as $p) {
                $newPlayer = new Players();
                $newPlayer->setName($p->getName());
                $newPlayer->setSurname($p->getSurname());
                $newPlayer->setTeams($newTeam);
                $entityManager->persist($newPlayer);
            }
            $entityManager->flush();
            return $this->redirectToRoute('app_teams_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/marketplace', name: 'app_teams_marketplace')]
    public function marketplace(Request $request, TeamsRepository $teamsRepository, EntityManagerInterface $entityManager): Response
    {

        if ($request->isXmlHttpRequest() && !empty($request->query->get('teamId'))) {
            $jsonData = array();
            $teamId = (int) $request->query->get('teamId');
            $teamData = $teamsRepository->find($teamId);
            $pl = $teamData->getPlayers();
            $idx = 0;
            if (!empty($pl))
                foreach ($pl as $p) {
                    $temp = array(
                        'id' => $p->getId(),
                        'name' => $p->getName(),
                        'surname' => $p->getSurname(),
                    );
                    $jsonData[$idx++] = $temp;
                }

            return new JsonResponse($jsonData);
        } else {

            $form = $this->createForm(MarketplaceType::class);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $data = $request->request->all();
                $transactionAmount = (float)$data['price'];

                //find buyer team
                //Modify Money balance for buyer team
                $idTeamBuyer = (int) $data['marketplace']['buyer'];
                $teamBuyer = $entityManager->getRepository(Teams::class)->find($idTeamBuyer);
                //Get actual money Balance 
                $actualBalance = $teamBuyer->getMoneyBalance();
                $newBalance = (float) $actualBalance - $transactionAmount;
                if ($newBalance < 0) {
                    $response = new Response(
                        'You do not have enougth budget to buy this player. <a href="/marketplace">back</a>',
                        Response::HTTP_OK,
                        ['content-type' => 'text/html']
                    );
                    return $response;
                }

                $teamBuyer->setMoneyBalance($newBalance);
                $entityManager->persist($teamBuyer);
                //Modify Money balance for seller team
                $idTeamSeller = (int) $data['marketplace']['seller'];
                $teamSeller = $entityManager->getRepository(Teams::class)->find($idTeamSeller);
                $actualBalance = $teamSeller->getMoneyBalance();
                $newBalance = (float) $actualBalance + $transactionAmount;
                $teamSeller->setMoneyBalance($newBalance);
                $entityManager->persist($teamSeller);

                //id player 
                $idPlayer =  (int) $data['playerlist'];
                $player = $entityManager->getRepository(Players::class)->find($idPlayer);
                //Change Player team
                $player->setTeams($teamBuyer);
                $entityManager->persist($player);

                $entityManager->flush();
                return $this->redirectToRoute('app_teams_index', [], Response::HTTP_SEE_OTHER);
            }
            return $this->render('marketplace.html.twig', [
                'form' => $form,
            ]);
        }
    }
}
