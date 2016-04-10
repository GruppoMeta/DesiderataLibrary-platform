<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:epub="http://www.idpf.org/2007/ops" lang="it">

<head>
    <meta charset="utf-8"/>
    <title>Indice</title>
    <link rel="stylesheet" href="css/gruppometa.easybook.css"/>
</head>

<body>
<div class="container">
    <nav epub:type="toc">
        <h1>Contenuti</h1>
        <ol>
        <?php
        $oldDepth = 0;
        foreach ($items as $item) {
            if ('pages' == $item['type']) {
                if ($oldDepth >= $item['depth']) {
                    for ($i = $oldDepth; $i >= $item['depth']; $i--) {
                        echo '</li>';
                    }
                }
                echo '<li id="' . $item['id'] . '"><a href="' . $item['path'] . '">'.$item['title']."</a>";
                $oldDepth = $item['depth'];

            }
        }
        for ($i = $oldDepth; $i >= 1; $i--) {
            echo '</li>';
        }
        ?>
            <li id="nav"><a href="nav.xhtml">Indice</a></li>
        </ol>
    </nav>
</div>
</body>
</html>