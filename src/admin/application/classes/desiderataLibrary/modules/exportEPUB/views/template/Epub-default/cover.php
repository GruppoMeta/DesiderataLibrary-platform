<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="it">

<head>
    <meta charset="utf-8" />
    <title><?= $doctitle; ?></title>
    <link rel="stylesheet" href="css/gruppometa.easybook.css"/>
    <style type="text/css" title="override_css">
        @page {
            margin: 0pt;
            padding: 0pt;
        }

        body {
            margin: 0pt;
            padding: 0pt;
            text-align: center;
        }
    </style>
</head>

<body>
<div class="container">
    <?php if ($cover) { ?>
        <div>
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"
                 width="100%" height="100%" viewBox="0 0 <?= $cover['width']; ?> <?= $cover['height']; ?>"
                 preserveAspectRatio="none">
                <image width="<?= $cover['width']; ?>" height="<?= $cover['height']; ?>"
                       xlink:href="<?= $cover['path']; ?>" />
            </svg>
        </div>
    <?php } ?>
    <div class="item item:cover">
        <h1><?= $title; ?></h1>

        <h2><?php
            foreach ($authors as $author) {
                echo $author . ' ';
            }
            ?></h2>
    </div>
</div>
</body>
</html>