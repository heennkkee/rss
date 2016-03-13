<?php

namespace henaro\rss;

class CrssTest extends \PHPUnit_Framework_TestCase {

    public function testComplete()
    {
        $rss = new \henaro\rss\Crss(['sendHeader' => false]);
        $rss->insertRSS(['TITLE' => 'This one wont show', 'LINK' => 'http://github.com', 'DESCRIPTION' => 'This feed wont show, as well clear the database.']);
        $rss->clearRSS();
        $rss->insertRSS(['TITLE' => 'New feed', 'LINK' => 'http://github.com', 'DESCRIPTION' => 'Hopefully this feed will show as expected.']);
        $rss->getRSS();
    }

    public function testFailDB()
    {
        $rss = new \henaro\rss\Crss(['db' => ['debug' => true, 'dsn' => 'ThisBetterFail']]);
    }
}
