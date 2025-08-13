<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;

class ImportController extends Controller
{
    public function tasks(Request $request)
    {
        $request->validate(['file' => ['required','file']]);
        $file = $request->file('file');
        $count = 0;
        if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
            $header = fgetcsv($handle, 0, ',');
            while (($row = fgetcsv($handle, 0, ',')) !== false) {
                $data = array_combine($header, $row);
                Task::create([
                    'project_id' => (int)($data['project_id'] ?? 1),
                    'title' => $data['title'] ?? 'Untitled',
                    'description' => $data['description'] ?? null,
                    'status' => $data['status'] ?? 'todo',
                    'priority' => $data['priority'] ?? 'med',
                    'due_at' => $data['due_at'] ?? null,
                    'start_at' => $data['start_at'] ?? null,
                    'assignee_id' => $data['assignee_id'] ?? null,
                    'tags' => isset($data['tags']) ? array_filter(array_map('trim', explode('|', $data['tags']))) : null,
                    'reminders' => isset($data['reminders']) ? array_filter(array_map('trim', explode('|', $data['reminders']))) : null,
                    'is_recurring' => (bool)($data['is_recurring'] ?? false),
                    'recurrence_rule' => $data['recurrence_rule'] ?? null,
                ]);
                $count++;
            }
            fclose($handle);
        }
        return response()->json(['imported' => $count]);
    }
}