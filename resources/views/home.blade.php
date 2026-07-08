@extends('layouts.app')

@push('styles')
    @vite([
        'resources/css/reset.css',
        'resources/css/home.css',
    ])    
@endpush

@section('title', 'ARX | Plate-forme SOC')

@section('meta_description', 'Plate-forme de sécurisation du réseau domestique')

@section('content')
{{-- Section : Home --}}
<section class="home">
    <div class="circuit-container">
        @foreach($home_data['menu'] as $item)
            <a href="{{ route($item['route']) }}" class="menu-item {{ $item['class'] }}">
                {{ $item['name'] }}
            </a>
        @endforeach
    </div>
</section>
{{-- Section : Home END --}}
@endsection