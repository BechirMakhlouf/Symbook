<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\PanierItem;
use App\Entity\Livres;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PanierController extends AbstractController
{
    // Inject the Security service in the constructor
    private $security;
    private $entityManager;

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    #[Route('/addLivreToPanier/{livre}', name: 'add_livre_to_panier', methods: ['GET'])]
    public function addLivreToPanier(Livres $livre): Response
    {
        $user = $this->security->getUser();

        if (!$user->getPanier()) {
            $panier = new Panier();
            $user->setPanier($panier);
            $this->entityManager->persist($panier);
        }

        $livreExistant = null;
        foreach ($user->getPanier()->getPanierItems() as $item) {
            if ($item->getLivre()->getId() === $livre->getId()) {
                $livreExistant = $item;
                break;
            }
        }

        if ($livreExistant !== null) {
            $livreExistant->setQuantite($livreExistant->getQuantite() + 1);
            $this->entityManager->persist($livreExistant);
        } else {
            $panierItem = new PanierItem();
            $panierItem->setLivre($livre);
            $panierItem->setQuantite(1);
            $panierItem->setPanier($user->getPanier());
            $this->entityManager->persist($panierItem);
        }

        $this->entityManager->flush();
        // return new Response('Livre added to Panier successfully');
        return $this->redirectToRoute('app_panier');
    }

    public function calculateTotal(Panier $panier): float
    {
        $total = 0.0;

        foreach ($panier->getPanierItems() as $item) {
            $total += $item->getLivre()->getPrix() * $item->getQuantite();
        }

        return $total;
    }

    #[Route('/panier', name: 'app_panier')]
    public function displayPanier(): Response
    {
        $user = $this->security->getUser();
        if (!$user->getPanier()) {
            $panier = new Panier();
            $user->setPanier($panier);
            $this->entityManager->persist($panier);
        }
        $panier = $user->getPanier();
        $panierItems = $panier->getPanierItems();

        return $this->render('panier/index.html.twig', [
            'panierItems' => $panierItems,
            'total' => $this->calculateTotal($panier),
        ]);
    }

    #[Route('/panier/remove/{id}', name: 'remove_panier_item')]
    public function removePanierItem(PanierItem $panierItem): Response
    {
        $user = $this->security->getUser();
        $panier = $user->getPanier();

        // Remove the PanierItem from the Panier
        $panier->removePanierItem($panierItem);

        // Persist the changes to the database
        $this->entityManager->persist($panier);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_panier');
    }
}
