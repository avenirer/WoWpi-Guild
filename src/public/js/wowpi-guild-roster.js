import $ from 'jquery';
import dt from 'datatables.net';

$(document).ready( function () {
    const tableColumns = ['name', 'race', 'class', 'role', 'level', 'rank'];

    $('.wowpi-roster').each( function() {
        let tableRanks = $(this).data('ranks');
        let dataTableOpts = {
            "processing": true,
            "serverSide": true,
            "ajax": wowpiRosterAjax.ajaxurl,
            "paging": true,
            "pageLength": parseInt(rows),
            "columns": [
                {
                    'data': 'name',
                    'sortable': true,
                },
                {
                    'data': 'race',
                    'render': function (data, type, row) {
                        return '<div class="icon"><img src="/wp-content/plugins/wowpi-guild/assets/icon/' + data.icon + '" alt="' + data.name + '" /></div>';
                    },
                    'sortable': false,
                },
                {
                    'data': 'class',
                    'render': function (data, type, row) {
                        return '<div class="icon"><img src="/wp-content/plugins/wowpi-guild/assets/icon/' + data.icon + '" alt="' + data.name + '" /></div>';
                    },
                    'sortable': false,
                },
                {
                    'data': 'role',
                    'render': function (data, type, row) {
                        return '<div class="icon"><img src="/wp-content/plugins/wowpi-guild/assets/icon/role_' + data.type + '.png" alt="' + data.name + '" /></div>';
                    },
                    'sortable': false,
                },
                {
                    'data': 'level',
                    'sortable': true,
                },
                {
                    'data': 'rank',
                    'sortable': false,
                },
            ],
            'searchCols' : [
                null,
                null,
                null,
                null,
                null,
                { "search": tableRanks}
            ]
        }

        let rows = $(this).data('length');
        dataTableOpts.pageLength = parseInt(rows);

        let ordering = [];
        let orderBy = $(this).data('orderby');
        let orderByCols = orderBy.split('|');

        for( let i=0; i< orderByCols.length; i++) {
            let orderByColOrdering = orderByCols[i].split(' ');
            let col = tableColumns.indexOf(orderByColOrdering[0]);
            let order = orderByColOrdering[1] || 'asc';
            let newOrdering = [col, order];
            ordering.push(newOrdering);
        }
        if( ordering.length > 0 ) {
            dataTableOpts.order = ordering;
        }

        /*
        l - length changing input control
        f - filtering input
        t - The table
        i - Table information summary
        p - pagination control
        r - processing display element
        */

        let dom = '';

        let showSelectPageLength = parseInt($(this).data('showselectlength'));
        if(1 === showSelectPageLength) {
            dom += 'l';
        }

        let showSearch = parseInt($(this).data('showsearch'));
        if(1 === showSearch) {
            dom += 'f';
        }

        dom += 'rtip';

        dataTableOpts.dom = dom;



        $(this).DataTable( dataTableOpts );
    })
});