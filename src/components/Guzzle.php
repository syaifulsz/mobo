<?php

namespace ssz\mobo\components;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\RequestException;
use app\components\Config;

class Guzzle
{
    private $__request;

    protected $baseUrl;
    protected $headers = [];
    protected $cacheDuration = null;

    // Default OK
    public $responseCode = 200;
    public $response = null;

    public function __construct($baseUrl = '', $headers = [])
    {
        $this->baseUrl = $baseUrl;
        $this->headers = $headers;
    }

    private function restCall($authType, $type, $base_url, $uri, $parameter = null, $headers = [], $json = false)
    {
        if (!$uri) {
            $error_log['message'] = ("Parameter $uri cannot be empty");
            return $error_log;
        }

        $client = new Client(['base_uri' => $base_url]);
        unset($parameter['files']);

        $throwError = false;
        if (array_key_exists('throwError', (array)$parameter)) {
            if ($parameter['throwError']) {
                $throwError = true;
            }

            unset($parameter['throwError']);
        }

        try {
            $requestTimeout = Config::get( 'app.timeout.connection' );

            if (isset($parameter['request_timeout'])) {
                $requestTimeout = $parameter['request_timeout'];
                unset($parameter['request_timeout']);
            }

            $key = $type == 'get' ? 'query' : 'form_params';
            $parameter = is_array($parameter) ? ["$key" => $parameter] : [];

            $headers = (empty($headers)) ? $this->headers : array_merge($this->headers, $headers);

            $parameter['headers'] = $headers;

            $parameter['timeout'] = $requestTimeout;
            $parameter['connect_timeout'] = Config::get( 'app.timeout.connection' );


            if ($json) {
                $parameter['headers']['Content-Type'] = 'application/json';
                if (isset($parameter['form_params'])) {
                    $parameter[\GuzzleHttp\RequestOptions::JSON] = $parameter['form_params'];
                    unset($parameter['form_params']);
                }
            }

            // This is for Debugging errors
            $GLOBALS['_API'] = ['url' => $uri, 'parameter' => $parameter];

            if ($authType === 'billplz') {
                $parameter['auth'] = [Config::get('app.billplz.secret_key'), ''];
            }

            $this->__request = $client->$type($uri, $parameter);
            $this->responseCode = $this->__request->getStatusCode();

            return (string)$this->__request->getBody();
        } catch (ServerException $e) {
            if ($throwError && $e->getResponse()->getStatusCode() == '500') {
                throw new HttpException(503, $e->getResponse()->getReasonPhrase());
            }

            $this->responseCode = $e->getResponse()->getStatusCode();
            $this->response = $e->getResponse();

            return null;
        } catch (ClientException $e) {
            $this->responseCode = $e->getResponse()->getStatusCode();
            $this->response = $e->getResponse();
            if ($throwError) {
                $response = (string)$this->response->getBody();
                if ($this->responseCode == '403') {
                    throw new ServerErrorHttpException('cURL: Forbidden! ' . ($response?:$e->getMessage()));
                } elseif ($this->responseCode != '404') {
                    $response = json_decode($response, true);
                    throw new HttpException($this->responseCode, 'cURL: ' . ($response['message']??$e->getMessage()));
                }
            }

            if ($this->response && !in_array($this->responseCode, [404, 403])) {
                return (string)$this->response->getBody();
            }

            return null;
        } catch (RequestException $e) {
            if ($throwError && $e->getHandlerContext()['errno']==28) {
                throw new HttpException(503, $e->getMessage());
            }

            $this->responseCode = 500;
            $this->response = $e->getResponse();

            return null;
        } catch (\Exception $e) {
            $this->responseCode = 500;
            $this->response = false;

            return null;
        }
    }

    /**
     * Make a Http GET call to base on url + uri
     *
     * @param  string $uri Uri to call base on base url
     * @param  array $parameter Model's attribute
     * @param  bool $json
     * @param  array $headers
     *
     * @return string|null json array('error' => null, 'result' => null) array Error or result
     */
    public function get($authType, $uri, $parameter = null, $json = false, $headers = [])
    {
        if ($authType === 'billplz') {
            $base_url = Config::get('app.billplz.base_url');
        } else {
            //Change accordingly
            $base_url = Config::get('app.billplz.base_url');
        }

        return $this->restCall($authType, 'get', $base_url, $uri, $parameter, $headers, $json);
    }

    /**
     * Make a Http POST call to babase on url + uri
     *
     * @param  string $authType Auth to use base on request
     * @param  string $uri Uri to call base on base url
     * @param  array $parameter Model's attribute
     * @param  bool $json
     * @param    array $headers
     * @internal param array $header
     *
     * @return null|string json array('error' => null, 'result' => null) array Error or result
     */
    public function post($authType, $uri, $parameter = null, $json = false, $headers = [])
    {
        if ($authType === 'billplz') {
            $base_url = Config::get('app.billplz.base_url');
        } else {
            //Change accordingly
            $base_url = Config::get('app.billplz.base_url');
        }

        return $this->restCall($authType, 'post', $base_url, $uri, $parameter, $headers, $json);
    }

    /**
     * Make a Http PUT call to babase on url + uri
     *
     * @param  string $uri Uri to call base on base url
     * @param  array $parameter Model's attribute
     *
     * @return null|string json array('error' => null, 'result' => null) array Error or result
     */
    private function put($uri, $parameter = null, $json = false)
    {
        return $this->restCall('put', $uri, $parameter, [], $json);
    }

    /**
     * Make a Http DELETE call base on base url + uri
     *
     * @param  string $uri Uri to call base on base url
     * @param  array $parameter Model's attribute
     *
     * @return null|string json array('error' => null, 'result' => null) array Error or result
     */
    private function delete($uri, $parameter = null)
    {
        return $this->restCall('delete', $uri, $parameter);
    }

    /**
     * Set Requester Headers
     *
     * @param array $headers
     */
    public function setDefaultHeaders(array $headers)
    {
        $this->headers = $headers;
    }

    /**
     * Get Requester Headers
     *
     * @return array
     */
    public function getDefaultHeaders()
    {
        return $this->headers;
    }

    /**
     * Add Requester Headers
     * This will not overwrite the header, but append the data only
     *
     * @param array $headers
     */
    public function addDefaultHeaders(array $headers)
    {
        $headers = array_merge($this->getDefaultHeaders(), $headers);
        $this->headers = $headers;
    }

}
