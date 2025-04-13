@extends('layouts.app')

@section('title', 'To-Do List')

@section('content')
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<div class="container">
    <h2 class="text-center mb-4">To-Do List</h2>

    <!-- Form Tambah Tugas -->
    <form action="{{ route('tasks.store') }}" method="POST" class="mb-3">
        @csrf
        <div class="input-group">
            <input type="text" name="title" class="form-control" placeholder="Tambah tugas baru" required>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Tambah
            </button>
        </div>
    </form>

    <!-- List Tugas yang Belum Selesai -->
    <h5 class="mt-4">Tugas</h5>
@forelse ($tasks->where('completed', false) as $task)
<div id="taskkuning-{{ $task->id }}" class="task-item d-flex flex-column p-3 bg-warning rounded mb-3 shadow"
     onclick="openTaskDetail('{{ $task->id }}', '{{ $task->title }}')"
     oncontextmenu="showContextMenu(event, '{{ $task->id }}')">
    <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="toggle_completed" value="{{ $task->id }}">
                <input type="checkbox" class="form-check-input me-3" onchange="this.form.submit()" onclick="event.stopPropagation()">
            </form>
            <strong>{{ $task->title }}</strong>
            @if($task->priority)
                <i class="bi bi-star-fill text-primary ms-2"></i>
            @endif
        </div>
        <div>
            <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-primary btn-sm me-1" onclick="event.stopPropagation()">
                <i class="bi bi-pencil"></i>
            </a>
            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm" onclick="event.stopPropagation()">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Subtask List -->
    <ul class="subtask-list mt-3 ps-3">
    @foreach ($task->subtasks as $subtask)
    <li class="d-flex align-items-center mb-2">
        <input type="checkbox" class="form-check-input me-2" 
               {{ $subtask->completed ? 'checked' : '' }} 
               onchange="toggleSubtask({{ $subtask->id }}, {{ $task->id }})" 
               onclick="event.stopPropagation()">
        <span class="flex-grow-1">{{ $subtask->title }}</span>
        <button class="btn btn-sm btn-purple me-2" onclick="editSubtask(event, {{ $subtask->id }}, '{{ $subtask->title }}', {{ $task->id }})">
            <i class="bi bi-pencil"></i>
        </button>
        <button class="btn btn-sm btn-pink" onclick="deleteSubtask(event, {{ $subtask->id }}, {{ $task->id }})">
            <i class="bi bi-trash"></i>
        </button>
    </li>
    @endforeach
</ul>

    <!-- Progress Bar -->
@if ($task->subtasks->count() > 0)
    @php
        $progress = $task->progress();
    @endphp
    <div class="mt-3">
        <div class="progress">
            <div class="progress-bar bg-success" role="progressbar" 
                 style="width: {{ $progress }}%;" 
                 aria-valuenow="{{ $progress }}" 
                 aria-valuemin="0" 
                 aria-valuemax="100">
                {{ round($progress) }}%
            </div>
        </div>
    </div>
@endif
</div>
@empty
<p class="text-muted">Tidak ada tugas.</p>
@endforelse
    <!-- List Tugas yang Sudah Selesai -->
    <h5 class="mt-4">Completed</h5>
    @forelse ($tasks->where('completed', true) as $task)
        <div class="task-item d-flex align-items-center justify-content-between p-2 bg-success text-white rounded mb-2 shadow">
            <div class="d-flex align-items-center flex-grow-1">
                <form action="{{ route('tasks.update', $task->id) }}" method="POST" class="m-0 me-2">
                    @csrf
                    @method('PATCH')
                    <input type="checkbox" class="form-check-input" checked onchange="this.form.submit()">
                </form>
                <span>{{ $task->title }}</span>
            </div>
            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="m-0">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
        </div>
    @empty
        <p class="text-muted">Belum ada tugas yang diselesaikan.</p>
    @endforelse
</div>

<!-- Sidebar Task -->
<div id="taskSidebar" class="position-fixed top-0 end-0 h-100 bg-dark text-white p-3 shadow-lg"
     style="width: 350px; transform: translateX(100%); transition: 0.3s;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 id="taskTitle">Detail Tugas</h5>
        <button class="btn btn-light btn-sm" onclick="closeTaskDetail()">✖</button>
    </div>
    <ul id="subtaskList" class="subtask-list mt-2"></ul>

    <!-- Form Tambah Subtask -->
    <form id="subtaskForm" action="{{ route('subtasks.store') }}" method="POST" onsubmit="createSubtask(event)">
    @csrf
    <input type="hidden" name="task_id" id="sidebarTaskId">
    <div class="input-group mt-3">
        <input type="text" name="title" id="subtaskTitle" class="form-control" placeholder="Tambah subtask baru" required>
        <button type="submit" class="btn btn-primary">Tambah</button>
    </div>
</form>
</div>

<!-- Custom Context Menu -->
<div id="contextMenu" class="context-menu bg-white shadow rounded position-absolute p-2 d-none">
    <button class="dropdown-item" onclick="markAsImportant()"> 
        <i class="bi bi-star-fill"></i> Tambahkan Prioritas 
    </button>
</div>

<style>
    #taskSidebar {
        position: fixed;
        right: 0;
        top: 0;
        width: 300px;
        height: 100vh;
        background: white;
        box-shadow: -2px 0 10px rgba(0, 0, 0, 0.1);
        transform: translateX(100%);
        transition: transform 0.3s ease-in-out;
        z-index: 1050;
    }
    #taskkuning:hover {
        background-color: #d39e00 !important;
        cursor: pointer;
    }

    .btn-purple {
    background-color: #6f42c1; /* Warna ungu */
    color: white;
    border: none;
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem; /* Ukuran kecil */
    border-radius: 0.2rem;
}

.btn-purple:hover {
    background-color: #5a379e; /* Warna ungu lebih gelap saat hover */
}

.btn-pink {
    background-color: #e83e8c; /* Warna pink */
    color: white;
    border: none;
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem; /* Ukuran kecil */
    border-radius: 0.2rem;
}

.btn-pink:hover {
    background-color: #c73275; /* Warna pink lebih gelap saat hover */
}

.btn-purple:focus,
.btn-purple:active {
    background-color: #6f42c1; /* Warna ungu */
    color: white;
    outline: none; /* Hilangkan border fokus default */
    box-shadow: 0 0 0 0.2rem rgba(111, 66, 193, 0.25); /* Tambahkan efek fokus */
}

.btn-pink:focus,
.btn-pink:active {
    background-color: #e83e8c; /* Warna pink */
    color: white;
    outline: none; /* Hilangkan border fokus default */
    box-shadow: 0 0 0 0.2rem rgba(232, 62, 140, 0.25); /* Tambahkan efek fokus */
}
</style>

<script>

function editSubtask(event, subtaskId, currentTitle, taskId) {
    event.stopPropagation(); // Mencegah event bubbling

    const newTitle = prompt("Edit Subtask", currentTitle);
    if (newTitle && newTitle.trim() !== "") {
        fetch(`/subtasks/${subtaskId}`, {
            method: "PATCH",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ title: newTitle }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload halaman untuk memperbarui tampilan
                location.reload();
            }
        })
        .catch(error => console.error("Error editing subtask:", error));
    } else {
        // Reload halaman jika prompt dibatalkan
        location.reload();
    }
}

function deleteSubtask(event, subtaskId, taskId) {
    event.stopPropagation(); // Mencegah event bubbling

    if (confirm("Apakah Anda yakin ingin menghapus subtask ini?")) {
        fetch(`/subtasks/${subtaskId}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload halaman untuk memperbarui tampilan
                location.reload();
            }
        })
        .catch(error => console.error("Error deleting subtask:", error));
    } else {
        // Reload halaman jika konfirmasi dibatalkan
        location.reload();
    }
}

 function openTaskDetail(taskId, taskTitle) {
    const sidebar = document.getElementById("taskSidebar");
    document.getElementById("taskTitle").textContent = taskTitle;
    document.getElementById("sidebarTaskId").value = taskId;

    const subtaskList = document.getElementById("subtaskList");
    subtaskList.innerHTML = "";

    fetch(`/api/subtasks/${taskId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const taskKuning = document.getElementById(`taskkuning-${taskId}`);
                const taskSubtaskList = taskKuning.querySelector(".subtask-list");

                // Kosongkan daftar subtask di task kuning
                taskSubtaskList.innerHTML = "";

                // Tambahkan subtasks ke sidebar dan task kuning
                data.subtasks.forEach(subtask => {
                    // Tambahkan ke sidebar
                    const sidebarItem = document.createElement("li");
                    sidebarItem.className = "list-group-item bg-dark text-white mt-2";
                    sidebarItem.textContent = subtask.title;
                    subtaskList.appendChild(sidebarItem);

                    // Tambahkan ke task kuning
                    const taskItem = document.createElement("li");
                    taskItem.className = "d-flex align-items-center";
                    taskItem.innerHTML = `
                        <input type="checkbox" class="form-check-input me-2" ${subtask.completed ? "checked" : ""} onchange="toggleSubtask(${subtask.id}, ${taskId})">
                        <span>${subtask.title}</span>
                    `;
                    taskSubtaskList.appendChild(taskItem);
                });
            }
        })
        .catch(error => console.error("Error fetching subtasks:", error));

    sidebar.style.transform = "translateX(0)";
    
}

    function closeTaskDetail() {
        document.getElementById("taskSidebar").style.transform = "translateX(100%)";
        location.reload(); // Reload halaman untuk memperbarui tampilan
    }

    function toggleSubtask(subtaskId, taskId) {
    fetch(`/subtasks/${subtaskId}`, {
        method: "PATCH",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ toggle_completed: true }), // Kirim permintaan untuk toggle status
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Perbarui progress bar
            const taskKuning = document.getElementById(`taskkuning-${taskId}`);
            const progressBar = taskKuning.querySelector(".progress-bar");
            if (progressBar) {
                progressBar.style.width = `${data.progress}%`;
                progressBar.textContent = `${Math.round(data.progress)}%`;
            }
        }
    })
    .catch(error => console.error("Error toggling subtask:", error));
}

    function showContextMenu(event, taskId) {
        event.preventDefault();
        const menu = document.getElementById("contextMenu");
        menu.style.top = `${event.clientY}px`;
        menu.style.left = `${event.clientX}px`;
        menu.classList.remove("d-none");
        menu.dataset.taskId = taskId;
    }

    document.addEventListener("click", function () {
        document.getElementById("contextMenu").classList.add("d-none");
    });

    function createSubtask(event) {
    event.preventDefault(); // Mencegah form submit default

    const taskId = document.getElementById("sidebarTaskId").value;
    const title = document.getElementById("subtaskTitle").value;

    fetch("{{ route('subtasks.store') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ task_id: taskId, title: title }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {

            location.reload();
            // Tambahkan subtask ke task kuning
            const taskKuning = document.getElementById(`taskkuning-${taskId}`);
            const subtaskList = taskKuning.querySelector(".subtask-list");

            const subtaskItem = document.createElement("li");
            subtaskItem.className = "d-flex align-items-center";
            subtaskItem.innerHTML = `
                <input type="checkbox" class="form-check-input me-2" onchange="toggleSubtask(${data.subtask.id}, ${taskId})">
                <span>${data.subtask.title}</span>
            `;
            subtaskList.appendChild(subtaskItem);

            // Kosongkan input subtask
            document.getElementById("subtaskTitle").value = "";

            // Perbarui progress bar jika ada
            const progressBar = taskKuning.querySelector(".progress-bar");
            if (progressBar) {
                progressBar.style.width = `${data.progress}%`;
                progressBar.textContent = `${Math.round(data.progress)}%`;
            }
        }
    })
    .catch(error => console.error("Error creating subtask:", error));
}
    function markAsImportant() {
        const taskId = document.getElementById("contextMenu").dataset.taskId;
        fetch(`/tasks/${taskId}/priority`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                "Content-Type": "application/json",
            },
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert("Berhasil ditambahkan ke prioritas!");
                location.reload();
            }
        })
        .catch(err => console.error(err));
    }
</script>
@endsection