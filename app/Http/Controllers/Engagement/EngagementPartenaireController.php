<?php

namespace App\Http\Controllers\Engagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Engagement\StorePartenaireRequest;
use App\Models\Banque;
use App\Services\Engagement\EngagementAccessService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Gestion légère du référentiel partenaires (banques, EMF, autres).
 * Permet la création rapide depuis la grille.
 */
class EngagementPartenaireController extends Controller
{
    public function __construct(private readonly EngagementAccessService $access) {}

    public function index(Request $request): JsonResponse
    {
        if (! $this->access->userPeutConsulter(auth()->user())) {
            abort(403);
        }

        $kind = $request->query('kind');
        $query = Banque::query()->where(function ($q) {
            $q->where('actif', true)->orWhereNull('actif');
        });

        if (in_array($kind, array_keys(Banque::KIND_LABELS), true)) {
            $query->where('kind', $kind);
        }

        $items = $query->orderBy('name')->get(['id', 'name', 'siege', 'kind']);

        return response()->json([
            'items' => $items->map(fn (Banque $b) => [
                'id' => $b->id,
                'name' => $b->name,
                'siege' => $b->siege,
                'kind' => $b->kind ?? Banque::KIND_BANQUE,
                'kind_label' => $b->kind_label,
            ]),
        ]);
    }

    public function store(StorePartenaireRequest $request): JsonResponse
    {
        if (! auth()->user() || ! $this->access->userPeutConsulter(auth()->user())) {
            abort(403);
        }

        $partenaire = Banque::create($request->payload());

        return response()->json([
            'partenaire' => [
                'id' => $partenaire->id,
                'name' => $partenaire->name,
                'siege' => $partenaire->siege,
                'kind' => $partenaire->kind,
                'kind_label' => $partenaire->kind_label,
            ],
        ], 201);
    }
}
