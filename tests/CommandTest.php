<?php

namespace App\Tests;

use App\Factory\CategoryFactory;
use App\Factory\QuoteFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class CommandTest extends WebTestCase
{  
    public function testRandomQuoteWithCategory(): void
    {

        $category =CategoryFactory::new()->create([
            'name' => 'test'
        ]);

        QuoteFactory::new()->create([
            'content' => 'citation test',
            'category' => $category
        ]);
        $kernel = static::createKernel();
        $application = new Application($kernel);
        $command = $application->find('app:random-quote');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            '--category' => 'test'
        ]);
        
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('citation test', $output);
    }

    public function testRandomQuoteWithoutCategory(): void
    {
        $category =CategoryFactory::new()->create([
            'name' => 'test'
        ]);

        QuoteFactory::new()->create([
            'content' => 'citation test',
            'category' => $category
        ]);
        $kernel = static::createKernel();
        $application = new Application($kernel);
        $command = $application->find('app:random-quote');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);
        
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('citation test', $output);
    }

    public function testRandomQuoteWithCategoryNotFound(): void
    {
        $category =CategoryFactory::new()->create([
            'name' => 'test'
        ]);

        QuoteFactory::new()->create([
            'content' => 'citation test',
            'category' => $category
        ]);
        $kernel = static::createKernel();
        $application = new Application($kernel);
        $command = $application->find('app:random-quote');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            '--category' => 'test2'
        ]);
        
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Category not found', $output);
    }

    public function testRandomQuoteNotFound(): void
    {
        $category =CategoryFactory::new()->create([
            'name' => 'test'
        ]);
        $kernel = static::createKernel();
        $application = new Application($kernel);
        $command = $application->find('app:random-quote');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            '--category' => 'test'
        ]);
        
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('No quote found', $output);

    }


}
