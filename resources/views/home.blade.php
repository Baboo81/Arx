@extends('layouts.app')

@push('styles')
    @vite(['resources/css/reset.css', 'resources/css/home.css'])
@endpush

@section('title', 'ARX | ARX Core')

@section('meta_description', 'Plate-forme de sécurisation du réseau domestique')

@section('content')
    {{-- Section : Home --}}
    <header class="d-flex flex-column align-items-center justify-content-center text-center my-5">
        <h1 class="mb-0">
            {{ $home_data['main_title'] }}
        </h1>
        <h2 class="my-4">
            {{ $home_data['sub_title'] }}
        </h2>
    </header>
    <section class="home d-flex flex-column align-items-center justify-content-center text-center">
        <nav class="circuit-container" aria-label="Navigation principale ARX">
            @foreach ($home_data['menu'] as $item)
                <a href="{{ route($item['route']) }}" class="menu-item {{ $item['class'] }}">
                    {{ $item['name'] }}
                </a>
            @endforeach
        </nav>
    </section>
    {{-- Section : Home END --}}
@endsection
