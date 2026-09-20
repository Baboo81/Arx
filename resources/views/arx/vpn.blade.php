@extends('layouts.app')

@push('styles')
    @vite(['resources/css/reset.css', 'resources/css/arx_server.css'])
@endpush

@section('title', 'ARX VPN | ARX VPN')

@section('meta_description', 'ARX VPN')

@section('content')

    {{-- Section : Banner --}}
    <header class="text-center py-8">
        <h1>
            {{ $vpn_data['main_title'] }}
        </h1>
    </header>

    <section class="arxAi-content">

        <div class="max-w-5xl mx-auto px-4 py-12">

            <div class="arx-ai-box">

                {{-- Visuel ARX SERVER --}}
                <div class="arx-ai-visual text-center">

                    <img src="{{ asset('assets/img/arx_vpn/arx_vpn.svg') }}" alt="ARX VPN" class="arx-ai-image mx-auto">

                </div>

                {{-- Interaction avec ARX AI --}}
                <div class="arx-ai-interface">

                    <h2 class="text-center mb-6">
                    
                    </h2>

                    <div class="arx-ai-chat mb-6">
                        <p>
                           
                        </p>
                    </div>

                    <form>

                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">

                            <div class="flex-1">

                                <x-arx-input type="text" name="search" placeholder="Rechercher" />

                            </div>

                            <div class="shrink-0">

                                <x-arx-button href="{{ route('arx.ai') }}">
                                    ARX AI
                                </x-arx-button>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </section>

    {{-- Section : Banner END --}}

@endsection
