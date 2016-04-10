<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <title><?php print($doctitle); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php print($head); ?>
    <link rel="stylesheet" href="css/styles.css" />
</head>
<body>
    <div class="box-content box-padding">
        <?php print($content); ?>
    </div>
    <?php print($tail); ?>
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