<?php

namespace kahra\core\src\api;

// TODO: Abstract class?
class Connection {

    static function get($extension, $data=false) {
        return static::execEndpoint($extension, false, $data);
    }

    static function post($extension, $data=false) {
        return static::execEndpoint($extension, true, $data);
    }

    static function getId($response) : int {
        //return $response["objects"];
        return ((
            $response
            && is_array($response)
            && array_key_exists("id", $response)
            && is_int($response["id"])
        ) ? $response["id"] : 0);
    }

    static function getObjects($response) {
        return ((
                $response
                && is_array($response)
                && array_key_exists("objects", $response)
                && is_array($response["objects"])
            ) ? $response["objects"] : false);
    }

    /**
     * @param $extension
     * @param bool $post
     * @param array $data
     * @return string
     */
    static function execEndpoint($extension, $post=false, $data=array())  {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, API_HOST . "/" . $extension . ($post ? "" : ($data ? "?" . http_build_query($data) : "")));
        //. "&api_key=" . getenv("ELGG_API_KEY") . ($post ? "" : ($data ? "&" . http_build_query($data) : "")));

        curl_setopt($curl, CURLOPT_POST, $post);

        $token = (isset($_SESSION) && array_key_exists("token", $_SESSION)) ? $_SESSION["token"] : false;
        //if ($post && $data) {
        if ($post) {
            //$json_data = json_encode($data);
            //$post = array('request' => base64_encode($json_data));
            //$post_data = http_build_query($post);

            //if ($_SESSION && array_key_exists("token", $_SESSION)) $data["token"] = $_SESSION["token"];

            if ($token) $data["token"] = $token;

            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        } elseif ($token) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array("token" => $token)));
        }

        //curl_setopt($curl, CURLOPT_HEADER, static::CURL_HEADER);
        //curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        //curl_setopt($curl, CURLOPT_USERPWD, static::CURL_USERNAME . ":" . static::CURL_PASSWORD);
        //curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

        //curl_setopt($curl, CURLOPT_COOKIEJAR, static::COOKIE_FILE);
        //curl_setopt($curl, CURLOPT_COOKIEFILE, static::COOKIE_FILE);

        //curl_setopt($curl, CURLOPT_RETURNTRANSFER, static::CURL_RETURN);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($curl, CURLOPT_)

        $exec = curl_exec($curl);

        //print_r($exec);
        //var_dump(API_HOST . "/" . $extension . ($post ? "" : ($data ? "?" . http_build_query($data) : "")));
        //var_dump($exec);

        $result = json_decode($exec, true);

        return $result;
    }
}

class APIObject {

}



?>