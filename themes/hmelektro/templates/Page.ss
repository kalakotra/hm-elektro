<!doctype html>
<html lang="$ContentLocale">
<head>
    <% base_tag %>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <% include SeoHead %>
    <link rel="icon" href="$resourceURL('themes/hmelektro/images/favicon.svg')" type="image/svg+xml">
    <link rel="icon" href="$resourceURL('themes/hmelektro/images/favicon-32.png')" sizes="32x32" type="image/png">
    <link rel="apple-touch-icon" href="$resourceURL('themes/hmelektro/images/apple-touch-icon.png')">
    <meta name="theme-color" content="#1D1D1B">
    $ViteClient.RAW
    <link rel="preload" href="$Vite('node_modules/@fontsource/barlow/files/barlow-latin-400-normal.woff2')" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="$Vite('node_modules/@fontsource/barlow-semi-condensed/files/barlow-semi-condensed-latin-700-normal.woff2')" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="$Vite('app/client/src/scss/app.scss')">
    <script type="module" src="$Vite('app/client/src/js/app.js')"></script>
</head>
<body class="$ClassName.ShortName.LowerCase">
    <% include IconSprite %>
    <% include SkipLink %>
    <% include Header %>
    <main id="main" tabindex="-1">
        $Layout
    </main>
    <% include Footer %>
    <% include StickyActionBar %>
</body>
</html>
