@extends('admin.layouts.app')

@section('content')
    <h1 class="h3 mb-4">Новый продукт</h1>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.products.store') }}">
                @include('admin.products._form')
            </form>
        </div>
    </div>
@endsection
