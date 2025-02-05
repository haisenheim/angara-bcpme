<?php

namespace App\Http\Controllers\Api\Agent;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProducteurResource;
use App\Models\Structuration\Exploitant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    //
    public function getProducteurs(){
        $page = request()->page;
        //$user_id = request()->user
        //dd()
        if(!$page){
            $page=1;
        }
        $offset = 100*($page-1);
          $posts = Exploitant::where('cooperative_id',Auth::guard('agent')->user()->cooperative_id)->skip($offset)
          ->take(100)
          ->get();
          return response()->json(ProducteurResource::collection($posts));
      }
}
