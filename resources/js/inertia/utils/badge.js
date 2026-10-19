// Variantes Bootstrap héritées → variantes de StatusBadge (miroir de App\Support\Inertia\Badge).
export const badgeVariant = (bootstrap) => ({
    success: 'success',
    danger: 'danger',
    warning: 'warning',
    info: 'info',
    primary: 'info',
}[bootstrap] ?? 'neutral');
