<?php
/**
 * slince mechanic library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Mechanic\TestCase\ApiTest;

class Api
{
    /**
     * @var Url
     */
    protected $url;

    /**
     * 请求方法
     * @var string
     */
    protected $method = 'GET';

    /**
     * query参数
     * @var array
     */
    protected $query = [];

    /**
     * post参数
     * @var array
     */
    protected $posts = [];

    /**
     * 待上传的文件
     * @var array
     */
    protected $files = [];

    /**
     * auth验证
     * @var array
     */
    protected $auth = [];

    /**
     * 超时时间
     * @var float
     */
    protected $timeout = 0;

    /**
     * 是否跟随转向
     * @var bool
     */
    protected $followRedirect = false;
    /**
     * 自定义header
     * @var array
     */
    protected $headers = [];

    /**
     * 自定义cookie
     * @var array
     */
    protected $cookies = [];

    /**
     * 是否开启cookie,默认开启
     * @var bool
     */
    protected $enableCookie = true;

    /**
     * 代理
     * @var mixed
     */
    protected $proxy = null;
    /**
     * 自定义证书校验
     * @var string
     */
    protected $verify = null;

    function __construct(
        $url,
        $method = 'GET',
        array $query = [],
        array $posts = [],
        array $files = [],
        array $auth = [],
        $timeout = 0,
        $followRedirect = false,
        array $headers = [],
        array $cookies = [],
        $enableCookie = false,
        $proxy = null,
        $verify = null
    ) {
        $this->url = Url::createFromUrl($url);
        $this->method = $method;
        $this->query = $query;
        $this->posts = $posts;
        $this->files = $files;
        $this->auth = $auth;
        $this->timeout = $timeout;
        $this->followRedirect = $followRedirect;
        $this->headers = $headers;
        $this->cookies = $cookies;
        $this->enableCookie = $enableCookie;
        $this->proxy = $proxy;
        $this->verify = $verify;
    }

    /**
     * 设置url
     * @param Url $url
     * @return $this
     */
    public function setUrl(Url $url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return Url
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * 设置请求方式
     * @param $method
     * @return $this
     */
    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * 获取请求方式
     * @return string
     */
    function getMethod()
    {
        return $this->method;
    }

    /**
     * 设置query
     * @param $query
     * @return $this
     */
    public function setQuery($query)
    {
        $this->query = $query;
        return $this;
    }

    /**
     * 获取query参数
     * @return array
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * 设置post参数
     * @param array $posts
     * @return $this
     */
    public function setPosts($posts)
    {
        $this->posts = $posts;
        return $this;
    }

    /**
     * 获取post参数
     * @return array
     */
    public function getPosts()
    {
        return $this->posts;
        return $this;
    }

    /**
     * 设置上传文件
     * @param array $files
     * @return $this
     */
    public function setFiles($files)
    {
        $this->files = $files;
        return $this;
    }

    /**
     * 获取上传文件
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * 设置auth验证
     * @param $auth
     * @return $this
     */
    public function setAuth($auth)
    {
        $this->auth = $auth;
        return $this;
    }

    /**
     * 获取auth验证
     * @return mixed
     * @return $this
     */
    public function getAuth()
    {
        return $this->auth;
    }

    /**
     * 设置自定义header
     * @param $headers
     * @return $this
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * 获取header
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * 设置cookies
     * @param $cookies
     * @return $this
     */
    public function setCookies($cookies)
    {
        $this->cookies = $cookies;
        return $this;
    }

    /**
     * 获取cookie
     * @return array
     */
    public function getCookies()
    {
        return $this->cookies;
    }

    /**
     * 设置启用cookie
     * @param $enableCookie
     * @return $this
     */
    public function setEnableCookie($enableCookie)
    {
        $this->enableCookie = $enableCookie;
        return $this;
    }

    /**
     * 获取cookie是否启用
     * @return bool
     */
    function getEnableCookie()
    {
        return $this->enableCookie;
    }

    /**
     * 设置超时上限
     * @param $timeout
     * @return $this
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
        return $this;
    }

    /**
     * 获取超时上限
     * @return float|int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * 设置代理
     * @param $proxy
     * @return $this
     */
    public function setProxy($proxy)
    {
        $this->proxy = $proxy;
        return $this;
    }

    /**
     * 获取代理
     * @return mixed|null
     */
    public function getProxy()
    {
        return $this->proxy;
    }

    /**
     * 设置证书地址
     * @param $verify
     * @return $this
     */
    public function setVerify($verify)
    {
        $this->verify = $verify;
        return $this;
    }

    /**
     * 获取证书验证
     * @return null|string
     */
    public function getVerify()
    {
        return $this->verify;
    }

    /**
     * 设置是否跟随30x跳转
     * @param $followRedirect
     * @return $this
     */
    public function setFollowRedirect($followRedirect)
    {
        $this->followRedirect = $followRedirect;
        return $this;
    }

    /**
     * 获取是否跟随
     * @return bool
     */
    public function getFollowRedirect()
    {
        return $this->followRedirect;
    }
}