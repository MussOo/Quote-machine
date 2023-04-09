<?php

namespace App\Tests;

use App\Factory\CategoryFactory;
use App\Factory\QuoteFactory;
use App\Factory\UserFactory;
use App\Repository\QuoteRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CitationTest extends WebTestCase
{
    public function testNew(): void
    {
        $client = static::createClient();

        $testUser = UserFactory::createOne()->object();

        $client->loginUser($testUser);

        $client->request('GET', '/quote/new');

        $client->submitForm('Enregistrer', [
            'edit_quote[Content]' => 'TestTest',
            'edit_quote[Meta]' => 'TestTest',
        ]);

        $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('quote_index');
        $this->assertSelectorTextContains('p', 'TestTest');

    }

    public function testEdit(): void
    {
        $client = static::createClient();
        $user = UserFactory::createOne()->object();
        $client->loginUser($user);

        $quote = QuoteFactory::createOne([
            'content' => 'TestTest',
            'meta' => 'TestTest',
            'user' => $user
        ])->object();
        $client->request('GET', 'quote/' . $quote->getId() . '/edit');

        $client->submitForm('Enregistrer', [
            'edit_quote[Content]' => 'TestTest',
            'edit_quote[Meta]' => 'TestTest',
        ]);

        $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('quote_index');
        $this->assertSelectorTextContains('p', 'TestTest');

    }

    public function testDelete() : void
    {
        $client = static::createClient();
        $user = UserFactory::createOne()->object();
        $client->loginUser($user);

        QuoteFactory::createOne([
            'content' => 'TestTest',
            'meta' => 'TestTest',
            'user' => $user
        ])->object();
        $client->request('GET', '/quote');

        $client->clickLink('Supprimer');



        $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('quote_index');
        $this->assertSelectorTextContains('body', 'Aucun résultat trouvé');

    }

}
