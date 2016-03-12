<?php
if (isset($_POST['TITLE'])) {
  $rss = new \henaro\rss\Crss();
  $rss->insertRSS(['LINK' => $_POST['LINK'], 'TITLE' => $_POST['TITLE'], 'DESCRIPTION' => $_POST['DESCRIPTION']]);
  Header('Location: ' . $_SERVER['PHP_SELF']);
}
?>
<html>
<body>
  <p><a href="rss.php">View rss feed</a><p>
  <fieldset>
    <form action="?" method="POST">
      <p><input required name="TITLE" type="text" placeholder="Title"></p>
      <p><input required name="LINK" type="url" placeholder="Link"></p>
      <p><input required name="DESCRIPTION" type="text" placeholder="Description"></p>
      <input type="submit">
    </form>
  </fieldset>
</body>
</html>
