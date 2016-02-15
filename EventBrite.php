<?php

class EventBrite
{

    private $url;
    private $file;
    private $json;
    private $events;

    function  __construct($url)
    {
        $this->url  = $url;
        $this->file = 'cache/'.md5($url);
        $this->setEvents();
    }

    public function set_url($url)
    {
        $this->url = $url;
    }

    private function setJson($url)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL            => $url,
            CURLOPT_HTTPHEADER     => array(
                'Authorization: Bearer API_TOKEN'
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

    public function setEvents()
    {

        if (file_exists($this->file)) {
            $fh        = fopen($this->file, 'r');
            $cacheTime = trim(fgets($fh));
            //ToDo: Run Exec to get new file
            $this->events = json_decode(fread($fh, filesize($this->file)));
            if ($cacheTime > strtotime('-4 hours')) {
                fclose($fh);
                return;
            }
        }

        unlink($this->file);
        $url = $this->url;
        $i   = 2;
        while ($this->setJson($url) == 200) {
            if (!isset( $json )) {
                $json = $this->json;
            } else {
                $json = json_encode(array_merge_recursive(json_decode($json, true), json_decode($this->json, true)));
            }
            $url = $this->url.'?page='.$i;
            $i ++;
        }
        if (isset( $json )) {
            $fh = fopen($this->file, 'w');
            fwrite($fh, time()."\n");
            fwrite($fh, $json);
            fclose($fh);
            $this->events = json_decode($json);
        }
    }

    /**
     * @return array
     */
    public function getEvents()
    {
        return $this->events;
    }

}