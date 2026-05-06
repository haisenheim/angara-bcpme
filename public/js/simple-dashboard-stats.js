/**
 * Chargement asynchrone de métriques tableau de bord (GET JSON).
 *
 * @param {string} url
 * @param {Record<string, string>} map Clé JSON → id d'élément DOM
 * @param {{ credentials?: RequestCredentials }} [opts]
 */
window.AngaraLoadDashboardStats = function (url, map, opts) {
    const credentials = (opts && opts.credentials) || 'same-origin';
    const headers = {
        Accept: 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    };

    return fetch(url, { credentials, headers })
        .then(function (r) {
            if (!r.ok) {
                throw new Error('HTTP ' + r.status);
            }
            return r.json();
        })
        .then(function (data) {
            Object.keys(map).forEach(function (key) {
                const id = map[key];
                const el = document.getElementById(id);
                if (!el) {
                    return;
                }
                const v = data[key];
                el.textContent = v === undefined || v === null ? '—' : String(v);
            });
        })
        .catch(function () {
            Object.keys(map).forEach(function (key) {
                const el = document.getElementById(map[key]);
                if (el) {
                    el.textContent = '—';
                }
            });
        });
};
