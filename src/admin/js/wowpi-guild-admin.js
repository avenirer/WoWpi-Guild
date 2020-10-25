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
    $(".synchronize").click( function(e) {
        let api = $(this).data('api');
        let resultsDiv = $('#synchronize-' + api + '-results .results');
        $(resultsDiv).html('');
        e.preventDefault();
        if('classes' === api) {
            getRemoteClasses();
        }
        else if('achievements' == api) {
            getRemoteAchievements();
        }
        else {
            getRemoteData(api);
        }
    });
});

function getRemoteData(api) {
    let resultsDiv = $('#synchronize-' + api + '-results .results');

    $.ajax({
        type: "post",
        dataType: "json",
        url: wowpiGuildAdminAjax.ajaxurl,
        data: {
            action: "getRemoteDataRegistered",
            security: wowpiGuildAdminAjax.ajaxnonce,
            retrieve: api
        },
        /*
        beforeSend: function() {
            let newNodeStarting = document.createElement('div');
            newNodeStarting.innerHTML = 'Started to retrieve ' + item.toString();
            resultsDiv.appendChild(newNodeStarting);
        },*/
        success: function (response) {
            let newNodeEnding = document.createElement('p');
            if (response.type === "success") {
                let message = '<p>' + capitalize(api.toString()) + ' data was retrieved successfully. Inserted: '
                    + response.data.inserted
                    + '. Updated: ' + response.data.updated + '</p>';
                resultsDiv.append(message);
            } else {
                let message  = '<p><strong>Could not retrieve ' + api.toString() + ' data</strong></p>';
                resultsDiv.append(message);
                console.log('not working');
                //alert("Your like could not be added");
            }
        }
    });
}

function getRemoteClasses(classId) {
    let resultsContainerDiv = $('#synchronize-classes-results');
    let resultsDiv =  $('#synchronize-classes-results .results');


    let passedData = {
        action: "getRemoteDataRegistered",
        security: wowpiGuildAdminAjax.ajaxnonce,
        retrieve: 'classes'
    }

    if(classId) {
        passedData.retrieve = 'specializations';
        passedData.classId = classId;
    }

    $.ajax({
        type: "post",
        dataType: "json",
        url: wowpiGuildAdminAjax.ajaxurl,
        data: passedData,

        beforeSend: function() {
            resultsContainerDiv.addClass('loading');
        },
        success: function (response) {
            if (response.type === "success") {
                let responseData = response.data;
                if(responseData.hasOwnProperty('classes')) {
                    const classes = responseData.classes;
                    totalQueries = Object.keys(classes).length;
                    //newNodeEnding.innerHTML = responseData.message;
                    for(const property in classes) {
                        const classObj = classes[property];
                        getRemoteClasses(classObj.id);
                    }
                }
                else if(responseData.hasOwnProperty('message')) {
                    totalQueries--;
                    $(resultsDiv).append('<p>' + responseData.message + '</p>');
                }
                if(totalQueries == 0) {
                    resultsContainerDiv.removeClass('loading');
                }
            } else {
                $(resultsDiv).append('<p><strong>Could not retrieve class data</strong></p>');
                console.log('not working');
                //alert("Your like could not be added");
            }
        }
    });
}


function getRemoteAchievements(categoryId) {

    let resultsContainerDiv = $('#synchronize-classes-results');
    let resultsDiv = $('#synchronize-achievements-results .results');

    let passedData = {
        action: "getRemoteData",
        security: wowpiGuildAdminAjax.ajaxnonce,
        retrieve: 'achievementCategories'
    }

    if(categoryId) {
        passedData.retrieve = 'achievements';
        passedData.categoryId = categoryId;
    }

    $.ajax({
        type: "post",
        dataType: "json",
        url: wowpiGuildAdminAjax.ajaxurl,
        data: passedData,

        beforeSend: function() {
            resultsDiv.classList.add('loading');
        },
        success: function (response) {
            if (response.type === "success") {
                let responseData = response.data;
                if(responseData.hasOwnProperty('categories')) {
                    const categories = responseData.categories;
                    totalQueries = Object.keys(categories).length;
                    for(const property in categories) {
                        const categoryObj = categories[property];
                        getRemoteAchievements(categoryObj.id);
                    }
                }
                else if(responseData.hasOwnProperty('message')) {
                    totalQueries--;
                    $(resultsDiv).append('<p>' + responseData.message + '</p>');
                }
                if(totalQueries == 0) {
                    resultsContainerDiv.removeClass('loading');
                }
            } else {
                $(resultsDiv).append('<p><strong>Could not retrieve class data</strong></p>');
                console.log('not working');
                //alert("Your like could not be added");
            }
        }
    });
}





const capitalize = (s) => {
    if (typeof s !== 'string') return ''
    return s.charAt(0).toUpperCase() + s.slice(1)
}

/*
$( document ).ajaxComplete(function( event, request, settings ) {
    console.log(event, request, settings);
    //$( "#msg" ).append( "<li>Request Complete.</li>" );
});
 */