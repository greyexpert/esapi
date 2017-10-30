<?php
/**
 * Created by PhpStorm.
 * User: skambalin
 * Date: 28.10.17
 * Time: 16.26
 */

namespace Everywhere\Api\Contract\Auth;


use Zend\Authentication\AuthenticationServiceInterface as ZendAuthenticationServiceInterface;

interface AuthenticationServiceInterface
    extends ZendAuthenticationServiceInterface, AuthenticationAdatpterAwareInterface
{

}