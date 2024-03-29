<?php

namespace App\Http\Controllers\Admin;
session_start();
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

use App\Models\User;

use Illuminate\Support\Facades\Hash;



class HomeController extends Controller
{
    //admin panel home page
    public function index(){
        return view('admin.index');
    }
    //admin panel login page
    public function login(){
        return view('admin.login');
    }
    //admin register page
     public function getUserAddPage(){
        $roles = Role::all();
        return view('admin.users.user_add', compact('roles'));
    } 
    //add users
    public function getUserAdd(Request $request){
        //Validation user form
        $request->validate([
            'name'=>'required | min:3 | max:20',
            'surname'=>'required | min:5 | max:25',
            'email'=>'required | email',
            'password'=>'required | min:8',
            //'roLId'=>'required'
        ]);

        try{
            $name = $request->name;
            $surname = $request->surname;
            $email = $request->email;
            $password = $request->password;
            //$roLId = $request->roLId;
            $userStatus = $request->user_status;

            $user = new User();
            $user->name = $name;
            $user->surname = $surname;
            $user->email = $email;
            $user->password = Hash::make($password);
            //$user->roLId = $roLId;
            $user->user_status = $userStatus == 'on' ? 'on' : 'off';
            $user->save();
            $user->syncRoles($request->role);
            

            Toastr::success('İstifadəçi uqurla əlavə olundu', 'Uqurlu');
            return redirect()->back();
        }catch(\Exception $e){
            Toastr::error('İstifadəçi əlavə olunarkən xəta baş verdi. ' . $e->getMessage());
            return redirect()->back();
        }  
    }
    //list users
    public function userList(){
        $userLists = User::with('roles')->get();
        return view('admin.users.user_list', compact('userLists'));
    }
    //user edit
    public function userEdit($id){
        $roles = Role::all();
        $user = User::find($id) ?? abort(404, 'Belə bir istifadəçi mövcud deyil');
        return view('admin.users.user-edit', compact('user', 'roles'));
    }
    //user update
    public function userUpdate(Request $request, $id){
        //Validation user form
        
        $validation = [
            'name'=>'required | min:3 | max:20',
            'surname'=>'required | min:5 | max:25',
            'email'=>'required | email',
            //'password'=>'required | min:8',
            //'roLId'=>'required'
        ];
        
        
        if(!empty($request->password))
            $validation['password'] = 'required min:8';

        $request->validate($validation);


        try{
            $name = $request->name;
            $surname = $request->surname;
            $email = $request->email;
            //$roLId = $request->roLId;
            $userStatus = $request->user_status;
        

            $user = User::find($id);
            $user->name = $name;
            $user->surname = $surname;
            $user->email = $email;
            
            if(!empty($request->password))
                $user->password = $request->password;

            //$user->roLId = $roLId;
            $user->user_status = $userStatus == 'on' ? 'on' : 'off';
            $user->update();
            $user->syncRoles($request->role);
            //$user->roles()->attach($roLId);
            Toastr::success('İstifadəçi uğurla yeniləndi', 'Uqurlu');
            return redirect()->back();
        }catch(\Exception $e){
            Toastr::error('İstifadəçi yenilənərkən xəta baş verdi .'.$e->getMessage().'');
            return redirect()->back();
        }
        
        
    }
    //delete users
    public function userDelete(Request $request){
        try{
            $user_id = $request->data;
            $user = User::findOrFail($user_id);
            $deleted = $user->delete();

        return response()->json([
            'status'=>'success',
            'message'=>'İstifadəçi uğurla silindi',
        ]);
        }catch(\Exception $e){
            return response()->json([
                'status'=>'error',
                'message'=>'İstifadəçi silinərkən xəta baş verdi',
            ]);
        }
        
    }
    
     
}
