@extends('layouts.default')

@section('content')
<h3>List Files</h3>
<ul id="files">
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
                @if(!empty($file->webContentLink))
				<a href="{{ $file->webContentLink }}">download</a>
			    @endif
            </div>
		</div>
	</li>
	@endforeach
</ul>
@stop