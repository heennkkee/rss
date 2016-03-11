<?php

namespace henaro\rss;

class Crss {

    private $table = 'RSSFeed';
    private $rssFile;
    private $valid = false;
    private $dbOptions = [];
    private $dbStructure;
    private $db;
    private $newsCount;
    private $feedDescription;

 /**
 * Initiates the class, takes parameters as input but should work with standard settings.
 * Connects to the DB and makes sure the table exists.
 * @param $params is the input to adjust standard settings. I recommend to change only the 'feedDescription' part.
 */
    public function __construct($params = [])
    {
        $options = [
            'rssFile' => REALPATH(__DIR__) . '/rsscache/rss.xml',
            'table' => $this->table,
            'newsCount' => 5,
            'db' => [
                'dsn' => 'sqlite:' . REALPATH(__DIR__) . '/src.sqlite',
                'username' => null,
                'password' => null,
                'driver_options' => null,
                'debug' => false
            ],
            'feedDescription' => [
                'title' => 'CRSS easy feed',
                'link' => 'http://www.github.com',
                'description' => 'Description of the amazing feed.'
            ]
        ];

        foreach($params as $key => $value) {
            if (is_array($value)) {
                foreach($value as $subKey => $subValue) {
                    $options[$key][$subKey] = $subValue;
                }
            } else {
                $options[$key] = $value;
            }
        }

        $this->rssFile = $options['rssFile'];
        $this->table = $options['table'];
        $this->dbOptions = $options['db'];
        $this->newsCount = $options['newsCount'];
        $this->feedDescription = $options['feedDescription'];

        $this->dbStructure = 'CREATE TABLE IF NOT EXISTS ' . $this->table . ' (
            ID INTEGER PRIMARY KEY NOT NULL,
            TITLE CHAR(50) NOT NULL,
            LINK CHAR(50) NOT NULL,
            DESCRIPTION CHAR(255) NOT NULL,
            CREATED DATETIME
        )';

        $this->connect();
        $this->createDB();
    }

 /**
 * Internal function for connecting to the database
 *
 * @return void
 */
    private function connect()
    {
        try {
            $this->db = new \PDO(
                $this->dbOptions['dsn'],
                $this->dbOptions['username'],
                $this->dbOptions['password'],
                $this->dbOptions['driver_options']
            );

        } catch(\Exception $e) {
            //Change to true to debug database connection
            if ($this->dbOptions['debug']) {
                // For debug purpose, shows all connection details
                throw $e;
            } else {
                // Hide connection details.
                throw new \PDOException("Could not connect to database, hiding connection details.");
            }
        }
        $this->db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
    }

    public function createDB() {
        $stmt = $this->db->prepare($this->dbStructure);
        $stmt->execute();
    }

 /**
 * Compares RSS file created timestamp and database timestamp to judge if new RSS file is needed
 *
 * Sets internal variable $this->valid to true/false depending if new file needs to be generate
 * @return void
 */
    private function checkValidity()
    {

        if (!is_writable($this->rssFile)) {
            $this->valid = false;
            return;
        }

        $stmt = $this->db->prepare('SELECT CREATED FROM ' . $this->table . ' ORDER BY CREATED DESC LIMIT 1');
        $stmt->execute();
        $res = $stmt->fetchAll();

        if (count($res) == 0) {
            $this->valid = false;
            return;
        }

        $latestInput = strtotime($res[0]->CREATED);

        $rssCreated = filemtime($this->rssFile);

        if ($latestInput > $rssCreated) {
            $this->valid = false;
        } else {
            $this->valid = true;
        }
    }

 /**
 * Creates a new RSS File with information from the database. Max amount of news is configured in the initiation.
 *
 * @return void
 */
    private function createRSS()
    {
        $file = fopen($this->rssFile, "w+");

        $stmt = $this->db->prepare('SELECT * FROM ' . $this->table . ' ORDER BY CREATED DESC LIMIT ?');
        $stmt->execute([$this->newsCount]);
        $res = $stmt->fetchAll();

        $xmlVersion = '<?xml version="1.0" encoding="UTF-8" ?>';

        $startString = <<<EOD
{$xmlVersion}
<rss version="2.0">
<channel>
    <title>{$this->feedDescription['title']}</title>
    <link>{$this->feedDescription['link']}</link>
    <description>{$this->feedDescription['description']}</description>
EOD;
        fwrite($file, $startString);

        foreach ($res as $post) {
            $date = date("D, d M y H:i:s O", strtotime($post->CREATED));
            $string = <<<EOD

    <item>
        <title>{$post->TITLE}</title>
        <link>{$post->LINK}</link>
        <description>{$post->DESCRIPTION}</description>
        <pubDate>{$date}</pubDate>
    </item>
EOD;
            fwrite($file, $string);
        }

        fwrite($file, '
</channel>
</rss>');
    }

 /**
 * Function that should be called to receive the RSS feed.
 * Checks if the latest RSS File is up to date, if not, generates a new one.
 * @return void
 */
    public function getRSS()
    {
        $this->checkValidity();

        if (!$this->valid) {
            $this->createRSS();
        }

        header('Content-type: application/rss+xml; charset=UTF-8');
        readfile($this->rssFile);
    }

 /**
 * Inserts information to RSS database
 * @param $input should be an associative array with three pars TITLE, LINK, DESCRIPTION
 *
 * @return void
 */
    public function insertRSS($input = [])
    {
        $stmt = $this->db->prepare("INSERT INTO  ". $this->table ." (TITLE, LINK, DESCRIPTION, CREATED) VALUES (?, ?, ?, datetime('now', 'localtime'))");
        $stmt->execute([$input['TITLE'], $input['LINK'], $input['DESCRIPTION']]);
    }
}
