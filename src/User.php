<?php

namespace xdrew\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

/**
 * @see     https://vk.com/dev/fields
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
    protected function getField($key)
    {
        return !empty($this->response[$key]) ? $this->response[$key] : null;
    }

    /**
     * @return string|null DD.MM.YYYY
     */
    public function getBirthday()
    {
        return $this->getField('birthday');
    }
    /**
     * @return array [id =>, title => string]
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
     * @return int 1|2 => woman|man
     */
    public function getGender()
    {
        return $this->getField('gender');
    }
}
