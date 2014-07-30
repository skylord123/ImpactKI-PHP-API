<?php

class ImpactAPI
{
    private $apiPath;

    private $apiUrl;

    private $apiKey;

    private $command;

    private $data;

    private $response;

    function  __construct()
    {
        $this->data = array();
        $this->apiPath = '/api/execute/{command}?key={key}';
        $this->setUrl('login.impactki.com');
    }

    /**
     * Set auth key
     *
     * @param $key
     * @return $this
     */
    function setAuthKey($key)
    {
        $this->apiKey = $key;

        return $this;
    }

    /**
     * Get auth key
     *
     * @return mixed
     */
    function getAuthKey()
    {
        return $this->apiKey;
    }

    /**
     * Set API url
     *
     * @param $url
     * @return $this
     */
    function setUrl($url)
    {
        $this->apiUrl = $url;

        return $this;
    }

    /**
     * Get API url
     *
     * @return string
     */
    function getUrl()
    {
        return $this->apiUrl;
    }

    /**
     * Set command
     *
     * @param $command
     * @return $this
     */
    function setCommand($command)
    {
        $this->command = $command;

        return $this;
    }

    /**
     * Get command
     *
     * @return mixed
     */
    function getCommand()
    {
        return $this->command;
    }

    /**
     * Set data
     *
     * @param $data
     * @return $this
     * @throws \Exception
     */
    function setData($data)
    {
        if(empty($data) || !is_array($data))
        {
            throw new \Exception('Data must be array.');
        }

        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return array
     */
    function getData()
    {
        return $this->data;
    }

    /**
     * Set response
     *
     * @param $response
     */
    function setResponse($response)
    {
        $this->response = json_decode($response, true);
    }

    /**
     * Get response from API call
     *
     * @return mixed
     * @throws \Exception
     */
    function getResponse()
    {
        if(empty($this->response))
        {
            throw new \Exception('Response is empty..');
        }

        return $this->response;
    }

    /**
     * Execute api call
     *
     * @return mixed
     * @throws \Exception
     */
    function execute()
    {
        if(empty($this->command))
        {
            throw new \Exception('Command has not been set.');
        }

        if(empty($this->apiKey))
        {
            throw new \Exception('API Key is undefined.');
        }

        if(empty($this->apiUrl))
        {
            throw new \Exception('API URL is undefined.');
        }

        $this->setResponse($this->send($this->getRequestUrl(), $this->getData()));

        return $this;
    }

    /**
     * Get URL that will be executed
     *
     * @return string
     */
    private function getRequestUrl()
    {
        return $this->getUrl() . str_replace(array('{key}','{command}'), array($this->getAuthKey(), $this->getCommand()), $this->apiPath);
    }

    /**
     * Send the request to the url with given data.
     *
     * @param $url
     * @param $post
     * @return mixed
     */
    private function send($url, $post)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
} 