<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Product Ready!</title>
        <!-- This whole form could be done better! -->
    </head>
    <body class="antialiased">
        <form action="/" method="POST">
            @csrf
            <label for="quantity">Amount to apply for</label>
            <input type="number" name="quantity" id="quantity" value="{{ old('quantity') ?? isset($quantity) ? $quantity : '' }}" />
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <button type="submit">Check</button>
        </form>
        @isset($amount)
        <form action="/apply" method="POST">
            @csrf
            <p>Estimated amount: ${{ $amount }}</p>
            <small>This is subject to change, depending on activity.</small>
            <input type="hidden" name="quantity" id="quantity" value="{{ $quantity }}" />
            <button type="submit">Apply</button>
        </form>
        @endisset
    </body>
</html>
