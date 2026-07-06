@extends('layouts.app')

@push('styles')
    @vite([
        'resources/css/reset',
        'resources/css/home',
    ])    
@endpush

@section('title', 'ARX | Plate-forme SOC')

@section('meta_description', 'Plate-forme de sécurisatiion du réseau domestique')

@section('content')

@endsection