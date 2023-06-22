<?php

namespace App\Controller;

use ArrayObject;
use App\Entity\Bid;
use App\Entity\Team;
use App\Entity\User;
use App\Form\TeamType;
use App\Util\JsonDecoder;
use App\DTO\BidRequestDTO;
use App\Enums\PositionEnum;
use App\Util\Form\FormErrors;
use App\Service\Bid\BidService;
use App\Repository\BidRepository;
use App\Service\Bid\BidException;
use App\Repository\TeamRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\EntityManagerInterface;
use App\Controller\Traits\PaginationTrait;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Rompetomp\InertiaBundle\Service\InertiaInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route("/app")]
class TeamsController extends AbstractController
{
    use PaginationTrait;

    public function __construct(
        private readonly InertiaInterface $inertia,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    /**
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    #[Route(
        '/teams/{page}',
        name: 'app_teams',
        requirements: ["page" => "\d+"],
        options: ['expose' => true],
        methods: ["GET"]
    )]
    public function index(
        Request             $request,
        TeamRepository      $teamRepository,
        int                 $page = 1
    ): Response {
        $search = $request->get('search');

        [$limit, $offset] = $this->getPaginationLimitAndOffset($page);

        $teams = $this->wrapWithPaginationData(
            array_map(
                static function (Team $team): array {
                    return $team->getJsonResponse();
                },
                $teamRepository->findAllMatchingFilter($search, $limit, $offset)
            ),
            $teamRepository->countAllMatchingFilter($search),
            $page,
            'app_teams'
        );

        return $this->inertia->render(
            "Teams/Index",
            [
                'teams' => $teams,
                'filters' => ['search' => $search]
            ]
        );
    }

    #[Route('/teams/create', 'app_teams_create', options: ['expose' => true])]
    public function create(
        Request $request,
        TeamRepository $teamRepository
    ): Response {
        if ($request->getMethod() === Request::METHOD_POST) {
            return $this->handleTeamCreation($request, $teamRepository);
        }

        $this->inertia->share("playerPositions", PositionEnum::getAllValues());

        return $this->inertia->render(
            "Teams/Create",
            [
                'errors' => new ArrayObject()
            ]
        );
    }

    #[Route('/teams/view/{id}', 'app_teams_view', options: ['expose' => true])]
    public function view(Team $team, BidRepository $bidRepository): Response
    {
        $bids = $bidRepository->getTeamBids($team);

        return $this->inertia->render(
            "Teams/View",
            [
                "bids"  => array_map(function (Bid $bid) {
                    return $bid->getJsonResponse();
                }, $bids),
                "team"  => $team->getJsonResponse()
            ]
        );
    }

    #[Route('/bids/create', name: 'app_create_bid', options: ['expose' => true], methods: ["POST"])]
    public function createBid(
        BidService $bidService,
        #[MapRequestPayload] BidRequestDTO $bidRequestDTO
    ): RedirectResponse {
        try {
            $bid = $bidService->createBidFromRequest($bidRequestDTO);

            $this->addFlash("success", "Bid successfully placed for {$bid->getPlayer()->getFullName()}");
        } catch (BidException $e) {
            $this->addFlash("error", $e->getMessage());
        }

        return $this->redirectToRoute("app_teams_view", ["id" => $bidRequestDTO->playerTeamId]);
    }

    #[Route('/bids/accept/{id}', name:'app_accept_bid', options:['expose' => true], methods: ["POST"])]
    public function acceptBid(Bid $bid, BidService $bidService): RedirectResponse
    {
        // TODO: Voter here to dey access

        $initialPlayerTeamId = $bid->getPlayer()->getTeam()->getId();

        try {
            $bidService->acceptBid($bid);

            $this->addFlash("success", "Bid successfully accepted");
        } catch (BidException $e) {
            $this->addFlash("error", $e->getMessage());
        }

        return $this->redirectToRoute("app_teams_view", ["id" => $initialPlayerTeamId]);
    }

    #[Route('/bids/reject/{id}', name:'app_reject_bid', options:['expose' => true], methods: ["POST"])]
    public function rejectBid(Bid $bid, BidService $bidService): RedirectResponse
    {
        // TODO: Voter here to deny access

        $initialPlayerTeamId = $bid->getPlayer()->getTeam()->getId();

        $bidService->rejectBid($bid);

        $this->addFlash("success", "Bid successfully rejected");

        return $this->redirectToRoute("app_teams_view", ["id" => $initialPlayerTeamId]);
    }

    private function handleTeamCreation(Request $request, TeamRepository $teamRepository): Response
    {
        $team = new Team();

        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(TeamType::class, $team)
            ->submit([
                "name"      => $request->request->get('name'),
                "players"   => JsonDecoder::decode($request->request->get('players')),
                "logoFile"  => $request->files->get('logo'),
            ]);

        if ($form->isValid()) {
            $team->setOwner($user);

            foreach ($form["players"]->getData() as $player) {
                $player->setTeam($team);
            }

            $teamRepository->save($team, true);

            return $this->redirectToRoute("app_teams");
        }

        $errorProps = array_merge(
            $this->inertia->getShared("errors"),
            FormErrors::getErrors($form)
        );

        $this->inertia->share("errors", $errorProps);

        return $this->inertia->render("Teams/Create");
    }
}
