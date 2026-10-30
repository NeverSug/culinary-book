@extends('admin.layouts.app')

@section('content')
    <h1 class="h3 mb-4">Новый рецепт</h1>
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.recipes.store') }}">
                @include('admin.recipes._form')
            </form>
        </div>
    </div>
@endsection
