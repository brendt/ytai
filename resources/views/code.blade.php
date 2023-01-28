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
    <style>
        :root {
            --background-color: #e7f0fe;
            --border-color: #5ca8d8;
            --shadow-color: #5ca8d8AA;
            --code-background-color: #F7F7F7;
        }

        .hljs-highlight.space,
        .hljs-highlight.tab {
            position: relative;
        }


        .hljs-highlight.space:after {
            --dot-size: .2em;
            --dot-color: #AAA;
            display: block;
            position: absolute;
            left: 0;
            top: 0;
            content: '';
            border-radius: 50%;
            margin-top: 26px;
            margin-left: .1em;
            background: var(--dot-color);
            height: var(--dot-size);
            width: var(--dot-size);
        }

        .hljs-highlight.tab:after {
            --dot-size: .2em;
            --dot-color: #AAA;
            display: block;
            position: absolute;
            left: 0;
            right: .1em;
            top: 0;
            content: '';
            border-radius: 5px;
            margin-top: 0.66em;
            margin-left: .1em;
            background: var(--dot-color);
            height: var(--dot-size);
            width: 85%;
        }
    </style>
</head>
<body>

<div class="container">
    {!! $code !!}
</div>

</body>
</html>

