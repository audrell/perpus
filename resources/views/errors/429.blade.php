@extends('errors.layout')

@section('code', '429')
@section('title', 'Too Many Requests')
@section('cause', 'Terlalu banyak request.')

@section('actions')
	<button onclick="location.reload()" class="btn btn-primary btn-soft mr-2 mb-2 border-0">
		<i class="fas fa-sync-alt mr-1"></i> Coba Lagi
	</button>
	<button onclick="history.back()" class="btn btn-light btn-soft mb-2 border">
		<i class="fas fa-arrow-left mr-1"></i> Kembali
	</button>
@endsection