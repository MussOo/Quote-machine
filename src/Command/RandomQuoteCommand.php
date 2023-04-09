<?php

namespace App\Command;

use App\Entity\Category;
use App\Entity\Quote;
use App\Repository\QuoteRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:random-quote',
    description: 'Add a short description for your command',
)]
class RandomQuoteCommand extends command
{
    public function __construct(private ManagerRegistry $doctrine, private QuoteRepository $quoteRepository)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('category', null, InputOption::VALUE_OPTIONAL, 'Category of the quote')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $optionCategory = $input->getOption('category');
        $repository = $this->doctrine->getRepository(Quote::class);
        $category = $this->doctrine->getRepository(Category::class)->findOneBy(['name' => $optionCategory]);

        if (!$optionCategory) {
            $randomQuote = $this->quoteRepository->RandomQuote();
            if (!$randomQuote) {
                $io->error('No quote found');

                return Command::FAILURE;
            }
            $io->title('===META===');
            $io->writeln($randomQuote->getMeta());
            $io->newline(2);
            $io->title('===CONTENT===');
            $io->writeln($randomQuote->getContent());
            $io->success('SUCCESS !');

            return Command::SUCCESS;
        }

        if ($category) {
            $randomQuote = $this->quoteRepository->RandomQuoteByCategory($category);
            if (!$randomQuote) {
                $io->error('No quote found');

                return Command::FAILURE;
            }
            $io->title('===META===');
            $io->writeln($randomQuote->getMeta());
            $io->writeln('');
            $io->title('===CONTENT===');
            $io->writeln($randomQuote->getContent());
            $io->success('SUCCESS !');

            return Command::SUCCESS;
        } else {
            $io->error('Category not found');

            return Command::FAILURE;
        }
    }
}
