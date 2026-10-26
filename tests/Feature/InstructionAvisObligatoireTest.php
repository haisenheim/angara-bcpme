<?php

namespace Tests\Feature;

use App\Models\Dossier;
use App\Models\Entreprise;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Chaque intervenant du circuit d'instruction doit saisir son avis avant de valider son étape.
 */
class InstructionAvisObligatoireTest extends TestCase
{
    use DatabaseTransactions;

    protected $connectionsToTransact = ['mysql', 'central_app_mysql'];

    private function user(int $roleId): User
    {
        $user = new User;
        $user->forceFill(['name' => 'Test avis '.$roleId, 'email' => uniqid('avis', true).'@test.local', 'role_id' => $roleId, 'password' => bcrypt('secret')])->save();

        return $user;
    }

    /**
     * Les contrôleurs ne lisent pas tous le dossier sur la même connexion (défaut ou central_app_mysql) :
     * chaque connexion ayant sa propre transaction de test, le dossier est créé là où il sera lu.
     */
    private function dossier(array $attributes = [], ?string $connection = null): Dossier
    {
        $entreprise = Entreprise::create(['name' => 'Test avis instruction', 'token' => sha1(uniqid('', true)), 'prospect' => false]);

        return Dossier::on($connection)->create($attributes + [
            'entreprise_id' => $entreprise->id,
            'token' => sha1(uniqid('dossier', true)),
            'active' => 0,
        ]);
    }

    /** @return array<string, mixed> tous les avis du circuit + transmission à la direction */
    private function circuitComplet(): array
    {
        return [
            'instruction_agence_ca_avis' => '<p>Avis CA</p>',
            'exploitation_analyste_instruction_avis' => '<p>Analyse AF</p>',
            'exploitation_avis_credit' => '<p>Avis crédit</p>',
            'juridique_analyste_avis' => '<p>Avis analyste juridique</p>',
            'juridique_responsable_avis' => '<p>Avis RJU</p>',
            'reng_analyste_credit_avis' => '<p>Avis analyste crédit</p>',
            'reng_responsable_avis' => '<p>Avis RENG</p>',
            'rerx_analyste_risques_avis' => '<p>Avis analyste risques</p>',
            'rerx_responsable_avis' => '<p>Avis RISQ</p>',
            'rerx_submitted_to_direction_at' => now(),
        ];
    }

    public function test_closure_prerequisites_list_every_missing_avis(): void
    {
        $missing = (new Dossier)->missingPrerequisitesForInstructionClosure();

        $this->assertContains('avis du chef d’agence', $missing);
        $this->assertContains('avis du responsable risques', $missing);
        $this->assertContains('transmission du dossier à la direction par le pôle risques', $missing);
        $this->assertSame([], (new Dossier)->forceFill($this->circuitComplet())->missingPrerequisitesForInstructionClosure());
    }

    public function test_empty_html_is_not_an_avis(): void
    {
        $dossier = (new Dossier)->forceFill(['rerx_responsable_avis' => '<p><br></p>'] + $this->circuitComplet());

        $this->assertSame(['avis du responsable risques'], $dossier->missingPrerequisitesForInstructionClosure());
    }

    public function test_direction_cannot_approve_closure_before_all_avis(): void
    {
        $dossier = $this->dossier(['exploitation_avis_credit' => '<p>Avis crédit</p>'], 'central_app_mysql');

        $this->actingAs($this->user((int) config('angara.role_dg', 4)))
            ->post(route('instruction.dossiers.closure.approve', $dossier->token), ['instruction_closure_note' => 'Accord'])
            ->assertSessionHasErrors('instruction_closure_note');

        $this->assertNull($dossier->fresh()->instruction_closure_validated_at);
    }

    public function test_direction_can_approve_closure_when_circuit_is_complete(): void
    {
        $dossier = $this->dossier($this->circuitComplet(), 'central_app_mysql');

        $this->actingAs($this->user((int) config('angara.role_dg', 4)))
            ->post(route('instruction.dossiers.closure.approve', $dossier->token), ['instruction_closure_note' => 'Accord'])
            ->assertSessionHasNoErrors();

        $this->assertNotNull($dossier->fresh()->instruction_closure_validated_at);
    }

    public function test_responsable_exploitation_cannot_agree_without_avis_credit(): void
    {
        $respexp = $this->user((int) config('angara.role_responsable_exploitation', 6));
        $dossier = $this->dossier([
            'analyste_id' => $respexp->id,
            'exploitation_analyste_transmitted_to_exploitation_at' => now(),
        ]);

        $this->actingAs($respexp)
            ->post(route('respexp.dossiers.validation-engagements', $dossier->token), ['decision' => 'accord'])
            ->assertSessionHasErrors('decision');
        $this->assertNull($dossier->fresh()->exploitation_engagements_decision);

        $dossier->update(['exploitation_avis_credit' => '<p>Favorable</p>']);
        $this->actingAs($respexp)
            ->post(route('respexp.dossiers.validation-engagements', $dossier->token), ['decision' => 'accord'])
            ->assertSessionHasNoErrors();
        $this->assertSame('accord', $dossier->fresh()->exploitation_engagements_decision);
    }

    public function test_chef_agence_validation_requires_avis(): void
    {
        $dossier = $this->dossier();

        $this->actingAs($this->user((int) config('angara.role_chef_agence', 15)))
            ->post(route('ca.workflow.instruction-dossiers.approve', $dossier->token), ['instruction_agence_ca_avis' => '<p> </p>'])
            ->assertSessionHasErrors('instruction_agence_ca_avis');
    }
}
