/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};

/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {

/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;

/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			exports: {},
/******/ 			id: moduleId,
/******/ 			loaded: false
/******/ 		};

/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);

/******/ 		// Flag the module as loaded
/******/ 		module.loaded = true;

/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}


/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;

/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;

/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";

/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ function(module, exports) {

	var angle, element, fade, frame, loaded, opacity, skip, splash, update;

	element = document.querySelector('.loader');

	splash = document.querySelector('.splash');

	angle = 0;

	skip = false;

	loaded = false;

	opacity = 1;

	frame = 0;

	fade = function() {
	  frame += 1 / 60;
	  opacity = Math.max(0, Math.min(1, 12 - frame * 2));
	  splash.style.opacity = opacity;
	  if (opacity > 0) {
	    return requestAnimationFrame(fade);
	  }
	};

	update = function() {
	  if (!loaded) {
	    requestAnimationFrame(update);
	  }
	  if (loaded) {
	    element.classList.add('hide');
	  }
	  if (loaded) {
	    fade();
	  }
	  skip = !skip;
	  if (!skip) {
	    angle = (angle + 30) % 360;
	    return element.style.transform = "rotate(" + angle + "deg)";
	  }
	};

	requestAnimationFrame(update);

	window.onload = function() {
	  return setTimeout((function() {
	    return loaded = true;
	  }), 100);
	};


/***/ }
/******/ ]);