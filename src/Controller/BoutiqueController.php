<?php

namespace App\Controller;

use App\Repository\LivresRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BoutiqueController extends AbstractController
{
    #[Route('/boutique', name: 'app_boutique')]
    public function index(LivresRepository $livresRepository): Response
    {
        // return $this->render('livres/index.html.twig', [
        //     'livres' => $livresRepository->findAll(),
        // ]);
        return $this->render('boutique/index.html.twig', [
            'controller_name' => 'BoutiqueController',
            'livres' => $livresRepository->findAll(),
        ]);
    }
}
