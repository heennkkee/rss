<?php

$rss = new \henaro\rss\Crss(['rssFile' => REALPATH(__DIR__) . '/rsscache/rss.xml']);

$rss->getRSS();
