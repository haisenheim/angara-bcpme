<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Entreprise;
use App\Services\Inertia\EntrepriseFormSchema;
use App\Services\Inertia\QuestionnairePresenter;
use Illuminate\Support\Str;

/**
 * Formulaires Inertia communs aux espaces admin et analyste (création d'entreprise, tiers,
 * questionnaire), branchés sur les actions POST historiques de l'espace ($space.*).
 */
trait RendersEntrepriseFormPages
{
    protected function entrepriseCreatePage(string $space): \Inertia\Response
    {
        return \Inertia\Inertia::render('Entreprises/Form', [
            'title' => 'Nouvelle entreprise',
            'backHref' => route("$space.entreprises.index"),
            'schema' => [
                'action' => route("$space.entreprises.store"),
                'enforceRequired' => true,
                'cancelHref' => route("$space.entreprises.index"),
                'steps' => app(EntrepriseFormSchema::class)->entrepriseSteps(),
            ],
            'breadcrumb' => [
                ['label' => 'Tableau de bord', 'href' => route("$space.dashboard")],
                ['label' => 'Entreprises', 'href' => route("$space.entreprises.index")],
                ['label' => 'Nouvelle entreprise'],
            ],
        ]);
    }

    protected function tiersPhysiquePage(string $space, Entreprise $item): \Inertia\Response
    {
        return $this->tiersPage($space, $item, 'Nouveau tiers personne physique', "$space.entreprise.physique.save",
            app(EntrepriseFormSchema::class)->tiersPhysiqueSteps());
    }

    protected function tiersMoralePage(string $space, Entreprise $item): \Inertia\Response
    {
        return $this->tiersPage($space, $item, 'Nouveau tiers personne morale', "$space.entreprise.morale.save",
            app(EntrepriseFormSchema::class)->tiersMoraleSimpleSteps());
    }

    protected function questionnairePage(string $space, Entreprise $item): \Inertia\Response
    {
        $questionnaire = app(QuestionnairePresenter::class);

        return \Inertia\Inertia::render('Entreprises/Questionnaire', $questionnaire->props(
            $item,
            $questionnaire->flat(),
            route("$space.entreprise.questionnaire.save"),
            route("$space.entreprises.show", $item->token),
            $this->entrepriseFormBreadcrumb($space, $item, 'Questionnaire'),
        ));
    }

    private function tiersPage(string $space, Entreprise $item, string $title, string $saveRoute, array $steps): \Inertia\Response
    {
        $back = route("$space.entreprises.show", $item->token);

        return \Inertia\Inertia::render('Entreprises/Form', [
            'title' => $title,
            'subtitle' => $item->name,
            'backHref' => $back,
            'schema' => [
                'action' => route($saveRoute),
                'hidden' => ['entreprise_id' => $item->id, 'token' => $item->token],
                'enforceRequired' => true,
                'cancelHref' => $back,
                'steps' => $steps,
            ],
            'breadcrumb' => $this->entrepriseFormBreadcrumb($space, $item, $title),
        ]);
    }

    private function entrepriseFormBreadcrumb(string $space, Entreprise $item, string $label): array
    {
        return [
            ['label' => 'Tableau de bord', 'href' => route("$space.dashboard")],
            ['label' => 'Entreprises', 'href' => route("$space.entreprises.index")],
            ['label' => Str::limit((string) $item->name, 40), 'href' => route("$space.entreprises.show", $item->token)],
            ['label' => $label],
        ];
    }
}
