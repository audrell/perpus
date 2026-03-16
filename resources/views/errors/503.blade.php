@extends('errors.layout')

@section('code', '503')
@section('title', 'Service Unavailable')
@section('cause', 'Layanan sedang maintenance atau tidak tersedia sementara.')

@section('actions')
	<button onclick="location.reload()" class="btn btn-primary btn-soft mr-2 mb-2 border-0">
		<i class="fas fa-sync-alt mr-1"></i> Coba Lagi
	</button>
	<a href="{{ url('/') }}" class="btn btn-outline-primary btn-soft mr-2 mb-2">
		<i class="fas fa-compass mr-1"></i> Ke Beranda
	</a>
@endsection