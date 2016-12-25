<html>
<head>
    <title>Wichtel.me - @yield('title')</title>
    <style>
        body {
            background: #bf0303;
        }

        .wrapper {
            max-width: 500px;
            margin: 1em auto;
            background: #fff;
            border: 2px solid #000;
        }

        header {
            background: #f1f2eb;
        }

        header h1 {
            margin: 0;
            padding: 0.3em;
        }

        .container {
            padding: 1em;
        }

    </style>
</head>
<body>
    <div class="wrapper">
        <header>
            <h1>Wichtel.me</h1>
        </header>

        <div class="container">
            @yield('content')
        </div>
    </div>
</body>
</html>
