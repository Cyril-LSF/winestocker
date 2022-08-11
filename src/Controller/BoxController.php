<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/box')]
class BoxController extends AbstractController
{
    #[Route('/', name: 'box.index')]
    public function index(): Response
    {
        return $this->render('box/index.html.twig');
    }
}
