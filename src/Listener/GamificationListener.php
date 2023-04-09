<?php

namespace App\EventListener;

use App\Event\QuoteCreated;
use App\Repository\QuoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
class GamificationListener
{
    public const QUOTE_CREATED_EXP = 100;
    public const QUOTE_CREATED_FIRST_TIME_CATEGORY_EXP = 120;

    private EntityManagerInterface $entityManager;

    private QuoteRepository $quoteRepository;

    public function __construct(EntityManagerInterface $entityManager, QuoteRepository $quoteRepository)
    {
        $this->entityManager = $entityManager;
        $this->quoteRepository = $quoteRepository;
    }

    public function __invoke(QuoteCreated $event): void
    {
        $quote = $event->getQuote();
        $experience = self::QUOTE_CREATED_EXP;

        $category = $quote->getCategory();
        if (null !== $category) {
            $nbQuotesInSameCategory = $this->quoteRepository->count([
                'author' => $quote->getUser(),
                'category' => $quote->getCategory(),
            ]);

            if (1 === $nbQuotesInSameCategory) {
                $experience = self::QUOTE_CREATED_FIRST_TIME_CATEGORY_EXP;
            }
        }

        $quote->getUser()->addExperience($experience);

        $this->entityManager->flush();
    }
}
