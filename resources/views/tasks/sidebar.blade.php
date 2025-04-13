<div id="taskSidebar">
    <h5 id="taskTitle">Detail Tugas</h5>

    <!-- Form tambah subtask -->
    <form action="{{ route('subtasks.store', $task->id) }}" method="POST">
        @csrf
        <div class="input-group mb-2">
            <input type="text" name="title" class="form-control" placeholder="Tambah subtask..." required>
            <button type="submit" class="btn btn-primary">+</button>
        </div>
    </form>

    <!-- List subtasks -->
    <ul class="list-group">
        @foreach ($task->subtasks as $subtask)
            <li class="list-group-item d-flex align-items-center">
                <form action="{{ route('subtasks.update', $subtask->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="checkbox" class="form-check-input me-2"
                           onchange="this.form.submit()" {{ $subtask->completed ? 'checked' : '' }}>
                </form>
                <span class="{{ $subtask->completed ? 'text-decoration-line-through text-muted' : '' }}">
                    {{ $subtask->title }}
                </span>
                <form action="{{ route('subtasks.destroy', $subtask->id) }}" method="POST" class="ms-auto">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
                </form>
            </li>
        @endforeach
    </ul>

    <button class="btn btn-secondary mt-3" onclick="closeTaskDetail()">Tutup</button>
</div>
