<?php

namespace ssz\mobo\components;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\{
    ClientException,
    ServerException,
    RequestException
};
use ssz\mobo\components\Config;

class API
{
    private $client;
    private $baseUri = '';
    private $timeout = 2;
    private $uri = '';
    private $headers = [];
    private $response = null;
    private $statusCode = 200;
    private $exception = null;

    private $params = [];
    private $paramsQuery = [];
    private $paramsJson = [];
    private $paramsFormParams = [];
    private $paramsMultiparts = [];

    private $oauth = '';

    public function __construct( string $baseUri = '', int $timeout = 2 )
    {
        $this->baseUri = $baseUri;
        $this->timeout = $timeout;

        $this->client = new Client( [
            'base_uri' => $this->baseUri,
            'timeout' => $this->timeout,
        ] );
    }

    /**
     * Add File to Params Multiparts
     * @param string $name
     * @param string $file
     * @param string $filename
     * @param array  $headers
     */
    public function addParamFile( string $name, string $file, string $filename = '', array $headers = [] )
    {
        $data = [
            'name' => $name,
            'contents' => fopen( $file , 'r' ),
            'filename' => $filename,
            'headers' => $headers
        ];
        $this->paramsMultiparts[] = $data;
        return $data;
    }

    /**
     * Set Params Multiparts
     * @param array $params
     */
    public function setParamMultipart( array $params )
    {
        return $this->paramsMultiparts = $params;
    }

    /**
     * Add Params Multiparts
     * @param array $params
     */
    public function addParamMultipart( array $params )
    {
        if ( $params[ 'name' ] && $params[ 'content' ] ) {
            $this->paramsMultiparts[] = $params;
        }

        return $this->paramsMultiparts;
    }

    /**
     * Set Params
     * @param array $params
     */
    public function setParams( array $params )
    {
        return $this->params = $params;
    }

    /**
     * Add Params
     * @param array $params
     */
    public function addParams( array $params )
    {
        return $this->params = array_replace_recursive( $this->params, $params );
    }

    /**
     * Set Param Query
     * @param array $query
     */
    public function setParamQuery( array $query )
    {
        return $this->paramsQuery = $query;
    }

    /**
     * Add Param Query
     * @param array $query
     */
    public function addParamQuery( array $query )
    {
        return $this->paramsQuery = array_replace_recursive( $this->paramsQuery, $query );
    }

    /**
     * Set Param Json
     * @param array $params
     */
    public function setParamsJson( array $params )
    {
        return $this->paramsJson = $params;
    }

    /**
     * Add Param Json
     * @param array $params
     */
    public function addParamsJson( array $params )
    {
        return $this->paramsJson = $this->json = array_merge( $this->paramsJson, $params );
    }

    /**
     * Set Headers
     * @param  array $headers
     * @return array
     */
    public function setHeaders( array $headers = [] ) : array
    {
        return $this->headers = $headers;
    }

    /**
     * Add Headers
     * @param  array $headers
     * @return array
     */
    public function addHeaders( array $headers = [] ) : array
    {
        return $this->headers = array_merge( $this->headers, $headers );
    }

    /**
     * Set OAuth Token to Headers
     * @param string $token
     */
    public function setOAuth( string $token )
    {
        $this->headers[ 'Authorization' ] = 'Bearer ' . $token;
    }

    /**
     * Remove OAuth Token
     */
    public function removeOAuth()
    {
        unset( $this->headers[ 'Authorization' ] );
    }

    /**
     * Initial Request
     *
     * @param  string $type
     * @param  string $uri
     * @return Client
     */
    public function request( string $type = 'GET', string $uri = '') : Client
    {
        $params = $this->params;

        if ( $this->paramsQuery ) {
            $params[ 'query' ] = $this->paramsQuery;
        }

        if ( $this->paramsJson ) {
            $params[ 'json' ] = $this->paramsJson;
        }

        if ( $this->paramsFormParams ) {
            $params[ 'form_params' ] = $this->paramsFormParams;
        }

        if ( $this->paramsMultiparts ) {
            $params[ 'multiparts' ] = $this->paramsMultiparts;
        }

        if ( $this->headers ) {
            $params[ 'headers' ] = $this->headers;
        }

        try {
            $this->response = $this->client->request( strtoupper( $type ), $uri, $params );
        } catch ( ClientException $e ) {
            $this->exception = $e;
        } catch ( ServerException $e ) {
            $this->exception = $e;
        } catch ( RequestException $e ) {
            $this->exception = $e;
        } catch ( \Exception $e ) {
            $this->exception = $e;
        }

        return $this->client;
    }

    /**
     * Manage Request Response
     * @param  boolean $decodeJson
     * @return array
     */
    public function response( $decodeJson = false ) : array
    {
        if ( $this->exception ) {
            return [
                'code' => $this->exception->getCode() ?: 400,
                'response' => $this->exception->getMessage()
            ];
        }

        return [
            'code' => $this->statusCode = $this->response->getStatusCode(),
            'response' => ( $decodeJson ? json_decode( $this->response->getBody()->getContents(), true ) : $this->response->getBody()->getContents() )
        ];
    }
}
