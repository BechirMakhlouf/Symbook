<?php

namespace App\Controller;

use App\Entity\Livres;
use App\Repository\LivresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BoutiqueController extends AbstractController
{
    // #[Route('/boutique', name: 'app_boutique')]
    // public function index(LivresRepository $livresRepository): Response
    // {
    //     // return $this->render('livres/index.html.twig', [
    //     //     'livres' => $livresRepository->findAll(),
    //     // ]);
    //     return $this->render('boutique/index.html.twig', [
    //         'controller_name' => 'BoutiqueController',
    //         'livres' => $livresRepository->findAll(),
    //     ]);
    // }

    #[Route('/boutique/{page<\d+>?1}', name: 'app_boutique')]
    public function index(LivresRepository $livresRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $livresRepository->createQueryBuilder('l')->getQuery();
        $pagination = $paginator->paginate(
            $query, 
            $request->attributes->get('page', 1), 
            10 
        );

        return $this->render('boutique/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/boutique/livre/{id}', name: 'app_boutique_livre', methods: ['GET'])]
    public function show(Livres $livre): Response
    {
        return $this->render('boutique/livre.html.twig', [
            'livre' => $livre,
        ]);
    }

}
