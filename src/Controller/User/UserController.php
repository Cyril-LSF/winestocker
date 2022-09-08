<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/user')]
class UserController extends AbstractController
{
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
    #[IsGranted('ROLE_MANAGER', statusCode: 403, message: "Acces non autorisé")]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $this->userRepository->findAll(),
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
            $this->addFlash('error', "Acces non autorisé");
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
    public function edit(Request $request, User $user, FileUploader $fileUploader): Response
    {
        //Acces control
        if(!$this->isGranted('ROLE_MANAGER') && $user->getId() != $this->getUser()->getId()){
            $this->addFlash('error', "Tu n'as pas l'autorisation d'accéder à cette page");
            return $this->redirectToRoute('box.index');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $plainPassword =  $form->get('plainPassword')->getData();
            $pictureFile = $form->get('picturesFile')->getData();
            if($pictureFile){
                $picturesFileName = $fileUploader->upload($pictureFile);
            }
            
            $this->userRepository->edit($user, true, $plainPassword, $picturesFileName);

            $this->addFlash('success', "Les informations de l'utilisateur ont bien été modifiées");
            return $this->redirectToRoute('user.show', ['id'=>$user->getId()], Response::HTTP_SEE_OTHER);
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
    #[IsGranted('ROLE_MANAGER', statusCode: 403, message: "Acces non autorisé")]
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $this->userRepository->remove($user, true);
        }

        $this->addFlash('success', "l'utilisateur a bien été supprimé");
        return $this->redirectToRoute('user.index', [], Response::HTTP_SEE_OTHER);
    }
}
