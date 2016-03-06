#LICENSE
This software is distributed under the MIT License.


##Usage
The library needs read/write access to a folder, standard is the folder "rss" in your webroot, but if you specify another rssFile then you'll have to adjust your permissions accordingly.
In addition it needs read/write in it's own src folder, to manage the database. Standard setting is to create the database src.sqlite in the src folder, but this can be changed as well.

Either you change the sourcecode to fit your feed description, or you do it when you initiate the class on the route that generates the RSS feed. In my example code I wouldn't write my RSS Description when I call the rss class on the "add page", only on the actual "rss.php".

Out of the box this will show your 5 latest "RSS news".

To insert a new item to your RSS you call
`$rss->insertRSS(['LINK' => 'Link to your post', 'TITLE' => 'Title of the post', 'DESCRIPTION' => 'Short description of the item']);`
