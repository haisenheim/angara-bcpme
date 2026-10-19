// Comptage de mots d'un contenu HTML (éditeur riche). Même règle que App\Support\WordCount côté serveur :
// suites de lettres / chiffres, apostrophes et traits d'union internes inclus (« l'entreprise », « chef-lieu »).
const WORD = /[\p{L}\p{N}]+(?:['’-][\p{L}\p{N}]+)*/gu;

export function textFromHtml(html) {
    if (!html) return '';
    if (typeof DOMParser === 'undefined') return String(html).replace(/<[^>]*>/g, ' ');
    // Séparer les blocs pour ne pas coller les mots de deux paragraphes.
    const spaced = String(html).replace(/<(br|\/p|\/div|\/li|\/h[1-6])[^>]*>/gi, ' $&');
    return new DOMParser().parseFromString(spaced, 'text/html').body.textContent ?? '';
}

export function countWords(html) {
    return (textFromHtml(html).match(WORD) ?? []).length;
}

export const formatWords = (n) => `${n.toLocaleString('fr-FR')} mot${n > 1 ? 's' : ''}`;
