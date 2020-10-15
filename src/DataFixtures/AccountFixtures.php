<?php

namespace App\DataFixtures;

use App\Entity\Account;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AccountFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $account = new Account();
        $account->setBallance(0);
        $account->setUid('account-1');
        $manager->persist($account);

        $manager->flush();
    }
}
