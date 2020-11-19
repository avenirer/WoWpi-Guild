import $ from 'jquery';
import dt from 'datatables.net';

$(document).ready( function () {
    $('#' + wowpiRosterAjax.datatable_id).DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": wowpiRosterAjax.ajaxurl,
            "paging": false,
            //"length": parseInt(wowpiRosterAjax.datatable_length),
            "columns": [
                {
                    'data': 'name',
                    'sortable': true,
                },
                {
                    'data': 'race',
                    'render': function (data, type, row) {
                        return '<div class="icon"><img src="/wp-content/plugins/wowpi-guild/assets/icon/race_' + data.icon + '.jpg" alt="' + data.name + '" /></div>';
                    },
                    'sortable': false,
                },
                {
                    'data': 'class',
                    'render': function(data, type, row) {
                        return '<div class="icon"><img src="/wp-content/plugins/wowpi-guild/assets/icon/classicon_' + data.id + '.jpg" alt="' + data.name + '" /></div>';
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
            ]
        }
    );
} );