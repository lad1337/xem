<!DOCTYPE html>
<html lang="en">
<head></head>
<body>
<h1><?=$user_nick?> requested the publication of <?=$show->main_name?></h1>

<p>
Draft can be found here &rarr; <?=anchor('http://thexem.de/xem/draft/'.$show->parent, $show->main_name)?>
</p>

</body>
</html>