/**
 * AngaraTable — table personnalisée (sans DataTables) compatible endpoints DataTables server-side.
 * - Recherche (debounce)
 * - Pagination
 * - Tri (clic sur en-tête)
 * - Filtres (inputs/select externes)
 *
 * Attendu côté backend: réponse JSON type DataTables:
 * { draw, recordsTotal, recordsFiltered, data: [] }
 */
(function (global) {
  'use strict';

  function qs(root, sel) {
    return (root || document).querySelector(sel);
  }

  function qsa(root, sel) {
    return Array.from((root || document).querySelectorAll(sel));
  }

  function debounce(fn, delay) {
    var t;
    return function () {
      var args = arguments;
      clearTimeout(t);
      t = setTimeout(function () {
        fn.apply(null, args);
      }, delay);
    };
  }

  function toInt(v, fallback) {
    var n = parseInt(String(v || ''), 10);
    return Number.isFinite(n) ? n : fallback;
  }

  function buildDtParams(state, config) {
    var params = new URLSearchParams();
    state.draw += 1;
    params.set('draw', String(state.draw));
    params.set('start', String(state.page * state.length));
    params.set('length', String(state.length));
    params.set('search[value]', state.search || '');
    params.set('search[regex]', 'false');

    // Columns meta (for server sorting)
    (config.columns || []).forEach(function (c, i) {
      var dataKey = c.data != null ? String(c.data) : '';
      var nameKey = c.name != null ? String(c.name) : dataKey;
      params.set('columns[' + i + '][data]', dataKey);
      params.set('columns[' + i + '][name]', nameKey);
      params.set('columns[' + i + '][searchable]', c.searchable === false ? 'false' : 'true');
      params.set('columns[' + i + '][orderable]', c.orderable === false ? 'false' : 'true');
      params.set('columns[' + i + '][search][value]', '');
      params.set('columns[' + i + '][search][regex]', 'false');
    });

    if (state.order && state.order.col != null) {
      params.set('order[0][column]', String(state.order.col));
      params.set('order[0][dir]', state.order.dir || 'asc');
    }

    // External filters
    if (config.filters) {
      Object.keys(config.filters).forEach(function (param) {
        var elId = config.filters[param];
        var el = document.getElementById(elId);
        if (!el) return;
        var v = (el.value || '').trim();
        if (v !== '') params.set(param, v);
      });
    }

    // Allow caller to add extra params
    if (typeof config.buildExtraParams === 'function') {
      config.buildExtraParams(params);
    }

    return params;
  }

  function renderPagination(container, state, totalFiltered) {
    if (!container) return;
    var totalPages = Math.max(1, Math.ceil((totalFiltered || 0) / state.length));
    var current = state.page + 1;
    if (current > totalPages) current = totalPages;

    // windowed pagination
    var windowSize = 7;
    var half = Math.floor(windowSize / 2);
    var start = Math.max(1, current - half);
    var end = Math.min(totalPages, start + windowSize - 1);
    start = Math.max(1, end - windowSize + 1);

    var html = '<nav aria-label="Pagination"><ul class="pagination pagination-sm mb-0">';
    var prevDisabled = current <= 1 ? ' disabled' : '';
    var nextDisabled = current >= totalPages ? ' disabled' : '';

    html += '<li class="page-item' + prevDisabled + '"><button class="page-link" type="button" data-page="prev">Préc.</button></li>';

    if (start > 1) {
      html += '<li class="page-item"><button class="page-link" type="button" data-page="1">1</button></li>';
      if (start > 2) html += '<li class="page-item disabled"><span class="page-link">…</span></li>';
    }

    for (var p = start; p <= end; p++) {
      var active = p === current ? ' active' : '';
      html += '<li class="page-item' + active + '"><button class="page-link" type="button" data-page="' + p + '">' + p + '</button></li>';
    }

    if (end < totalPages) {
      if (end < totalPages - 1) html += '<li class="page-item disabled"><span class="page-link">…</span></li>';
      html += '<li class="page-item"><button class="page-link" type="button" data-page="' + totalPages + '">' + totalPages + '</button></li>';
    }

    html += '<li class="page-item' + nextDisabled + '"><button class="page-link" type="button" data-page="next">Suiv.</button></li>';
    html += '</ul></nav>';

    container.innerHTML = html;
  }

  function AngaraTable(tableEl, config) {
    if (!tableEl) throw new Error('AngaraTable: table element required');
    if (!config || !config.ajaxUrl) throw new Error('AngaraTable: ajaxUrl required');

    this.tableEl = tableEl;
    this.tbody = qs(tableEl, 'tbody');
    this.config = config;
    this.root = tableEl.closest('.angara-table') || tableEl.parentElement;

    this.state = {
      draw: 0,
      page: 0,
      length: toInt(config.pageLength, 25),
      search: '',
      order: config.order ? { col: config.order[0], dir: config.order[1] } : null,
      recordsTotal: 0,
      recordsFiltered: 0,
    };

    this.ui = {
      info: this.root ? qs(this.root, '[data-angara-table-info]') : null,
      paging: this.root ? qs(this.root, '[data-angara-table-paging]') : null,
      length: this.root ? qs(this.root, '[data-angara-table-length]') : null,
      search: this.root ? qs(this.root, '[data-angara-table-search]') : null,
      empty: this.root ? qs(this.root, '[data-angara-table-empty]') : null,
    };

    this._bind();
    this.reload();
  }

  AngaraTable.prototype._bind = function () {
    var self = this;

    // Search
    if (self.ui.search) {
      var onSearch = debounce(function (v) {
        self.state.search = v || '';
        self.state.page = 0;
        self.reload();
      }, toInt(self.config.searchDelay, 300));

      self.ui.search.addEventListener('input', function () {
        onSearch(self.ui.search.value);
      });
    }

    // Page length
    if (self.ui.length) {
      self.ui.length.addEventListener('change', function () {
        self.state.length = toInt(self.ui.length.value, self.state.length);
        self.state.page = 0;
        self.reload();
      });
    }

    // Filters
    if (self.config.filters) {
      Object.keys(self.config.filters).forEach(function (param) {
        var id = self.config.filters[param];
        var el = document.getElementById(id);
        if (!el) return;
        el.addEventListener('change', function () {
          self.state.page = 0;
          self.reload();
        });
      });
    }

    // Sorting by header click
    var ths = qsa(self.tableEl, 'thead th[data-angara-sort-col]');
    ths.forEach(function (th) {
      th.style.cursor = 'pointer';
      th.addEventListener('click', function () {
        var col = toInt(th.getAttribute('data-angara-sort-col'), null);
        if (col == null) return;
        var current = self.state.order && self.state.order.col === col ? self.state.order.dir : null;
        var nextDir = current === 'asc' ? 'desc' : 'asc';
        self.state.order = { col: col, dir: nextDir };
        self.state.page = 0;
        self.reload();
      });
    });

    // Pagination click
    if (self.ui.paging) {
      self.ui.paging.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-page]');
        if (!btn) return;
        var p = btn.getAttribute('data-page');
        var totalPages = Math.max(1, Math.ceil((self.state.recordsFiltered || 0) / self.state.length));
        if (p === 'prev') {
          self.state.page = Math.max(0, self.state.page - 1);
        } else if (p === 'next') {
          self.state.page = Math.min(totalPages - 1, self.state.page + 1);
        } else {
          var n = toInt(p, 1);
          self.state.page = Math.min(Math.max(0, n - 1), totalPages - 1);
        }
        self.reload();
      });
    }
  };

  AngaraTable.prototype._setInfo = function () {
    if (!this.ui.info) return;
    var total = this.state.recordsFiltered || 0;
    var start = total === 0 ? 0 : this.state.page * this.state.length + 1;
    var end = Math.min(total, (this.state.page + 1) * this.state.length);
    this.ui.info.textContent = 'Affichage de ' + start + ' à ' + end + ' sur ' + total + ' entrées';
  };

  AngaraTable.prototype._renderRows = function (rows) {
    if (!this.tbody) return;
    var cols = this.config.columns || [];
    var html = '';

    (rows || []).forEach(function (row) {
      html += '<tr>';
      cols.forEach(function (c) {
        if (c._actionsOnly) return; // internal
        var cls = c.className ? ' class="' + c.className + '"' : '';
        var style = c.width ? ' style="width:' + c.width + ';"' : '';
        var cell;
        try {
          if (typeof c.render === 'function') {
            cell = c.render(row[c.data], 'display', row);
          } else if (c.data == null) {
            cell = '';
          } else {
            cell = row[c.data] != null ? String(row[c.data]) : '';
          }
        } catch (_) {
          cell = '';
        }
        html += '<td' + cls + style + '>' + (cell == null ? '' : cell) + '</td>';
      });
      html += '</tr>';
    });

    this.tbody.innerHTML = html;

    if (this.ui.empty) {
      var empty = !rows || rows.length === 0;
      this.ui.empty.classList.toggle('d-none', !empty);
    }
  };

  AngaraTable.prototype.reload = function () {
    var self = this;
    var params = buildDtParams(self.state, self.config);
    var url = self.config.ajaxUrl + (self.config.ajaxUrl.includes('?') ? '&' : '?') + params.toString();

    if (self.root) self.root.classList.add('is-loading');
    return fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
      .then(function (r) { return r.json(); })
      .then(function (json) {
        self.state.recordsTotal = toInt(json.recordsTotal, 0);
        self.state.recordsFiltered = toInt(json.recordsFiltered, 0);
        self._renderRows(json.data || []);
        self._setInfo();
        renderPagination(self.ui.paging, self.state, self.state.recordsFiltered);
        if (typeof self.config.drawCallback === 'function') self.config.drawCallback(json);
      })
      .catch(function (err) {
        console.error('AngaraTable:', err);
        self._renderRows([]);
        self._setInfo();
        renderPagination(self.ui.paging, self.state, 0);
      })
      .finally(function () {
        if (self.root) self.root.classList.remove('is-loading');
      });
  };

  global.AngaraTable = AngaraTable;

  // Global helper: copy-to-clipboard for dropdown actions
  document.addEventListener('click', async function (e) {
    var btn = e.target.closest('[data-copy-text]');
    if (!btn) return;
    var text = btn.getAttribute('data-copy-text') || '';
    try {
      await navigator.clipboard.writeText(text);
    } catch (_) {
      var ta = document.createElement('textarea');
      ta.value = text;
      ta.style.position = 'fixed';
      ta.style.opacity = '0';
      document.body.appendChild(ta);
      ta.focus();
      ta.select();
      document.execCommand('copy');
      document.body.removeChild(ta);
    }
  });
})(typeof window !== 'undefined' ? window : this);

