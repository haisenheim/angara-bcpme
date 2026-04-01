<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Tenant extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'data' => 'array',
        ];
    }

    protected $dates = ['dtn'];

    public function getTenantKey(): string
    {
        return (string) $this->getKey();
    }

    /**
     * Exécute un callback avec la base MySQL de cette coopérative (connexion structuration_app_mysql).
     */
    public function run(callable $callback): mixed
    {
        $connectionName = 'structuration_app_mysql';
        $config = config("database.connections.{$connectionName}");
        $originalDatabase = $config['database'] ?? '';
        $tenantDatabase = $this->databaseName();

        config(["database.connections.{$connectionName}.database" => $tenantDatabase]);
        DB::purge($connectionName);

        try {
            return $callback();
        } finally {
            config(["database.connections.{$connectionName}.database" => $originalDatabase]);
            DB::purge($connectionName);
        }
    }

    protected function databaseName(): string
    {
        return config('structuration.tenant_database_prefix').$this->id.config('structuration.tenant_database_suffix');
    }

    public function domains(): HasMany
    {
        return $this->hasMany(Domain::class, 'tenant_id');
    }

    public function domaine()
    {
        return $this->belongsTo(Domaine::class);
    }

    public function secteur()
    {
        return $this->belongsTo(Secteur::class);
    }

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'union_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'union_id');
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function departement()
    {
        return $this->belongsTo(Departement::class);
    }

    public function arrondissement()
    {
        return $this->belongsTo(Arrondissement::class);
    }

    public function comptes()
    {
        return $this->hasMany(Structuration\BanqueCooperative::class, 'tenant_id');
    }

    public function entrepots()
    {
        return $this->hasMany(Structuration\Entrepot::class, 'tenant_id');
    }

    public function membres()
    {
        return $this->hasMany(Structuration\Membre::class, 'tenant_id');
    }

    public function caisses()
    {
        return $this->hasMany(Structuration\Caisse::class, 'tenant_id');
    }

    public function wallets()
    {
        return $this->hasMany(Structuration\Wallet::class, 'tenant_id');
    }

    public function requests()
    {
        return $this->hasMany(Structuration\Request::class, 'tenant_id');
    }
}
