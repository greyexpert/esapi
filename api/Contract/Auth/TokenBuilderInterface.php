<?php
/**
 * Created by PhpStorm.
 * User: skambalin
 * Date: 30.10.17
 * Time: 16.20
 */

namespace Everywhere\Api\Contract\Auth;


interface TokenBuilderInterface
{
    /**
     * @param $identity
     * @param $payload
     *
     * @return string
     */
    public function build($identity, $payload = null);
}