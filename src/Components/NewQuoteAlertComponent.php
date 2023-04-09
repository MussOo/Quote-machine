<?php
namespace App\Components;

use App\Repository\QuoteRepository;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('new_quote_alert')]
class NewQuoteAlertComponent
{
    use DefaultActionTrait;
    private QuoteRepository $quoteRepository;

    public string $type = 'success';

    public function __construct(QuoteRepository $quoteRepository)
    {
        $this->quoteRepository = $quoteRepository;
    }

    public function getNewQuote(): ?string
    {

        $quotes = $this->quoteRepository->findBy([], ['date' => 'DESC']);
        dd($quotes);
    }


    public function getRandomNumber(): int
    {
        return rand(0, 100);
    }
}