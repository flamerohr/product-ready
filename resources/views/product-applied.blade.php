<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Product Ready!</title>
        <!-- This whole form could be done better! -->
    </head>
    <body class="antialiased">
        <p>Successfully bought {{ $quantity }} products for a total of ${{ $amount }}!
    </body>
</html>
