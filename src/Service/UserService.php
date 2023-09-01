<?php

namespace App\Service;

class UserService
{
    private static $users = [
        [
            'user_id' => 1,
            'first_name' => 'John',
            'last_name' => 'Smith',
            'gender' => 'M',
            'accounts' => [
                [
                    'account_id' => 344,
                    'account_type' => 'saving',
                    'account_balance' => 10,
                    'account_number' => 64633441
                ],
            ],
        ],
        [
            'user_id' => 2,
            'first_name' => 'Sophie',
            'last_name' => 'Adams',
            'gender' => 'F',
            'accounts' => [
                [
                    'account_id' => 985,
                    'account_type' => 'saving',
                    'account_balance' => 100.00,
                    'account_number' => 24145522
                ],
            ],
        ],
        [
            'user_id' => 3,
            'first_name' => 'Lucy',
            'last_name' => 'Wright',
            'gender' => 'F',
            'accounts' => [
                [
                    'account_id' => 213,
                    'account_type' => 'normal',
                    'account_balance' => 6875.31,
                    'account_number' => 53298426
                ],
            ],
        ],
        [
            'user_id' => 4,
            'first_name' => 'Christian',
            'last_name' => 'Anderson',
            'gender' => 'M',
            'accounts' => [
                [
                    'account_id' => 788,
                    'account_type' => 'saving',
                    'account_balance' => 10000.00,
                    'account_number' => 86883314
                ],
            ],
        ]
    ];

    private $userFields = [
        'user_id',
        'first_name',
        'last_name',
        'gender'
    ];

    private $accountFields = [
        'account_id',
        'account_type',
        'account_balance',
        'account_number'
    ];

    /**
     * @throws \Exception
     */
    public function getUserInfo(int $userId, string $key): string | int | null
    {
        $user = $this->getUser($userId);
        if (!$user) {
            throw new \Exception(sprintf('User not found with user Id = %s', $userId));
        }

        if (
            !in_array($key, $this->userFields) &&
            !in_array($key, $this->accountFields)) {
            throw new \Exception(sprintf('%s is an invalid key', $key));
        }

        if (in_array($key, $this->userFields)) {
            return $user[$key];
        }

        $accounts = [];
        foreach ($user['accounts'] as $account) {
            $accounts[] = $account[$key];
        }

        return implode(",", $accounts);
    }

    /**
     * @throws \Exception
     */
    public function sortUsersData(string $key, $isAsc = true): array
    {
        $users = self::$users;
        if (!in_array($key, $this->userFields) && !in_array($key, $this->accountFields) ) {
            throw new \Exception(sprintf('%s is an invalid key', $key));
        }

        if ($key == 'account_type') {
            usort($users, function ($a, $b) use($isAsc){
                $aAccountTypes = array_column($a['accounts'], 'account_type');
                $bAccountTypes = array_column($b['accounts'], 'account_type');

                sort($aAccountTypes);
                sort($bAccountTypes);

                if ($isAsc) {
                    return strcmp(implode(',', $aAccountTypes), implode(',', $bAccountTypes));
                }

                return strcmp(implode(',', $bAccountTypes), implode(',', $aAccountTypes));
            });
        } else {
            usort($users, function ($a, $b) use ($key, $isAsc) {
                if (in_array($key, $this->userFields)) {
                    if ($a[$key] === $b[$key]) {
                        return 0;
                    }
                    if ($isAsc) {
                        return ($a[$key] < $b[$key]) ? -1 : 1;
                    }
                    return ($a[$key] < $b[$key]) ? 1 : -1;

                }
                else if (in_array($key, $this->accountFields)) {

                    $aAccountKey = 0;
                    $bAccountKey = 0;
                    foreach ($a['accounts'] as $account) {
                        $aAccountKey = $aAccountKey + $account[$key];
                    }

                    foreach ($b['accounts'] as $account) {
                        $bAccountKey = $bAccountKey + $account[$key];
                    }

                    if ($isAsc) {
                        return $bAccountKey < $aAccountKey ? 1 : -1;
                    }
                    return $bAccountKey < $aAccountKey ? -1 : 1;
                }
            });
        }

        return $users;
    }

    public function updateUser(int $userId, string $key, string $value): bool
    {
        $user = $this->getUser($userId);
        if (!$user) {
            throw new \Exception(sprintf('User not found with user Id = %s', $userId));
        }

        if (!in_array($key, $this->userFields)) {
            throw new \Exception(sprintf('%s is an invalid key', $key));
        }

        $user[$key] = $value;

        return true;
    }

    public function getUser(int $userId): ?array
    {
        foreach (self::$users as $user) {
            if ($user['user_id'] === $userId) {
                return $user;
            }
        }
        return null;
    }


    public function getAccountByNumber(int $accountNumber): ?array
    {
        foreach (self::$users as $user) {
            foreach ($user['accounts'] as $account) {
                if ($account['account_number'] === $accountNumber) {
                    return $account;
                }
            }
        }
        return null;
    }
}