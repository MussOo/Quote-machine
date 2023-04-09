<?php

namespace App\Tests;

use App\Factory\CategoryFactory;
use App\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoryTest extends WebTestCase
{
    public function testNew(): void
    {
        $client = static::createClient();
        $testUser = UserFactory::createOne([ 'roles' => ['ROLE_ADMIN'] ])->object();
        $client->loginUser($testUser);

        $category = CategoryFactory::createOne()->object();
        $client->request('GET', 'category/new');

        $client->submitForm('Save', [
            'category[name]' => "TESTTEST",
        ]);

        $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('app_category_index');

        $this->assertSelectorTextContains('body', 'TESTTEST');
    }

    public function testShow() : void
    {
        $client = static::createClient();
        $testUser = UserFactory::createOne([ 'roles' => ['ROLE_ADMIN'] ])->object();
        $client->loginUser($testUser);

        $category = CategoryFactory::createOne()->object();
        $client->request('GET', '/category/'.$category->getId());

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('body', $category->getName());
    }
    public function testDelete() : void
    {
        $client = static::createClient();
        $testUser = UserFactory::createOne([ 'roles' => ['ROLE_ADMIN'] ])->object();
        $client->loginUser($testUser);

        $category = CategoryFactory::createOne()->object();
        $client->request('GET', '/category/'.$category->getId());

        $client->submitForm('Delete');

        $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('app_category_index');
        $this->assertSelectorTextContains('body', 'Aucune categorie trouvé');

    }
    public function testEdit(): void
    {
        $client = static::createClient();
        $testUser = UserFactory::createOne([ 'roles' => ['ROLE_ADMIN'] ])->object();
        $client->loginUser($testUser);

        $category = CategoryFactory::createOne()->object();
        $client->request('GET', 'category/' . $category->getId() . '/edit');

        $client->submitForm('Update', [
            'category[name]' => "TESTTEST",
        ]);

        $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('app_category_index');

        $this->assertSelectorTextContains('body', 'TESTTEST');
    }
}
