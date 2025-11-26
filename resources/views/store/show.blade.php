@extends('layouts.app')

@section('title', ($store->store_name ?? $store->name) . ' - Toko - Ebrystoree')

@section('content')
<div class="min-h-screen">
    {{-- Store Banner --}}
    @include('store.partials.banner')
    
    {{-- Store Header with Stats --}}
    @include('store.partials.header')
    
    {{-- Navigation Tabs --}}
    @include('store.partials.tabs')
    
    <div class="container mx-auto px-3 sm:px-4 py-6">
        {{-- Tab Content --}}
        @if($activeTab === 'products')
            @include('store.partials.products')
        @elseif($activeTab === 'services')
            @include('store.partials.services')
        @elseif($activeTab === 'reviews')
            @include('store.partials.reviews')
        @elseif($activeTab === 'about')
            @include('store.partials.about')
        @endif
    </div>
</div>
@endsection


