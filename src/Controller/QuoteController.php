<?php

namespace App\Controller;

use App\Entity\Quote;
use App\Event\QuoteCreated;
use App\Form\EditQuoteType;
use App\Repository\QuoteRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
// Include paginator interface
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class QuoteController extends AbstractController
{
    #[Route('/quote', name: 'quote_index')]
    public function index(ManagerRegistry $doctrine, Request $request, QuoteRepository $quoteRepository, PaginatorInterface $paginator): Response
    {
        $queryBuilder = $quoteRepository->createQueryBuilder('q');

        $search = $request->query->get('search');
        if (!empty($search)) {
            $queryBuilder->where('q.content LIKE :search')->setParameter('search', '%'.$search.'%');
        }

        // Paginate the results of the query
        $appointments = $paginator->paginate(
            // Doctrine Query, not results
            $queryBuilder->getQuery()->getResult(),
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );

        return $this->render('quote/index.html.twig', [
            'citations' => $appointments,
            'search' => $search,
        ]);
    }

    #[Route('/quote/new', name: 'new_quotes')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function newQuotes(EventDispatcherInterface $eventDispatcher, ManagerRegistry $doctrine, Request $request, ValidatorInterface $validator): Response
    {
        // FORM - Create Quote
        $quote = new Quote();

        $form = $this->createForm(EditQuoteType::class, $quote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {  // si il y a un submit et que le form est valid
            $quote->setDateCreation(new \DateTime());
            $quote->setUser($this->getUser());
            $doctrine->getManager()->persist($quote);
            $doctrine->getManager()->flush();
            $this->addFlash('success', 'Citation ajoutée avec succès !');

            $event = new QuoteCreated($quote);
            $eventDispatcher->dispatch($event);

            return $this->redirectToRoute('quote_index');
        }

        return $this->render('quote/new-quotes.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/quote/{id}/delete', name: 'delete_quotes')]
    #[ParamConverter('Quote', options: ['mapping' => ['quote_id' => 'id']])]
    #[IsGranted('QUOTE_DELETE', subject: 'quote')]
    public function deleteQuotes(ManagerRegistry $doctrine, Quote $quote, Request $request): Response
    {
        $entityManager = $doctrine->getManager();

        if (null == $quote->getCategory()) {
            $entityManager->remove($quote);
            $entityManager->flush();
            $this->addFlash('success', 'Citation supprimée avec succès !');

            return $this->redirectToRoute('quote_index');
        } else {
            $entityManager->remove($quote);
            $entityManager->flush();
            $this->addFlash('success', 'Citation supprimée avec succès !');

            return $this->redirectToRoute('app_category_show', [
                'id' => $quote->getCategory()->getId(),
            ]);
        }
    }

    #[Route('/quote/{id}/edit', name: 'edit_quotes')]
    #[ParamConverter('Quote', options: ['mapping' => ['quote_id' => 'id']])]
    #[IsGranted('QUOTE_EDIT', subject: 'quote')]
    public function editQuotes(ManagerRegistry $doctrine, Quote $quote, Request $request): Response
    {
        $entityManager = $doctrine->getManager();

        // FORM - Create Quote
        $form = $this->createForm(EditQuoteType::class, $quote);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {  // si il y a un submit et que le form est valid
            $doctrine->getManager()->persist($quote);
            $doctrine->getManager()->flush();
            $this->addFlash('success', 'Citation ajoutée avec succès !');

            return $this->redirectToRoute('quote_index');
        }

        return $this->render('quote/edit-quotes.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/quote/random', name: 'random_quote')]
    public function RandomQuote(ManagerRegistry $doctrine, Request $request): Response
    {
        $repository = $doctrine->getRepository(Quote::class);
        $quotes = $repository->findAll();

        if (null == $quotes) {
            $randomQuote = null;
        } else {
            $randomQuote = $quotes[array_rand($quotes)];
        }

        return $this->render('quote/random_quote.html.twig', [
            'quote' => $randomQuote,
        ]);
    }

    #[Route('/quote.csv', name: 'quote_csv')]
    public function exportCsv(SerializerInterface $serializer): Response
    {
        // collumns content, date_creation, user name
        $quotes = $this->getDoctrine()->getRepository(Quote::class)->findAll();

        $data = $serializer->serialize(
            $quotes,
            'csv',
            ['groups' => 'export']
        );

        $response = new Response($data);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="quotes.csv"');

        return $response;
    }
}
