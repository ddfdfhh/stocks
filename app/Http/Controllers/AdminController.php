<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
   public function index(){
    $user=auth()->user();
    $user->assignRole('Admin');
    return view('admin.dashboard');
   }
   public function unauthorized(){
  
    return view('admin.unauthorized');
   }
}
