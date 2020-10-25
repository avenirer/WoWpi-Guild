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

/***/ "./src/public/js/wowpi-guild-public.js":
/*!*********************************************!*\
  !*** ./src/public/js/wowpi-guild-public.js ***!
  \*********************************************/
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
  jquery__WEBPACK_IMPORTED_MODULE_0___default()(".synchronize-guild").click(function (e) {
    var synchAll = jquery__WEBPACK_IMPORTED_MODULE_0___default()('#synchall').is(":checked");
    console.log(synchAll);
    var forced = jquery__WEBPACK_IMPORTED_MODULE_0___default()(this).data('forced');
    var resultsDiv = jquery__WEBPACK_IMPORTED_MODULE_0___default()('#synchronize-guild-results .results');
    jquery__WEBPACK_IMPORTED_MODULE_0___default()(resultsDiv).html('');
    getGuildData(forced, synchAll);
    e.preventDefault();
  });
});

function getGuildData(forced, synchAll) {
  var resultsContainerDiv = jquery__WEBPACK_IMPORTED_MODULE_0___default()('#synchronize-guild-results');
  var resultsDiv = jquery__WEBPACK_IMPORTED_MODULE_0___default()('#synchronize-guild-results .results');
  jquery__WEBPACK_IMPORTED_MODULE_0___default.a.ajax({
    type: "post",
    dataType: "json",
    url: wowpiGuildPublicAjax.ajaxurl,
    data: {
      action: "getRemoteData",
      security: wowpiGuildPublicAjax.ajaxnoncepublic,
      retrieve: 'roster',
      forced: forced
    },
    beforeSend: function beforeSend() {
      resultsContainerDiv.addClass('loading');
    },
    success: function success(response) {
      if (response.type === "success") {
        var responseData = response.data;

        if (responseData.hasOwnProperty('roster')) {
          var roster = responseData.roster;
          totalQueries = Object.keys(roster).length;

          for (var property in roster) {
            var characterObj = roster[property];
            importRemoteMember(characterObj.id, forced, synchAll);
          }
        }
      } else {
        jquery__WEBPACK_IMPORTED_MODULE_0___default()(resultsDiv).append('<p><strong>Could not retrieve guild data</strong></p>');
        console.log('not working'); //alert("Your like could not be added");
      }
    }
  });
}

function importRemoteMember(characterId, forced, synchAll) {
  var resultsContainerDiv = jquery__WEBPACK_IMPORTED_MODULE_0___default()('#synchronize-guild-results');
  var resultsDiv = jquery__WEBPACK_IMPORTED_MODULE_0___default()('#synchronize-guild-results .results');
  jquery__WEBPACK_IMPORTED_MODULE_0___default.a.ajax({
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
    success: function success(response) {
      if (response.type === "success") {
        var responseData = response.data;
        totalQueries--;
        jquery__WEBPACK_IMPORTED_MODULE_0___default()(resultsDiv).append('<p>' + responseData.message + '</p>');
      } else {
        var _responseData = response.data;
        totalQueries--;
        jquery__WEBPACK_IMPORTED_MODULE_0___default()(resultsDiv).append('<p>There was a problem when updating a character. ' + _responseData.message + '</p>');
      }

      console.log(totalQueries);

      if (totalQueries === 0) {
        jquery__WEBPACK_IMPORTED_MODULE_0___default()(resultsContainerDiv).removeClass('loading');
      }
    }
  });
}

/***/ }),

/***/ 0:
/*!***************************************************!*\
  !*** multi ./src/public/js/wowpi-guild-public.js ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! D:\laragon\www\wowpi\wp-content\plugins\wowpi-guild\src\public\js\wowpi-guild-public.js */"./src/public/js/wowpi-guild-public.js");


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
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAiLCJ3ZWJwYWNrOi8vLy4vc3JjL3B1YmxpYy9qcy93b3dwaS1ndWlsZC1wdWJsaWMuanMiLCJ3ZWJwYWNrOi8vL2V4dGVybmFsIFwialF1ZXJ5XCIiXSwibmFtZXMiOlsidG90YWxRdWVyaWVzIiwiJCIsImRvY3VtZW50IiwicmVhZHkiLCJjbGljayIsImUiLCJzeW5jaEFsbCIsImlzIiwiY29uc29sZSIsImxvZyIsImZvcmNlZCIsImRhdGEiLCJyZXN1bHRzRGl2IiwiaHRtbCIsImdldEd1aWxkRGF0YSIsInByZXZlbnREZWZhdWx0IiwicmVzdWx0c0NvbnRhaW5lckRpdiIsImFqYXgiLCJ0eXBlIiwiZGF0YVR5cGUiLCJ1cmwiLCJ3b3dwaUd1aWxkUHVibGljQWpheCIsImFqYXh1cmwiLCJhY3Rpb24iLCJzZWN1cml0eSIsImFqYXhub25jZXB1YmxpYyIsInJldHJpZXZlIiwiYmVmb3JlU2VuZCIsImFkZENsYXNzIiwic3VjY2VzcyIsInJlc3BvbnNlIiwicmVzcG9uc2VEYXRhIiwiaGFzT3duUHJvcGVydHkiLCJyb3N0ZXIiLCJPYmplY3QiLCJrZXlzIiwibGVuZ3RoIiwicHJvcGVydHkiLCJjaGFyYWN0ZXJPYmoiLCJpbXBvcnRSZW1vdGVNZW1iZXIiLCJpZCIsImFwcGVuZCIsImNoYXJhY3RlcklkIiwibWVzc2FnZSIsInJlbW92ZUNsYXNzIl0sIm1hcHBpbmdzIjoiO1FBQUE7UUFDQTs7UUFFQTtRQUNBOztRQUVBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBOztRQUVBO1FBQ0E7O1FBRUE7UUFDQTs7UUFFQTtRQUNBO1FBQ0E7OztRQUdBO1FBQ0E7O1FBRUE7UUFDQTs7UUFFQTtRQUNBO1FBQ0E7UUFDQSwwQ0FBMEMsZ0NBQWdDO1FBQzFFO1FBQ0E7O1FBRUE7UUFDQTtRQUNBO1FBQ0Esd0RBQXdELGtCQUFrQjtRQUMxRTtRQUNBLGlEQUFpRCxjQUFjO1FBQy9E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LEtBQUssQ0FBcEIsRUFBdUI7QUFDbkJDLHFEQUFDLENBQUNlLG1CQUFELENBQUQsQ0FBdUI0QixXQUF2QixDQUFtQyxTQUFuQztBQUNIO0FBQ0o7QUFoQ0UsR0FBUDtBQWtDSCxDOzs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7OztBQ2hHRCx3QiIsImZpbGUiOiJ3b3dwaS1ndWlsZC1wdWJsaWMuanMiLCJzb3VyY2VzQ29udGVudCI6WyIgXHQvLyBUaGUgbW9kdWxlIGNhY2hlXG4gXHR2YXIgaW5zdGFsbGVkTW9kdWxlcyA9IHt9O1xuXG4gXHQvLyBUaGUgcmVxdWlyZSBmdW5jdGlvblxuIFx0ZnVuY3Rpb24gX193ZWJwYWNrX3JlcXVpcmVfXyhtb2R1bGVJZCkge1xuXG4gXHRcdC8vIENoZWNrIGlmIG1vZHVsZSBpcyBpbiBjYWNoZVxuIFx0XHRpZihpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXSkge1xuIFx0XHRcdHJldHVybiBpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXS5leHBvcnRzO1xuIFx0XHR9XG4gXHRcdC8vIENyZWF0ZSBhIG5ldyBtb2R1bGUgKGFuZCBwdXQgaXQgaW50byB0aGUgY2FjaGUpXG4gXHRcdHZhciBtb2R1bGUgPSBpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXSA9IHtcbiBcdFx0XHRpOiBtb2R1bGVJZCxcbiBcdFx0XHRsOiBmYWxzZSxcbiBcdFx0XHRleHBvcnRzOiB7fVxuIFx0XHR9O1xuXG4gXHRcdC8vIEV4ZWN1dGUgdGhlIG1vZHVsZSBmdW5jdGlvblxuIFx0XHRtb2R1bGVzW21vZHVsZUlkXS5jYWxsKG1vZHVsZS5leHBvcnRzLCBtb2R1bGUsIG1vZHVsZS5leHBvcnRzLCBfX3dlYnBhY2tfcmVxdWlyZV9fKTtcblxuIFx0XHQvLyBGbGFnIHRoZSBtb2R1bGUgYXMgbG9hZGVkXG4gXHRcdG1vZHVsZS5sID0gdHJ1ZTtcblxuIFx0XHQvLyBSZXR1cm4gdGhlIGV4cG9ydHMgb2YgdGhlIG1vZHVsZVxuIFx0XHRyZXR1cm4gbW9kdWxlLmV4cG9ydHM7XG4gXHR9XG5cblxuIFx0Ly8gZXhwb3NlIHRoZSBtb2R1bGVzIG9iamVjdCAoX193ZWJwYWNrX21vZHVsZXNfXylcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubSA9IG1vZHVsZXM7XG5cbiBcdC8vIGV4cG9zZSB0aGUgbW9kdWxlIGNhY2hlXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLmMgPSBpbnN0YWxsZWRNb2R1bGVzO1xuXG4gXHQvLyBkZWZpbmUgZ2V0dGVyIGZ1bmN0aW9uIGZvciBoYXJtb255IGV4cG9ydHNcbiBcdF9fd2VicGFja19yZXF1aXJlX18uZCA9IGZ1bmN0aW9uKGV4cG9ydHMsIG5hbWUsIGdldHRlcikge1xuIFx0XHRpZighX193ZWJwYWNrX3JlcXVpcmVfXy5vKGV4cG9ydHMsIG5hbWUpKSB7XG4gXHRcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIG5hbWUsIHsgZW51bWVyYWJsZTogdHJ1ZSwgZ2V0OiBnZXR0ZXIgfSk7XG4gXHRcdH1cbiBcdH07XG5cbiBcdC8vIGRlZmluZSBfX2VzTW9kdWxlIG9uIGV4cG9ydHNcbiBcdF9fd2VicGFja19yZXF1aXJlX18uciA9IGZ1bmN0aW9uKGV4cG9ydHMpIHtcbiBcdFx0aWYodHlwZW9mIFN5bWJvbCAhPT0gJ3VuZGVmaW5lZCcgJiYgU3ltYm9sLnRvU3RyaW5nVGFnKSB7XG4gXHRcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIFN5bWJvbC50b1N0cmluZ1RhZywgeyB2YWx1ZTogJ01vZHVsZScgfSk7XG4gXHRcdH1cbiBcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsICdfX2VzTW9kdWxlJywgeyB2YWx1ZTogdHJ1ZSB9KTtcbiBcdH07XG5cbiBcdC8vIGNyZWF0ZSBhIGZha2UgbmFtZXNwYWNlIG9iamVjdFxuIFx0Ly8gbW9kZSAmIDE6IHZhbHVlIGlzIGEgbW9kdWxlIGlkLCByZXF1aXJlIGl0XG4gXHQvLyBtb2RlICYgMjogbWVyZ2UgYWxsIHByb3BlcnRpZXMgb2YgdmFsdWUgaW50byB0aGUgbnNcbiBcdC8vIG1vZGUgJiA0OiByZXR1cm4gdmFsdWUgd2hlbiBhbHJlYWR5IG5zIG9iamVjdFxuIFx0Ly8gbW9kZSAmIDh8MTogYmVoYXZlIGxpa2UgcmVxdWlyZVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy50ID0gZnVuY3Rpb24odmFsdWUsIG1vZGUpIHtcbiBcdFx0aWYobW9kZSAmIDEpIHZhbHVlID0gX193ZWJwYWNrX3JlcXVpcmVfXyh2YWx1ZSk7XG4gXHRcdGlmKG1vZGUgJiA4KSByZXR1cm4gdmFsdWU7XG4gXHRcdGlmKChtb2RlICYgNCkgJiYgdHlwZW9mIHZhbHVlID09PSAnb2JqZWN0JyAmJiB2YWx1ZSAmJiB2YWx1ZS5fX2VzTW9kdWxlKSByZXR1cm4gdmFsdWU7XG4gXHRcdHZhciBucyA9IE9iamVjdC5jcmVhdGUobnVsbCk7XG4gXHRcdF9fd2VicGFja19yZXF1aXJlX18ucihucyk7XG4gXHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShucywgJ2RlZmF1bHQnLCB7IGVudW1lcmFibGU6IHRydWUsIHZhbHVlOiB2YWx1ZSB9KTtcbiBcdFx0aWYobW9kZSAmIDIgJiYgdHlwZW9mIHZhbHVlICE9ICdzdHJpbmcnKSBmb3IodmFyIGtleSBpbiB2YWx1ZSkgX193ZWJwYWNrX3JlcXVpcmVfXy5kKG5zLCBrZXksIGZ1bmN0aW9uKGtleSkgeyByZXR1cm4gdmFsdWVba2V5XTsgfS5iaW5kKG51bGwsIGtleSkpO1xuIFx0XHRyZXR1cm4gbnM7XG4gXHR9O1xuXG4gXHQvLyBnZXREZWZhdWx0RXhwb3J0IGZ1bmN0aW9uIGZvciBjb21wYXRpYmlsaXR5IHdpdGggbm9uLWhhcm1vbnkgbW9kdWxlc1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5uID0gZnVuY3Rpb24obW9kdWxlKSB7XG4gXHRcdHZhciBnZXR0ZXIgPSBtb2R1bGUgJiYgbW9kdWxlLl9fZXNNb2R1bGUgP1xuIFx0XHRcdGZ1bmN0aW9uIGdldERlZmF1bHQoKSB7IHJldHVybiBtb2R1bGVbJ2RlZmF1bHQnXTsgfSA6XG4gXHRcdFx0ZnVuY3Rpb24gZ2V0TW9kdWxlRXhwb3J0cygpIHsgcmV0dXJuIG1vZHVsZTsgfTtcbiBcdFx0X193ZWJwYWNrX3JlcXVpcmVfXy5kKGdldHRlciwgJ2EnLCBnZXR0ZXIpO1xuIFx0XHRyZXR1cm4gZ2V0dGVyO1xuIFx0fTtcblxuIFx0Ly8gT2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eS5jYWxsXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm8gPSBmdW5jdGlvbihvYmplY3QsIHByb3BlcnR5KSB7IHJldHVybiBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGwob2JqZWN0LCBwcm9wZXJ0eSk7IH07XG5cbiBcdC8vIF9fd2VicGFja19wdWJsaWNfcGF0aF9fXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnAgPSBcIlwiO1xuXG5cbiBcdC8vIExvYWQgZW50cnkgbW9kdWxlIGFuZCByZXR1cm4gZXhwb3J0c1xuIFx0cmV0dXJuIF9fd2VicGFja19yZXF1aXJlX18oX193ZWJwYWNrX3JlcXVpcmVfXy5zID0gMCk7XG4iLCJpbXBvcnQgJCBmcm9tICdqcXVlcnknO1xyXG5cclxuLypcclxuZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcignRE9NQ29udGVudExvYWRlZCcsIChldmVudCkgPT4ge1xyXG4gICAgZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3N5bmNocm9uaXplLXN0YXRpYycpLm9uY2xpY2sgPSBmdW5jdGlvbihlKXtcclxuICAgICAgICBjb25zb2xlLmxvZygnY2xpY2snKTtcclxuICAgIH1cclxufSk7XHJcbiovXHJcbmxldCB0b3RhbFF1ZXJpZXMgPSAwO1xyXG4kKGRvY3VtZW50KS5yZWFkeSggZnVuY3Rpb24oKSB7XHJcbiAgICAkKFwiLnN5bmNocm9uaXplLWd1aWxkXCIpLmNsaWNrKCBmdW5jdGlvbihlKSB7XHJcbiAgICAgICAgbGV0IHN5bmNoQWxsID0gJCgnI3N5bmNoYWxsJykuaXMoXCI6Y2hlY2tlZFwiKTtcclxuICAgICAgICBjb25zb2xlLmxvZyhzeW5jaEFsbCk7XHJcbiAgICAgICAgbGV0IGZvcmNlZCA9ICQodGhpcykuZGF0YSgnZm9yY2VkJyk7XHJcbiAgICAgICAgbGV0IHJlc3VsdHNEaXYgPSAkKCcjc3luY2hyb25pemUtZ3VpbGQtcmVzdWx0cyAucmVzdWx0cycpO1xyXG4gICAgICAgICQocmVzdWx0c0RpdikuaHRtbCgnJyk7XHJcbiAgICAgICAgZ2V0R3VpbGREYXRhKGZvcmNlZCwgc3luY2hBbGwpO1xyXG4gICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcclxuICAgIH0pO1xyXG59KTtcclxuXHJcblxyXG5mdW5jdGlvbiBnZXRHdWlsZERhdGEoZm9yY2VkLCBzeW5jaEFsbCkge1xyXG4gICAgbGV0IHJlc3VsdHNDb250YWluZXJEaXYgPSAkKCcjc3luY2hyb25pemUtZ3VpbGQtcmVzdWx0cycpO1xyXG4gICAgbGV0IHJlc3VsdHNEaXYgPSAkKCcjc3luY2hyb25pemUtZ3VpbGQtcmVzdWx0cyAucmVzdWx0cycpO1xyXG4gICAgJC5hamF4KHtcclxuICAgICAgICB0eXBlOiBcInBvc3RcIixcclxuICAgICAgICBkYXRhVHlwZTogXCJqc29uXCIsXHJcbiAgICAgICAgdXJsOiB3b3dwaUd1aWxkUHVibGljQWpheC5hamF4dXJsLFxyXG4gICAgICAgIGRhdGE6IHtcclxuICAgICAgICAgICAgYWN0aW9uOiBcImdldFJlbW90ZURhdGFcIixcclxuICAgICAgICAgICAgc2VjdXJpdHk6IHdvd3BpR3VpbGRQdWJsaWNBamF4LmFqYXhub25jZXB1YmxpYyxcclxuICAgICAgICAgICAgcmV0cmlldmU6ICdyb3N0ZXInLFxyXG4gICAgICAgICAgICBmb3JjZWQ6IGZvcmNlZFxyXG4gICAgICAgIH0sXHJcbiAgICAgICAgYmVmb3JlU2VuZDogZnVuY3Rpb24oKSB7XHJcbiAgICAgICAgICAgIHJlc3VsdHNDb250YWluZXJEaXYuYWRkQ2xhc3MoJ2xvYWRpbmcnKTtcclxuICAgICAgICB9LFxyXG4gICAgICAgIHN1Y2Nlc3M6IGZ1bmN0aW9uIChyZXNwb25zZSkge1xyXG4gICAgICAgICAgICBpZiAocmVzcG9uc2UudHlwZSA9PT0gXCJzdWNjZXNzXCIpIHtcclxuICAgICAgICAgICAgICAgIGxldCByZXNwb25zZURhdGEgPSByZXNwb25zZS5kYXRhO1xyXG4gICAgICAgICAgICAgICAgaWYocmVzcG9uc2VEYXRhLmhhc093blByb3BlcnR5KCdyb3N0ZXInKSkge1xyXG4gICAgICAgICAgICAgICAgICAgIGNvbnN0IHJvc3RlciA9IHJlc3BvbnNlRGF0YS5yb3N0ZXI7XHJcbiAgICAgICAgICAgICAgICAgICAgdG90YWxRdWVyaWVzID0gT2JqZWN0LmtleXMocm9zdGVyKS5sZW5ndGg7XHJcbiAgICAgICAgICAgICAgICAgICAgZm9yKGNvbnN0IHByb3BlcnR5IGluIHJvc3Rlcikge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBjb25zdCBjaGFyYWN0ZXJPYmogPSByb3N0ZXJbcHJvcGVydHldO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBpbXBvcnRSZW1vdGVNZW1iZXIoY2hhcmFjdGVyT2JqLmlkLCBmb3JjZWQsIHN5bmNoQWxsKTtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICAgICAkKHJlc3VsdHNEaXYpLmFwcGVuZCgnPHA+PHN0cm9uZz5Db3VsZCBub3QgcmV0cmlldmUgZ3VpbGQgZGF0YTwvc3Ryb25nPjwvcD4nKTtcclxuICAgICAgICAgICAgICAgIGNvbnNvbGUubG9nKCdub3Qgd29ya2luZycpO1xyXG4gICAgICAgICAgICAgICAgLy9hbGVydChcIllvdXIgbGlrZSBjb3VsZCBub3QgYmUgYWRkZWRcIik7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9XHJcbiAgICB9KTtcclxufVxyXG5cclxuZnVuY3Rpb24gaW1wb3J0UmVtb3RlTWVtYmVyKGNoYXJhY3RlcklkLCBmb3JjZWQsIHN5bmNoQWxsKSB7XHJcbiAgICBsZXQgcmVzdWx0c0NvbnRhaW5lckRpdiA9ICQoJyNzeW5jaHJvbml6ZS1ndWlsZC1yZXN1bHRzJyk7XHJcbiAgICBsZXQgcmVzdWx0c0RpdiA9ICQoJyNzeW5jaHJvbml6ZS1ndWlsZC1yZXN1bHRzIC5yZXN1bHRzJyk7XHJcbiAgICAkLmFqYXgoe1xyXG4gICAgICAgIHR5cGU6IFwicG9zdFwiLFxyXG4gICAgICAgIGRhdGFUeXBlOiBcImpzb25cIixcclxuICAgICAgICB1cmw6IHdvd3BpR3VpbGRQdWJsaWNBamF4LmFqYXh1cmwsXHJcbiAgICAgICAgZGF0YToge1xyXG4gICAgICAgICAgICBhY3Rpb246IFwiZ2V0UmVtb3RlRGF0YVwiLFxyXG4gICAgICAgICAgICBzZWN1cml0eTogd293cGlHdWlsZFB1YmxpY0FqYXguYWpheG5vbmNlcHVibGljLFxyXG4gICAgICAgICAgICByZXRyaWV2ZTogJ2NoYXJhY3RlcicsXHJcbiAgICAgICAgICAgIGZvcmNlZDogZm9yY2VkLFxyXG4gICAgICAgICAgICBjaGFyYWN0ZXJJZDogY2hhcmFjdGVySWQsXHJcbiAgICAgICAgICAgIHN5bmNoQWxsOiBzeW5jaEFsbFxyXG4gICAgICAgIH0sXHJcbiAgICAgICAgLypcclxuICAgICAgICBiZWZvcmVTZW5kOiBmdW5jdGlvbigpIHtcclxuICAgICAgICAgICAgbGV0IG5ld05vZGVTdGFydGluZyA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xyXG4gICAgICAgICAgICBuZXdOb2RlU3RhcnRpbmcuaW5uZXJIVE1MID0gJ1N0YXJ0ZWQgdG8gcmV0cmlldmUgJyArIGl0ZW0udG9TdHJpbmcoKTtcclxuICAgICAgICAgICAgcmVzdWx0c0Rpdi5hcHBlbmRDaGlsZChuZXdOb2RlU3RhcnRpbmcpO1xyXG4gICAgICAgIH0sKi9cclxuICAgICAgICBzdWNjZXNzOiBmdW5jdGlvbiAocmVzcG9uc2UpIHtcclxuICAgICAgICAgICAgaWYgKHJlc3BvbnNlLnR5cGUgPT09IFwic3VjY2Vzc1wiKSB7XHJcbiAgICAgICAgICAgICAgICBsZXQgcmVzcG9uc2VEYXRhID0gcmVzcG9uc2UuZGF0YTtcclxuICAgICAgICAgICAgICAgIHRvdGFsUXVlcmllcy0tO1xyXG4gICAgICAgICAgICAgICAgJChyZXN1bHRzRGl2KS5hcHBlbmQoJzxwPicgKyByZXNwb25zZURhdGEubWVzc2FnZSArICc8L3A+Jyk7XHJcbiAgICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICAgICBsZXQgcmVzcG9uc2VEYXRhID0gcmVzcG9uc2UuZGF0YTtcclxuICAgICAgICAgICAgICAgIHRvdGFsUXVlcmllcy0tO1xyXG4gICAgICAgICAgICAgICAgJChyZXN1bHRzRGl2KS5hcHBlbmQoJzxwPlRoZXJlIHdhcyBhIHByb2JsZW0gd2hlbiB1cGRhdGluZyBhIGNoYXJhY3Rlci4gJyArIHJlc3BvbnNlRGF0YS5tZXNzYWdlICsgJzwvcD4nKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICBjb25zb2xlLmxvZyh0b3RhbFF1ZXJpZXMpO1xyXG4gICAgICAgICAgICBpZih0b3RhbFF1ZXJpZXMgPT09IDApIHtcclxuICAgICAgICAgICAgICAgICQocmVzdWx0c0NvbnRhaW5lckRpdikucmVtb3ZlQ2xhc3MoJ2xvYWRpbmcnKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH1cclxuICAgIH0pO1xyXG59XHJcblxyXG5cclxuIiwibW9kdWxlLmV4cG9ydHMgPSBqUXVlcnk7Il0sInNvdXJjZVJvb3QiOiIifQ==