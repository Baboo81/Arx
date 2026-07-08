@extends('layouts.app')

@push('styles')
    @vite([
        'resources/css/reset.css',
        'resources/css/home.css',
    ])    
@endpush

@section('title', 'ARX | Plate-forme SOC')

@section('meta_description', 'Plate-forme de sécurisatiion du réseau domestique')

@section('content')
{{-- Section : Home --}}
<section class="home">
    <div class="container h-100">
        <div class="row">
            <div class="main-content">
                
            </div>
        </div>
    </div>
</section>
{{-- Section : Home END --}}
@endsection