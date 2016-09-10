<?php
/**
 * Created by PhpStorm.
 * User: Patrick
 * Date: 10.09.2016
 * Time: 19:50.
 */
namespace TerraGana\AppBundle\Helper;

class JsonHelper
{
    private $json;

    /**
     * JsonHelper constructor.
     *
     * @param string $json
     */
    public function __construct($json = '{}')
    {
        $this->json = json_decode($json);
    }

    /**
     * Get JSON.
     *
     * @return mixed
     */
    public function getJson()
    {
        return $this->json;
    }

    /**
     * Set JSON.
     *
     * @param $json
     * @param bool $encoded
     */
    public function setJson($json, $encoded = true)
    {
        $this->json = $encoded ? json_decode($json) : $json;
    }

    /**
     * Helper function to validate the Value.
     *
     * @param $value
     * @param $default
     *
     * @return mixed
     */
    public function validateValue($value, $default = null)
    {
        return isset($this->json->{$value}) ? $this->json->{$value} : $default;
    }
}
