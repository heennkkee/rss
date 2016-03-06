#LICENSE
This software is distributed under the MIT License.


##Usage
The library needs read/write access to a folder, standard is the folder "rsscache" in your webroot, but if you specify another rssFile then you'll have to adjust your permissions accordingly.
In addition it needs read/write in it's own src folder, to manage the database. Standard setting is to create the database src.sqlite in the src folder, but this can be changed as well.

Either you change the sourcecode to fit your feed description, or you do it when you initiate the class on the route that generates the RSS feed. In my example code I wouldn't write my RSS Description when I call the rss class on the "add page", only on the actual "rss.php".

Out of the box this will show your 5 latest "RSS news".

###Initiation
`$rss = new \henaro\rss\crss();`

Editable options are (followed with standard value): 

* rssFile => rsscache/rss.xml
* table => RSSFeed
* newsCount => 5
* db
..*dsn => sqlite:' . REALPATH(__DIR__) . '/src.sqlite (so in the same folder as the source document)
⋅⋅* username => null
⋅⋅* password => null
⋅⋅* driver_options => null
* feedDescription 
⋅⋅* title => CRSS easy feed
⋅⋅* link => http://www.github.com
⋅⋅* description => Description of the amazing feed.

Standard options are adjusted so it's as much "good to go" as possible. Highly recommended to edit the feedDescription to fit your needs.

###Insert new item
`$rss->insertRSS(['LINK' => 'Link to your post', 'TITLE' => 'Title of the post', 'DESCRIPTION' => 'Short description of the item']);`

Inserts a new post to your database with approiate information needed to generate a new rss.xml file when requested.

###Generate RSS Feed
`$rss->getRSS();`

->getRSS() handles the generation of a new RSS file, if needed, and then reads the content of rsscache/rss.xml into the browser.

All SQL commands are made to work with SQLite, nothing else.
