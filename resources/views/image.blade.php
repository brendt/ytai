<html lang="en">
<head>
    <title>image</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        {!! file_get_contents(resource_path('css/code.css')) !!}
        {!! file_get_contents(resource_path('css/app.css')) !!}
    </style>
</head>
<body>

<div class="container">
    {!! $code !!}
</div>

</body>
</html>

