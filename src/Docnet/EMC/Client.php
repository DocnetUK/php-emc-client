<?php
/**
 * Copyright 2014 Docnet
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace Docnet\EMC;

/**
 * Email Campaigner API Client
 *
 * @author Tom Walder <tom@docnet.nu>
 */
class Client
{

    /**
     * Email Campaigner API key
     *
     * @var string
     */
    private $str_key = NULL;

    /**
     * Email Campaigner API secret
     * @var null
     */
    private $str_secret = NULL;

    /**
     * API response object
     *
     * @var object
     */
    private $obj_response = NULL;

    /**
     * Pass in your API key and secret on construction
     *
     * @param $str_key
     * @param $str_secret
     */
    public function __construct($str_key, $str_secret)
    {
        $this->str_key = $str_key;
        $this->str_secret = $str_secret;
        $this->checkMinimumRequirements();
    }

    /**
     * Subscribe a new contact
     *
     * @param string $str_email
     */
    public function subscribe($str_email)
    {
        $this->send('/contact/subscribe', (object)array(
            'email' => $str_email
        ));
    }

    /**
     * Unsubscribe an existing contact
     *
     * @param string $str_email
     */
    public function unsubscribe($str_email)
    {
        $this->send('/contact/unsubscribe', (object)array(
            'email' => $str_email
        ));
    }

    /**
     * Get the last API response
     *
     * @return object
     */
    public function getResponse()
    {
        return $this->obj_response;
    }

    /**
     * Send a payload to Email Campaigner
     *
     * Calculate and include the SHA256 hash
     *
     * @param string $str_path
     * @param object $obj_payload
     */
    private function send($str_path, $obj_payload)
    {
        $str_hash = hash_hmac('sha256', json_encode($obj_payload), $this->str_secret, FALSE);
        $this->httpPost($str_path, (object)array(
            'key' => $this->str_key,
            'hash' => $str_hash,
            'payload' => $obj_payload
        ));
    }

    /**
     * Carry out an HTTP POST as JSON data
     *
     * Check the PHP auto-populated array $http_response_header for errors
     *
     * @param string $str_path
     * @param object $obj_data
     * @throws \Exception
     */
    private function httpPost($str_path, $obj_data)
    {
        $arr_opts = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type: application/json',
                'content' => json_encode($obj_data)
            )
        );
        $this->obj_response = json_decode(@file_get_contents('https://emc-five.appspot.com/api' . $str_path, FALSE, stream_context_create($arr_opts)));
        if(!is_array($http_response_header)) {
            throw new \Exception("HTTP POST failed");
        }
        if (FALSE === strpos($http_response_header[0], "200")) {
            throw new \Exception("HTTP POST failed with: " . $http_response_header[0]);
        }
    }

    /**
     * Validate the minimum requirements for the API to work
     *
     * @throws \Exception
     */
    private function checkMinimumRequirements()
    {
        if (!extension_loaded('json')) {
            throw new \Exception('JSON extension not available');
        }
        if (!in_array('sha256', hash_algos())) {
            throw new \Exception('SHA256 not available');
        }
    }
}