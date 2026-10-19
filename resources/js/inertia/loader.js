/**
 * Loader global : visible pendant les visites Inertia (navigation, envoi de formulaire) et pendant
 * les changements de page classiques (liens, formulaires natifs comme la connexion).
 *
 * Usage manuel : import { loader } from '@/loader'; loader.show('Message'); loader.hide();
 */
import { reactive } from 'vue';
import { router } from '@inertiajs/vue3';

const DELAY_MS = 300; // pas de clignotement pour les réponses rapides
const SAFETY_MS = 20000; // filet de sécurité si aucune fin de chargement n'est détectée

export const loader = reactive({
    visible: false,
    message: null,
    _delay: null,
    _safety: null,

    show(message = null, immediate = false) {
        this.message = message;
        clearTimeout(this._delay);
        clearTimeout(this._safety);
        const display = () => {
            this.visible = true;
            this._safety = setTimeout(() => this.hide(), SAFETY_MS);
        };
        if (immediate) display();
        else this._delay = setTimeout(display, DELAY_MS);
    },

    hide() {
        clearTimeout(this._delay);
        clearTimeout(this._safety);
        this.visible = false;
        this.message = null;
    },
});

// Liens qui déclenchent un téléchargement : la page ne change pas, le loader resterait affiché.
const isDownload = (url) => /(export|\.pdf|\.xlsx?|\/pdf|\/xlsx|download|format=)/i.test(url);

export function installLoader() {
    // Visites Inertia. Les rechargements partiels (données différées des tableaux de bord) ont leurs propres squelettes.
    router.on('start', (event) => {
        const visit = event.detail.visit;
        if (visit.prefetch || (visit.only && visit.only.length) || isDownload(String(visit.url))) return;
        loader.show(visit.method !== 'get' ? 'Enregistrement en cours…' : null);
    });
    router.on('finish', () => loader.hide());

    if (typeof window === 'undefined') return;

    // Changements de page classiques (liens <a> hors Inertia).
    document.addEventListener('click', (e) => {
        if (e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
        const a = e.target.closest?.('a[href]');
        if (!a || a.target === '_blank' || a.hasAttribute('download')) return;
        const href = a.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('mailto:') || href.startsWith('tel:')) return;
        const url = new URL(a.href, window.location.href);
        if (url.origin !== window.location.origin || isDownload(url.href)) return;
        if (url.pathname === window.location.pathname && url.search === window.location.search && url.hash) return;
        // Les liens Inertia sont gérés par les événements du routeur.
        setTimeout(() => {
            if (!e.defaultPrevented) loader.show();
        }, 0);
    });

    // Formulaires natifs (ex. connexion) : attente de la redirection.
    document.addEventListener('submit', (e) => {
        const form = e.target;
        if (e.defaultPrevented || form.target === '_blank' || isDownload(form.action || '')) return;
        setTimeout(() => {
            if (!e.defaultPrevented) loader.show(form.dataset.loaderMessage || null, true);
        }, 0);
    });

    // Retour arrière depuis le cache du navigateur : la page réapparaît sans rechargement.
    window.addEventListener('pageshow', () => loader.hide());
}
