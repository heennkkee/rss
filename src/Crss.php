<?php

namespace henaro\rss;

class crss {

    private $table = 'RSSFeed';

 /**
 * Compares RSS file created timestamp and database timestamp to judge if new RSS file is needed
 *
 * Sets internal variable $this->valid to true/false depending if new file needs to be generate
 * @return void
 */
    private function checkValidity()
    {

    }

    public function getRSS()
    {
        $this->checkValidity();
        
    }


}
