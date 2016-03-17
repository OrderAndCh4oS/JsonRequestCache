<?php

class ReadJson
{

    private $url;
    private $file;
    private $items;
    private $per_page;
    private $page;

    function  __construct($url, $paginate = null)
    {
        $this->url  = $url;
        $this->file = 'cache/course_list';
        $this->setItems();
        if ($paginate) {
            $this->per_page = $paginate['per_page'];
            $this->page = $paginate['page'];
        }
    }

    private function setItems()
    {
        if (file_exists($this->file)) {
            $fh        = fopen($this->file, 'r');
            trim(fgets($fh));
            $this->items = json_decode(fread($fh, filesize($this->file)));
        } else {
            $this->items = false;
        }
    }

    /**
     * @return array
     */
    public function getItems()
    {
        if (!empty($this->items->events)) {
            if ($this->page && $this->per_page) {
                $offset = ($this->page - 1) * $this->per_page;
                $paged_items = array_slice($this->items->events, $offset, $this->per_page);
                return $paged_items;
            }
            return $this->items->events;
        }
        return false;
    }

    public function pagination($base_url = null) {
        $length = count($this->items->events);
        $pages = ceil($length / $this->per_page);
        $output = '<div class="pagination"><ul>';
        for ($i = 1; $i <= $pages; $i++) {
            $output .= '<li>';
            if ($base_url) {
                $output .= '<a href="'.$base_url.$i.'"';
            }
            $output .= '<a href="?page='.$i.'"';
            if ($this->page == $i) {
                $output .= ' class="current"';
            }
            $output .= '>';
            $output .= $i;
            $output .= '</a>';
            $output .= '</li>';
        }
        $output .= '</ul></div>';
        return $output;
    }

}