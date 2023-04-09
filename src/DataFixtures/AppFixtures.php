<?php

namespace App\DataFixtures;

use App\Factory\CategoryFactory;
use App\Factory\QuoteFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct()
    {
    }

    public function load(ObjectManager $manager): void
    {
        UserFactory::createOne([
            'email' => 'admin@localhost',
            'roles' => ['ROLE_ADMIN'],
            'name' => 'julien Mariaud',
            'experience' => 0,
        ]);

        $user1 = UserFactory::createOne([
            'email' => 'root@localhost',
            'roles' => ['ROLE_USER'],
            'name' => 'Nico Tranchant',
            'experience' => 0,
        ]);
        $cat2 = CategoryFactory::createOne([
            'name' => 'Category 1',
        ]);
        $cat1 = CategoryFactory::createOne([
            'name' => 'Category 2',
        ]);
        $q1 = QuoteFactory::createOne([
            'content' => 'Qu\'est-ce que vous voulez-vous insinuyer Sire ?',
            'meta' => 'Roparzh, Kaamelott, Livre III, 74 : Saponides et detergents',
            'category' => $cat1,
            'user' => $user1,
            'date_creation' => new \DateTime('2021-01-01'),
        ]);

        $q2 = QuoteFactory::createOne([
            'content' => 'Sire, Sire ! On en a gros !',
            'meta' => 'Perceval, Kaamelott, Livre II, Les Exploités',
            'category' => $cat1,
            'user' => $user1,
            'date_creation' => new \DateTime('2021-01-01'),
        ]);

        $q3 = QuoteFactory::createOne([
            'content' => 'Mais évidemment c\'est sans alcool !',
            'meta' => 'Merlin, Kaamelott, Livre II, 4 : Le rassemblement du corbeau',
            'category' => $cat2,
            'user' => $user1,
            'date_creation' => new \DateTime('2021-01-01'),
        ]);

        $q4 = QuoteFactory::createOne([
            'content' => 'Quand on veut être sûr de son coup, Seigneur Dagonet… on plante des navets. On ne pratique pas le putsch.',
            'meta' => 'Loth, Kaamelott, Livre V, Les Repentants',
            'category' => $cat2,
            'user' => $user1,
            'date_creation' => new \DateTime('2021-01-01'),
        ]);

        $q5 = QuoteFactory::createOne([
            'content' => 'Vous savez c\'que c\'est, mon problème ? Trop gentil.',
            'meta' => 'Léodagan, Kaamelott, Livre II, Le complot',
            'category' => $cat2,
            'user' => $user1,
            'date_creation' => new \DateTime('2021-01-01'),
        ]);

        $manager->flush();
    }
}
