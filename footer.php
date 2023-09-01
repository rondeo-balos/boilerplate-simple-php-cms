<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo get_title() ?></title>
    <?php
        enqueue_style( 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css' );
        enqueue_style( 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css', array(
            'integrity' => 'sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC',
            'crossorigin' => 'anonymous'
        ) );
        render_styles();

        //enqueue_script( 'https://cdn.tailwindcss.com' );
        enqueue_script( 'https://code.jquery.com/jquery-3.7.1.min.js', array( 'integrity' => 'sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=', 'crossorigin' => 'anonymous' ) );
        enqueue_script( 'https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js', array(
            'integrity' => 'sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p',
            'crossorigin' => 'anonymous'
        ) );
        enqueue_script( 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js', array(
            'integrity' => 'sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF',
            'crossorigin' => 'anonymous'
        ) );
        render_scripts();
    ?>
</head>
<body>
    <?php
        if( function_exists( 'content' ) ) {
            content();
        }
    ?>
</body>
</html>