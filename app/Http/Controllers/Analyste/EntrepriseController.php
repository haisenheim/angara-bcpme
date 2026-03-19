<?php

namespace App\Http\Controllers\Analyste;

use App\Http\Controllers\Controller;
use App\Models\Banque;
use App\Models\Entreprise;
use App\Models\Instruction\Engagement;
use App\Models\Instruction\EngagementEntreprise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EntrepriseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $resp = Http::get('http://localhost:8080/entreprises');
        $items = json_decode($resp->body(),true);
        //dd($items);
        return view('/Analyste/Entreprises/index')->with(compact('items'));
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        return back();
    }



    private function parse($eng,$id){

        $data = [
            'id'=>$eng->id,
            'name'=>$eng->name,
            'montant'=>$eng->montant??0,
            'encours_montant'=>$eng->encours_montant??0,
            'encours_impaye'=>$eng->encours_impaye??0,
            'sollicite_montant'=>$eng->sollicite_montant??0,
            'variation'=>$eng->variation,
            'parent_id'=>$eng->parent_id,
            'is_title'=>$eng->is_title,
            'is_leaf'=>$eng->is_leaf,
            'niveau'=>$eng->niveau,
        ];
        if($data['is_leaf']){
            $elts = EngagementEntreprise::with('banque')->where('engagement_id',$eng->id)->where('entreprise_id',$id)->get();
            $data['encours_montant'] = $elts->reduce(function($carry,$item){
                return $carry + ($item->encours_montant ?? 0);
            },0);
            $data['sollicite_montant']= $elts->reduce(function($carry,$item){
                return $carry + ($item->sollicite_montant ?? 0);
            },0);
            $data['encours_impaye'] = $elts->reduce(function($carry,$item){
                return $carry + ($item->encours_impaye ?? 0);
            },0);
            $data['elts'] = $elts->map(function($elt){
                return [
                    'banque_name' => $elt->banque?->name ?? '—',
                    'encours_montant' => $elt->encours_montant ?? 0,
                    'encours_impaye' => $elt->encours_impaye ?? 0,
                    'encours_dt_validite' => $elt->encours_dt_validite ?? '—',
                    'sollicite_montant' => $elt->sollicite_montant ?? 0,
                    'sollicite_dt_validite' => $elt->sollicite_dt_validite ?? '—',
                ];
            })->values()->toArray();
            $data['variation'] = $data['sollicite_montant'] - $data['encours_montant'];

        }else{
            $data['children'] = $eng->children->map(function($child)use($id){
                return $this->parse($child,$id);
            });
            foreach($data['children'] as $child){
                $data['encours_montant'] += $child['encours_montant'];
                $data['sollicite_montant'] += $child['sollicite_montant'];
                $data['encours_impaye'] += $child['encours_impaye'];
                $data['variation'] += $child['variation'];
            }
        }
        return $data;
    }

    public function getEngagementReport($token){
        $entreprise = Entreprise::where('token',$token)->first();
        if($entreprise){
            $engagements = Engagement::where('parent_id',0)->get();
             $data = [];
             foreach($engagements as $eng){
                 $data[] = $this->parse($eng,$entreprise->id);
             }
             //dd($data);

            $engagements = $data;
            $banques = Banque::all();
            //$engagements = EngagementEntreprise::where('entreprise_id',$entreprise->id)->get();
            return view('Analyste.Companies.engagement_report',compact('engagements','entreprise','banques'));
        }else{
            return back();
        }

    }

    public function setEngagement(Request $request){
        $data = $request->all();
        EngagementEntreprise::updateOrCreate(
          [
            'banque_id'=>$data['banque_id'],
            'entreprise_id'=>$data['entreprise_id'],
            'engagement_id'=>$data['engagement_id'],
          ],
          $data
        );
        return back();
    }





    public function setAnalyse(){
        $data = request()->except('_token');
        //dd($data);
        $resp = Http::post('http://localhost:8080/entreprise/dossier/analyse',$data);
        return back();
    }

    public function  enable($id){

        return back();
    }

    public function  disable($id){

        return back();
    }




}
