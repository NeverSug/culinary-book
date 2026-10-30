@extends('admin.layouts.app')

@section('content')
    <h1 class="h3 mb-4">Новая категория</h1>
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.categories.store') }}">
                @include('admin.categories._form')
            </form>
        </div>
    </div>
@endsection
