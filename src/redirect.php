<?php
$url = str_replace('/go', '/books/#', $_SERVER['PHP_SELF']);
header('Location: '.$url);