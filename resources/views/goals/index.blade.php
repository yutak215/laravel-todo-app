<!-- 子ビューに個別のコードを書くときはまず、@extendsというディレクティブを使ってベースとなる親ビューを指定します。ファイル名の指定方法はコントローラで使ったview()ヘルパーと一緒
resources/viewsを省略し、フォルダ名.ファイル名（.blade.phpは不要）と記述します。今回の場合は@extends('layouts.app') -->
@extends('layouts.app')
 
 @section('content')
     <div class="container h-100"> 
         @if ($errors->any())
             <div class="alert alert-danger">
                 <ul>
                     @foreach ($errors->all() as $error)
                         <li>{{ $error }}</li>
                     @endforeach
                 </ul>
             </div>
         @endif
 
         <!-- 目標の追加用モーダル -->
         @include('modals.add_goal')  
 
         <div class="d-flex mb-3">
             <a href="#" class="link-dark text-decoration-none" data-bs-toggle="modal" data-bs-target="#addGoalModal">
                 <div class="d-flex align-items-center">
                     <span class="fs-5 fw-bold">＋</span>&nbsp;目標の追加
                 </div>
             </a> 
         </div>  
         
         <div class="row row-cols-1 row row-cols-md-2 row-cols-lg-3 g-4">                         
             @foreach ($goals as $goal) 
             
                 <!-- 目標の編集用モーダル -->
                 @include('modals.edit_goal') 
 
                 <!-- 目標の削除用モーダル -->
                 @include('modals.delete_goal')  

                 <!-- ToDoの追加用モーダル -->
                 @include('modals.add_todo') 
 
                 <div class="col">     
                     <div class="card bg-light">
                         <div class="card-body d-flex justify-content-between align-items-center">
                             <h4 class="card-title ms-1 mb-0">{{ $goal->title }}</h4>
                             <div class="d-flex align-items-center"> 
                                <a href="#" class="px-2 fs-5 fw-bold link-dark text-decoration-none" data-bs-toggle="modal" data-bs-target="#addTodoModal{{ $goal->id }}">＋</a>                                
                                <div class="dropdown">
                                     <a href="#" class="dropdown-toggle px-1 fs-5 fw-bold link-dark text-decoration-none menu-icon" id="dropdownGoalMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">︙</a>
                                     <ul class="dropdown-menu dropdown-menu-end text-center" aria-labelledby="dropdownGoalMenuLink">
                                         <li><a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editGoalModal{{ $goal->id }}">編集</a></li>                                   
                                         <div class="dropdown-divider"></div>
                                         <li><a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#deleteGoalModal{{ $goal->id }}">削除</a></li>                                                                                                      
                                     </ul>
                                 </div>
                             </div>
                         </div>
                        <!-- asc,昇順［false（未完了） → true（完了）の順」で並び替え -->
                        <!-- その目標におけるtodoを全てをcollectionクラスの配列として、未完了順から取得して、個別に表示させる、 -->
                         @foreach ($goal->todos()->orderBy('done', 'asc')->get() as $todo) 
                             <!-- ToDoの編集用モーダル -->
                             @include('modals.edit_todo')
 
                             <!-- ToDoの削除用モーダル -->
                             @include('modals.delete_todo')
 
                             <div class="card mx-2 mb-2">
                                 <div class="card-body">
                                     <div class="d-flex justify-content-between align-items-center mb-2">
                                        <!-- doneなら横線いれる -->
                                     <h5 class="card-title ms-1 mb-0">
                                             @if ($todo->done)
                                                 <s>{{ $todo->content }}</s>
                                             @else
                                                 {{ $todo->content }}
                                             @endif
                                         </h5>
                                         <div class="dropdown">
                                             <a href="#" class="dropdown-toggle px-1 fs-5 fw-bold link-dark text-decoration-none menu-icon" id="dropdownTodoMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">︙</a>
                                             <ul class="dropdown-menu dropdown-menu-end text-center" aria-labelledby="dropdownTodoMenuLink">     
                                             <li>
                                                     <form action="{{ route('goals.todos.update', [$goal, $todo]) }}" method="post">
                                                         @csrf
                                                         @method('patch')
                                                         <!-- input type="hidden"を使うことで、ブラウザ上には表示されない隠しデータ（今回の場合はorderパラメータとその値）を送信することができます。 -->
                                                         <!-- 「フォームに入力させる必要はないけど、この値を送信したい」という場合に便利 -->
                                                         <input type="hidden" name="content" value="{{ $todo->content }}">
                                                         @if ($todo->done)  
                                                             <input type="hidden" name="done" value="false">
                                                             <button type="submit" class="dropdown-item btn btn-link">未完了</button>
                                                         @else
                                                             <input type="hidden" name="done" value="true">
                                                             <button type="submit" class="dropdown-item btn btn-link">完了</button> 
                                                         @endif
                                                     </form>                                                       
                                                 </li>                                                                                                                                                                                  
                                                 <li><a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editTodoModal{{ $todo->id }}">編集</a></li>                                                  
                                                 <div class="dropdown-divider"></div>
                                                 <li><a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#deleteTodoModal{{ $todo->id }}">削除</a></li>  
                                             </ul>
                                         </div>
                                     </div>   
                                     <h6 class="card-subtitle ms-1 mb-1 text-muted">{{ $todo->created_at }}</h6>                                                               
                                 </div>                                
                             </div>
                         @endforeach
                     </div>                           
                 </div>
             @endforeach                     
         </div>
     </div>
     @endsection