<?php

namespace App\DataFixtures;

use App\Entity\Account;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user1 = new User();
        $user1->setFirstName('John');
        $user1->setLastName('Smith');
        $user1->setGender('M');
        $manager->persist($user1);
        $newAccount1 = new Account();
        $newAccount1->setAccountNumber(64633441);
        $newAccount1->setAccountType('normal');
        $newAccount1->setAccountBalance(12401.22);
        $newAccount1->setAccountHolder($user1);
        $manager->persist($newAccount1);

        $user2 = new User();
        $user2->setFirstName('Sophie');
        $user2->setLastName('Adams');
        $user2->setGender('F');
        $manager->persist($user2);
        $newAccount2 = new Account();
        $newAccount2->setAccountNumber(24145522);
        $newAccount2->setAccountType('saving');
        $newAccount2->setAccountBalance(100.00);
        $newAccount2->setAccountHolder($user2);
        $manager->persist($newAccount2);

        $user3 = new User();
        $user3->setFirstName('Lucy');
        $user3->setLastName('Wright');
        $user3->setGender('F');
        $manager->persist($user3);
        $newAccount3 = new Account();
        $newAccount3->setAccountNumber(53298426);
        $newAccount3->setAccountType('normal');
        $newAccount3->setAccountBalance(6875.31);
        $newAccount3->setAccountHolder($user3);
        $manager->persist($newAccount3);

        $user4 = new User();
        $user4->setFirstName('Christian');
        $user4->setLastName('Anderson');
        $user4->setGender('M');
        $manager->persist($user4);
        $newAccount4 = new Account();
        $newAccount4->setAccountNumber(86883314);
        $newAccount4->setAccountType('saving');
        $newAccount4->setAccountBalance(10000.00);
        $newAccount4->setAccountHolder($user4);
        $manager->persist($newAccount4);

        $manager->flush();
    }
}
