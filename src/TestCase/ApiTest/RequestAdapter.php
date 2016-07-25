<?php
/**
 * slince mechanic library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Mechanic\TestCase\ApiTest;

use Slince\Mechanic\Exception\InvalidArgumentException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;
use Slince\Mechanic\Mechanic;

class RequestAdapter
{
    /**
     * @var CookieContainer
     */
    protected $cookieContainer;

    /**
     * cookies
     * @var CookieJar
     */
    protected $cookies;

    /**
     * @var Mechanic
     */
    protected $mechanic;

    function __construct(Mechanic $mechanic)
    {
        $this->mechanic = $mechanic;
        $this->cookieContainer = new CookieContainer();
        $this->cookies = new CookieJar();
    }

    /**
     * 生成request
     * @param Api $api
     * @return Request
     */
    function makeRequest(Api $api)
    {
        //预先替换掉参数里的所有变量，注意如果有变量被声明单没有替换的话会终止
        $method = $this->mechanic->processValue($api->getMethod());
        $url = $this->mechanic->processValue(strval($api->getUrl()));
        return new Request($method, $url);
    }

    /**
     * 获取请求的options
     * @param Api $api
     * @return array
     */
    function getOptions(Api $api)
    {
        return $this->processOptions($this->getRawOptions($api));
    }

    /**
     * 获取请求options
     * @param Api $api
     * @return array
     */
    protected function getRawOptions(Api $api)
    {
        //支持的option
        $options = [
            'timeout' => $api->getTimeout(),
            'headers' => $api->getHeaders(),
            'proxy' => $api->getProxy(),
            'allow_redirects' => $api->getFollowRedirect()
        ];
        if ($auth = $api->getAuth()) {
            $options['auth'] = $auth;
        }
        //需要兼容url中带有query参数的情况
        if ($query = $api->getQuery()) {
            parse_str($api->getUrl()->getQuery(), $urlQuery);
            $options['query'] = array_merge($urlQuery, $query);
        }
        //post参数
        if ($posts = $api->getPosts()) {
            $options['form_params'] = $posts;
        }
        //文件上传
        if ($files = $api->getFiles()) {
            $multipartParams = [];
            if (!empty($options['form_params'])) {
                $multipartParams = $this->convertFormParamsToMultipart($options['form_params']);
            }
            $multipartParams = array_merge($multipartParams, $this->convertFilesToMultipart($files));
            $options['multipart'] = $multipartParams;
            unset($options['form_params']);
        }
        //ssl校验
        if ($verify = $api->getVerify()) {
            $options['verify'] = $verify;
        }
        //如果开启cookie或者有自定义cookie都视为需要cookie支持
        if ($api->getEnableCookie() || $cookies = $api->getCookies()) {
            $options['cookies'] = $this->getCookies($api);
        }
        return $options;
    }

    /**
     * 将自定义的cookie追加进请求
     * @param Api $api
     * @return CookieJar
     */
    protected function getCookies(Api $api)
    {
        if ($cookies = $api->getCookies()) {
            $this->cookieContainer->setCookies($cookies);
        }
        //将cookie自身的容器交还给guzzle
        foreach ($this->cookieContainer->getCookies() as $cookie) {
            $this->cookies->setCookie(new SetCookie([
                'Name' => $cookie->getName(),
                'Value' => $cookie->getValue(),
                'Domain' => $cookie->getDomain() ?: $api->getUrl()->getHost(),
                'Path' => $cookie->getPath(),
                'Expires' => $cookie->getExpires()
            ]));
        }
        return $this->cookies;
    }

    /**
     * 转换form params成multipart格式
     * @param $formParams
     * @return array
     */
    protected function convertFormParamsToMultipart($formParams)
    {
        $posts = [];
        foreach ($formParams as $name => $value) {
            $posts[] = [
                'name' => $name,
                'contents' => $value
            ];
        }
        return $posts;
    }

    /**
     * 转换files到multipart格式
     * @param $files
     * @return array
     * @throws InvalidArgumentException
     */
    protected function convertFilesToMultipart($files)
    {
        $posts = [];
        foreach ($files as $name => $file) {
            if (!$this->mechanic->getFilesystem()->isAbsolutePath($file)) {
                $file = getcwd() . DIRECTORY_SEPARATOR . $file;
            }
            if (!file_exists($file)) {
                throw new InvalidArgumentException(sprintf("File [%s] does not exists", $file));
            }
            $posts[] = [
                'name' => $name,
                'contents' => fopen($file, 'r')
            ];
        }
        return $posts;
    }

    /**
     * 处理options
     * @param $options
     * @return array
     */
    protected function processOptions(array $options)
    {
        $processedOptions = [];
        foreach ($options as $key => $option) {
            $processedOptions[$key] = is_array($option) ?
                $this->processOptions($option)
                : $this->mechanic->processValue($option);
        }
        return $processedOptions;
    }
}