<?php

namespace App\Http\Controllers\Tenant\Payeur;

use App\Http\Controllers\Controller;
use App\Models\Structuration\Agent;
use App\Models\Structuration\AgentOperateur;
use App\Models\Structuration\Operateur;
use Illuminate\Http\Request;

class KpiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private function map($ds){

    }
    public function getAgentSolde()
    {
        $wallets = AgentOperateur::where('cooperative_id',auth()->user()->cooperative_id)->get();
        $grps = $wallets->groupBy(function($elt){
            return $elt->agent->name;
        });

        $grps = $grps->map(function($v,$k){
            return $v->groupBy(function($elt){
                return $elt->operateur->name;
            });
        });

        $operateurs = Operateur::all();
        $operateurs = $operateurs->pluck('name');
        $agents = Agent::where('cooperative_id',auth()->user()->cooperative_id)->get();
        $agents = $agents->pluck('name');
        $datasets = [];
        foreach($operateurs as $operateur){
            $datasets[$operateur] = [];
        }



        //dd($grps);

        /* //dd($grps);
        $operateurs = Operateur::all();
        $operateurs = $operateurs->pluck('name');
        $agents = Agent::where('cooperative_id',auth()->user()->cooperative_id)->get();
        $agents = $agents->pluck('name');
        $datasets = [];
        foreach($operateurs as $operateur){
            $datasets[] = [
                'label'=>$operateur,


            ];
        }
        $data=[];
        foreach($grps as $k=>$v){
            $data[$k] = [];
            foreach($v as $x=>$y){

                $s = $y->reduce(function($c,$i){
                    return $c + $i->montant;
                });
                $data[$k][$x] = $s;
                $data[]=[
                    'agent'=>$k,
                    'operateur'=>$x,
                    'total'=>$s,
                ];
            }
        }
       // dd($data);
       // $data = array_values($data);
        return response()->json($data); */
        //dd(array_values($grps));
        //$data=[];
        $labels = [];
        foreach($grps as $k=>$v){
            //$data[$k] = [];
            $labels[]=$k;
            foreach($v as $x=>$y){
                $s = $y->reduce(function($c,$i){
                    return $c + $i->montant;
                });
                $datasets[$x][] = $s;
                //$data[$k][$x] = $s;
            }
        }
        $colors = ["#fe7a00","#ffcc00"];
        $i=0;
        foreach($datasets as $k=>$v){
            $dts[] = [
                'label'=>$k,
                'backgroundColor'=>$colors[$i],
                'data'=>$v
            ];
            $i++;
        }

        return response()->json([
            'labels'=>$labels,
            'datasets'=>$dts
        ]);
    }





}
