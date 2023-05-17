<?php

namespace App\Controller;

use App\Entity\Travel;
use App\Form\TravelType;
use App\Repository\TravelRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/travel')]
class TravelController extends AbstractController
{
    #[Route('/', name: 'app_travel_index', methods: ['GET'])]
    public function index(TravelRepository $travelRepository): Response
    {


        return $this->render('travel/index.html.twig', [
            'travels' => $travelRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_travel_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TravelRepository $travelRepository): Response
    {
        $user = $this->getUser();

        $travel = new Travel();
        $travel->setLeader($user);

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

    #[Route('/{id}', name: 'app_travel_show', methods: ['GET'])]
    public function show(Travel $travel): Response
    {
        return $this->render('travel/show.html.twig', [
            'travel' => $travel,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_travel_edit', methods: ['GET', 'POST'])]
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

    #[Route('/{id}', name: 'app_travel_delete', methods: ['POST'])]
    public function delete(Request $request, Travel $travel, TravelRepository $travelRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$travel->getId(), $request->request->get('_token'))) {
            $travelRepository->remove($travel, true);
        }

        return $this->redirectToRoute('app_travel_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/register/{id}',name: 'app_travel_register' )]
    public function register(
        EntityManagerInterface $entityManager,
        $id,
        TravelRepository $travelRepository,
    ): Response
    {
        $registered = false;
        $maxTravelersReached = false;

        $currentUser = $this->getUser();

        $travelToRegister = $travelRepository->find($id);

        $statusId = $travelToRegister->getStatus()->getId();

        if ($statusId!=2)
        {
            $this->addFlash('warning', 'STATUS ERROR : You cannot register to this travel it is not open for registration .');
        }else {
            foreach ($travelToRegister->getSubscriptionedTravelers() as $traveler) {
                if ($traveler->getUserIdentifier() === $currentUser->getUserIdentifier()){
                    $this->addFlash('warning', 'ALREADY REGISTER ERROR : You have already registered for this travel');
                    $registered = true;
                }
            }
            if (!$registered) {
                $nbTravelers = count($travelToRegister->getSubscriptionedTravelers());
                $maxtraveler = $travelToRegister->getNbMaxTraveler();
                if ($nbTravelers >= $maxtraveler) {
                    $this->addFlash('warning', 'TRAVELERS ERROR : You cannot register to this travel : the maximum travelers has been reached');
                    $maxTravelersReached = true;
                } else {
                    $travelToRegister->addSubscriptionedTraveler($currentUser);
                    $entityManager->persist($travelToRegister);
                    $entityManager->flush();
                    $this->addFlash('success', 'You have registered for this travel');
                }
            }
        }
        return $this->redirectToRoute('app_travel_index');
    }
}
