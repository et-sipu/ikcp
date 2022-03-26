<html>
<head>
    <style>
        .ck-content .image-style-side,
        .ck-content .image-style-align-left,
        .ck-content .image-style-align-center,
        .ck-content .image-style-align-right {
            max-width: 50%;
        }
        .ck-content .image-style-side {
            float: right;
            margin-left: var(--ck-image-style-spacing);
        }
        .ck-content .image-style-align-left {
            float: left;
            margin-right: var(--ck-image-style-spacing);
        }
        .ck-content .image-style-align-center {
            margin-left: auto;
            margin-right: auto;
        }
        .ck-content .image-style-align-right {
            float: right;
            margin-left: var(--ck-image-style-spacing);
        }
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        .ck-content .image {
            clear:both;
            text-align:center;
            margin:1em 0
        }
        .ck-content .image>img {
            display:block;
            margin:0 auto;
            max-width:100%
        }
        .ck-content .image {
            position:relative;
            overflow:hidden
        }
    </style>
</head>

<body style="background-color: white !important;">
    <div class="ck-content">
        {!! $content !!}
    </div>

    <footer>
        @lang('mails.layout.regards'), {{ config('app.name') }}
    </footer>
</body>

</html>
