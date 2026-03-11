<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function index(){

        return view('users.index',);
    }

    public function getUserData(){
        $users = User::select(['id','name','email','updated_at' ,'created_at']);

        return DataTables::of($users)
                ->addIndexColumn()
                ->make(true);
    }
}
