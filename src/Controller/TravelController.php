<?php

namespace App\Controller;

use App\Entity\Status;
use App\Entity\Travel;
use App\Entity\User;
use App\Form\TravelType;
use App\Repository\TravelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\Mapping\Id;
use Psr\Container\ContainerInterface;
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
            'travel' => $travelRepository->findAll(),
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

    /**
     * @throws ORMException
     */
    #[Route('/register/{id}',name: 'app_travel_register' )]
    public function register(User $user, $id,Request $request, TravelRepository $travelRepository, EntityManagerInterface $entityManager,Status $status )
    {
        $travel =$travelRepository->find($id);

        $form = $this->createForm(TravelType::class, $travel);
        $form->handleRequest($request);

        $nbMaxTraveler=$travel->getNbMaxTraveler();
        $status= $travel->getStatus();

        if ($status === 'Ouvert' and count($travel->getSubscriptionedTravelers())< $nbMaxTraveler){
            $travel->setNbMaxTraveler($this->$user());
            if (count($travel->getSubscriptionedTravelers() === $nbMaxTraveler)){
                $travel->setStatus($this->$status[3]);
            }
            $entityManager->persist($travel);
            $entityManager->flush();
            $this->addFlash('success','Vous étè bien inscrit pour la Sortie');
            return $this->redirectToRoute('app_travel_index');
        }
        else{
            $this->addFlash('warning','Vous n avait pas étè bien inscrit pour la Sortie');
            return $this->redirectToRoute('app_travel_index');
        }
    }
}
