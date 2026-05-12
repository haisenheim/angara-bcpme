<?php

namespace Modules\Simulator\Domain\DTOs;

use DateTimeImmutable;
use InvalidArgumentException;
use Modules\Simulator\Domain\Enums\AmortizationType;
use Modules\Simulator\Domain\Enums\DeferralType;
use Modules\Simulator\Domain\Enums\InsuranceBasis;
use Modules\Simulator\Domain\Enums\Periodicity;

/**
 * Immutable input for credit simulation.
 *
 * All monetary amounts are expressed in the same currency.
 * Rates (annual_rate, fee_pct, insurance_pct, vat_rate) are expressed in PERCENT (8 means 8%).
 */
final class SimulationInput
{
    public function __construct(
        public readonly float $principal,
        public readonly float $annualRate,
        public readonly int $termPeriods,
        public readonly Periodicity $periodicity,
        public readonly AmortizationType $amortizationType,
        public readonly DeferralType $deferralType = DeferralType::NONE,
        public readonly int $deferralPeriods = 0,
        public readonly ?DateTimeImmutable $firstPeriodDate = null,
        public readonly string $currency = 'XOF',
        public readonly ?float $fxRate = null,
        public readonly float $dossierFeeFixed = 0.0,
        public readonly float $dossierFeePct = 0.0,
        public readonly float $insurancePct = 0.0,
        public readonly InsuranceBasis $insuranceBasis = InsuranceBasis::OUTSTANDING_BALANCE,
        public readonly float $vatRate = 0.0,
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        if ($this->principal <= 0) {
            throw new InvalidArgumentException('Le capital doit etre strictement positif.');
        }
        if ($this->annualRate < 0) {
            throw new InvalidArgumentException('Le taux annuel ne peut pas etre negatif.');
        }
        if ($this->termPeriods <= 0) {
            throw new InvalidArgumentException('La duree doit etre strictement positive.');
        }
        if ($this->deferralPeriods < 0) {
            throw new InvalidArgumentException('Le nombre de periodes de differe ne peut pas etre negatif.');
        }
        if ($this->deferralPeriods >= $this->termPeriods) {
            throw new InvalidArgumentException('Le differe doit etre strictement inferieur a la duree totale.');
        }
        if ($this->deferralType === DeferralType::NONE && $this->deferralPeriods > 0) {
            throw new InvalidArgumentException('Le type de differe est NONE mais le nombre de periodes est non nul.');
        }
        if ($this->deferralType !== DeferralType::NONE && $this->deferralPeriods === 0) {
            throw new InvalidArgumentException('Le type de differe est defini mais le nombre de periodes est zero.');
        }
        if ($this->dossierFeeFixed < 0) {
            throw new InvalidArgumentException('Les frais de dossier fixes ne peuvent pas etre negatifs.');
        }
        if ($this->dossierFeePct < 0) {
            throw new InvalidArgumentException('Les frais de dossier en pourcentage ne peuvent pas etre negatifs.');
        }
        if ($this->insurancePct < 0) {
            throw new InvalidArgumentException("Le taux d'assurance ne peut pas etre negatif.");
        }
        if ($this->vatRate < 0) {
            throw new InvalidArgumentException('Le taux de TVA ne peut pas etre negatif.');
        }
        if (strlen($this->currency) !== 3) {
            throw new InvalidArgumentException('La devise doit etre un code ISO a 3 lettres.');
        }
        if ($this->fxRate !== null && $this->fxRate <= 0) {
            throw new InvalidArgumentException('Le taux de change doit etre positif.');
        }
    }

    public function periodicRate(): float
    {
        return ($this->annualRate / 100.0) / $this->periodicity->periodsPerYear();
    }

    public function amortizingPeriods(): int
    {
        return $this->termPeriods - $this->deferralPeriods;
    }

    public function totalDossierFees(): float
    {
        return $this->dossierFeeFixed + ($this->principal * $this->dossierFeePct / 100.0);
    }

    public function toArray(): array
    {
        return [
            'principal' => $this->principal,
            'annual_rate' => $this->annualRate,
            'term_periods' => $this->termPeriods,
            'periodicity' => $this->periodicity->value,
            'amortization_type' => $this->amortizationType->value,
            'deferral_type' => $this->deferralType->value,
            'deferral_periods' => $this->deferralPeriods,
            'first_period_date' => $this->firstPeriodDate?->format('Y-m-d'),
            'currency' => $this->currency,
            'fx_rate' => $this->fxRate,
            'dossier_fee_fixed' => $this->dossierFeeFixed,
            'dossier_fee_pct' => $this->dossierFeePct,
            'insurance_pct' => $this->insurancePct,
            'insurance_basis' => $this->insuranceBasis->value,
            'vat_rate' => $this->vatRate,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            principal: (float) ($data['principal'] ?? 0),
            annualRate: (float) ($data['annual_rate'] ?? 0),
            termPeriods: (int) ($data['term_periods'] ?? 0),
            periodicity: Periodicity::from((string) ($data['periodicity'] ?? Periodicity::MONTHLY->value)),
            amortizationType: AmortizationType::from((string) ($data['amortization_type'] ?? AmortizationType::CONSTANT->value)),
            deferralType: DeferralType::from((string) ($data['deferral_type'] ?? DeferralType::NONE->value)),
            deferralPeriods: (int) ($data['deferral_periods'] ?? 0),
            firstPeriodDate: ! empty($data['first_period_date'])
                ? new DateTimeImmutable((string) $data['first_period_date'])
                : null,
            currency: (string) ($data['currency'] ?? 'XOF'),
            fxRate: isset($data['fx_rate']) && $data['fx_rate'] !== '' ? (float) $data['fx_rate'] : null,
            dossierFeeFixed: (float) ($data['dossier_fee_fixed'] ?? 0),
            dossierFeePct: (float) ($data['dossier_fee_pct'] ?? 0),
            insurancePct: (float) ($data['insurance_pct'] ?? 0),
            insuranceBasis: InsuranceBasis::from((string) ($data['insurance_basis'] ?? InsuranceBasis::OUTSTANDING_BALANCE->value)),
            vatRate: (float) ($data['vat_rate'] ?? 0),
        );
    }
}
