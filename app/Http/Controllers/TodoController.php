<?php

namespace App\Http\Controllers;

use App\Mail\TodoShared;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Todo::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $todo = new Todo($request->all());

        $todo->save();

        return $todo;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function show(Todo $todo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Todo $todo)
    {
        $todo->fill($request->all());

        return $todo->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Todo $todo)
    {
        return $todo->delete();
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function restore(int $id)
    {
        $todo = Todo::withTrashed()->findOrFail($id);

        $todo->restore();

        return $todo;
    }

    /**
     * Force remove the specified resource from storage.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(int $id)
    {
        $todo = Todo::withTrashed()->findOrFail($id);

        $todo->forceDelete();

        return $todo;
    }

    /**
     * Force remove the specified resource from storage.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function share(Request $request, Todo $todo)
    {
        $request->validate([
            'shared_emails' => ['nullable', 'string']
        ]);

        $todo->fill($request->all());

        $todo->save();

        if (!empty($todo->shared_emails)) {
            foreach (explode(',', $todo->shared_emails) as $recipient) {
                $recipient = trim($recipient);

                if (!empty($recipient)) {
                    Mail::to($recipient)->send(new TodoShared($todo));
                }
            }
        }

        return $todo;
    }
}
