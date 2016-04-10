<!DOCTYPE html>
<html lang="en">
    <head>
    <title><?php print($doctitle); ?></title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <?php print($head); ?>
        <script>

        </script>
        <link rel="stylesheet" media="all" type="text/css" href="../../../static/jquery/jquery-impromptu/themes/base.css" />
        <link rel="stylesheet" href="css/styles.css" />
    </head>
    <body class="menu-horizontal">
    <div id="outer">
        <div id="topbar">
            <div class="pull-left">
                <div id="dummy-logo"><img src="img/logo/logo-top.png" alt="<?php print( __Config::get( 'APP_NAME' ) ) ?>"></div>
                <div id="dummy-text"><?php print( __Config::get( 'APP_NAME' ) ) ?></div>
            </div>

            <div class="pull-right">

                <!-- show-nav-for-iphone -->
                <button type="button" class="show-nav-for-iphone" data-toggle="collapse" data-target="#nav-collapse"></button>
                <!-- show-nav-for-iphone -->

                <div class="pull-left">
                    <div id="exit-menu" class="pull-left"><?php print($logout); ?></div>
                </div>
            </div>
        </div>

        <div id="container">
            <div id="container-inner" class="container-fluid">
                <div class="navigation with-sub row-fluid">
                    <div class=" span12">
                        <?php print($topbar); ?>
                    </div>
                </div>
                <?php if ($subNavigation || $actions) {?>
                <div class="subnavigation row-fluid">
                    <div class="span12">
                        <?php print($subNavigation); ?>
                        <div id="breadcrumb-actions">
                            <?php print($actions); ?>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <div class="row-fluid">
                    <div class="span12">
                        <?php if (!$treeview) {?>
                            <?php print($pageTitle); ?>
                            <div id="admincontent" class="box-content">
                                <div id="message-box"></div>
                                <?php print($content); ?>
                                <?php print($treeview); ?>
                            </div>
                        <?php } else { ?>
                            <?php print($treeview); ?>
                            <div id="container" class="with-treeview">
                                <div id="container-inner" class="container-fluid">
                                    <?php print($content); ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <p><?php echo __Config::get('APP_NAME').' v'.__Config::get('APP_VERSION') ?></p>
    </footer>
    <?php print($tail); ?>
    <script type="text/javascript" src="../../../static/jquery/jquery-impromptu/jquery-impromptu.min.js"></script>
    <script type="text/javascript">
// <![CDATA[
$(function(){
    if ($.fn.button && $.fn.button.noConflict) {
        var bootstrapButton = $.fn.button.noConflict();
        $.fn.bootstrapBtn = bootstrapButton;
    }
})
// ]]>
</script>
    </body>
</html>