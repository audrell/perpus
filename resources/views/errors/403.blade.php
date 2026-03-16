@extends('errors.layout')

@section('code', '403')
@section('title', 'Forbidden')
@section('cause', 'Tidak punya akses.')

@section('actions')
	<a href="{{ auth()->check() ? url('/home') : url('/login') }}" class="btn btn-primary btn-soft mr-2 mb-2">
		<i class="fas fa-home mr-1"></i> Ke Home
	</a>
	<button onclick="history.back()" class="btn btn-light btn-soft mb-2 border">
		<i class="fas fa-arrow-left mr-1"></i> Kembali
	</button>
@endsection