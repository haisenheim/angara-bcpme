<?php

namespace App\Services\Inertia;

use App\Models\Entreprise;
use App\Models\Instruction\Critere as InstructionCritere;
use App\Models\QuestionSousCritere;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Questionnaire de mise en relation (page Inertia Entreprises/Questionnaire).
 * L'enregistrement reste l'endpoint JSON historique saveQuestionnaire (choices[]).
 */
class QuestionnairePresenter
{
    /**
     * Regroupement par critère principal (parcours gestionnaire).
     */
    public function byCriterePrincipal(): array
    {
        return InstructionCritere::query()
            ->whereHas('questionnaireSousCriteres')
            ->with(['questionnaireSousCriteres' => fn ($q) => $q->orderBy('id')->with(['questions.choices'])])
            ->orderBy('id')
            ->get()
            ->map(fn ($cp) => ['title' => $cp->name, 'sections' => $this->sections($cp->questionnaireSousCriteres)])
            ->values()->all();
    }

    /**
     * Tous les sous-critères à plat (parcours historique admin / analyste).
     */
    public function flat(): array
    {
        return [['title' => null, 'sections' => $this->sections(QuestionSousCritere::query()->with('questions.choices')->get())]];
    }

    public function props(Entreprise $item, array $groups, string $saveUrl, string $returnUrl, array $breadcrumb): array
    {
        return [
            'entreprise' => ['id' => $item->id, 'name' => $item->name, 'href' => $returnUrl],
            'groups' => $groups,
            'answers' => (object) $item->reponses()->pluck('choice_id', 'question_id')->map(fn ($v) => (int) $v)->all(),
            'saveUrl' => $saveUrl,
            'returnUrl' => $returnUrl,
            'breadcrumb' => $breadcrumb ?: [['label' => Str::limit((string) $item->name, 40), 'href' => $returnUrl], ['label' => 'Questionnaire']],
        ];
    }

    private function sections(Collection $sousCriteres): array
    {
        return $sousCriteres->map(fn ($sc) => [
            'id' => $sc->id,
            'critereId' => $sc->critere_id,
            'name' => $sc->name,
            'questions' => $sc->questions->map(fn ($q) => [
                'id' => $q->id,
                'name' => $q->name,
                'choices' => $q->choices->map(fn ($c) => ['id' => $c->id, 'name' => $c->name, 'value' => $c->value])->values()->all(),
            ])->values()->all(),
        ])->values()->all();
    }
}
