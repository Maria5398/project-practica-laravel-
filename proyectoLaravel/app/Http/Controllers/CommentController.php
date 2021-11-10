<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function save(Request $request){
        //validacion
        $validate = $this->validate($request,[
            'image_id' => 'integer|required',
            'content' => 'string|required'
        ]);
        //recoger datos del formulario
        $user = \Auth::user();
        $image_id = $request->input('image_id');
        $content = $request->input('content');

        //assigno nuevo valor a mi objeto aguardar
        $comment = new Comment();
        $comment->user_id = $user->id;
        $comment->image_id = $image_id;
        $comment->content = $content;

        //guardar en la db
        $comment->save();

        //redirect
        return redirect()->route('image.detail',['id' => $image_id])
                        ->with([
                            'message' => 'has publicado tu  comentario con exito'
                        ]);

    }

    public function delete($id){
        //conseguir datos del user loguado
        $user = \Auth::user();

        //conseguir objeto del comenttario
        $comment = Comment::find($id);

        //comprobar si soyy el dueño del comentario publicado

        if($user && ($comment->user_id == $user->id || $comment->image->user_id == $user->id)){
            $comment->delete();
            //redirect
            return redirect()->route('image.detail',['id' => $comment->image->id])
                            ->with([
                                'message' => 'has eliminado tu  comentario con exito'
                            ]);

        }else{
            //redirect
            return redirect()->route('image.detail',['id' => $comment->image->id])
                        ->with([
                            'message' => 'el comentario no se a eliminado'
                        ]);

        }
        
    }
}
