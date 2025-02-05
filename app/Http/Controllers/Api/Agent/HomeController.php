<?php

namespace App\Http\Controllers\Api\Agent;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProducteurResource;
use App\Models\Structuration\Cooperative;
use App\Models\Structuration\Exploitant;
use App\Models\Village;
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

      public function getProducteur($id){
        $item = Exploitant::find($id);
        return response()->json(new ProducteurResource($item));
      }

      public function getVillages(){
        $coop = Cooperative::find(Auth::guard('agent')->user()->cooperative_id);
        $villages = Village::select('id','name')->where('arrondissement_id',$coop->arrondissement_id)->get();
        return response()->json($villages);
      }
}
