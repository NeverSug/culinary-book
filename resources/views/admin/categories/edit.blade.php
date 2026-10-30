@extends('admin.layouts.app')

@section('content')
    <h1 class="h3 mb-4">Категория: {{ $category->name }}</h1>
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.categories.update', $category) }}">
                @method('PUT')
                @include('admin.categories._form')
            </form>
        </div>
    </div>
@endsection
