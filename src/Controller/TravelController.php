<?php

namespace App\Controller;

use App\Entity\Travel;
use App\Form\TravelCancelType;
use App\Form\TravelType;
use App\Repository\TravelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/travel', name: 'app_travel_')]
class TravelController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(TravelRepository $travelRepository): Response
    {


        return $this->render('travel/index.html.twig', [
            'travels' => $travelRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, TravelRepository $travelRepository): Response
    {
        $user = $this->getUser();

        $travel = new Travel();
        $travel->setLeader($user)
            ->setDateStart(new \DateTime('now'));

        $form = $this->createForm(TravelType::class, $travel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $travelRepository->save($travel, true);

            return $this->redirectToRoute('app_travel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('travel/new.html.twig', [
            'travel' => $travel,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Travel $travel): Response
    {
        return $this->render('travel/show.html.twig', [
        'travel' => $travel,
     ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Travel $travel, TravelRepository $travelRepository): Response
    {
        $form = $this->createForm(TravelType::class, $travel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $travelRepository->save($travel, true);

            return $this->redirectToRoute('app_travel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('travel/edit.html.twig', [
            'travel' => $travel,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Travel $travel, TravelRepository $travelRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $travel->getId(), $request->request->get('_token'))) {
            $travelRepository->remove($travel, true);
        }

        return $this->redirectToRoute('app_travel_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @throws ORMException
     */
    #[Route('/register/{id}',name: 'register' )]
    public function register(User $user, $id, Request $request, TravelRepository $travelRepository, EntityManagerInterface $entityManager, Status $status)
    {
        $registered = false;
        $maxTravelersReached = false;


        $currentUser = $this->getUser();

        $travelToRegister = $travelRepository->find($id);


        $statusId = $travelToRegister->getStatus()->getId();

        if ($statusId != 2) {
            $this->addFlash('warning', 'STATUS ERROR : You cannot be register to this travel it is not open for registration .');
        } else {
            foreach ($travelToRegister->getSubscriptionedTravelers() as $traveler) {
                if ($traveler->getUserIdentifier() === $currentUser->getUserIdentifier()) {
                    $this->addFlash('warning', 'ALREADY REGISTERED ERROR : You have already been registered for this travel');
                    $registered = true;
                }
            }
            if (!$registered) {
                $nbTravelers = count($travelToRegister->getSubscriptionedTravelers());
                $maxtraveler = $travelToRegister->getNbMaxTraveler();
                if ($nbTravelers >= $maxtraveler) {
                    $this->addFlash('warning', 'TRAVELERS ERROR : You cannot be register to this travel : the maximum travelers has been reached');
                    $maxTravelersReached = true;
                } else {
                    $travelToRegister->addSubscriptionedTraveler($currentUser);
                    $entityManager->persist($travelToRegister);
                    $entityManager->flush();
                    $this->addFlash('success', 'You have been registered for this travel');
                }
            }

        }
        return $this->redirectToRoute('app_travel_index');
    }


    #[Route('/unregister/{id}', name: 'unregister')]
    public function unregister(
        $id,
        EntityManagerInterface $entityManager,
        TravelRepository $travelRepository
    ): Response
    {
        $currentUser = $this->getUser();
        $travelToUnsubscribe = $travelRepository->find($id);

        if ($travelToUnsubscribe->getSubscriptionedTravelers()->contains($currentUser)) {
            $travelToUnsubscribe->removeSubscriptionedTraveler($currentUser);
            $entityManager->persist($travelToUnsubscribe);
            $entityManager->flush();

            $this->addFlash('success', 'Your registration have been canceled');
        }


        return $this->redirectToRoute('app_travel_index');
    }


    #[Route("/{id}/cancel'", name: 'cancel_travel', methods: ['GET', 'POST'])]
    public function cancelTravel(Travel $travel, Request $request, TravelRepository $travelRepository): Response
    {
        $form = $this->createForm(TravelCancelType::class, $travel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $travelRepository->save($travel, true);

            return $this->redirectToRoute('app_travel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('travel/cancel.html.twig', [
            'travel' => $travel,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_travel_recherche', methods: ['GET'])]
    public function recherche(Request $request, Travel $travel, TravelRepository $travelRepository, $dateStart, EntityManagerInterface $entityManager, $status, $statusRepository): Response
    {
        $user = $this->getUser();

        $today = date("Y-m-d H:i:s");
        $travelCampus = $travelRepository->findByNonClosCampus($user->getCampus()->getId());
        $travel = $travelRepository->findByNonClos();
        $campus = $travelRepository->findAll();
        $research = "";
        $contient = null;
        $dateStart = null;
        $duration = null;
        $lead = false;
        $subscri = false;
        $dontSubscri = false;
        $endTravel = false;
        $dateValid = true;


        if ($request->query->get("campusResearch")) {
            //recherche par nom
            if ($request->query->get("dateEnd") != "" && $request->query->get("dateFirs") != "" && $request->query->get("dateEnd") < $request->query->get("dateFirs")) {
                $dateValid = false;
            } else {
                $travel = $travelRepository->findByResearch($request->query, $this->getUser());
            }
            if ($request->query->get("litteration") != "") {
                $research = $request->query->get("litteration");
            }
            if ($request->query->get("nameTravelRecherch") != "") {
                $contient = $request->query->get("nameTravelRecherch");
            }
            if ($request->query->get("dateFirs") != "") {
                $dateStart = $request->query->get("dateFirs");
            }
            if ($request->query->get("dateEnd") != "") {
                $duration = $request->query->get("dateEnd");
            }
            if ($request->query->get("leaderTravel") != null) {
                $lead = true;
            }
            if ($request->query->get("travelSubscri") != null) {
                $subscri = true;
            }

            if ($request->query->get("travelDontSubscri") != null) {
                $dontSubscri = true;
            }
            if ($request->query->get("travelEnd") != null) {
                $endTravel = true;
            }
        }
        return $this->render('travel/index.html.twig', [
            'travel' => $travel,
            'travelCampus' => $travelCampus,
            'campus' => $campus,
            'research' => $research,
            'contient' => $contient,
            'dateStart' => $dateStart,
            'duration' => $duration,
            'lead' => $lead,
            'subscri' => $subscri,
            'dontSubscri' => $dontSubscri,
            'travelEnd' => $endTravel,
            'today' => $today,
            'dateValid' => $dateValid,
        ]);
    }


}