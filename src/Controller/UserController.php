<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\QuoteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/profil', name: 'profil')]
    public function profil(QuoteRepository $quoteRepository): Response
    {
        // On récupère l'utilisateur connecté
        $user = $this->getUser();

        $query = $quoteRepository->createQueryBuilder('c')
            ->where('c.user = :user')
            ->orderBy('c.date_creation', 'DESC')
            ->setMaxResults(5)
            ->setParameter('user', $user->getId())
            ->getQuery();

        return $this->render('user/profil.html.twig', [
            'controller_name' => 'UserController',
            'user' => $user,
            'quotes' => $query->getResult(),
        ]);
    }

    #[Route('/user/{id}', name: 'profil_id')]
    public function profilId(User $user, QuoteRepository $quoteRepository): Response
    {
        $query = $quoteRepository->createQueryBuilder('c')
            ->where('c.user = :user')
            ->orderBy('c.date_creation', 'DESC')
            ->setMaxResults(5)
            ->setParameter('user', $user->getId())
            ->getQuery();

        return $this->render('user/profil.html.twig', [
            'controller_name' => 'UserController',
            'user' => $user,
            'quotes' => $query->getResult(),
        ]);
    }
}
