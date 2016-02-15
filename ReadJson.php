<?php

class ReadJson
{

    private $url;
    private $file;
    private $items;

    function  __construct($url)
    {
        $this->url  = $url;
        $this->file = 'cache/'.md5($url);
        $this->setItems();
    }

    private function setItems()
    {
        if (file_exists($this->file)) {
            $fh        = fopen($this->file, 'r');
            $cacheTime = trim(fgets($fh));
            $this->items = json_decode(fread($fh, filesize($this->file)));
            if ($cacheTime > strtotime('1 hour')) {
                fclose($fh);
                return;
            }
        } else {
            $this->items = false;
        }
        $this->execInBackground('php Json.php ' . $this->url);
    }

    private function execInBackground($cmd) {
        if (substr(php_uname(), 0, 7) == "Windows"){
            pclose(popen("start /B ". $cmd, "r"));
        }else{
            exec($cmd . " > /dev/null &");
        }
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

}