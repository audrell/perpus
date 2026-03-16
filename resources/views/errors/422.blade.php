@extends('errors.layout')

@section('code', '422')
@section('title', 'Validation Error')
@section('cause', 'Validasi gagal.')

@section('actions')
	<button onclick="history.back()" class="btn btn-primary btn-soft mr-2 mb-2 border-0">
		<i class="fas fa-edit mr-1"></i> Perbaiki Input
	</button>
	<a href="{{ auth()->check() ? url('/home') : url('/') }}" class="btn btn-outline-primary btn-soft mr-2 mb-2">
		<i class="fas fa-home mr-1"></i> Ke Home
	</a>
@endsection