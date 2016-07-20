<?php
/**
 * slince mechanic library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Mechanic\TestCase\ApiTest;

class Cookie
{
    /**
     * cookie名称
     * @var string
     */
    protected $name;

    /**
     * cookie值
     * @var string
     */
    protected $value;

    /**
     * cookie过期时间
     * @var string
     */
    protected $expires;

    /**
     * 有效路径
     * @var string
     */
    protected $path;

    /**
     * 域名
     * @var string
     */
    protected $domain;

    function __construct($name, $value, $expires = null, $path = '/', $domain = null)
    {
        $this->name = $name;
        $this->value = $value;
        $this->expires = $expires;
        $this->path = $path;
        $this->domain = $domain;
    }

    /**
     * 创建cookie
     * @param array $parameters
     * @return static
     */
    static function createFromArray(array $parameters)
    {
        $defaultParameters = [
            'expires' => null,
            'path' => '/',
            'domain' => null
        ];
        $parameters = array_merge($defaultParameters, $parameters);
        return new static($parameters['name'], $parameters['value'],
            $parameters['expires'], $parameters['path'], $parameters['domain']);
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @param mixed $expires
     */
    public function setExpires($expires)
    {
        $this->expires = $expires;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @param mixed $domain
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return mixed
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return mixed
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * cookie是否过期
     * @return bool
     */
    function isValid()
    {
        return $this->expires > time();
    }
}