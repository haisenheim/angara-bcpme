/**
 * Angara — helpers pour synchroniser filtres/recherche avec exports.
 * Usage:
 * - AngaraTableFilters.buildExportQuery({ format, baseUrl, filterFields, searchDomId })
 */
(function (global) {
  'use strict';

  function valById(id) {
    if (!id) return '';
    var el = document.getElementById(id);
    if (!el) return '';
    return String(el.value || '').trim();
  }

  function appendFilters(params, filterFields) {
    if (!filterFields) return params;
    Object.keys(filterFields).forEach(function (param) {
      var id = filterFields[param];
      var v = valById(id);
      if (v !== '') params.set(param, v);
      else params.delete(param);
    });
    return params;
  }

  function buildExportQuery(opts) {
    var params = new URLSearchParams();
    if (opts && opts.format) params.set('format', String(opts.format));
    appendFilters(params, (opts && opts.filterFields) || {});
    var searchId = opts && opts.searchDomId ? String(opts.searchDomId) : '';
    var search = valById(searchId);
    if (search !== '') params.set('search[value]', search);
    return params.toString();
  }

  global.AngaraTableFilters = {
    appendFilters: appendFilters,
    buildExportQuery: buildExportQuery,
  };
})(typeof window !== 'undefined' ? window : this);

