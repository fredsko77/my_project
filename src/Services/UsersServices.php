<?php
namespace App\Services;

use App\Services\Validator;

/**
 * [class UsersServices]
 */
class UsersServices
{

    public function __construct()
    {
    }

    /**
     * @param array $user
     * @return mixed
     */
    public static function validate(array $user)
    {
        $errors = [];
        if (!Validator::password($user['password'])) {
            $errors['password'] = 'Le mot de passe doit contenir au moins une masjuscule, une minuscule et un chiffre avec au moins 8 caractères ! ';
        }
        if (!Validator::pseudo($user['username'])) {
            $errors['username'] = 'Votre nom d\'utilisateur doit contenir au moins 6 caractères !';
        }
        if (!Validator::email($user['email'])) {
            $errors['email'] = 'Cette adresse email n\'est pas valide!';
        }

        return count($errors) > 0 ? $errors : true;
    }

}
