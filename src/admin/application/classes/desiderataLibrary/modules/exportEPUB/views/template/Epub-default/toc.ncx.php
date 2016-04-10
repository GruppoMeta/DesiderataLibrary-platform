<?= '<?xml version="1.0" encoding="UTF-8" ?>'; ?>
<!--<!DOCTYPE ncx PUBLIC "-//NISO//DTD ncx 2005-1//EN" "http://www.daisy.org/z3986/2005/ncx-2005-1.dtd">-->

<ncx version="2005-1" xml:lang="it" xmlns="http://www.daisy.org/z3986/2005/ncx/">

    <head>
        <meta name="dtb:uid" content="<?= $pubId; ?>"/>
        <meta name="dtb:depth" content="<?= $depth; ?>"/>
        <meta name="dtb:totalPageCount" content="0"/>
        <!-- must be 0 -->
        <meta name="dtb:maxPageNumber" content="0"/>
        <!-- must be 0 -->
    </head>

    <docTitle>
        <text><?= $title; ?></text>
    </docTitle>

    <?php
    foreach ($authors as $author) {
        echo '<docAuthor><text>'.$author.'</text></docAuthor>';
    }
    ?>

    <navMap>
        <?php
        $playOrder = 0;
        $oldDepth = 0;
        foreach ($items as $item) {
            if ('pages' == $item['type']) {
                $playOrder++;
                if ($oldDepth >= $item['depth']) {
                    for ($i = $oldDepth; $i >= $item['depth']; $i--) {
                        echo '</navPoint>';
                    }
                }
                echo '<navPoint class="chapter" id="' . $item['id'] . '" playOrder="' . $playOrder . '">';
                echo '<navLabel><text>' . $item['title'] . '</text></navLabel>';
                echo '<content src="' . $item['path'] . '"/>';
                $oldDepth = $item['depth'];
            }
        }
        for ($i = $oldDepth; $i >= 1; $i--) {
            echo '</navPoint>';
        }
        ?>
    </navMap>
</ncx>