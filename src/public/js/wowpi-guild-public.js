import $ from 'jquery';

/*
document.addEventListener('DOMContentLoaded', (event) => {
    document.getElementById('synchronize-static').onclick = function(e){
        console.log('click');
    }
});
*/
let totalQueries = 0;
$(document).ready( function() {
    $(".synchronize-guild").click( function(e) {
        let synchAll = $('#synchall').is(":checked");
        console.log(synchAll);
        let forced = $(this).data('forced');
        let resultsDiv = $('#synchronize-guild-results .results');
        $(resultsDiv).html('');
        getGuildData(forced, synchAll);
        e.preventDefault();
    });
});


function getGuildData(forced, synchAll) {
    let resultsContainerDiv = $('#synchronize-guild-results');
    let resultsDiv = $('#synchronize-guild-results .results');
    $.ajax({
        type: "post",
        dataType: "json",
        url: wowpiGuildPublicAjax.ajaxurl,
        data: {
            action: "getRemoteData",
            security: wowpiGuildPublicAjax.ajaxnoncepublic,
            retrieve: 'roster',
            forced: forced
        },
        beforeSend: function() {
            resultsContainerDiv.addClass('loading');
        },
        success: function (response) {
            if (response.type === "success") {
                let responseData = response.data;
                if(responseData.hasOwnProperty('roster')) {
                    const roster = responseData.roster;
                    totalQueries = Object.keys(roster).length;
                    for(const property in roster) {
                        const characterObj = roster[property];
                        importRemoteMember(characterObj.id, forced, synchAll);
                    }
                }
            } else {
                $(resultsDiv).append('<p><strong>Could not retrieve guild data</strong></p>');
                console.log('not working');
                //alert("Your like could not be added");
            }
        }
    });
}

function importRemoteMember(characterId, forced, synchAll) {
    let resultsContainerDiv = $('#synchronize-guild-results');
    let resultsDiv = $('#synchronize-guild-results .results');
    $.ajax({
        type: "post",
        dataType: "json",
        url: wowpiGuildPublicAjax.ajaxurl,
        data: {
            action: "getRemoteData",
            security: wowpiGuildPublicAjax.ajaxnoncepublic,
            retrieve: 'character',
            forced: forced,
            characterId: characterId,
            synchAll: synchAll
        },
        /*
        beforeSend: function() {
            let newNodeStarting = document.createElement('div');
            newNodeStarting.innerHTML = 'Started to retrieve ' + item.toString();
            resultsDiv.appendChild(newNodeStarting);
        },*/
        success: function (response) {
            if (response.type === "success") {
                let responseData = response.data;
                totalQueries--;
                $(resultsDiv).append('<p>' + responseData.message + '</p>');
            } else {
                let responseData = response.data;
                totalQueries--;
                $(resultsDiv).append('<p>There was a problem when updating a character. ' + responseData.message + '</p>');
            }
            console.log(totalQueries);
            if(totalQueries === 0) {
                $(resultsContainerDiv).removeClass('loading');
            }
        }
    });
}


