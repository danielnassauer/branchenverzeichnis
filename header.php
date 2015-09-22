<html>
    <head>
        <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
        <meta name="google-signin-client_id" content="1042880545551-5qeha6pmrq4r72a48p5f7r0bsqqdm1o5.apps.googleusercontent.com">
        <title><?php wp_title(' - ', true, 'right'); ?> <?php bloginfo('name'); ?></title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css"/>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <script type="text/javascript">
            function onSignIn(googleUser) {
                var profile = googleUser.getBasicProfile();
                console.log('ID: ' + profile.getId()); // Do not send to your backend! Use an ID token instead.
                console.log('Name: ' + profile.getName());
                console.log('Image URL: ' + profile.getImageUrl());
                console.log('Email: ' + profile.getEmail());
            }
        </script>
        <?php wp_head(); ?>
    </head>
    <body>
