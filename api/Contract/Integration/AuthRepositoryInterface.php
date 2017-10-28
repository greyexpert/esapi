<?php
/**
 * Created by PhpStorm.
 * User: skambalin
 * Date: 28.10.17
 * Time: 16.41
 */

namespace Everywhere\Api\Contract\Integration;


interface AuthRepositoryInterface
{
    /**
     * @param string $login
     * @param string $password
     *
     * @return mixed
     */
    public function authenticate($login, $password);
}