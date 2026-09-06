@extends('layouts.app')

@push('styles')
    @vite(['resources/css/reset.css', 'resources/css/ai.css'])
@endpush

@section('title', 'ARX AI | ARX AI')

@section('meta_description', 'ARX AI')

@section('content')
    {{-- Section : Banner --}}
    <header class="text-center">
        <h1>
            {{ $ai_data['main_title'] }}
        </h1>
    </header>
    <section class="arxAi-content">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10 col-xl-8">
                    <div class="arx-ai-box">
                        <!-- Visuel ARX AI -->
                        <div class="arx-ai-visual text-center">
                            <img src="{{ asset('assets/img/arx_ai/arx_ai.svg') }}" alt="ARX AI"
                                class="img-fluid arx-ai-image">
                        </div>
                        <!-- Interaction avec ARX AI -->
                        <div class="arx-ai-interface">
                            <h2 class="text-center mb-4">
                                Interaction avec ARX AI
                            </h2>

                            <div class="arx-ai-chat mb-4">
                                <p>
                                    ARX AI est prêt
                                </p>
                            </div>
                            <form>

                                <div class="input-group">

                                    <input type="text" class="form-control" placeholder="Posez une question à ARX AI...">

                                    <button class="btn arx-ai-button" type="submit">
                                        Envoyer
                                    </button>

                                </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- Section : Banner END --}}
@endsection
