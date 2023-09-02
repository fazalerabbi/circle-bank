<?php

namespace App\Repository;

use App\Entity\Account;
use App\Entity\User;
use App\Trait\Helper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use PhpParser\Node\Scalar\DNumber;

use function Symfony\Component\DependencyInjection\Loader\Configurator\inline_service;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    use Helper;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @throws NonUniqueResultException
     * @throws \Exception
     */
    public function getUserKey(int $userId, string $key): string | int
    {
        $user = $this->findOneBy(['id' => $userId]);

        if (!$user) {
            throw new \Exception(sprintf("User with id = %s not found", $userId));
        }

        if (!property_exists($user, $key) && !property_exists($user->getAccounts()[0], $key)) {
            throw new \Exception(sprintf("Key = %s not found", $key));
        }
        $method = $this->snakeToUcWord($key);
        if (property_exists($user, $key)) {
            return $user->{'get'.$method}();
        }

        $accounts = [];
        foreach ($user->getAccounts() as $account) {
            $accounts[] = $account->{'get'.$method}();
        }

        return implode(",", $accounts);
    }

    /**
     * @throws \Exception
     */
    public function updateUser(int $userId, string $key, string $value): bool
    {
        $user = $this->findOneBy(['id' => $userId]);
        if (!$user) {
            throw new \Exception(sprintf("User with id = %s not found", $userId));
        }
        $user->{'set'.$this->snakeToUcWord($key)}($value);
        $this->_em->persist($user);
        $this->_em->flush();
        return true;
    }

    // sort users by key and order (asc or desc) through query builder

    /**
     * @throws \Exception
     */
    public function getUser(string $key, bool $isAsc): array
    {
        $qb = $this->createQueryBuilder('u');
        $qb->select('u');
        $qb->innerJoin('u.accounts', 'a');

        if (property_exists(User::class, $key)) {
            $qb->orderBy('u.' . $key, $isAsc ? 'ASC' : 'DESC');
        }
        else if (property_exists(Account::class, $key)) {
            $qb->orderBy('a.' . $key, $isAsc ? 'ASC' : 'DESC');
        }
        else {
            throw new \Exception(sprintf("Key = %s not found", $key));
        }
        $query = $qb->getQuery();
        return $query->getResult();
    }
}
