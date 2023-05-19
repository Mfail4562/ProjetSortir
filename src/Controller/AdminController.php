<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserAdminType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin_dashboard', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('admin/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/admin/{id}/edit', name: 'app_admin_userEdit', methods: ['GET', 'POST'])]
    public function userEdit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserAdminType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_admin_dashboard', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/{id}', name: 'app_admin_user_show', methods: ['GET'])]
    public function userShow(int $id, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);
        return $this->render('admin/show.html.twig', [
            'user' => $user,
        ]);
    }
}
