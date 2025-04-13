@extends('layouts.app')

@section('title', 'Edit Tugas')

@section('content')
    <h2 class="text-center mb-4">Edit Tugas</h2>

    <form action="{{ route('tasks.update', $task->id) }}" method="POST" class="card p-3 shadow">
    @csrf
    @method('PATCH')  {{-- Ubah PUT ke PATCH --}}

    <div class="mb-3">
        <label for="title" class="form-label">Judul Tugas</label>
        <input type="text" name="title" id="title" class="form-control" value="{{ $task->title }}" required>
    </div>

    <div class="mb-3">
        <label for="completed" class="form-label">Status</label>
        <select name="completed" id="completed" class="form-select">
            <option value="0" {{ !$task->completed ? 'selected' : '' }}>Belum Selesai</option>
            <option value="1" {{ $task->completed ? 'selected' : '' }}>Selesai</option>
        </select>
    </div>

    <div class="d-flex justify-content-between">
        <a href="{{ route('tasks.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <button type="submit" class="btn btn-success">
            <i class="bi bi-save"></i> Simpan Perubahan
        </button>
    </div>
</form>

@endsection
