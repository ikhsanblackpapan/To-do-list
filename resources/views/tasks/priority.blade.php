@extends('layouts.app')

@section('title', 'Prioritas')

@section('content')
    <h2>Halaman Prioritas</h2>

    @forelse ($tasks as $task)
        <div class="p-2 mb-2 bg-warning text-dark rounded shadow">
            {{ $task->title }}
        </div>
    @empty
        <p>Belum ada tugas prioritas.</p>
    @endforelse
@endsection
