<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\User;

class UserController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }

	public function index($search = null){
		if(!empty($search)){
			$users = User::where('nick','LIKE','%'.$search.'%')
							->orWhere('surname','LIKE','%'.$search.'%')
							->orderBy('id','desc')
							->paginate(5);
		}else{
		$users = User::orderBy('id','desc')->paginate(5);
		}
		return view('user.index',[
			'users' => $users
		]);
	}
	
    public function config(){
		return view('user.config');
	}
	public function update(Request $request){
		//user identificado
		$user = \Auth::user();
		$id = $user->id;

		//validacion de users
		$validate = $this->validate($request,[
			'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'nick' => ['required', 'string', 'max:255', 'unique:users,nick,'.$id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$id]
		]);


		//recoger datos del formulario 
		$name = $request->input('name');
		$surname = $request->input('surname');
		$nick = $request->input('nick');
		$email = $request->input('email');

		//asignas nuevos valores al objeto del user

		$user->name = $name;
		$user->surname = $surname;
		$user->nick = $nick;
		$user->email = $email;

		//subir imagen

		$image_path = $request->file('image_path');
		if($image_path){
			//poner nombre  unnico
			$image_path_name = time().$image_path->getClientOriginalName();
			
			//guardar en la capeta storaage
			storage::disk('users')->put($image_path_name, File::get($image_path));
			//setear el nommbre de la imagen
			$user->image= $image_path_name;
		}
		
		//ejecutar consulta y cambios de la db

		$user->update();

		return redirect()->route('config')
						 ->with(['message'=>'usuario actualizado correctamente']);

	}

	//mostraar  imagen
	public function getImage($filename){
		$file = storage::disk('users')->get($filename);
		return new Response($file,200);
	}
	public function profile($id){
		$user = User::find($id); 
		return view('user.profile',[
		'user' => $user
		]);

	}

}
