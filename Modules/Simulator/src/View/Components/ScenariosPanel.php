<?php

namespace Modules\Simulator\View\Components;

use App\Models\Dossier;
use Illuminate\View\Component;
use Illuminate\View\View;
use Modules\Simulator\Persistence\Models\Scenario;

class ScenariosPanel extends Component
{
    public function __construct(
        public Dossier $dossier,
    ) {}

    public function render(): View
    {
        $programmeIds = $this->dossier->instructionProgrammes()->pluck('id')->all();

        $scenarios = Scenario::query()
            ->where(function ($q) use ($programmeIds) {
                $q->where('dossier_id', $this->dossier->id);
                if ($programmeIds !== []) {
                    $q->orWhereIn('dossier_instruction_programme_id', $programmeIds);
                }
            })
            ->latest()
            ->get();

        $programmes = $this->dossier->instructionProgrammes()->with('programme')->get();

        return view('simulator::components.scenarios-panel', [
            'dossier' => $this->dossier,
            'scenarios' => $scenarios,
            'programmes' => $programmes,
        ]);
    }
}
