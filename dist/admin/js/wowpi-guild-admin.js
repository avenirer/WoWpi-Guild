/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/admin/js/wowpi-guild-admin.js":
/*!*******************************************!*\
  !*** ./src/admin/js/wowpi-guild-admin.js ***!
  \*******************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);

/*
document.addEventListener('DOMContentLoaded', (event) => {
    document.getElementById('synchronize-static').onclick = function(e){
        console.log('click');
    }
});
*/

var totalQueries = 0;
jquery__WEBPACK_IMPORTED_MODULE_0___default()(document).ready(function () {
  jquery__WEBPACK_IMPORTED_MODULE_0___default()(".synchronize").click(function (e) {
    var api = jquery__WEBPACK_IMPORTED_MODULE_0___default()(this).data('api');
    var resultsDiv = jquery__WEBPACK_IMPORTED_MODULE_0___default()('#synchronize-' + api + '-results .results');
    jquery__WEBPACK_IMPORTED_MODULE_0___default()(resultsDiv).html('');
    e.preventDefault();

    if ('classes' === api) {
      getRemoteClasses();
    } else if ('achievements' == api) {
      getRemoteAchievements();
    } else {
      getRemoteData(api);
    }
  });
});

function getRemoteData(api) {
  var resultsDiv = jquery__WEBPACK_IMPORTED_MODULE_0___default()('#synchronize-' + api + '-results .results');
  jquery__WEBPACK_IMPORTED_MODULE_0___default.a.ajax({
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
    success: function success(response) {
      var newNodeEnding = document.createElement('p');

      if (response.type === "success") {
        var message = '<p>' + capitalize(api.toString()) + ' data was retrieved successfully. Inserted: ' + response.data.inserted + '. Updated: ' + response.data.updated + '</p>';
        resultsDiv.append(message);
      } else {
        var _message = '<p><strong>Could not retrieve ' + api.toString() + ' data</strong></p>';

        resultsDiv.append(_message);
        console.log('not working'); //alert("Your like could not be added");
      }
    }
  });
}

function getRemoteClasses(classId) {
  var resultsContainerDiv = jquery__WEBPACK_IMPORTED_MODULE_0___default()('#synchronize-classes-results');
  var resultsDiv = jquery__WEBPACK_IMPORTED_MODULE_0___default()('#synchronize-classes-results .results');
  var passedData = {
    action: "getRemoteDataRegistered",
    security: wowpiGuildAdminAjax.ajaxnonce,
    retrieve: 'classes'
  };

  if (classId) {
    passedData.retrieve = 'specializations';
    passedData.classId = classId;
  }

  jquery__WEBPACK_IMPORTED_MODULE_0___default.a.ajax({
    type: "post",
    dataType: "json",
    url: wowpiGuildAdminAjax.ajaxurl,
    data: passedData,
    beforeSend: function beforeSend() {
      resultsContainerDiv.addClass('loading');
    },
    success: function success(response) {
      if (response.type === "success") {
        var responseData = response.data;

        if (responseData.hasOwnProperty('classes')) {
          var classes = responseData.classes;
          totalQueries = Object.keys(classes).length; //newNodeEnding.innerHTML = responseData.message;

          for (var property in classes) {
            var classObj = classes[property];
            getRemoteClasses(classObj.id);
          }
        } else if (responseData.hasOwnProperty('message')) {
          totalQueries--;
          jquery__WEBPACK_IMPORTED_MODULE_0___default()(resultsDiv).append('<p>' + responseData.message + '</p>');
        }

        if (totalQueries == 0) {
          resultsContainerDiv.removeClass('loading');
        }
      } else {
        jquery__WEBPACK_IMPORTED_MODULE_0___default()(resultsDiv).append('<p><strong>Could not retrieve class data</strong></p>');
        console.log('not working'); //alert("Your like could not be added");
      }
    }
  });
}

function getRemoteAchievements(categoryId) {
  var resultsContainerDiv = jquery__WEBPACK_IMPORTED_MODULE_0___default()('#synchronize-classes-results');
  var resultsDiv = jquery__WEBPACK_IMPORTED_MODULE_0___default()('#synchronize-achievements-results .results');
  var passedData = {
    action: "getRemoteData",
    security: wowpiGuildAdminAjax.ajaxnonce,
    retrieve: 'achievementCategories'
  };

  if (categoryId) {
    passedData.retrieve = 'achievements';
    passedData.categoryId = categoryId;
  }

  jquery__WEBPACK_IMPORTED_MODULE_0___default.a.ajax({
    type: "post",
    dataType: "json",
    url: wowpiGuildAdminAjax.ajaxurl,
    data: passedData,
    beforeSend: function beforeSend() {
      resultsDiv.classList.add('loading');
    },
    success: function success(response) {
      if (response.type === "success") {
        var responseData = response.data;

        if (responseData.hasOwnProperty('categories')) {
          var categories = responseData.categories;
          totalQueries = Object.keys(categories).length;

          for (var property in categories) {
            var categoryObj = categories[property];
            getRemoteAchievements(categoryObj.id);
          }
        } else if (responseData.hasOwnProperty('message')) {
          totalQueries--;
          jquery__WEBPACK_IMPORTED_MODULE_0___default()(resultsDiv).append('<p>' + responseData.message + '</p>');
        }

        if (totalQueries == 0) {
          resultsContainerDiv.removeClass('loading');
        }
      } else {
        jquery__WEBPACK_IMPORTED_MODULE_0___default()(resultsDiv).append('<p><strong>Could not retrieve class data</strong></p>');
        console.log('not working'); //alert("Your like could not be added");
      }
    }
  });
}

var capitalize = function capitalize(s) {
  if (typeof s !== 'string') return '';
  return s.charAt(0).toUpperCase() + s.slice(1);
};
/*
$( document ).ajaxComplete(function( event, request, settings ) {
    console.log(event, request, settings);
    //$( "#msg" ).append( "<li>Request Complete.</li>" );
});
 */

/***/ }),

/***/ 0:
/*!*************************************************!*\
  !*** multi ./src/admin/js/wowpi-guild-admin.js ***!
  \*************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! D:\laragon\www\wowpi\wp-content\plugins\wowpi-guild\src\admin\js\wowpi-guild-admin.js */"./src/admin/js/wowpi-guild-admin.js");


/***/ }),

/***/ "jquery":
/*!*************************!*\
  !*** external "jQuery" ***!
  \*************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = jQuery;

/***/ })

/******/ });
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAiLCJ3ZWJwYWNrOi8vLy4vc3JjL2FkbWluL2pzL3dvd3BpLWd1aWxkLWFkbWluLmpzIiwid2VicGFjazovLy9leHRlcm5hbCBcImpRdWVyeVwiIl0sIm5hbWVzIjpbInRvdGFsUXVlcmllcyIsIiQiLCJkb2N1bWVudCIsInJlYWR5IiwiY2xpY2siLCJlIiwiYXBpIiwiZGF0YSIsInJlc3VsdHNEaXYiLCJodG1sIiwicHJldmVudERlZmF1bHQiLCJnZXRSZW1vdGVDbGFzc2VzIiwiZ2V0UmVtb3RlQWNoaWV2ZW1lbnRzIiwiZ2V0UmVtb3RlRGF0YSIsImFqYXgiLCJ0eXBlIiwiZGF0YVR5cGUiLCJ1cmwiLCJ3b3dwaUd1aWxkQWRtaW5BamF4IiwiYWpheHVybCIsImFjdGlvbiIsInNlY3VyaXR5IiwiYWpheG5vbmNlIiwicmV0cmlldmUiLCJzdWNjZXNzIiwicmVzcG9uc2UiLCJuZXdOb2RlRW5kaW5nIiwiY3JlYXRlRWxlbWVudCIsIm1lc3NhZ2UiLCJjYXBpdGFsaXplIiwidG9TdHJpbmciLCJpbnNlcnRlZCIsInVwZGF0ZWQiLCJhcHBlbmQiLCJjb25zb2xlIiwibG9nIiwiY2xhc3NJZCIsInJlc3VsdHNDb250YWluZXJEaXYiLCJwYXNzZWREYXRhIiwiYmVmb3JlU2VuZCIsImFkZENsYXNzIiwicmVzcG9uc2VEYXRhIiwiaGFzT3duUHJvcGVydHkiLCJjbGFzc2VzIiwiT2JqZWN0Iiwia2V5cyIsImxlbmd0aCIsInByb3BlcnR5IiwiY2xhc3NPYmoiLCJpZCIsInJlbW92ZUNsYXNzIiwiY2F0ZWdvcnlJZCIsImNsYXNzTGlzdCIsImFkZCIsImNhdGVnb3JpZXMiLCJjYXRlZ29yeU9iaiIsInMiLCJjaGFyQXQiLCJ0b1VwcGVyQ2FzZSIsInNsaWNlIl0sIm1hcHBpbmdzIjoiO1FBQUE7UUFDQTs7UUFFQTtRQUNBOztRQUVBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNB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Q0FBQ0MsR0FBUixDQUFZLGFBQVosRUFGRyxDQUdIO0FBQ0g7QUFDSjtBQWhDRSxHQUFQO0FBa0NIOztBQU1ELElBQU1OLFVBQVUsR0FBRyxTQUFiQSxVQUFhLENBQUMyQixDQUFELEVBQU87QUFDdEIsTUFBSSxPQUFPQSxDQUFQLEtBQWEsUUFBakIsRUFBMkIsT0FBTyxFQUFQO0FBQzNCLFNBQU9BLENBQUMsQ0FBQ0MsTUFBRixDQUFTLENBQVQsRUFBWUMsV0FBWixLQUE0QkYsQ0FBQyxDQUFDRyxLQUFGLENBQVEsQ0FBUixDQUFuQztBQUNILENBSEQ7QUFLQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsRzs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7QUN2TEEsd0IiLCJmaWxlIjoid293cGktZ3VpbGQtYWRtaW4uanMiLCJzb3VyY2VzQ29udGVudCI6WyIgXHQvLyBUaGUgbW9kdWxlIGNhY2hlXG4gXHR2YXIgaW5zdGFsbGVkTW9kdWxlcyA9IHt9O1xuXG4gXHQvLyBUaGUgcmVxdWlyZSBmdW5jdGlvblxuIFx0ZnVuY3Rpb24gX193ZWJwYWNrX3JlcXVpcmVfXyhtb2R1bGVJZCkge1xuXG4gXHRcdC8vIENoZWNrIGlmIG1vZHVsZSBpcyBpbiBjYWNoZVxuIFx0XHRpZihpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXSkge1xuIFx0XHRcdHJldHVybiBpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXS5leHBvcnRzO1xuIFx0XHR9XG4gXHRcdC8vIENyZWF0ZSBhIG5ldyBtb2R1bGUgKGFuZCBwdXQgaXQgaW50byB0aGUgY2FjaGUpXG4gXHRcdHZhciBtb2R1bGUgPSBpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXSA9IHtcbiBcdFx0XHRpOiBtb2R1bGVJZCxcbiBcdFx0XHRsOiBmYWxzZSxcbiBcdFx0XHRleHBvcnRzOiB7fVxuIFx0XHR9O1xuXG4gXHRcdC8vIEV4ZWN1dGUgdGhlIG1vZHVsZSBmdW5jdGlvblxuIFx0XHRtb2R1bGVzW21vZHVsZUlkXS5jYWxsKG1vZHVsZS5leHBvcnRzLCBtb2R1bGUsIG1vZHVsZS5leHBvcnRzLCBfX3dlYnBhY2tfcmVxdWlyZV9fKTtcblxuIFx0XHQvLyBGbGFnIHRoZSBtb2R1bGUgYXMgbG9hZGVkXG4gXHRcdG1vZHVsZS5sID0gdHJ1ZTtcblxuIFx0XHQvLyBSZXR1cm4gdGhlIGV4cG9ydHMgb2YgdGhlIG1vZHVsZVxuIFx0XHRyZXR1cm4gbW9kdWxlLmV4cG9ydHM7XG4gXHR9XG5cblxuIFx0Ly8gZXhwb3NlIHRoZSBtb2R1bGVzIG9iamVjdCAoX193ZWJwYWNrX21vZHVsZXNfXylcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubSA9IG1vZHVsZXM7XG5cbiBcdC8vIGV4cG9zZSB0aGUgbW9kdWxlIGNhY2hlXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLmMgPSBpbnN0YWxsZWRNb2R1bGVzO1xuXG4gXHQvLyBkZWZpbmUgZ2V0dGVyIGZ1bmN0aW9uIGZvciBoYXJtb255IGV4cG9ydHNcbiBcdF9fd2VicGFja19yZXF1aXJlX18uZCA9IGZ1bmN0aW9uKGV4cG9ydHMsIG5hbWUsIGdldHRlcikge1xuIFx0XHRpZighX193ZWJwYWNrX3JlcXVpcmVfXy5vKGV4cG9ydHMsIG5hbWUpKSB7XG4gXHRcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIG5hbWUsIHsgZW51bWVyYWJsZTogdHJ1ZSwgZ2V0OiBnZXR0ZXIgfSk7XG4gXHRcdH1cbiBcdH07XG5cbiBcdC8vIGRlZmluZSBfX2VzTW9kdWxlIG9uIGV4cG9ydHNcbiBcdF9fd2VicGFja19yZXF1aXJlX18uciA9IGZ1bmN0aW9uKGV4cG9ydHMpIHtcbiBcdFx0aWYodHlwZW9mIFN5bWJvbCAhPT0gJ3VuZGVmaW5lZCcgJiYgU3ltYm9sLnRvU3RyaW5nVGFnKSB7XG4gXHRcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIFN5bWJvbC50b1N0cmluZ1RhZywgeyB2YWx1ZTogJ01vZHVsZScgfSk7XG4gXHRcdH1cbiBcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsICdfX2VzTW9kdWxlJywgeyB2YWx1ZTogdHJ1ZSB9KTtcbiBcdH07XG5cbiBcdC8vIGNyZWF0ZSBhIGZha2UgbmFtZXNwYWNlIG9iamVjdFxuIFx0Ly8gbW9kZSAmIDE6IHZhbHVlIGlzIGEgbW9kdWxlIGlkLCByZXF1aXJlIGl0XG4gXHQvLyBtb2RlICYgMjogbWVyZ2UgYWxsIHByb3BlcnRpZXMgb2YgdmFsdWUgaW50byB0aGUgbnNcbiBcdC8vIG1vZGUgJiA0OiByZXR1cm4gdmFsdWUgd2hlbiBhbHJlYWR5IG5zIG9iamVjdFxuIFx0Ly8gbW9kZSAmIDh8MTogYmVoYXZlIGxpa2UgcmVxdWlyZVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy50ID0gZnVuY3Rpb24odmFsdWUsIG1vZGUpIHtcbiBcdFx0aWYobW9kZSAmIDEpIHZhbHVlID0gX193ZWJwYWNrX3JlcXVpcmVfXyh2YWx1ZSk7XG4gXHRcdGlmKG1vZGUgJiA4KSByZXR1cm4gdmFsdWU7XG4gXHRcdGlmKChtb2RlICYgNCkgJiYgdHlwZW9mIHZhbHVlID09PSAnb2JqZWN0JyAmJiB2YWx1ZSAmJiB2YWx1ZS5fX2VzTW9kdWxlKSByZXR1cm4gdmFsdWU7XG4gXHRcdHZhciBucyA9IE9iamVjdC5jcmVhdGUobnVsbCk7XG4gXHRcdF9fd2VicGFja19yZXF1aXJlX18ucihucyk7XG4gXHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShucywgJ2RlZmF1bHQnLCB7IGVudW1lcmFibGU6IHRydWUsIHZhbHVlOiB2YWx1ZSB9KTtcbiBcdFx0aWYobW9kZSAmIDIgJiYgdHlwZW9mIHZhbHVlICE9ICdzdHJpbmcnKSBmb3IodmFyIGtleSBpbiB2YWx1ZSkgX193ZWJwYWNrX3JlcXVpcmVfXy5kKG5zLCBrZXksIGZ1bmN0aW9uKGtleSkgeyByZXR1cm4gdmFsdWVba2V5XTsgfS5iaW5kKG51bGwsIGtleSkpO1xuIFx0XHRyZXR1cm4gbnM7XG4gXHR9O1xuXG4gXHQvLyBnZXREZWZhdWx0RXhwb3J0IGZ1bmN0aW9uIGZvciBjb21wYXRpYmlsaXR5IHdpdGggbm9uLWhhcm1vbnkgbW9kdWxlc1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5uID0gZnVuY3Rpb24obW9kdWxlKSB7XG4gXHRcdHZhciBnZXR0ZXIgPSBtb2R1bGUgJiYgbW9kdWxlLl9fZXNNb2R1bGUgP1xuIFx0XHRcdGZ1bmN0aW9uIGdldERlZmF1bHQoKSB7IHJldHVybiBtb2R1bGVbJ2RlZmF1bHQnXTsgfSA6XG4gXHRcdFx0ZnVuY3Rpb24gZ2V0TW9kdWxlRXhwb3J0cygpIHsgcmV0dXJuIG1vZHVsZTsgfTtcbiBcdFx0X193ZWJwYWNrX3JlcXVpcmVfXy5kKGdldHRlciwgJ2EnLCBnZXR0ZXIpO1xuIFx0XHRyZXR1cm4gZ2V0dGVyO1xuIFx0fTtcblxuIFx0Ly8gT2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eS5jYWxsXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm8gPSBmdW5jdGlvbihvYmplY3QsIHByb3BlcnR5KSB7IHJldHVybiBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGwob2JqZWN0LCBwcm9wZXJ0eSk7IH07XG5cbiBcdC8vIF9fd2VicGFja19wdWJsaWNfcGF0aF9fXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnAgPSBcIlwiO1xuXG5cbiBcdC8vIExvYWQgZW50cnkgbW9kdWxlIGFuZCByZXR1cm4gZXhwb3J0c1xuIFx0cmV0dXJuIF9fd2VicGFja19yZXF1aXJlX18oX193ZWJwYWNrX3JlcXVpcmVfXy5zID0gMCk7XG4iLCJpbXBvcnQgJCBmcm9tICdqcXVlcnknO1xyXG5cclxuLypcclxuZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcignRE9NQ29udGVudExvYWRlZCcsIChldmVudCkgPT4ge1xyXG4gICAgZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3N5bmNocm9uaXplLXN0YXRpYycpLm9uY2xpY2sgPSBmdW5jdGlvbihlKXtcclxuICAgICAgICBjb25zb2xlLmxvZygnY2xpY2snKTtcclxuICAgIH1cclxufSk7XHJcbiovXHJcbmxldCB0b3RhbFF1ZXJpZXMgPSAwO1xyXG4kKGRvY3VtZW50KS5yZWFkeSggZnVuY3Rpb24oKSB7XHJcbiAgICAkKFwiLnN5bmNocm9uaXplXCIpLmNsaWNrKCBmdW5jdGlvbihlKSB7XHJcbiAgICAgICAgbGV0IGFwaSA9ICQodGhpcykuZGF0YSgnYXBpJyk7XHJcbiAgICAgICAgbGV0IHJlc3VsdHNEaXYgPSAkKCcjc3luY2hyb25pemUtJyArIGFwaSArICctcmVzdWx0cyAucmVzdWx0cycpO1xyXG4gICAgICAgICQocmVzdWx0c0RpdikuaHRtbCgnJyk7XHJcbiAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xyXG4gICAgICAgIGlmKCdjbGFzc2VzJyA9PT0gYXBpKSB7XHJcbiAgICAgICAgICAgIGdldFJlbW90ZUNsYXNzZXMoKTtcclxuICAgICAgICB9XHJcbiAgICAgICAgZWxzZSBpZignYWNoaWV2ZW1lbnRzJyA9PSBhcGkpIHtcclxuICAgICAgICAgICAgZ2V0UmVtb3RlQWNoaWV2ZW1lbnRzKCk7XHJcbiAgICAgICAgfVxyXG4gICAgICAgIGVsc2Uge1xyXG4gICAgICAgICAgICBnZXRSZW1vdGVEYXRhKGFwaSk7XHJcbiAgICAgICAgfVxyXG4gICAgfSk7XHJcbn0pO1xyXG5cclxuZnVuY3Rpb24gZ2V0UmVtb3RlRGF0YShhcGkpIHtcclxuICAgIGxldCByZXN1bHRzRGl2ID0gJCgnI3N5bmNocm9uaXplLScgKyBhcGkgKyAnLXJlc3VsdHMgLnJlc3VsdHMnKTtcclxuXHJcbiAgICAkLmFqYXgoe1xyXG4gICAgICAgIHR5cGU6IFwicG9zdFwiLFxyXG4gICAgICAgIGRhdGFUeXBlOiBcImpzb25cIixcclxuICAgICAgICB1cmw6IHdvd3BpR3VpbGRBZG1pbkFqYXguYWpheHVybCxcclxuICAgICAgICBkYXRhOiB7XHJcbiAgICAgICAgICAgIGFjdGlvbjogXCJnZXRSZW1vdGVEYXRhUmVnaXN0ZXJlZFwiLFxyXG4gICAgICAgICAgICBzZWN1cml0eTogd293cGlHdWlsZEFkbWluQWpheC5hamF4bm9uY2UsXHJcbiAgICAgICAgICAgIHJldHJpZXZlOiBhcGlcclxuICAgICAgICB9LFxyXG4gICAgICAgIC8qXHJcbiAgICAgICAgYmVmb3JlU2VuZDogZnVuY3Rpb24oKSB7XHJcbiAgICAgICAgICAgIGxldCBuZXdOb2RlU3RhcnRpbmcgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcclxuICAgICAgICAgICAgbmV3Tm9kZVN0YXJ0aW5nLmlubmVySFRNTCA9ICdTdGFydGVkIHRvIHJldHJpZXZlICcgKyBpdGVtLnRvU3RyaW5nKCk7XHJcbiAgICAgICAgICAgIHJlc3VsdHNEaXYuYXBwZW5kQ2hpbGQobmV3Tm9kZVN0YXJ0aW5nKTtcclxuICAgICAgICB9LCovXHJcbiAgICAgICAgc3VjY2VzczogZnVuY3Rpb24gKHJlc3BvbnNlKSB7XHJcbiAgICAgICAgICAgIGxldCBuZXdOb2RlRW5kaW5nID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgncCcpO1xyXG4gICAgICAgICAgICBpZiAocmVzcG9uc2UudHlwZSA9PT0gXCJzdWNjZXNzXCIpIHtcclxuICAgICAgICAgICAgICAgIGxldCBtZXNzYWdlID0gJzxwPicgKyBjYXBpdGFsaXplKGFwaS50b1N0cmluZygpKSArICcgZGF0YSB3YXMgcmV0cmlldmVkIHN1Y2Nlc3NmdWxseS4gSW5zZXJ0ZWQ6ICdcclxuICAgICAgICAgICAgICAgICAgICArIHJlc3BvbnNlLmRhdGEuaW5zZXJ0ZWRcclxuICAgICAgICAgICAgICAgICAgICArICcuIFVwZGF0ZWQ6ICcgKyByZXNwb25zZS5kYXRhLnVwZGF0ZWQgKyAnPC9wPic7XHJcbiAgICAgICAgICAgICAgICByZXN1bHRzRGl2LmFwcGVuZChtZXNzYWdlKTtcclxuICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgIGxldCBtZXNzYWdlICA9ICc8cD48c3Ryb25nPkNvdWxkIG5vdCByZXRyaWV2ZSAnICsgYXBpLnRvU3RyaW5nKCkgKyAnIGRhdGE8L3N0cm9uZz48L3A+JztcclxuICAgICAgICAgICAgICAgIHJlc3VsdHNEaXYuYXBwZW5kKG1lc3NhZ2UpO1xyXG4gICAgICAgICAgICAgICAgY29uc29sZS5sb2coJ25vdCB3b3JraW5nJyk7XHJcbiAgICAgICAgICAgICAgICAvL2FsZXJ0KFwiWW91ciBsaWtlIGNvdWxkIG5vdCBiZSBhZGRlZFwiKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH1cclxuICAgIH0pO1xyXG59XHJcblxyXG5mdW5jdGlvbiBnZXRSZW1vdGVDbGFzc2VzKGNsYXNzSWQpIHtcclxuICAgIGxldCByZXN1bHRzQ29udGFpbmVyRGl2ID0gJCgnI3N5bmNocm9uaXplLWNsYXNzZXMtcmVzdWx0cycpO1xyXG4gICAgbGV0IHJlc3VsdHNEaXYgPSAgJCgnI3N5bmNocm9uaXplLWNsYXNzZXMtcmVzdWx0cyAucmVzdWx0cycpO1xyXG5cclxuXHJcbiAgICBsZXQgcGFzc2VkRGF0YSA9IHtcclxuICAgICAgICBhY3Rpb246IFwiZ2V0UmVtb3RlRGF0YVJlZ2lzdGVyZWRcIixcclxuICAgICAgICBzZWN1cml0eTogd293cGlHdWlsZEFkbWluQWpheC5hamF4bm9uY2UsXHJcbiAgICAgICAgcmV0cmlldmU6ICdjbGFzc2VzJ1xyXG4gICAgfVxyXG5cclxuICAgIGlmKGNsYXNzSWQpIHtcclxuICAgICAgICBwYXNzZWREYXRhLnJldHJpZXZlID0gJ3NwZWNpYWxpemF0aW9ucyc7XHJcbiAgICAgICAgcGFzc2VkRGF0YS5jbGFzc0lkID0gY2xhc3NJZDtcclxuICAgIH1cclxuXHJcbiAgICAkLmFqYXgoe1xyXG4gICAgICAgIHR5cGU6IFwicG9zdFwiLFxyXG4gICAgICAgIGRhdGFUeXBlOiBcImpzb25cIixcclxuICAgICAgICB1cmw6IHdvd3BpR3VpbGRBZG1pbkFqYXguYWpheHVybCxcclxuICAgICAgICBkYXRhOiBwYXNzZWREYXRhLFxyXG5cclxuICAgICAgICBiZWZvcmVTZW5kOiBmdW5jdGlvbigpIHtcclxuICAgICAgICAgICAgcmVzdWx0c0NvbnRhaW5lckRpdi5hZGRDbGFzcygnbG9hZGluZycpO1xyXG4gICAgICAgIH0sXHJcbiAgICAgICAgc3VjY2VzczogZnVuY3Rpb24gKHJlc3BvbnNlKSB7XHJcbiAgICAgICAgICAgIGlmIChyZXNwb25zZS50eXBlID09PSBcInN1Y2Nlc3NcIikge1xyXG4gICAgICAgICAgICAgICAgbGV0IHJlc3BvbnNlRGF0YSA9IHJlc3BvbnNlLmRhdGE7XHJcbiAgICAgICAgICAgICAgICBpZihyZXNwb25zZURhdGEuaGFzT3duUHJvcGVydHkoJ2NsYXNzZXMnKSkge1xyXG4gICAgICAgICAgICAgICAgICAgIGNvbnN0IGNsYXNzZXMgPSByZXNwb25zZURhdGEuY2xhc3NlcztcclxuICAgICAgICAgICAgICAgICAgICB0b3RhbFF1ZXJpZXMgPSBPYmplY3Qua2V5cyhjbGFzc2VzKS5sZW5ndGg7XHJcbiAgICAgICAgICAgICAgICAgICAgLy9uZXdOb2RlRW5kaW5nLmlubmVySFRNTCA9IHJlc3BvbnNlRGF0YS5tZXNzYWdlO1xyXG4gICAgICAgICAgICAgICAgICAgIGZvcihjb25zdCBwcm9wZXJ0eSBpbiBjbGFzc2VzKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGNvbnN0IGNsYXNzT2JqID0gY2xhc3Nlc1twcm9wZXJ0eV07XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGdldFJlbW90ZUNsYXNzZXMoY2xhc3NPYmouaWQpO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIGVsc2UgaWYocmVzcG9uc2VEYXRhLmhhc093blByb3BlcnR5KCdtZXNzYWdlJykpIHtcclxuICAgICAgICAgICAgICAgICAgICB0b3RhbFF1ZXJpZXMtLTtcclxuICAgICAgICAgICAgICAgICAgICAkKHJlc3VsdHNEaXYpLmFwcGVuZCgnPHA+JyArIHJlc3BvbnNlRGF0YS5tZXNzYWdlICsgJzwvcD4nKTtcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIGlmKHRvdGFsUXVlcmllcyA9PSAwKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgcmVzdWx0c0NvbnRhaW5lckRpdi5yZW1vdmVDbGFzcygnbG9hZGluZycpO1xyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgJChyZXN1bHRzRGl2KS5hcHBlbmQoJzxwPjxzdHJvbmc+Q291bGQgbm90IHJldHJpZXZlIGNsYXNzIGRhdGE8L3N0cm9uZz48L3A+Jyk7XHJcbiAgICAgICAgICAgICAgICBjb25zb2xlLmxvZygnbm90IHdvcmtpbmcnKTtcclxuICAgICAgICAgICAgICAgIC8vYWxlcnQoXCJZb3VyIGxpa2UgY291bGQgbm90IGJlIGFkZGVkXCIpO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfVxyXG4gICAgfSk7XHJcbn1cclxuXHJcblxyXG5mdW5jdGlvbiBnZXRSZW1vdGVBY2hpZXZlbWVudHMoY2F0ZWdvcnlJZCkge1xyXG5cclxuICAgIGxldCByZXN1bHRzQ29udGFpbmVyRGl2ID0gJCgnI3N5bmNocm9uaXplLWNsYXNzZXMtcmVzdWx0cycpO1xyXG4gICAgbGV0IHJlc3VsdHNEaXYgPSAkKCcjc3luY2hyb25pemUtYWNoaWV2ZW1lbnRzLXJlc3VsdHMgLnJlc3VsdHMnKTtcclxuXHJcbiAgICBsZXQgcGFzc2VkRGF0YSA9IHtcclxuICAgICAgICBhY3Rpb246IFwiZ2V0UmVtb3RlRGF0YVwiLFxyXG4gICAgICAgIHNlY3VyaXR5OiB3b3dwaUd1aWxkQWRtaW5BamF4LmFqYXhub25jZSxcclxuICAgICAgICByZXRyaWV2ZTogJ2FjaGlldmVtZW50Q2F0ZWdvcmllcydcclxuICAgIH1cclxuXHJcbiAgICBpZihjYXRlZ29yeUlkKSB7XHJcbiAgICAgICAgcGFzc2VkRGF0YS5yZXRyaWV2ZSA9ICdhY2hpZXZlbWVudHMnO1xyXG4gICAgICAgIHBhc3NlZERhdGEuY2F0ZWdvcnlJZCA9IGNhdGVnb3J5SWQ7XHJcbiAgICB9XHJcblxyXG4gICAgJC5hamF4KHtcclxuICAgICAgICB0eXBlOiBcInBvc3RcIixcclxuICAgICAgICBkYXRhVHlwZTogXCJqc29uXCIsXHJcbiAgICAgICAgdXJsOiB3b3dwaUd1aWxkQWRtaW5BamF4LmFqYXh1cmwsXHJcbiAgICAgICAgZGF0YTogcGFzc2VkRGF0YSxcclxuXHJcbiAgICAgICAgYmVmb3JlU2VuZDogZnVuY3Rpb24oKSB7XHJcbiAgICAgICAgICAgIHJlc3VsdHNEaXYuY2xhc3NMaXN0LmFkZCgnbG9hZGluZycpO1xyXG4gICAgICAgIH0sXHJcbiAgICAgICAgc3VjY2VzczogZnVuY3Rpb24gKHJlc3BvbnNlKSB7XHJcbiAgICAgICAgICAgIGlmIChyZXNwb25zZS50eXBlID09PSBcInN1Y2Nlc3NcIikge1xyXG4gICAgICAgICAgICAgICAgbGV0IHJlc3BvbnNlRGF0YSA9IHJlc3BvbnNlLmRhdGE7XHJcbiAgICAgICAgICAgICAgICBpZihyZXNwb25zZURhdGEuaGFzT3duUHJvcGVydHkoJ2NhdGVnb3JpZXMnKSkge1xyXG4gICAgICAgICAgICAgICAgICAgIGNvbnN0IGNhdGVnb3JpZXMgPSByZXNwb25zZURhdGEuY2F0ZWdvcmllcztcclxuICAgICAgICAgICAgICAgICAgICB0b3RhbFF1ZXJpZXMgPSBPYmplY3Qua2V5cyhjYXRlZ29yaWVzKS5sZW5ndGg7XHJcbiAgICAgICAgICAgICAgICAgICAgZm9yKGNvbnN0IHByb3BlcnR5IGluIGNhdGVnb3JpZXMpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgY29uc3QgY2F0ZWdvcnlPYmogPSBjYXRlZ29yaWVzW3Byb3BlcnR5XTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgZ2V0UmVtb3RlQWNoaWV2ZW1lbnRzKGNhdGVnb3J5T2JqLmlkKTtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICBlbHNlIGlmKHJlc3BvbnNlRGF0YS5oYXNPd25Qcm9wZXJ0eSgnbWVzc2FnZScpKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgdG90YWxRdWVyaWVzLS07XHJcbiAgICAgICAgICAgICAgICAgICAgJChyZXN1bHRzRGl2KS5hcHBlbmQoJzxwPicgKyByZXNwb25zZURhdGEubWVzc2FnZSArICc8L3A+Jyk7XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICBpZih0b3RhbFF1ZXJpZXMgPT0gMCkge1xyXG4gICAgICAgICAgICAgICAgICAgIHJlc3VsdHNDb250YWluZXJEaXYucmVtb3ZlQ2xhc3MoJ2xvYWRpbmcnKTtcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgICQocmVzdWx0c0RpdikuYXBwZW5kKCc8cD48c3Ryb25nPkNvdWxkIG5vdCByZXRyaWV2ZSBjbGFzcyBkYXRhPC9zdHJvbmc+PC9wPicpO1xyXG4gICAgICAgICAgICAgICAgY29uc29sZS5sb2coJ25vdCB3b3JraW5nJyk7XHJcbiAgICAgICAgICAgICAgICAvL2FsZXJ0KFwiWW91ciBsaWtlIGNvdWxkIG5vdCBiZSBhZGRlZFwiKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH1cclxuICAgIH0pO1xyXG59XHJcblxyXG5cclxuXHJcblxyXG5cclxuY29uc3QgY2FwaXRhbGl6ZSA9IChzKSA9PiB7XHJcbiAgICBpZiAodHlwZW9mIHMgIT09ICdzdHJpbmcnKSByZXR1cm4gJydcclxuICAgIHJldHVybiBzLmNoYXJBdCgwKS50b1VwcGVyQ2FzZSgpICsgcy5zbGljZSgxKVxyXG59XHJcblxyXG4vKlxyXG4kKCBkb2N1bWVudCApLmFqYXhDb21wbGV0ZShmdW5jdGlvbiggZXZlbnQsIHJlcXVlc3QsIHNldHRpbmdzICkge1xyXG4gICAgY29uc29sZS5sb2coZXZlbnQsIHJlcXVlc3QsIHNldHRpbmdzKTtcclxuICAgIC8vJCggXCIjbXNnXCIgKS5hcHBlbmQoIFwiPGxpPlJlcXVlc3QgQ29tcGxldGUuPC9saT5cIiApO1xyXG59KTtcclxuICovIiwibW9kdWxlLmV4cG9ydHMgPSBqUXVlcnk7Il0sInNvdXJjZVJvb3QiOiIifQ==