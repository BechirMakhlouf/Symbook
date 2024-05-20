<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Repository\PanierRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CommandeController extends AbstractController
{
    private $security;
    private $entityManager;

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    // new route to create new commands
    #[Route('/commande/new', name: 'app_commande_new')]
    public function newCommande(): Response
    {
        $user = $this->security->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $userPanier = $user->getPanier();
        if (!$userPanier) {
            return $this->redirectToRoute('app_boutique');
        } elseif ($userPanier->getPanierItems()->isEmpty()) {
            return $this->redirectToRoute('app_boutique');
        }

        $commande = (new Commande())
                    ->setUser($user)
                    ->setEtat("en cours")
                    ->setPrix($userPanier->getTotal())
                    ->setDateDeCommande(new DateTime());

        // foreach ($userPanier->getPanierItems() as $item) {
        //     $commande->addCommandeItem($item);
        //     $this->entityManager->remove($item);
        // }
        foreach ($userPanier->getPanierItems() as $item) {
            $achat = $item->toAchat();
            $commande->addAchat($achat);
            $this->entityManager->persist($achat);
        }
        $this->entityManager->persist($commande);

        foreach ($userPanier->getPanierItems() as $item) {
            // $commande->addCommandeItem($item);
            // $this->entityManager->remove($item);
            // $user->getPanier()->getPanierItems()
            // $this->entityManager->persist($user);
            $user->getPanier()->removePanierItem($item);
            $this->entityManager->remove($item);
        }

        $this->entityManager->persist($user->getPanier());
        $this->entityManager->flush();

        return $this->redirectToRoute('app_commande_histoire');
    }

    // #[Route('/{id}', name: 'app_commande')]
    // public function commande(Commande $commande): Response
    // {
    //     return $this->render('commande/commande.html.twig', [
    //         'commande' => $commande,
    //     ]);
    // }

    #[Route('/commande/histoire', name: 'app_commande_histoire')]
    public function histoire(): Response
    {
        $user = $this->security->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('commande/histoire.html.twig', [
            'commandes' => $user->getCommandes(),
        ]);
    }
}
