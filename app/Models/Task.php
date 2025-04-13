<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'completed'];


    public function subtasks()
{
    return $this->hasMany(Subtask::class);
}

public function progress()
{
    $totalSubtasks = $this->subtasks()->count();
    if ($totalSubtasks === 0) {
        return 0; // Jika tidak ada subtask, progress adalah 0%
    }

    $completedSubtasks = $this->subtasks()->where('completed', true)->count();
    return ($completedSubtasks / $totalSubtasks) * 100; // Hitung persentase progress
}

}


