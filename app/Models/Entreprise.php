<?php

namespace App\Models;

use App\Models\Instruction\EngagementEntreprise;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Entreprise extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $connection = 'central_app_mysql';

    protected function casts(): array
    {
        return [
            'prospect_submitted_at' => 'datetime',
            'juridique_avis_at' => 'datetime',
            'conformite_avis_at' => 'datetime',
            'promu_client_at' => 'datetime',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'token';
    }

    public function tiers(){
        return $this->hasMany('App\Models\Tier','entreprise_id');
    }

    public function engagementEntreprises(): HasMany
    {
        return $this->hasMany(EngagementEntreprise::class, 'entreprise_id');
    }

    public function dossierEntreeRelation()
    {
        return $this->hasOne(DossierEntreeRelation::class);
    }

    public function dossierAnalyseCritique()
    {
        return $this->hasOne(DossierAnalyseCritique::class);
    }

    public function piecesExigibles()
    {
        return $this->hasMany(EntreprisePieceExigible::class);
    }

    /**
     * Pour chaque pièce paramétrée (active), indique si un fichier a été rattaché.
     *
     * @return Collection<int, array{definition: PieceExigibleDefinition, fourni: bool, entreprise_piece: ?EntreprisePieceExigible}>
     */
    public function piecesExigiblesChecklist(): Collection
    {
        $definitions = PieceExigibleDefinition::query()
            ->where('active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $parDef = $this->piecesExigibles()->with('fichier')->get()->keyBy('piece_exigible_definition_id');

        return $definitions->map(function (PieceExigibleDefinition $def) use ($parDef) {
            $ligne = $parDef->get($def->id);

            return [
                'definition' => $def,
                'fourni' => $ligne !== null && $ligne->isProvided(),
                'entreprise_piece' => $ligne,
            ];
        });
    }

    public function reponses(){
        return $this->hasMany('App\Models\QuestionAnswer','entreprise_id');
    }

    public function answers(){
        return $this->hasMany('App\Models\Instruction\Scoring\Individual\DossierChoice','dossier_id');
    }

    public function dossiers(){
        return $this->hasMany('App\Models\Dossier','entreprise_id');
    }

    public function fichiers(){
        return $this->hasMany('App\Models\Fichier','entreprise_id');
    }

    public function produit(){
        return $this->belongsTo('App\Models\Produit'); //produit principale
    }

    public function produits(){
        return $this->belongsToMany('App\Models\Produit','entreprise_produits'); //autres produits
    }

    public function appuis(){
        return $this->belongsToMany('App\Models\Service','entreprise_appuis'); //autres produits
    }

    public function elements(){
        return $this->hasMany('App\Models\EntrepriseElementConstitutif','entreprise_id'); //autres produits
    }

    public function filiere(){
        return $this->belongsTo('App\Models\Filiere');
    }

    public function branche(){
        return $this->belongsTo('App\Models\Branche');
    }

    public function forme(){
        return $this->belongsTo('App\Models\Forme');
    }

    public function programmes(){
        return $this->belongsToMany('App\Models\Programme','dossiers');
    }

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function juridiqueAvisUser()
    {
        return $this->belongsTo(User::class, 'juridique_avis_user_id');
    }

    public function conformiteAvisUser()
    {
        return $this->belongsTo(User::class, 'conformite_avis_user_id');
    }

    public function promuClientUser()
    {
        return $this->belongsTo(User::class, 'promu_client_user_id');
    }

    public function taille(){
        return $this->belongsTo('App\Models\Taille');
    }

    public function representation(){
        return $this->belongsTo('App\Models\Representation');
    }

    public function agence()
    {
        return $this->belongsTo('App\Models\Agence');
    }

    public function region(){
        return $this->belongsTo('App\Models\Region');
    }

    public function departement(){
        return $this->belongsTo('App\Models\Departement');
    }

    public function arrondissement(){
        return $this->belongsTo('App\Models\Arrondissement');
    }

    public function village(){
        return $this->belongsTo('App\Models\Village');
    }

    public function quartier(){
        return $this->belongsTo('App\Models\Quartier');
    }

    public function getTpersoAttribute(){
        if($this->personnel_mixte){
            return 'mixte';
        }
        if($this->personnel_permanent){
            return 'permanent';
        }
        if($this->personnel_saisonier){
            return 'saisonier';
        }
        return 'xxx';
    }
}
