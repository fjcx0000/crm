<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Redirect;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Task;
use App\Repositories\TaskRepository;

class TaskController extends Controller
{
    /**
     * The task respository instance.
     *
     * @var TaskRespository
     */
    protected $tasks;

    /**
     * Create a new controller instance
     *
     * @param TaskRespository $tasks
     * @return void
     */
    public function __construct(TaskRepository $tasks)
    {
        $this->middleware('web');
        $this->middleware('auth');
        /*
        if (Auth::check() == false) {
            return Redirect::guest('login');
        }
         */
        $this->tasks = $tasks;
    }

    /**
     * Display a list of all of the user's task.
     *
     * @param Reuqest $request
     * @return Response
     */
    public function index(Request $request)
    {
       // $tasks = Task::where('user_id', $request->user()->id)->get();

        return view('tasks', [
            'tasks' => $this->tasks->forUser($request->user()),
        ]);
    }

    /**
     * Create a new task.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
        ]);

        //Create The Task ...
        $request->user()->tasks()->create([
            'name' => $request->name,
        ]);
        return redirect('/tasks');
    }

    /**
     * Destroy the given task.
     *
     * @param Request $request
     * @param Task $task
     * @return Response
     */
    public function destroy(Request $request, Task $task)
    {
        $this->authorize('destroy', $task);
        //Delte the Task ...
        $task->delete();
        return redirect('/tasks');
    }
}
