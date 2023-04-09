<?php

namespace App\Listener;

use App\Event\QuoteCreated;
use App\Repository\QuoteRepository;
use App\Repository\UserRepository;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: QuoteCreated::class, method: 'Experience')]
#[AsEventListener(event: QuoteCreated::class, method: 'InNewCategory')]
class QuoteListener
{
    public function __construct(UserRepository $userRepository, QuoteRepository $quoteRepository)
    {
        $this->userRepository = $userRepository;
        $this->quoteRepository = $quoteRepository;
    }

    public function Experience(QuoteCreated $event): void
    {
        $category = $event->getQuote()->getCategory();
        $user = $event->getQuote()->getUser();
        $countQuote = $this->quoteRepository->count(['user' => $user, 'category' => $category]);

        if (null === $category) {
            $user->setExperience($user->getExperience() + 100);
            $this->userRepository->save($user, true);
        } else {
            if ($countQuote > 1) {
                $user->setExperience($user->getExperience() + 100);
                $this->userRepository->save($user, true);
            } else {
                $user->setExperience($user->getExperience() + 120);
                $this->userRepository->save($user, true);
            }
        }
    }

    public function InNewCategory(QuoteCreated $event)
    {
    }
}
