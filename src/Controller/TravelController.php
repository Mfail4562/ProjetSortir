<?php

    namespace App\Controller;

    use App\Entity\Travel;
    use App\Form\TravelCancelType;
    use App\Form\TravelType;
    use App\Repository\StatusRepository;
    use App\Repository\TravelRepository;
    use App\Service\RegisterService;
    use DateTimeZone;
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
        public function index(TravelRepository $travelRepository, StatusRepository $statusRepository): Response
        {

            $allTravels = $travelRepository->findAll();

            foreach ($allTravels as $travel) {
                $newStatusId = 0;
                $now = new \DateTime('now', new DateTimeZone('Europe/Paris'));
                if ($travel->getLimitDateSubscription() < $now) {
                    $newStatusId = 3;
                }

                /*$dateEnd = new \DateTime()
                if ($travel->getDateStart() < $now && $dateEnd < $now) {
                    $newStatusId = 4;
                }

                if ($dateEnd < $now) {
                    $newStatusId = 5;
                }*/

                if ($newStatusId != 0) {
                    $newStatus = $statusRepository->find($newStatusId);
                    $travel->setStatus($newStatus);
                    $travelRepository->save($travel, true);
                }
            }
            return $this->render('travel/index.html.twig', [
                'travels' => $allTravels,
            ]);
        }

        #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
        public function new(Request $request, TravelRepository $travelRepository, RegisterService $registerService, EntityManagerInterface $entityManager): Response
        {
            define('CREATING_MESSAGE', 'creation et inscription automatique :');
            $user = $this->getUser();

            $travel = new Travel();
            $travel->setLeader($user)
                ->setDateStart(new \DateTime('now', new DateTimeZone('Europe/Paris')));

            $form = $this->createForm(TravelType::class, $travel);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {


                $travelRepository->save($travel, true);

                $registerService->RegisterToTravel($entityManager, $travel->getId(), $travelRepository, $user, $request, CREATING_MESSAGE);

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

        #[Route('/register/{id}', name: 'register')]
        public function register(
            EntityManagerInterface $entityManager,
                                   $id,
            TravelRepository       $travelRepository,
            RegisterService        $registerService,
            Request                $request
        ): Response
        {
            define('REGISTERING_MESSAGE', 'inscription :');
            $currentUser = $this->getUser();

            $registerService->RegisterToTravel($entityManager, $id, $travelRepository, $currentUser, $request, REGISTERING_MESSAGE);

            return $this->redirectToRoute('app_travel_index');
        }


        #[Route('/unregister/{id}', name: 'unregister')]
        public function unregister(
            $id,
            EntityManagerInterface $entityManager,
            TravelRepository $travelRepository,
            RegisterService $registerService
        ): Response
        {

            define('UNREGISTERING_MESSAGE', 'désinscription :');
            $currentUser = $this->getUser();
            $travelToUnsubscribe = $travelRepository->find($id);

            if ($travelToUnsubscribe->getSubscriptionedTravelers()->contains($currentUser)) {
                $travelToUnsubscribe->removeSubscriptionedTraveler($currentUser);
                $entityManager->persist($travelToUnsubscribe);
                $entityManager->flush();

                $this->addFlash('success', 'Your registration have been canceled');

                $registerService->addToTxtFollowing(UNREGISTERING_MESSAGE, $currentUser, $travelToUnsubscribe);

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

    }