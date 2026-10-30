@extends('admin.layouts.app')

@section('content')
    <h1 class="h3 mb-4">Продукт: {{ $product->name }}</h1>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.products.update', $product) }}">
                @method('PUT')
                @include('admin.products._form')
            </form>
        </div>
    </div>
@endsection
