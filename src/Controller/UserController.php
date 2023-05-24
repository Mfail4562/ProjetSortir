<?php

    namespace App\Controller;

    use App\Entity\User;
    use App\Form\UserType;
    use App\Repository\UserRepository;
    use App\Service\UserService;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\File\Exception\FileException;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\String\Slugger\SluggerInterface;

    #[Route('/user')]
    class UserController extends AbstractController
    {
        #[Route('/', name: 'app_user_index', methods: ['GET'])]
        public function index(UserRepository $userRepository): Response
        {
            return $this->render('user/index.html.twig', [
                'users' => $userRepository->findAll(),
            ]);
        }

        #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
        public function show(int $id, UserRepository $userRepository): Response
        {
            $user = $userRepository->find($id);
            return $this->render('user/show.html.twig', [
                'user' => $user,
            ]);
        }

        #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
        public function edit(Request $request, User $user, UserRepository $userRepository, SluggerInterface $slugger, UserService $userService, $id): Response
        {
            $form = $this->createForm(UserType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $avatarFile = $form->get('avatar')->getData();
                if ($avatarFile) {
                    $fileName = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($fileName);
                    $fileName = $safeFilename . '-' . uniqid() . '.' . $avatarFile->guessExtension();

                    try {
                        $avatarFile->move(
                            $this->getParameter('avatar_directory'),
                            $fileName
                        );
                    } catch (FileException $e) {

                    }

                    $user->setAvatar($fileName);

                    $userRepository->save($user, true);

                }

                //$userRepository->save($user, true);

                return $this->redirectToRoute('app_user_show', ['id' => $id], Response::HTTP_SEE_OTHER);
            }

            return $this->render('user/edit.html.twig', [
                'user' => $user,
                'form' => $form->createView(),
            ]);
        }

        #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
        public function delete(Request $request, User $user, UserRepository $userRepository): Response
        {
            if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
                $userRepository->remove($user, true);
            }

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }


        #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
        public function new(Request $request, UserRepository $userRepository): Response
        {
            $user = new User();
            $form = $this->createForm(UserType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $userRepository->save($user, true);

                return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('user/new.html.twig', [
                'user' => $user,
                'form' => $form->createView(),
            ]);
        }
    }
