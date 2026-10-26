<?php

namespace Tests\Feature;

use App\Models\Entreprise;
use App\Services\EntrepriseDuplicateGuard;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class EntrepriseDuplicateGuardTest extends TestCase
{
    use DatabaseTransactions;

    protected $connectionsToTransact = ['central_app_mysql'];

    private function entreprise(array $attributes = []): Entreprise
    {
        return Entreprise::create($attributes + [
            'name' => 'Test anti-doublon',
            'token' => sha1(uniqid('', true)),
            'prospect' => true,
        ]);
    }

    public function test_creation_with_existing_niu_is_refused(): void
    {
        $this->entreprise(['niu' => 'M0123456789ZZ']);

        try {
            $this->entreprise(['niu' => ' m0123456789zz ']);
            $this->fail('Doublon NIU accepté.');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('niu', $e->errors());
            $this->assertStringContainsString('Test anti-doublon', $e->errors()['niu'][0]);
        }
    }

    public function test_creation_with_existing_rccm_is_refused_ignoring_case_and_spaces(): void
    {
        $this->entreprise(['rccm' => 'RC/TEST/2099/B/0001']);

        $this->expectException(ValidationException::class);
        $this->entreprise(['rccm' => 'rc / test / 2099 / b / 0001']);
    }

    public function test_update_to_an_identifier_used_by_another_record_is_refused(): void
    {
        $this->entreprise(['niu' => 'M0123456789ZZ']);
        $other = $this->entreprise(['niu' => 'M0123456789YY']);

        $this->expectException(ValidationException::class);
        $other->update(['niu' => 'M0123456789ZZ']);
    }

    public function test_record_can_be_saved_again_with_its_own_identifiers(): void
    {
        $item = $this->entreprise(['niu' => 'm0123456789zz', 'rccm' => 'RC/TEST/2099/B/0001']);

        $this->assertSame('M0123456789ZZ', $item->niu);

        $item->update(['name' => 'Renommée', 'niu' => 'M0123456789ZZ']);
        $this->assertSame('Renommée', $item->fresh()->name);
    }

    public function test_records_without_identifiers_are_not_considered_duplicates(): void
    {
        $this->entreprise(['niu' => '', 'rccm' => null]);
        $second = $this->entreprise(['niu' => '', 'rccm' => null]);

        $this->assertNull($second->niu);
    }

    public function test_find_for_linking_ignores_empty_contacts(): void
    {
        $this->entreprise(['phone' => null, 'email' => null]);

        $this->assertNull(app(EntrepriseDuplicateGuard::class)->findForLinking(['phone' => null, 'email' => '']));
    }

    public function test_find_for_linking_returns_record_with_same_rccm(): void
    {
        $existing = $this->entreprise(['rccm' => 'RC/TEST/2099/B/0001']);

        $found = app(EntrepriseDuplicateGuard::class)->findForLinking(['rccm' => 'rc/test/2099/b/0001']);

        $this->assertSame($existing->id, $found?->id);
    }
}
