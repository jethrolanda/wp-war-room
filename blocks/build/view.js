import * as __WEBPACK_EXTERNAL_MODULE__wordpress_interactivity_8e89b257__ from "@wordpress/interactivity";
/******/ var __webpack_modules__ = ({

/***/ "@wordpress/interactivity":
/*!*******************************************!*\
  !*** external "@wordpress/interactivity" ***!
  \*******************************************/
/***/ ((module) => {

module.exports = __WEBPACK_EXTERNAL_MODULE__wordpress_interactivity_8e89b257__;

/***/ })

/******/ });
/************************************************************************/
/******/ // The module cache
/******/ var __webpack_module_cache__ = {};
/******/ 
/******/ // The require function
/******/ function __webpack_require__(moduleId) {
/******/ 	// Check if module is in cache
/******/ 	var cachedModule = __webpack_module_cache__[moduleId];
/******/ 	if (cachedModule !== undefined) {
/******/ 		return cachedModule.exports;
/******/ 	}
/******/ 	// Create a new module (and put it into the cache)
/******/ 	var module = __webpack_module_cache__[moduleId] = {
/******/ 		// no module.id needed
/******/ 		// no module.loaded needed
/******/ 		exports: {}
/******/ 	};
/******/ 
/******/ 	// Execute the module function
/******/ 	__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 
/******/ 	// Return the exports of the module
/******/ 	return module.exports;
/******/ }
/******/ 
/************************************************************************/
/******/ /* webpack/runtime/make namespace object */
/******/ (() => {
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = (exports) => {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/ })();
/******/ 
/************************************************************************/
var __webpack_exports__ = {};
// This entry needs to be wrapped in an IIFE because it needs to be isolated against other modules in the chunk.
(() => {
/*!*********************!*\
  !*** ./src/view.js ***!
  \*********************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_interactivity__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/interactivity */ "@wordpress/interactivity");
/**
 * WordPress dependencies
 */

const {
  state
} = (0,_wordpress_interactivity__WEBPACK_IMPORTED_MODULE_0__.store)("warroom-block", {
  state: {
    get callrailKickAssComputed() {
      const context = (0,_wordpress_interactivity__WEBPACK_IMPORTED_MODULE_0__.getContext)();
      return parseInt(context.callrailKickAssCompare) - parseInt(context.callrailKickAss);
    },
    get callrailKickAssComputedColor() {
      const context = (0,_wordpress_interactivity__WEBPACK_IMPORTED_MODULE_0__.getContext)();
      return Math.sign(parseInt(context.callrailKickAssCompare) - parseInt(context.callrailKickAss)) === -1;
    },
    get callrailNeedsFuelComputed() {
      const context = (0,_wordpress_interactivity__WEBPACK_IMPORTED_MODULE_0__.getContext)();
      return parseInt(context.callrailNeedsFuelCompare) - parseInt(context.callrailNeedsFuel);
    },
    get callrailNeedsFuelComputedColor() {
      const context = (0,_wordpress_interactivity__WEBPACK_IMPORTED_MODULE_0__.getContext)();
      return Math.sign(parseInt(context.callrailNeedsFuelCompare) - parseInt(context.callrailNeedsFuel)) === -1;
    },
    get hubspotComputed() {
      const context = (0,_wordpress_interactivity__WEBPACK_IMPORTED_MODULE_0__.getContext)();
      return parseInt(context.hubspotCompare) - parseInt(context.hubspot);
    },
    get hubspotCompareComputedColor() {
      const context = (0,_wordpress_interactivity__WEBPACK_IMPORTED_MODULE_0__.getContext)();
      console.log(parseInt(context.hubspotCompare), parseInt(context.hubspot), Math.sign(parseInt(context.hubspotCompare) - parseInt(context.hubspot)) === -1);
      return Math.sign(parseInt(context.hubspotCompare) - parseInt(context.hubspot)) === -1;
    }
  },
  actions: {
    *submitForm(e) {
      e.preventDefault();
      const {
        ref
      } = (0,_wordpress_interactivity__WEBPACK_IMPORTED_MODULE_0__.getElement)();
      const formData = new FormData(ref);
      const context = (0,_wordpress_interactivity__WEBPACK_IMPORTED_MODULE_0__.getContext)();
      formData.append("action", "date_filter");
      formData.append("nonce", state.nonce);
      const notif = document.getElementById("notification");
      if (notif) {
        notif.style.display = "block";
        notif.querySelector("#processing").style.display = "block";
      }
      const data = yield fetch(state.ajaxUrl, {
        method: "POST",
        body: formData
      }).then(response => response.json());
      if (data.status == "success") {
        context.hubspot = data.data.hubspot;
        context.callrailKickAss = data.data.callRailKickAss;
        context.callrailNeedsFuel = data.data.callRailNeedsFuel;
        // Compare
        context.hubspotCompare = data.data.hubspotCompare;
        context.callrailKickAssCompare = data.data.callrailKickAssCompare;
        context.callrailNeedsFuelCompare = data.data.callrailNeedsFuelCompare;
        if (notif) {
          notif.querySelector("#processing").style.display = "none";
          notif.querySelector("#success").style.display = "block";
        }
        setTimeout(function () {
          const notif = document.getElementById("notification");
          if (notif) {
            notif.style.display = "none";
            notif.querySelector("#processing").style.display = "none";
            notif.querySelector("#success").style.display = "none";
          }
        }, 2000);
      }
    }
  },
  callbacks: {}
});
})();


//# sourceMappingURL=view.js.map