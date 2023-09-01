<?php

namespace App\Service;

class UserService
{
    private $users = [
        [
            'user_id' => 1,
            'first_name' => 'John',
            'last_name' => 'Smith',
            'gender' => 'M',
            'accounts' => [
                [
                    'account_id' => 344,
                    'account_type' => 'normal',
                    'account_balance' => 12401.22,
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
        'gender',
        'accounts'
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

    private function getUser(int $userId): ?array
    {
        foreach ($this->users as $user) {
            if ($user['user_id'] === $userId) {
                return $user;
            }
        }
        return null;
    }
}