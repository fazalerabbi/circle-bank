<?php

namespace App\Service;

class BankService
{
    public function __construct(private UserService $userService)
    {
    }

    /**
     * @throws \Exception
     */
    public function deposit($userId, $accountId, $amount): int
    {
        $user = $this->userService->getUser($userId);

        if (!$user) {
            throw new \Exception("User not found");
        }
        $account = $this->getAccountByUser($user, $accountId);

        if (!$account) {
            throw new \Exception("Account not found");
        }

        $account['account_balance'] += $amount;

        return $account['account_balance'];
    }

    /**
     * @throws \Exception
     */
    public function withdraw($userId, $accountNumber, $amount): int
    {
        $user = $this->userService->getUser($userId);
        $account = $this->getAccountByUser($user, $accountNumber);

        if (!$account) {
            throw new \Exception("Account not found");
        }

        if ($account['account_type'] == 'savings') {
            $newBalance = $account['account_balance'] - $amount;
            if ($newBalance < 0) {
                throw new \Exception("You can't withdraw more than your balance");
            }
        } else {
            $newBalance = $account['account_balance'] - $amount;
            if ($newBalance < 0) {
                $newBalance = 0.2 * $newBalance + $newBalance;
            }
        }

        $account['account_balance'] = $newBalance;

        return $account['account_balance'];
    }

    /**
     * @throws \Exception
     */
    public function transfer($userId, $accountNumber, $amount, $toAccountNumber): int
    {
        if ($accountNumber == $toAccountNumber) {
            throw new \Exception("You can't transfer to the same account");
        }
        $user = $this->userService->getUser($userId);
        $account = $this->getAccountByUser($user, $accountNumber);

        $newBalance = $account['account_balance'] - $amount;
        if($newBalance < 0) {
            throw new \Exception("You can't transfer more than your balance");
        }

        $toAccount = $this->userService->getAccountByNumber($toAccountNumber);
        $toAccount['account_balance'] += $amount;
        $account['account_balance'] = $newBalance;

        return $account['account_balance'];
    }

    private function getAccountByUser($user, $accountNumber): array | null
    {
        $account = null;
        foreach ($user['accounts'] as $acc) {
            if ($acc['account_number'] == $accountNumber) {
                return $acc;
            }
        }

        return null;
    }


}