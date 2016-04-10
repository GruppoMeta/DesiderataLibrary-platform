<!DOCTYPE HTML>
<html lang="it-IT" itemscope itemtype="http://schema.org/Book">
    <head prefix="og: http://ogp.me/ns#; dcterms: http://purl.org/dc/terms/#">
        <meta charset="utf-8">
        <title><?php echo $__title.', '.$publication->__title;?> - Desiderata Library</title>
        <meta property="dcterms:language" content="it-IT" />
        <meta name="dcterms.format" content="(SCHEME=IMT) text/html" />
        <meta property="dcterms:title" content="<?php echo htmlentities($__title.', '.$publication->__title);?>" />
        <meta property="og:title" content="<?php echo htmlentities($__title.', '.$publication->__title);?>" />
        <meta itemprop="name" content="<?php echo htmlentities($__title.', '.$publication->__title);?>" />
        <?php if ($publication->abstract) { ?>
        <meta property="dcterms:description" content="<?php echo htmlentities($publication->abstract);?>" />
        <meta property="og:description" content="<?php echo htmlentities($publication->abstract);?>" />
        <meta itemprop="description" content="<?php echo htmlentities($publication->abstract);?>" />
        <?php } ?>
        <?php if ($publication->subject && count($publication->subject)) { ?>
        <meta property="dcterms:subject" content="<?php echo htmlentities(implode(',', $publication->subject));?>" />
        <?php } ?>
        <?php if ($publication->publisher) { ?>
        <meta property="dcterms:publisher" content="<?php echo htmlentities($publication->publisher->text);?>" />
        <?php } ?>
        <?php if ($publication->dc_creator) { ?>
        <meta property="dcterms:creator" content="<?php echo htmlentities($publication->dc_creator);?>" />
        <?php } ?>
        <?php if ($publication->dc_contributor) { ?>
        <meta property="dcterms:contributor" content="<?php echo htmlentities($publication->dc_contributor);?>" />
        <?php } ?>
        <?php if ($publication->dc_type) { ?>
        <meta property="dcterms:type" content="<?php echo htmlentities($publication->dc_type);?>" />
        <?php } ?>
        <?php if ($publication->dc_identifier) { ?>
        <meta property="dcterms:identifier" content="<?php echo htmlentities($publication->dc_identifier);?>" />
        <?php } ?>
        <?php if ($publication->dc_source) { ?>
        <meta property="dcterms:source" content="<?php echo htmlentities($publication->dc_source);?>" />
        <?php } ?>
        <?php if ($publication->dc_relation) { ?>
        <meta property="dcterms:relation" content="<?php echo htmlentities($publication->dc_relation);?>" />
        <?php } ?>
        <?php if ($publication->dc_coverage) { ?>
        <meta property="dcterms:coverage" content="<?php echo htmlentities($publication->dc_coverage);?>" />
        <?php } ?>
        <?php if ($publication->dc_rights) { ?>
        <meta property="dcterms:rights" content="<?php echo htmlentities($publication->dc_rights);?>" />
        <?php } ?>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link rel="stylesheet" <?php echo 'hr'.'ef'?>="../css/app.css">
    </head>
    <body>
        <header>
            <nav class="navbar navbar-default"></nav>
            <div>
                <div class="logo"><img <?php echo 's'.'rc'?>="../img/desiderata-library-logo.png"></div>
            </div>
        </header>

        <div class="container">
        <main class="row-fluid">
            <article class="col-sm-8">
                <header>
                    <h1><?php echo $__title; ?></h1>
                </header>
                <?php if ($pageType=='text') { ?>
                    <?php echo $text; ?>
                    <?php
if ($media && is_array($media->media) && count($media->media)) {
    foreach($media->media as $m) {
        $m = json_decode($m);
        if ($m) {
            echo desiderataLibrary_plugins_export_services_SeoService::addMediaHtml($m->id);
        }
    }
}
                ?>
                <?php } else { ?>
                    <?php echo $text; ?>
                <?php } ?>
            </article>
            <aside  class="col-sm-3 publication">
                <div class="cover">
                   <?php if ($publication->cover) {
                        $cover = json_decode($publication->cover);
                        if ($cover) {
                            echo org_glizy_helpers_Media::getResizedImageById($cover->id, false, 200, 300);
                        }
                   } ?>
                </div>
                <p class="title"><span itemprop="name"><?php echo $publication->__title; ?> <?php echo $publication->subtitle; ?></span></p>
                <ul>
                    <?php if ($publication->author) { ?>
                        <li>Autore: <span itemprop="author" itemscope itemtype="http://schema.org/Person"><?php echo $publication->author; ?></span></li>
                    <?php } ?>
                    <?php if ($publication->isbn) { ?>
                        <li>ISBN: <span itemprop="isbn"><?php echo $publication->isbn; ?></span></li>
                    <?php } ?>
                    <li>Editore: <span itemprop="publisher" itemscope itemtype="http://schema.org/Organization "><?php echo $publication->publisher->text; ?></span></li>
                    <?php if ($publication->price) { ?>
                        <li>Prezzo: <span itemprop="publisher" itemscope itemtype="http://schema.org/Offer"><?php echo $publication->price; ?></span></li>
                    <?php } ?>
                    <?php if ($publication->isFree==1) { ?>
                        <li>Prezzo: <span itemprop="publisher" itemscope itemtype="http://schema.org/Offer">GRATUITO</span></li>
                    <?php } ?>
                </ul>
                <a href="http://desiderata-dev.gruppometa.it/books/" class="btn desiderata">Accedi a Desiderata Library</a>
            </aside>
        </main>
        </div>
    </body>
</html>