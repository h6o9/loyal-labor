/**
 * Lightweight list loader — vanilla jQuery GET AJAX (no DataTables package).
 * Talks to existing Yajra list endpoints using draw/start/length params.
 */

// #region agent log
function loyalDebugLog(location, message, data, hypothesisId) {
    fetch('http://127.0.0.1:7600/ingest/506040b6-b118-437d-ac00-d7a82a44f1c6', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Debug-Session-Id': '8fd336' },
        body: JSON.stringify({
            sessionId: '8fd336',
            location: location,
            message: message,
            data: data || {},
            timestamp: Date.now(),
            hypothesisId: hypothesisId || '',
            runId: 'pre-fix'
        })
    }).catch(function () {});
}
// #endregion

function initBootstrapToggles(container) {
    if (typeof $.fn.bootstrapToggle === 'undefined') {
        // #region agent log
        loyalDebugLog('datatable-common.js:initBootstrapToggles', 'bootstrapToggle plugin missing', {}, 'B');
        // #endregion
        return;
    }

    var $scope = container ? $(container) : $(document);
    var $inputs = $scope.find('input[data-toggle="toggle"]');
    var stats = { total: $inputs.length, alreadyWrapped: 0, destroyed: 0, inited: 0 };

    $inputs.each(function () {
        var $el = $(this);

        if ($el.parent().hasClass('toggle')) {
            stats.alreadyWrapped += 1;
            try {
                $el.bootstrapToggle('destroy');
                stats.destroyed += 1;
            } catch (e) {
                stats.destroyError = String(e);
            }
        }

        $el.bootstrapToggle();
        stats.inited += 1;
    });

    // #region agent log
    loyalDebugLog('datatable-common.js:initBootstrapToggles', 'toggle init complete', stats, 'A');
    // #endregion
}

function initSimpleAjaxTable(selector, url, columns, options) {
    options = options || {};
    columns = columns || [];

    var $table = $(selector);
    if (!$table.length) {
        return { reload: function () {}, ajax: { reload: function () {} } };
    }

    var $tbody = $table.find('tbody');
    var $responsive = $table.closest('.table-responsive');
    var colCount = $table.find('thead th').length || (columns.length + 1);
    var state = {
        page: 1,
        perPage: options.pageLength || 10,
        search: '',
        draw: 1
    };
    var extraDataFn = options.extraData || null;
    var searchTimer = null;

    function buildParams() {
        var params = {
            draw: state.draw,
            start: (state.page - 1) * state.perPage,
            length: state.perPage,
            'search[value]': state.search,
            'search[regex]': 'false'
        };

        columns.forEach(function (col, i) {
            params['columns[' + i + '][data]'] = col.data || '';
            params['columns[' + i + '][name]'] = col.name || col.data || '';
            params['columns[' + i + '][searchable]'] = col.searchable === false ? 'false' : 'true';
            params['columns[' + i + '][orderable]'] = col.orderable === false ? 'false' : 'true';
            params['columns[' + i + '][search][value]'] = '';
            params['columns[' + i + '][search][regex]'] = 'false';
        });

        if (typeof extraDataFn === 'function') {
            extraDataFn(params);
        }

        return params;
    }

    function showLoading() {
        $tbody.html(
            '<tr><td colspan="' + colCount + '" class="text-center py-4">' +
            '<div class="spinner-border loyal-theme-spinner" role="status"></div>' +
            '<span class="ml-2">Loading...</span></td></tr>'
        );
    }

    function renderPagination(total, filtered) {
        var $pager = $responsive.next('.loyal-ajax-pagination');
        if (!$pager.length) {
            $pager = $('<div class="loyal-ajax-pagination d-flex justify-content-between align-items-center mt-3 flex-wrap"></div>');
            $responsive.after($pager);
        }

        var lastPage = Math.max(1, Math.ceil(filtered / state.perPage));
        if (state.page > lastPage) {
            state.page = lastPage;
        }

        var info = 'Showing ' + (filtered === 0 ? 0 : ((state.page - 1) * state.perPage + 1)) +
            ' to ' + Math.min(state.page * state.perPage, filtered) + ' of ' + filtered + ' entries';

        var pages = '';
        if (state.page > 1) {
            pages += '<button type="button" class="btn btn-sm btn-theme loyal-page-btn" data-page="' + (state.page - 1) + '">Prev</button> ';
        }
        pages += '<span class="mx-2 small text-muted">Page ' + state.page + ' / ' + lastPage + '</span>';
        if (state.page < lastPage) {
            pages += '<button type="button" class="btn btn-sm btn-theme loyal-page-btn" data-page="' + (state.page + 1) + '">Next</button>';
        }

        $pager.html('<div class="small text-muted">' + info + '</div><div>' + pages + '</div>');

        $pager.off('click.loyalPager').on('click.loyalPager', '.loyal-page-btn', function () {
            state.page = parseInt($(this).data('page'), 10) || 1;
            load();
        });
    }

    function renderRows(res) {
        var rows = res.rows || res.data || [];

        if (!rows.length) {
            $tbody.html('<tr><td colspan="' + colCount + '" class="text-center text-muted py-4">No records found.</td></tr>');
            return;
        }

        var html = '';
        var startSn = res.start !== undefined ? parseInt(res.start, 10) : (state.page - 1) * state.perPage;

        rows.forEach(function (row, index) {
            html += '<tr>';
            html += '<td>' + (startSn + index + 1) + '</td>';

            if (row.cells) {
                row.cells.forEach(function (cell) {
                    html += '<td>' + cell + '</td>';
                });
            } else {
                columns.forEach(function (col) {
                    html += '<td>' + (row[col.data] !== undefined && row[col.data] !== null ? row[col.data] : '') + '</td>';
                });
            }

            html += '</tr>';
        });

        $tbody.html(html);

        initBootstrapToggles($tbody);

        if (typeof options.drawCallback === 'function') {
            options.drawCallback($table);
        }
    }

    function load() {
        state.draw += 1;
        showLoading();

        $.ajax({
            url: url,
            type: 'GET',
            data: buildParams(),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            success: function (res) {
                if (res.rows) {
                    renderRows(res);
                    if (res.pagination) {
                        renderPagination(res.pagination.total, res.pagination.total);
                    }
                    return;
                }

                var filtered = res.recordsFiltered !== undefined ? res.recordsFiltered : (res.data ? res.data.length : 0);
                var total = res.recordsTotal !== undefined ? res.recordsTotal : filtered;
                res.start = res.start !== undefined ? res.start : (state.page - 1) * state.perPage;
                renderRows(res);
                renderPagination(total, filtered);
            },
            error: function (xhr) {
                console.error('List AJAX failed:', xhr.status, xhr.statusText);
                if (xhr.responseText) {
                    console.error(xhr.responseText.substring(0, 800));
                }
                $tbody.html('<tr><td colspan="' + colCount + '" class="text-center text-danger py-4">Failed to load data. Please refresh.</td></tr>');
            }
        });
    }

    function ensureControls() {
        if ($responsive.prev('.loyal-table-toolbar').length) {
            return;
        }

        var $toolbar = $(
            '<div class="loyal-table-toolbar row align-items-center mb-3">' +
                '<div class="col-md-6">' +
                    '<label class="mb-0 small">Show ' +
                    '<select class="form-control form-control-sm d-inline-block w-auto loyal-per-page">' +
                        '<option value="10">10</option>' +
                        '<option value="25">25</option>' +
                        '<option value="50">50</option>' +
                        '<option value="100">100</option>' +
                    '</select> entries</label>' +
                '</div>' +
                '<div class="col-md-6 text-md-right">' +
                    '<label class="mb-0 small">Search: <input type="text" class="form-control form-control-sm d-inline-block w-auto loyal-search" placeholder="Type to search..."></label>' +
                '</div>' +
            '</div>'
        );

        $responsive.before($toolbar);
        $toolbar.find('.loyal-per-page').val(String(state.perPage));

        $toolbar.on('change', '.loyal-per-page', function () {
            state.perPage = parseInt($(this).val(), 10) || 10;
            state.page = 1;
            load();
        });

        $toolbar.on('input', '.loyal-search', function () {
            var val = $(this).val();
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function () {
                state.search = val;
                state.page = 1;
                load();
            }, 350);
        });
    }

    ensureControls();
    load();

    var api = {
        reload: function () {
            load();
        },
        ajax: {
            reload: function () {
                load();
            }
        }
    };

    return api;
}

function initServerDataTable(selector, ajaxConfig, columns, options) {
    options = options || {};
    columns = columns || [];

    var url = typeof ajaxConfig === 'string' ? ajaxConfig : (ajaxConfig && ajaxConfig.url ? ajaxConfig.url : '');
    var extraData = null;

    if (typeof ajaxConfig === 'object' && typeof ajaxConfig.data === 'function') {
        extraData = ajaxConfig.data;
    }

    return initSimpleAjaxTable(selector, url, columns, {
        pageLength: options.pageLength || 10,
        drawCallback: options.drawCallback,
        extraData: extraData
    });
}

window.initSimpleAjaxTable = initSimpleAjaxTable;
window.initServerDataTable = initServerDataTable;
window.initBootstrapToggles = initBootstrapToggles;
