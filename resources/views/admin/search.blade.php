@extends('layouts.default')

@section('content')
<h3>Search Files</h3>
<form method="GET">
    <div class="row">
        <label for="query">Query</label>
        <input type="text" name="query" id="query" value="{{ $query }}">
    </div>
    <button class="button-primary">Search</button>
</form>
@if(!empty($files))
<ul id="files">
    <h4>Search results for: {{ $query }}</h4>
    @foreach($files as $file)
    <li>
        <div class="file">

            <div class="file-title">
                <img src="{{ $file->iconLink }}">
                {{ $file->name }}
            </div>
            <div class="file-modified">
                last modified: {{ Date::format($file->modifiedTime) }}
            </div>
            <div class="file-links">
                <a href="{{ $file->webViewLink }}">view</a>
                @if(!empty($webContentLink))
                <a href="{{ $file->webContentLink }}">download</a>
                @endif
                <a href="/trash/{{ $file->id }}">trash</a>
            </div>
        </div>
    </li>
    @endforeach
</ul>
@else
No results for your query: {{ $query }}
@endif
@stop