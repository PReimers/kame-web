<?php
/**
 * Created by PhpStorm.
 * User: Patrick
 * Date: 10.09.2016
 * Time: 19:50.
 */
namespace TerraGana\AppBundle\Helper;

use DateTime;
use TerraGana\AppBundle\Document\User;

class UserHelper
{
    public function convertJson($json)
    {
        $return_json = [];
        $jsonHelper = new JsonHelper($json);
        $return_json['id'] = $jsonHelper->validateValue('id');
        $return_json['googleId'] = $jsonHelper->validateValue('googleId');
        $return_json['username'] = $jsonHelper->validateValue('username');
        $return_json['email'] = $jsonHelper->validateValue('email');

        return json_decode(json_encode($return_json));
    }

    /**
     * update user attributes.
     *
     * @param User $user
     * @param Object $json
     *
     * @return User
     */
    public function updateUser(User $user, Object $json)
    {
        (isset($json->googleId) ? $user->setGoogleId($json->googleId) : $user->setGoogleId($user->getGoogleId()));
        (isset($json->username) ? $user->setUsername($json->username) : $user->setUsername($user->getUsername()));
        (isset($json->email) ? $user->setEmail($json->email) : $user->setEmail($user->getEmail()));
        $user->setUpdatedAt(new DateTime('now'));

        return $user;
    }
}
