<?php

namespace App\Models;

use App\Helpers\DossierHelper;
use App\Models\Instruction\Reponse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dossier extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function entreprise(){
        return $this->belongsTo('App\Models\Entreprise');
    }

    public function programme(){
        return $this->belongsTo('App\Models\Programme');
    }

    public function gestionnaire(){
        return $this->belongsTo('App\Models\User','gestionnaire_id');
    }

    public function analyste(){
        return $this->belongsTo('App\Models\User','analyste_id');
    }

    public function agence(){
        return $this->belongsTo('App\Models\Agence','agence_id');
    }

    public function representation(){
        return $this->belongsTo('App\Models\Representation','represenantion_id');
    }

    public function indicateurs(){
        return $this->hasMany('App\Models\Instruction\IndicateurFinancier','dossier_id');
    }



    public function reponses()
    {
        return $this->hasMany(Reponse::class);
    }

    // Accessor pour 'name'
    public function getNameAttribute()
    {
        return $this->entreprise->name . ' ' . $this->programme->name;
    }

    // Accessor pour 'variations'
    public function getVariationsAttribute()
    {
        try {
            return DossierHelper::getVariations($this);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getStatusAttribute()
    {
        $indicateurs = $this->indicateurs;
        $reponses = $this->reponses;
        if($indicateurs->count() > 0){
            return [
                'status' => true,
                'name' => "En cours d'instruction",
                'code' => 1,
            ];
        }
        return [
            'status' => false,
            'name' => 'En attente d\'instruction',
            'code' => 0,
        ];
    }

    // Accessor pour 'note'
    public function getNoteAttribute()
    {
        $indicateurs = $this->indicateurs;
        $reponses = $this->reponses;
        $nql = 0;

        if ($reponses) {
            $nql = $reponses->sum('value');
        }

        $variations = [];
        $nf = 0;

        if ($indicateurs->count() > 0) {
            $annees = $indicateurs->pluck('annee')->sort()->toArray();
            for ($i = 0; $i < count($annees) - 1; $i++) {
                $n = $annees[$i + 1];
                $n_1 = $annees[$i];
                $indicateur_n = $indicateurs->where('annee', $n)->first();
                $indicateur_n_1 = $indicateurs->where('annee', $n_1)->first();

                if ($indicateur_n && $indicateur_n_1) {
                    $gap = [
                        'ca' => $this->calculateVariation($indicateur_n_1->ca, $indicateur_n->ca),
                        'marge_commerciale' => $this->calculateVariation($indicateur_n_1->marge_commerciale, $indicateur_n->marge_commerciale),
                        'va' => $this->calculateVariation($indicateur_n_1->va, $indicateur_n->va),
                        // Ajouter les autres variables ici...
                    ];
                    $variations[] = [
                        'periode' => "$n_1-$n",
                        'variation' => $gap,
                    ];
                }
            }

            // Exemple d'exercice
           /* $exercices = $indicateurs->map(function ($value) {
                return $value->serialize();
            }); */

           // $exercices = $indicateurs;

            $nf = $indicateurs[0]['notation']['note'];
        }

        return round($nf + $nql);
    }

    // Accessor pour 'state'
    public function getStateAttribute()
    {
        if ($this->indicateurs->count() > 0) {
            return [
                'status' => true,
                'name' => "En cours d'instruction",
            ];
        }

        return [
            'status' => false,
            'name' => 'En attente d\'instruction',
        ];
    }

    // Méthode pour calculer les variations
    private function calculateVariation($previous, $current)
    {
        return $previous != 0 ? round(($current - $previous) * 100 / $previous) : 0;
    }


}
