@extends('errors.layout')

@section('code', '500')
@section('title', 'Internal Server Error')
@section('cause', 'Terjadi error program.')

@section('actions')
	<a href="{{ auth()->check() ? url('/home') : url('/') }}" class="btn btn-primary btn-soft mr-2 mb-2">
		<i class="fas fa-home mr-1"></i> Ke Home
	</a>
@endsection