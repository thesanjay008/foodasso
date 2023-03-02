<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<title>{{ config('adminlte.name', 'Dakhter') }} @if(@$page_title) - {{$page_title}} @endif</title>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="title m-b-md">{{ $body }}</div>
            </div>
        </div>
    </body>
</html>
