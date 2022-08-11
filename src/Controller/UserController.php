<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/user')]
class UserController extends AbstractController
{
    private $accessControlService;
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Return the users list
     * @return Response
     */
    #[Route('/', name: 'user.index', methods: ['GET'])]
    public function index(): Response
    {
        //Acces control
        if(!$this->isGranted('ROLE_MANAGER')){
            $this->addFlash('error', "Tu n'as pas l'autorisation d'accéder à cette page");
            return $this->redirectToRoute('box.index');
        }

        return $this->render('user/index.html.twig', [
            'users' => $this->userRepository->findAll(),
        ]);
    }

    /**
     * Add new user in database
     * @param Request $request
     * @return Response
     */
    #[Route('/new', name: 'user.new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        //Acces control
        if(!$this->isGranted('ROLE_MANAGER')){
            $this->addFlash('error', "Tu n'as pas l'autorisation d'accéder à cette page");
            return $this->redirectToRoute('box.index');
        }

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userRepository->add($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * Return a user with id
     * @param User $user
     * @return Response
     */
    #[Route('/{id}', name: 'user.show', methods: ['GET'])]
    public function show(User $user): Response
    {
        //Acces control
        if(!$this->isGranted('ROLE_MANAGER') && $user->getId() != $this->getUser()->getId()){
            $this->addFlash('error', "Tu n'as pas l'autorisation d'accéder à cette page");
            return $this->redirectToRoute('box.index');
        }

        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * Update user
     * @param Request $request
     * @param User $user
     * @return Response
     */
    #[Route('/{id}/edit', name: 'user.edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user): Response
    {
        //Acces control
        if(!$this->isGranted('ROLE_MANAGER') && $user->getId() != $this->getUser()->getId()){
            $this->addFlash('error', "Tu n'as pas l'autorisation d'accéder à cette page");
            return $this->redirectToRoute('box.index');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userRepository->add($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * Delete user
     * @param Request $request
     * @param User $user
     * @return Response
     */
    #[Route('/{id}', name: 'user.delete', methods: ['POST'])]
    public function delete(Request $request, User $user): Response
    {
        //Acces control
        if(!$this->isGranted('ROLE_MANAGER')){
            $this->addFlash('error', "Tu n'as pas l'autorisation d'accéder à cette page");
            return $this->redirectToRoute('box.index');
        }
        
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $this->userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
