@extends('layouts.app')

@section('title', 'Tugas Selesai')

@section('content')
    <h2>Halaman Tugas Selesai</h2>
    <p>Daftar tugas yang sudah selesai akan ditampilkan di sini.</p>

    <h5 class="mt-4">Completed</h5>
    @forelse ($tasks as $task)
        <div class="task-item d-flex align-items-center justify-content-between p-2 bg-success text-white rounded mb-2 shadow">
            <div class="d-flex align-items-center flex-grow-1">
                <form action="{{ route('tasks.update', $task->id) }}" method="POST" class="m-0 me-2">
                    @csrf
                    @method('PATCH')
                    <input type="checkbox" class="form-check-input" checked onchange="this.form.submit()">
                </form>
                <span>{{ $task->title }}</span>
            </div>
           
        </div>
    @empty
        <p class="text-muted">Belum ada tugas yang diselesaikan.</p>
    @endforelse
@endsection
