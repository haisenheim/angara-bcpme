# Database Column Fix - Statut Column Issue

## ❌ Problem

Error: `Column not found: 1054 Unknown column 'statut' in 'where clause'`

The `Dossier` model doesn't have a `statut` column in the database. Instead, it uses a **computed attribute** called `status` that dynamically determines the dossier's state based on whether it has `indicateurs` or not.

## 🔍 Root Cause

The `Dossier` model has these accessors:
- `getStatusAttribute()` - Returns status based on indicateurs count
- `getStateAttribute()` - Similar to status

**Status Logic:**
- **Code 0 (En attente)**: Dossier has NO indicateurs
- **Code 1 (En cours)**: Dossier HAS indicateurs

This is a computed value, NOT a database column, so it cannot be used in `WHERE` clauses.

## ✅ Solution Applied

### For CA DashboardController

**Changed from:**
```php
->where('statut', 'en_attente')  // ❌ Column doesn't exist
->where('statut', 'en_cours')    // ❌ Column doesn't exist
```

**Changed to:**
```php
->whereDoesntHave('indicateurs')  // ✅ En attente
->whereHas('indicateurs')         // ✅ En cours
```

### For Analyste DashboardController

Same pattern applied to all queries.

### For JavaScript Files

**Updated status handling:**
```javascript
// Status codes: 0 = en attente, 1 = en cours, 2 = termine, 3 = rejete
const statusMap = {
    0: 'warning',  // en attente
    1: 'primary',  // en cours
    2: 'success',  // termine
    3: 'danger'    // rejete
};
```

## 📝 Updated Methods

### CA DashboardController
- ✅ `getStats()` - Fixed dossiers_en_cours and total_prospects
- ✅ `getMonthlyStats()` - Fixed completed_dossiers and active_prospects
- ✅ `getAlerts()` - Fixed pending_dossiers and old_pending
- ✅ `getRecentDossiers()` - Now returns status code and name

### Analyste DashboardController
- ✅ `getStats()` - Fixed all status-related counts
- ✅ `getDossiersDistribution()` - Uses collection filtering
- ✅ `getMonthlyAnalysis()` - Fixed completed analysis count
- ✅ `getRecentDossiers()` - Returns status code and name
- ✅ `getPerformanceMetrics()` - Fixed all counts
- ✅ `getAlerts()` - Fixed urgent and in-progress counts

### JavaScript Files
- ✅ `ca-dashboard.js` - Updated status class mapping
- ✅ `analyste-dashboard.js` - Updated status class mapping

## 🎯 How It Works Now

### Database Query Pattern:

**To find "En attente" dossiers:**
```php
Dossier::where('agence_id', $agenceId)
    ->whereDoesntHave('indicateurs')
    ->count();
```

**To find "En cours" dossiers:**
```php
Dossier::where('agence_id', $agenceId)
    ->whereHas('indicateurs')
    ->count();
```

### API Response Pattern:

```json
{
    "statut": 0,
    "statut_name": "En attente d'instruction"
}
```

Or:

```json
{
    "statut": 1,
    "statut_name": "En cours d'instruction"
}
```

## 🔧 Entreprise 'statut' vs 'prospect'

Also fixed: `Entreprise` table uses `prospect` column (boolean), not `statut`:

**Before:**
```php
->where('statut', 'prospect')  // ❌ Wrong column
```

**After:**
```php
->where('prospect', 1)  // ✅ Correct column
```

## ✅ Testing Checklist

- [ ] CA dashboard loads without errors
- [ ] Analyste dashboard loads without errors
- [ ] All statistics display correctly
- [ ] Charts render with proper data
- [ ] Status badges show correct colors:
  - Yellow (warning) for "En attente"
  - Blue (primary) for "En cours"
  - Green (success) for "Terminé"
  - Red (danger) for "Rejeté"
- [ ] No SQL errors in logs

## 📊 Status Reference

| Code | Name | Database Condition | Badge Color |
|------|------|-------------------|-------------|
| 0 | En attente d'instruction | No indicateurs | warning (yellow) |
| 1 | En cours d'instruction | Has indicateurs | primary (blue) |
| 2 | Terminé | (future) | success (green) |
| 3 | Rejeté | (future) | danger (red) |

## 🚨 Important Notes

1. **Never use `statut` in WHERE clauses** - It's not a database column
2. **Use relationship checks instead**: `whereHas('indicateurs')` or `whereDoesntHave('indicateurs')`
3. **For Entreprise prospects**: Use `prospect` column (boolean), not `statut`
4. **Status codes are returned in API** for frontend badge coloring

## ✅ All Fixes Applied

- [x] CA DashboardController - All methods fixed
- [x] Analyste DashboardController - All methods fixed
- [x] ca-dashboard.js - Status handling updated
- [x] analyste-dashboard.js - Status handling updated
- [x] No linting errors
- [x] Ready for testing


