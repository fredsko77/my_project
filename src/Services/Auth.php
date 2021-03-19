<?php
namespace App\Services;

use App\Entity\Users;
use App\Models\UsersModel;
use App\Services\Session;

class Auth
{

    public function __construct()
    {
    }

    /**
     * @param array $data
     *
     * @return void
     */
    public static function checkCredentials(array $data)
    {
        $model = new UsersModel;

        $user = $model->getUserEmail($data['email'], Users::class);

        if ($user instanceof Users) {

            if (password_verify($data['password'], $user->getPassword())) {
                static::setSession($data);

                return (object) ['response' => 'OK', 'user' => $user];
            }

        }

        return ['connexion' => 'Vos identifiants sont incorrects!'];

    }

    /**
     * @return void
     */
    public static function getAuth()
    {
        $model = new UsersModel;
        $session = new Session;

        return $session->get('auth') !== null ? $model->findBy(['token' => $session->get('auth')['token']], true) : null;
    }

    /**
     * @param Users $user
     *
     * @return void
     */
    public static function setSession(array $user)
    {
        $session = new Session;

        return $session->set('auth', [
            'token' => $user['token'],
            'email' => $user['email'],
        ]);
    }

    /**
     * @return void
     */
    public static function logout()
    {
        return (new Session)->unset('auth');
    }

}
