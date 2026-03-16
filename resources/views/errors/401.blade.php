@extends('errors.layout')

@section('code', '401')
@section('title', 'Unauthorized')
@section('cause', 'Belum login.')

@section('actions')
	<a href="{{ url('/login') }}" class="btn btn-primary btn-soft mr-2 mb-2">
		<i class="fas fa-sign-in-alt mr-1"></i> Login
	</a>
	<a href="{{ url('/') }}" class="btn btn-outline-primary btn-soft mr-2 mb-2">
		<i class="fas fa-compass mr-1"></i> Ke Beranda
	</a>
@endsection