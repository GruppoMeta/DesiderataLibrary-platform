<?= '<?xml version="1.0" encoding="UTF-8" ?>'; ?>
<package version="3.0" xmlns="http://www.idpf.org/2007/opf" unique-identifier="pub-identifier">

    <metadata xmlns:dc="http://purl.org/dc/elements/1.1/">
        <dc:title id="pub-title"><?= $title; ?></dc:title>
        <dc:language id="pub-language">it</dc:language>
        <dc:identifier id="pub-identifier"><?= $pubId; ?></dc:identifier>
        <?php
        foreach ($authors as $author) {
            echo '<dc:creator id="author">'.$author.'</dc:creator>';
        }
        ?>
        <dc:publisher id="publisher">MetaCMS Ebook Generator 1.0</dc:publisher>
        <dc:date><?= date('Y-m-d'); ?></dc:date>
        <meta name="cover" content="cover"/>
        <meta property="dcterms:modified" ><?= gmDate("Y-m-d\TH:i:s\Z"); ?></meta>
    </metadata>

    <manifest>
        <?php
        foreach ($items as $item) {
            echo '<item id="' . $item['id'] . '" href="' . $item['path'] . '" media-type="' . $item['mediaType'] . '" />';
        }
        ?>
        <item id="nav" href="nav.xhtml"  media-type="application/xhtml+xml" properties="nav" />
        <item id="ncx" href="toc.ncx" media-type="application/x-dtbncx+xml"/>
    </manifest>

    <spine toc="ncx">
        <itemref idref="cover"/>
        <?php
        foreach ($items as $item) {
            if ('pages' == $item['type']) {
                echo '<itemref idref="' . $item['id'] . '"  />';
            }
        }
        ?>
        <itemref idref="nav" />
    </spine>

    <guide>
        <reference href="cover.xhtml" type="cover" title="Cover"/>
    </guide>

</package>