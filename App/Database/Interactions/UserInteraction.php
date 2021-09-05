<?php

namespace App\Database\Interactions;


use App\Database\Managers\UserManager;
use App\Services\Session\SessionInterface;

class UserInteraction
{
    const prefix = "__user";

    /**
     * @var SessionInterface $session
     */
    private $session;

    /**
     * @var UserManager $manager
     */
    private $manager;

    public function __construct(SessionInterface $session, UserManager $manager)
    {
        $this->session = $session;
        $this->manager = $manager;
    }

    private function isUserValid(String $reference, String $password): bool
    {
        $user = $this->manager->fetch_user($reference);

        return $user !== false && password_verify($password, $user->password);
    }


    public static function checkFormFull(array $expected, array $parsedBody): bool
    {
        $flag = true;

        foreach ($expected as $item) {
            if (!array_key_exists($item, $parsedBody)) $flag = false;
            if (empty($parsedBody[$item])) $flag = false;

            if (!$flag) break;
        }

        return $flag;
    }

    public static function checkFormHasFields(array $expected, array $parsedBody): bool
    {
        $flag = true;

        foreach ($expected as $item) {
            if (!array_key_exists($item, $parsedBody)) $flag = false;
            if (!$flag) break;
        }

        return $flag;
    }

    public function checkUserValid(array $parsedBody): bool
    {
        return $this->isUserValid($parsedBody['username'], $parsedBody['password']);
    }



    public function loginUser(array $parsedBody): bool
    {
        if (!$this->isUserValid($parsedBody['username'], $parsedBody['password'])) return false;

        $user = $this->manager->fetch_user($parsedBody['username']);

        $this->session->set(UserInteraction::prefix, [
            'username' => $user->username
        ]);

        return true;
    }

    public function logoutUser()
    {
        $this->session->delete(UserInteraction::prefix);
    }

    public static function is_user_connected(SessionInterface $session): bool
    {
        return !is_null($session->get(self::prefix));
    }
}