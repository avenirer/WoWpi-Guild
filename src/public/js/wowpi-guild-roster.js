import $ from 'jquery';
import dt from 'datatables.net';
$(document).ready( function () {
    $('#wowpi_guild_roster').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": wowpiRosterAjax.ajaxurl,
            "columns": [
                {
                    'data': 'name',
                    'sortable': true,
                },
                {
                    'data': 'race',
                    'sortable': false,
                },
                {
                    'data': 'class',
                    'sortable': false,
                },
                {
                    'data': 'role',
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