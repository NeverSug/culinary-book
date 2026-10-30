@extends('layouts.app')
@section('title', 'Список покупок — Кулинарная книга')
@section('content')
    <div class="container">
        <h1 class="mb-4">Список покупок</h1>

        @if (empty($items))
            <p class="text-muted">
                Список пуст — запланируйте рецепты на неделю в
                <a href="{{ route('plan.index') }}">расписании</a>.
            </p>
        @else
            <table class="table table-striped align-middle">
                <thead>
                <tr>
                    <th>Продукт</th>
                    <th>Сколько нужно</th>
                    <th>Дни</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['amount'] }} {{ $item['unit'] }}</td>
                        <td class="text-muted">
                            @foreach ($item['days'] as $dayNum)
                                {{ \App\Models\MealPlan::DAYS[$dayNum] }}@if (!$loop->last),&nbsp;@endif
                            @endforeach
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif

        <a href="{{ route('plan.index') }}" class="btn btn-outline-secondary">← Назад к плану</a>
    </div>
@endsection
