<?php

namespace xdrew\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

/**
 * @see     https://apiok.ru/wiki/display/api/fields+ru
 *
 * @package xdrew\OAuth2\Client\Provider
 */
class User implements ResourceOwnerInterface
{
    /**
     * @type array
     */
    protected $response;

    /**
     * User constructor.
     *
     * @param array $response
     */
    public function __construct(array $response)
    {
        $this->response = $response;
    }
    /**
     * @return array
     */
    public function toArray()
    {
        return $this->response;
    }
    /**
     * @return integer
     */
    public function getId()
    {
        return (int)($this->getField('uid') ?: $this->getField('id'));
    }

    /**
     * Helper for getting user data
     *
     * @param string $key
     *
     * @return mixed|null
     */
    public function getField($key)
    {
        return !empty($this->response[$key]) ? $this->response[$key] : null;
    }

    /**
     * @return string|null
     */
    public function getBirthday()
    {
        return $this->getField('birthday');
    }

    /**
     * @return string|null
     */
    public function getEmail()
    {
        return $this->getField('email');
    }

    /**
     * @return array
     */
    public function getLocation()
    {
        return $this->getField('location');
    }
    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->getField('first_name');
    }
    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->getField('last_name');
    }
    /**
     * @return int
     */
    public function getGender()
    {
        return $this->getField('gender');
    }
}
