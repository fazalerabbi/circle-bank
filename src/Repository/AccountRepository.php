<?php

namespace App\Repository;

use App\Entity\Account;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Account>
 *
 * @method Account|null find($id, $lockMode = null, $lockVersion = null)
 * @method Account|null findOneBy(array $criteria, array $orderBy = null)
 * @method Account[]    findAll()
 * @method Account[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountRepository extends ServiceEntityRepository
{
    private const ACCOUNT_TYPE = ['normal', 'saving'];
    private const INTEREST_RATE = 0.2;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Account::class);
    }

    /**
     * @throws \Exception
     */
    public function deposit(int $accountNumber, float $amount): float
    {
        $account = $this->findOneBy(['account_number' => $accountNumber]);
        if (!$account) {
            throw new \Exception("Account not found");
        }
        $account->setAccountBalance($account->getAccountBalance() + $amount);
        $this->_em->persist($account);
        $this->_em->flush();
        return $account->getAccountBalance();
    }

    /**
     * @throws \Exception
     */
    public function transfer(int $fromAccountNumber, int $toAccountNumber, float $amount): float
    {
        if ($fromAccountNumber == $toAccountNumber) {
            throw new \Exception("You can't transfer to the same account");
        }
        $fromAccount = $this->findOneBy(['account_number' => $fromAccountNumber]);
        $toAccount = $this->findOneBy(['account_number' => $toAccountNumber]);

        $newBalance = $fromAccount->getAccountBalance() - $amount;
        if($newBalance < 0) {
            throw new \Exception("You can't transfer more than your balance");
        }

        $fromAccount->setAccountBalance($newBalance);
        $toAccount->setAccountBalance($toAccount->getAccountBalance() + $amount);

        $this->_em->persist($fromAccount);
        $this->_em->persist($toAccount);
        $this->_em->flush();
        return $fromAccount->getAccountBalance();
    }

    /**
     * @throws \Exception
     */
    public function withDraw(int $accountNumber, float $amount): float
    {
        $account = $this->findOneBy(['account_number' => $accountNumber]);
        if (!$account) {
            throw new \Exception("Account not found");
        }

        if ($account->getAccountType() == 'saving') {
            $newBalance = $account->getAccountBalance() - $amount;
            if ($newBalance < 0) {
                throw new \Exception("You can't withdraw more than your balance");
            }
        } else {
            $newBalance = $account->getAccountBalance() - $amount;
            if ($newBalance < 0) {
                $newBalance = self::INTEREST_RATE * $newBalance + $newBalance;
            }

            $account->setAccountBalance($newBalance);
            $this->_em->persist($account);
            $this->_em->flush();
        }

        return $account->getAccountBalance();
    }


    /**
     * @throws \Exception
     */
    public function switch(int $accountNumber, string $switchType): string
    {
        if (!in_array($switchType, self::ACCOUNT_TYPE)) {
            throw new \Exception("Invalid account type");
        }
        $account = $this->findOneBy(['account_number' => $accountNumber]);

        if (!$account) {
            throw new \Exception("Account not found");
        }

        if ($account->getAccountType() == 'saving' && $switchType == 'normal') {
            $account->setAccountType('normal');
        } else if ($account->getAccountType() == 'normal' && $switchType == 'saving') {
            if ($account->getAccountBalance() < 0) {
                throw new \Exception("You can't switch to saving account with negative balance");
            }
            $account->setAccountType('saving');
        }

        $this->_em->persist($account);
        $this->_em->flush();

        return $account->getAccountType();
    }
}
