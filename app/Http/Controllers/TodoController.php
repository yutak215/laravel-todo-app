<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Models\Goal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Goal $goal) {
        $request->validate([
            'content' => 'required',
        ]);

        $todo = new Todo();
        $todo->content = $request->input('content');
        $todo->user_id = Auth::id();
        $todo->goal_id = $goal->id;
        $todo->done = false;
        $todo->save();        

        return redirect()->route('goals.index');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Goal $goal, Todo $todo) {
        $request->validate([
            'content' => 'required',
        ]);

        $todo->content = $request->input('content');
        $todo->user_id = Auth::id();
        $todo->goal_id = $goal->id;
        // input()メソッドやboolean()メソッドでは、第2引数を渡すことで第1引数に指定したフォームの入力値が存在しない場合の初期値を設定できる
        // つまりユーザーが完了かどうかを編集しなくても、以前の値を代入する
        $todo->done = $request->boolean('done', $todo->done);
        $todo->save();

        return redirect()->route('goals.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Goal $goal, Todo $todo) {
        $todo->delete();
 
        return redirect()->route('goals.index');
    }
}
