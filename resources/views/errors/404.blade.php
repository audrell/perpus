@extends('errors.layout')

@section('code', '404')
@section('title', 'Not Found')
@section('cause', 'Route atau data tidak ada.')

@section('actions')
	<a href="{{ url('/') }}" class="btn btn-primary btn-soft mr-2 mb-2">
		<i class="fas fa-compass mr-1"></i> Ke Beranda
	</a>
@endsection