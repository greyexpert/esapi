<?php
/**
 * Created by PhpStorm.
 * User: skambalin
 * Date: 28.10.17
 * Time: 17.12
 */

namespace Everywhere\Api\Auth;


use Everywhere\Api\Contract\Auth\AuthenticationStorageInterface;
use Zend\Authentication\Storage\NonPersistent;

class AuthenticationStorage extends NonPersistent implements AuthenticationStorageInterface
{

}