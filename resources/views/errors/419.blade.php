@extends('errors.layout')

@section('code', '419')
@section('title', 'Page Expired')
@section('cause', 'CSRF token tidak ada atau sudah kedaluwarsa.')

@section('actions')
	<a href="{{ url('/login') }}" class="btn btn-primary btn-soft mr-2 mb-2">
		<i class="fas fa-redo mr-1"></i> Login Ulang
	</a>
	<button onclick="history.back()" class="btn btn-light btn-soft mb-2 border">
		<i class="fas fa-arrow-left mr-1"></i> Kembali
	</button>
@endsection