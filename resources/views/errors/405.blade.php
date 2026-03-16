@extends('errors.layout')

@section('code', '405')
@section('title', 'Method Not Allowed')
@section('cause', 'Method HTTP salah.')

@section('actions')
	<button onclick="history.back()" class="btn btn-primary btn-soft mr-2 mb-2 border-0">
		<i class="fas fa-arrow-left mr-1"></i> Kembali
	</button>
	<a href="{{ auth()->check() ? url('/home') : url('/') }}" class="btn btn-outline-primary btn-soft mr-2 mb-2">
		<i class="fas fa-home mr-1"></i> Ke Home
	</a>
@endsection