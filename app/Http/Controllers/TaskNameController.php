<?php

namespace App\Http\Controllers;

use App\Models\TaskName;
use Illuminate\Http\Request;

class TaskNameController extends Controller
{

    function __construct(){
        $this->middleware('permission:task_names|create task_names|edit task_names|delete task_names', ['only' => ['index','show']]);
        $this->middleware('permission:create task_names', ['only' => ['create','store']]);
        $this->middleware('permission:edit task_names', ['only' => ['edit','update']]);
        $this->middleware('permission:delete task_names', ['only' => ['destroy']]);
    }
    
    public function index(Request $request)
    {
        $data = TaskName::orderBy('order', 'asc');
        if($request->name != null){
            $search = $request->name;
            $data = $data->where('name', 'like', '%' . $search . '%');
        }
        $data = $data->paginate(20);
        return view('task_name.index', compact('data'));
    }

    public function create()
    {
        $maxOrder = TaskName::max('order');
        $nextOrder = $maxOrder ? $maxOrder + 1 : 1;
        return view('task_name.create', compact('nextOrder'));
    }

    public function store(Request $request)
    {
        $task = TaskName::create($request->validate([
            'name' => 'required|string',
            'order' => 'required|numeric',
            'allow_next_step' => 'boolean',
        ]));
        return redirect()->back()->with('success', 'Task Name Created Successfully');
    }

    public function edit($id)
    {
        $data = TaskName::find($id);
        return view('task_name.edit', compact('data'));
    }

    public function update(Request $request, TaskName $taskName)
    {
        $taskName->update($request->validate([
            'name' => 'string',
            'order' => 'numeric',
            'allow_next_step' => 'boolean',
        ]));
        return redirect()->back()->with('success', 'Task Name Updated Successfully');
    }

    public function destroy(TaskName $taskName)
    {
        $taskName->delete();
        $tasks = TaskName::orderBy('order')->get();
        $tasks->each(function ($task, $index) {
            $task->order = $index + 1;
            $task->save();
        });
        return redirect()->back()->with('success', 'Task Name Deleted Successfully');
    }

    public function reorder(Request $request)
    {
        foreach ($request->order as $item) {
            TaskName::where('id', $item['id'])->update(['order' => $item['order']]);
        }

        return response()->json(['status' => 'success']);
    }
}
