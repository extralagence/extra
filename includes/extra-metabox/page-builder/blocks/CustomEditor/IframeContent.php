<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>PageBuilder Content Editor</title>
    </head>
    <body>
        <div class="extra-page-builder-inner content">&nbsp;</div>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="//<?php
            $query = $_SERVER['PHP_SELF'];
            $path = pathinfo( $query );
            echo $_SERVER['SERVER_NAME'].$path['dirname'];
        ?>/js/iframeResizer.contentWindow.min.js"></script>
    </body>
</html>