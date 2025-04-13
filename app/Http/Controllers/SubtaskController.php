<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subtask;
use App\Models\Task;

class SubtaskController extends Controller
{
    /**
     * Menampilkan subtasks berdasarkan task ID.
     *
     * @param int $taskId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($taskId)
    {
        $task = Task::with('subtasks')->find($taskId);

        if (!$task) {
            return response()->json(['success' => false, 'message' => 'Task tidak ditemukan'], 404);
        }

        return response()->json(['success' => true, 'subtasks' => $task->subtasks]);
    }

    /**
     * Menyimpan subtask baru.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
   
public function store(Request $request)
{
    $request->validate([
        'task_id' => 'required|exists:tasks,id',
        'title' => 'required|string|max:255'
    ]);

    $subtask = Subtask::create($request->only('task_id', 'title'));

    $task = $subtask->task;
    $progress = $task->progress(); // Hitung progress terbaru

    return response()->json([
        'success' => true,
        'subtask' => $subtask,
        'progress' => $progress,
    ]);
}

    /**
     * Memperbarui status subtask (toggle completed).
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
   
     
public function update(Request $request, $id)
{
    $request->validate([
        'title' => 'sometimes|string|max:255',
    ]);

    $subtask = Subtask::findOrFail($id);

    // Perbarui judul jika ada
    if ($request->has('title')) {
        $subtask->title = $request->title;
    }

    // Toggle status completed jika diminta
    if ($request->has('toggle_completed')) {
        $subtask->completed = !$subtask->completed;
    }

    $subtask->save();

    $task = $subtask->task;
    $progress = $task->progress(); // Hitung progress terbaru

    return response()->json([
        'success' => true,
        'progress' => $progress,
    ]);
}
    /**
     * Menghapus subtask.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    
    public function destroy($id)
    {
        $subtask = Subtask::findOrFail($id);
        $task = $subtask->task;
    
        $subtask->delete();
    
        $progress = $task->progress(); // Hitung progress terbaru
    
        return response()->json([
            'success' => true,
            'progress' => $progress,
        ]);
    }

    /**
     * Mendapatkan semua subtasks untuk task tertentu.
     *
     * @param \App\Models\Task $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSubtasks(Task $task)
    {
        return response()->json([
            'subtasks' => $task->subtasks
        ]);
    }
}