<?php
require 'config.php';

class JsonRequest
{
    private $url;
    private $file;
    private $json;

    function  __construct($url)
    {
        $this->url  = $url;
        $this->file = 'cache/'.md5($url);
        $this->createFile();
    }

    private function setJson($url)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL            => $url,
            CURLOPT_HTTPHEADER     => array(
                'Authorization: Bearer ' . API_TOKEN
            ),
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        ));
        $json   = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        $this->json = $json;

        return $status;
    }

    public function createFile()
    {
        $url = $this->url;
        $i   = 2;
        while ($this->setJson($url) == 200) {
            if (!isset( $json )) {
                $json = $this->json;
            } else {
                $json = json_encode(array_merge_recursive(json_decode($json, true), json_decode($this->json, true)));
            }
            $url = $this->url.'&page='.$i;
            $i ++;
        }
        if (isset($json)) {
            $content = time()."\n".$json;
            file_put_contents($this->file, $content);
        } else {
            $content = file($this->file);
            $content[0] = time()."\n";
            file_put_contents($this->file, implode($content));
        }
    }
}

$json = new JsonRequest($argv[1]);
