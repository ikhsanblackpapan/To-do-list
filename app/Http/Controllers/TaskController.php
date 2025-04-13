<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all();
        return view('tasks.index', compact('tasks'));
    }

    public function markAsPriority(Task $task)
{
    $task->priority = true;
    $task->save();

    return response()->json(['success' => true, 'message' => 'Task ditandai sebagai prioritas']);
}




    public function store(Request $request)
    {
        Task::create($request->validate(['title' => 'required|string']));
        return redirect('/');
    }

    public function update(Request $request, Task $task)
{

    if ($request->has('toggle_completed')) {
        $task->completed = !$task->completed;
        $task->save();
        return redirect()->back()->with('success', 'Tugas diperbarui.');
    }

    $task->update($request->all());
    return redirect()->route('tasks.index')->with('success', 'Tugas diperbarui.');


    // Jika request memiliki 'title', berarti berasal dari halaman edit
    if ($request->has('title')) {
        $request->validate([
            'title' => 'required|string|max:255',
            'completed' => 'required|boolean',
        ]);

        $task->update([
            'title' => $request->title,
            'completed' => (bool) $request->completed,
        ]);

        return redirect()->route('tasks.index')->with('success', 'Tugas berhasil diperbarui.');
    }

    // Jika request tidak memiliki 'title', berarti berasal dari checkbox
    // $task->update([
    //     'completed' => !$task->completed
    // ]);

    // // Redirect sesuai dengan parameter 'redirect'
    // return $request->has('redirect') && $request->redirect === 'home'
    //     ? redirect('/')
    //     : redirect()->back();
}


    public function edit(Task $task)
{
    return view('tasks.edit', compact('task'));
}


    public function destroy(Task $task)
    {
        
        $task->delete();
        return redirect('/');
    }

    public function priority()
{
    $tasks = Task::where('priority', true)->get();
    return view('tasks.priority', compact('tasks'));
}

   public function completed()
    {
        $tasks = Task::where('completed', true)->get();
        return view('tasks.completed', compact('tasks'));
    }
    

    public function show(Task $task)
{
    return view('tasks.sidebar', compact('task'));
}

}