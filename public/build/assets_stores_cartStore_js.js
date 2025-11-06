"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["assets_stores_cartStore_js"],{

/***/ "./assets/js/api/cartApi.js":
/*!**********************************!*\
  !*** ./assets/js/api/cartApi.js ***!
  \**********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   addToCart: () => (/* binding */ addToCart),
/* harmony export */   clearCart: () => (/* binding */ clearCart),
/* harmony export */   getSummary: () => (/* binding */ getSummary),
/* harmony export */   removeFromCart: () => (/* binding */ removeFromCart)
/* harmony export */ });
/* harmony import */ var core_js_modules_es_symbol_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es.symbol.js */ "./node_modules/core-js/modules/es.symbol.js");
/* harmony import */ var core_js_modules_es_symbol_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_symbol_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var core_js_modules_es_symbol_description_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! core-js/modules/es.symbol.description.js */ "./node_modules/core-js/modules/es.symbol.description.js");
/* harmony import */ var core_js_modules_es_symbol_description_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_symbol_description_js__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var core_js_modules_es_error_cause_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! core-js/modules/es.error.cause.js */ "./node_modules/core-js/modules/es.error.cause.js");
/* harmony import */ var core_js_modules_es_error_cause_js__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_error_cause_js__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var core_js_modules_es_error_to_string_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! core-js/modules/es.error.to-string.js */ "./node_modules/core-js/modules/es.error.to-string.js");
/* harmony import */ var core_js_modules_es_error_to_string_js__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_error_to_string_js__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var core_js_modules_es_array_iterator_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! core-js/modules/es.array.iterator.js */ "./node_modules/core-js/modules/es.array.iterator.js");
/* harmony import */ var core_js_modules_es_array_iterator_js__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_array_iterator_js__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var core_js_modules_es_function_bind_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! core-js/modules/es.function.bind.js */ "./node_modules/core-js/modules/es.function.bind.js");
/* harmony import */ var core_js_modules_es_function_bind_js__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_function_bind_js__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var core_js_modules_es_object_create_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! core-js/modules/es.object.create.js */ "./node_modules/core-js/modules/es.object.create.js");
/* harmony import */ var core_js_modules_es_object_create_js__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_object_create_js__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var core_js_modules_es_object_define_property_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! core-js/modules/es.object.define-property.js */ "./node_modules/core-js/modules/es.object.define-property.js");
/* harmony import */ var core_js_modules_es_object_define_property_js__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_object_define_property_js__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var core_js_modules_es_object_get_prototype_of_js__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! core-js/modules/es.object.get-prototype-of.js */ "./node_modules/core-js/modules/es.object.get-prototype-of.js");
/* harmony import */ var core_js_modules_es_object_get_prototype_of_js__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_object_get_prototype_of_js__WEBPACK_IMPORTED_MODULE_8__);
/* harmony import */ var core_js_modules_es_object_proto_js__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! core-js/modules/es.object.proto.js */ "./node_modules/core-js/modules/es.object.proto.js");
/* harmony import */ var core_js_modules_es_object_proto_js__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_object_proto_js__WEBPACK_IMPORTED_MODULE_9__);
/* harmony import */ var core_js_modules_es_object_set_prototype_of_js__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! core-js/modules/es.object.set-prototype-of.js */ "./node_modules/core-js/modules/es.object.set-prototype-of.js");
/* harmony import */ var core_js_modules_es_object_set_prototype_of_js__WEBPACK_IMPORTED_MODULE_10___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_object_set_prototype_of_js__WEBPACK_IMPORTED_MODULE_10__);
/* harmony import */ var core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! core-js/modules/es.object.to-string.js */ "./node_modules/core-js/modules/es.object.to-string.js");
/* harmony import */ var core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_11___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_11__);
/* harmony import */ var core_js_modules_es_promise_js__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! core-js/modules/es.promise.js */ "./node_modules/core-js/modules/es.promise.js");
/* harmony import */ var core_js_modules_es_promise_js__WEBPACK_IMPORTED_MODULE_12___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_promise_js__WEBPACK_IMPORTED_MODULE_12__);
/* harmony import */ var core_js_modules_es_string_iterator_js__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! core-js/modules/es.string.iterator.js */ "./node_modules/core-js/modules/es.string.iterator.js");
/* harmony import */ var core_js_modules_es_string_iterator_js__WEBPACK_IMPORTED_MODULE_13___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_string_iterator_js__WEBPACK_IMPORTED_MODULE_13__);
/* harmony import */ var core_js_modules_web_dom_collections_iterator_js__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! core-js/modules/web.dom-collections.iterator.js */ "./node_modules/core-js/modules/web.dom-collections.iterator.js");
/* harmony import */ var core_js_modules_web_dom_collections_iterator_js__WEBPACK_IMPORTED_MODULE_14___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_web_dom_collections_iterator_js__WEBPACK_IMPORTED_MODULE_14__);
/* harmony import */ var core_js_modules_web_url_search_params_js__WEBPACK_IMPORTED_MODULE_15__ = __webpack_require__(/*! core-js/modules/web.url-search-params.js */ "./node_modules/core-js/modules/web.url-search-params.js");
/* harmony import */ var core_js_modules_web_url_search_params_js__WEBPACK_IMPORTED_MODULE_15___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_web_url_search_params_js__WEBPACK_IMPORTED_MODULE_15__);
/* harmony import */ var core_js_modules_web_url_search_params_delete_js__WEBPACK_IMPORTED_MODULE_16__ = __webpack_require__(/*! core-js/modules/web.url-search-params.delete.js */ "./node_modules/core-js/modules/web.url-search-params.delete.js");
/* harmony import */ var core_js_modules_web_url_search_params_delete_js__WEBPACK_IMPORTED_MODULE_16___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_web_url_search_params_delete_js__WEBPACK_IMPORTED_MODULE_16__);
/* harmony import */ var core_js_modules_web_url_search_params_has_js__WEBPACK_IMPORTED_MODULE_17__ = __webpack_require__(/*! core-js/modules/web.url-search-params.has.js */ "./node_modules/core-js/modules/web.url-search-params.has.js");
/* harmony import */ var core_js_modules_web_url_search_params_has_js__WEBPACK_IMPORTED_MODULE_17___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_web_url_search_params_has_js__WEBPACK_IMPORTED_MODULE_17__);
/* harmony import */ var core_js_modules_web_url_search_params_size_js__WEBPACK_IMPORTED_MODULE_18__ = __webpack_require__(/*! core-js/modules/web.url-search-params.size.js */ "./node_modules/core-js/modules/web.url-search-params.size.js");
/* harmony import */ var core_js_modules_web_url_search_params_size_js__WEBPACK_IMPORTED_MODULE_18___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_web_url_search_params_size_js__WEBPACK_IMPORTED_MODULE_18__);
function _regenerator() { /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/babel/babel/blob/main/packages/babel-helpers/LICENSE */ var e, t, r = "function" == typeof Symbol ? Symbol : {}, n = r.iterator || "@@iterator", o = r.toStringTag || "@@toStringTag"; function i(r, n, o, i) { var c = n && n.prototype instanceof Generator ? n : Generator, u = Object.create(c.prototype); return _regeneratorDefine2(u, "_invoke", function (r, n, o) { var i, c, u, f = 0, p = o || [], y = !1, G = { p: 0, n: 0, v: e, a: d, f: d.bind(e, 4), d: function d(t, r) { return i = t, c = 0, u = e, G.n = r, a; } }; function d(r, n) { for (c = r, u = n, t = 0; !y && f && !o && t < p.length; t++) { var o, i = p[t], d = G.p, l = i[2]; r > 3 ? (o = l === n) && (u = i[(c = i[4]) ? 5 : (c = 3, 3)], i[4] = i[5] = e) : i[0] <= d && ((o = r < 2 && d < i[1]) ? (c = 0, G.v = n, G.n = i[1]) : d < l && (o = r < 3 || i[0] > n || n > l) && (i[4] = r, i[5] = n, G.n = l, c = 0)); } if (o || r > 1) return a; throw y = !0, n; } return function (o, p, l) { if (f > 1) throw TypeError("Generator is already running"); for (y && 1 === p && d(p, l), c = p, u = l; (t = c < 2 ? e : u) || !y;) { i || (c ? c < 3 ? (c > 1 && (G.n = -1), d(c, u)) : G.n = u : G.v = u); try { if (f = 2, i) { if (c || (o = "next"), t = i[o]) { if (!(t = t.call(i, u))) throw TypeError("iterator result is not an object"); if (!t.done) return t; u = t.value, c < 2 && (c = 0); } else 1 === c && (t = i["return"]) && t.call(i), c < 2 && (u = TypeError("The iterator does not provide a '" + o + "' method"), c = 1); i = e; } else if ((t = (y = G.n < 0) ? u : r.call(n, G)) !== a) break; } catch (t) { i = e, c = 1, u = t; } finally { f = 1; } } return { value: t, done: y }; }; }(r, o, i), !0), u; } var a = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} t = Object.getPrototypeOf; var c = [][n] ? t(t([][n]())) : (_regeneratorDefine2(t = {}, n, function () { return this; }), t), u = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(c); function f(e) { return Object.setPrototypeOf ? Object.setPrototypeOf(e, GeneratorFunctionPrototype) : (e.__proto__ = GeneratorFunctionPrototype, _regeneratorDefine2(e, o, "GeneratorFunction")), e.prototype = Object.create(u), e; } return GeneratorFunction.prototype = GeneratorFunctionPrototype, _regeneratorDefine2(u, "constructor", GeneratorFunctionPrototype), _regeneratorDefine2(GeneratorFunctionPrototype, "constructor", GeneratorFunction), GeneratorFunction.displayName = "GeneratorFunction", _regeneratorDefine2(GeneratorFunctionPrototype, o, "GeneratorFunction"), _regeneratorDefine2(u), _regeneratorDefine2(u, o, "Generator"), _regeneratorDefine2(u, n, function () { return this; }), _regeneratorDefine2(u, "toString", function () { return "[object Generator]"; }), (_regenerator = function _regenerator() { return { w: i, m: f }; })(); }
function _regeneratorDefine2(e, r, n, t) { var i = Object.defineProperty; try { i({}, "", {}); } catch (e) { i = 0; } _regeneratorDefine2 = function _regeneratorDefine(e, r, n, t) { if (r) i ? i(e, r, { value: n, enumerable: !t, configurable: !t, writable: !t }) : e[r] = n;else { var o = function o(r, n) { _regeneratorDefine2(e, r, function (e) { return this._invoke(r, n, e); }); }; o("next", 0), o("throw", 1), o("return", 2); } }, _regeneratorDefine2(e, r, n, t); }



















function asyncGeneratorStep(n, t, e, r, o, a, c) { try { var i = n[a](c), u = i.value; } catch (n) { return void e(n); } i.done ? t(u) : Promise.resolve(u).then(r, o); }
function _asyncToGenerator(n) { return function () { var t = this, e = arguments; return new Promise(function (r, o) { var a = n.apply(t, e); function _next(n) { asyncGeneratorStep(a, r, o, _next, _throw, "next", n); } function _throw(n) { asyncGeneratorStep(a, r, o, _next, _throw, "throw", n); } _next(void 0); }); }; }
function getDefaultHeaders() {
  return {
    'X-Requested-With': 'XMLHttpRequest',
    'Content-Type': 'application/x-www-form-urlencoded'
  };
}
function getJsonHeaders() {
  return {
    'X-Requested-With': 'XMLHttpRequest'
  };
}
function parseJsonResponse(_x) {
  return _parseJsonResponse.apply(this, arguments);
} // 🔼 AJOUTER AU PANIER
function _parseJsonResponse() {
  _parseJsonResponse = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee(res) {
    var data, text, _t;
    return _regenerator().w(function (_context) {
      while (1) switch (_context.n) {
        case 0:
          _context.p = 0;
          _context.n = 1;
          return res.json();
        case 1:
          data = _context.v;
          if (!(!res.ok || data.error)) {
            _context.n = 2;
            break;
          }
          throw new Error(data.error || "Erreur HTTP ".concat(res.status));
        case 2:
          return _context.a(2, data);
        case 3:
          _context.p = 3;
          _t = _context.v;
          if (!(_t instanceof SyntaxError)) {
            _context.n = 5;
            break;
          }
          _context.n = 4;
          return res.text();
        case 4:
          text = _context.v;
          console.warn('Réponse non JSON valide :', text);
          throw new Error('Erreur inattendue : réponse non JSON');
        case 5:
          throw _t;
        case 6:
          return _context.a(2);
      }
    }, _callee, null, [[0, 3]]);
  }));
  return _parseJsonResponse.apply(this, arguments);
}
function addToCart(_x2) {
  return _addToCart.apply(this, arguments);
}

// 🔽 RETIRER DU PANIER
function _addToCart() {
  _addToCart = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee2(productId) {
    var quantity,
      res,
      _args2 = arguments;
    return _regenerator().w(function (_context2) {
      while (1) switch (_context2.n) {
        case 0:
          quantity = _args2.length > 1 && _args2[1] !== undefined ? _args2[1] : 1;
          _context2.n = 1;
          return fetch("/panier/add/".concat(productId), {
            method: 'POST',
            headers: getDefaultHeaders(),
            body: new URLSearchParams({
              quantity: quantity
            })
          });
        case 1:
          res = _context2.v;
          _context2.n = 2;
          return parseJsonResponse(res);
        case 2:
          return _context2.a(2, _context2.v);
      }
    }, _callee2);
  }));
  return _addToCart.apply(this, arguments);
}
function removeFromCart(_x3) {
  return _removeFromCart.apply(this, arguments);
}

// VIDER LE PANIER
function _removeFromCart() {
  _removeFromCart = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee3(productId) {
    var res;
    return _regenerator().w(function (_context3) {
      while (1) switch (_context3.n) {
        case 0:
          _context3.n = 1;
          return fetch("/panier/remove/".concat(productId), {
            method: 'POST',
            headers: getJsonHeaders()
          });
        case 1:
          res = _context3.v;
          _context3.n = 2;
          return parseJsonResponse(res);
        case 2:
          return _context3.a(2, _context3.v);
      }
    }, _callee3);
  }));
  return _removeFromCart.apply(this, arguments);
}
function clearCart() {
  return _clearCart.apply(this, arguments);
}
//  RÉSUMÉ DU PANIER
function _clearCart() {
  _clearCart = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee4() {
    var res;
    return _regenerator().w(function (_context4) {
      while (1) switch (_context4.n) {
        case 0:
          _context4.n = 1;
          return fetch("/panier/clear", {
            method: 'POST',
            headers: getJsonHeaders()
          });
        case 1:
          res = _context4.v;
          _context4.n = 2;
          return parseJsonResponse(res);
        case 2:
          return _context4.a(2, _context4.v);
      }
    }, _callee4);
  }));
  return _clearCart.apply(this, arguments);
}
function getSummary() {
  return _getSummary.apply(this, arguments);
}
function _getSummary() {
  _getSummary = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee5() {
    var res;
    return _regenerator().w(function (_context5) {
      while (1) switch (_context5.n) {
        case 0:
          _context5.n = 1;
          return fetch("/panier/summary", {
            method: 'GET',
            headers: getJsonHeaders()
          });
        case 1:
          res = _context5.v;
          _context5.n = 2;
          return parseJsonResponse(res);
        case 2:
          return _context5.a(2, _context5.v);
      }
    }, _callee5);
  }));
  return _getSummary.apply(this, arguments);
}

/***/ }),

/***/ "./assets/stores/cartStore.js":
/*!************************************!*\
  !*** ./assets/stores/cartStore.js ***!
  \************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   useCartStore: () => (/* binding */ useCartStore)
/* harmony export */ });
/* harmony import */ var core_js_modules_es_symbol_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es.symbol.js */ "./node_modules/core-js/modules/es.symbol.js");
/* harmony import */ var core_js_modules_es_symbol_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_symbol_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var core_js_modules_es_symbol_description_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! core-js/modules/es.symbol.description.js */ "./node_modules/core-js/modules/es.symbol.description.js");
/* harmony import */ var core_js_modules_es_symbol_description_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_symbol_description_js__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var core_js_modules_es_error_cause_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! core-js/modules/es.error.cause.js */ "./node_modules/core-js/modules/es.error.cause.js");
/* harmony import */ var core_js_modules_es_error_cause_js__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_error_cause_js__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var core_js_modules_es_error_to_string_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! core-js/modules/es.error.to-string.js */ "./node_modules/core-js/modules/es.error.to-string.js");
/* harmony import */ var core_js_modules_es_error_to_string_js__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_error_to_string_js__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var core_js_modules_es_array_reduce_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! core-js/modules/es.array.reduce.js */ "./node_modules/core-js/modules/es.array.reduce.js");
/* harmony import */ var core_js_modules_es_array_reduce_js__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_array_reduce_js__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var core_js_modules_es_function_bind_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! core-js/modules/es.function.bind.js */ "./node_modules/core-js/modules/es.function.bind.js");
/* harmony import */ var core_js_modules_es_function_bind_js__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_function_bind_js__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var core_js_modules_es_object_create_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! core-js/modules/es.object.create.js */ "./node_modules/core-js/modules/es.object.create.js");
/* harmony import */ var core_js_modules_es_object_create_js__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_object_create_js__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var core_js_modules_es_object_define_property_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! core-js/modules/es.object.define-property.js */ "./node_modules/core-js/modules/es.object.define-property.js");
/* harmony import */ var core_js_modules_es_object_define_property_js__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_object_define_property_js__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var core_js_modules_es_object_get_prototype_of_js__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! core-js/modules/es.object.get-prototype-of.js */ "./node_modules/core-js/modules/es.object.get-prototype-of.js");
/* harmony import */ var core_js_modules_es_object_get_prototype_of_js__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_object_get_prototype_of_js__WEBPACK_IMPORTED_MODULE_8__);
/* harmony import */ var core_js_modules_es_object_proto_js__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! core-js/modules/es.object.proto.js */ "./node_modules/core-js/modules/es.object.proto.js");
/* harmony import */ var core_js_modules_es_object_proto_js__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_object_proto_js__WEBPACK_IMPORTED_MODULE_9__);
/* harmony import */ var core_js_modules_es_object_set_prototype_of_js__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! core-js/modules/es.object.set-prototype-of.js */ "./node_modules/core-js/modules/es.object.set-prototype-of.js");
/* harmony import */ var core_js_modules_es_object_set_prototype_of_js__WEBPACK_IMPORTED_MODULE_10___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_object_set_prototype_of_js__WEBPACK_IMPORTED_MODULE_10__);
/* harmony import */ var core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! core-js/modules/es.object.to-string.js */ "./node_modules/core-js/modules/es.object.to-string.js");
/* harmony import */ var core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_11___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_11__);
/* harmony import */ var core_js_modules_es_promise_js__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! core-js/modules/es.promise.js */ "./node_modules/core-js/modules/es.promise.js");
/* harmony import */ var core_js_modules_es_promise_js__WEBPACK_IMPORTED_MODULE_12___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_promise_js__WEBPACK_IMPORTED_MODULE_12__);
/* harmony import */ var core_js_modules_esnext_iterator_constructor_js__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! core-js/modules/esnext.iterator.constructor.js */ "./node_modules/core-js/modules/esnext.iterator.constructor.js");
/* harmony import */ var core_js_modules_esnext_iterator_constructor_js__WEBPACK_IMPORTED_MODULE_13___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_esnext_iterator_constructor_js__WEBPACK_IMPORTED_MODULE_13__);
/* harmony import */ var core_js_modules_esnext_iterator_reduce_js__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! core-js/modules/esnext.iterator.reduce.js */ "./node_modules/core-js/modules/esnext.iterator.reduce.js");
/* harmony import */ var core_js_modules_esnext_iterator_reduce_js__WEBPACK_IMPORTED_MODULE_14___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_esnext_iterator_reduce_js__WEBPACK_IMPORTED_MODULE_14__);
/* harmony import */ var pinia__WEBPACK_IMPORTED_MODULE_16__ = __webpack_require__(/*! pinia */ "./node_modules/pinia/dist/pinia.mjs");
/* harmony import */ var _js_api_cartApi__WEBPACK_IMPORTED_MODULE_15__ = __webpack_require__(/*! ../js/api/cartApi */ "./assets/js/api/cartApi.js");
function _regenerator() { /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/babel/babel/blob/main/packages/babel-helpers/LICENSE */ var e, t, r = "function" == typeof Symbol ? Symbol : {}, n = r.iterator || "@@iterator", o = r.toStringTag || "@@toStringTag"; function i(r, n, o, i) { var c = n && n.prototype instanceof Generator ? n : Generator, u = Object.create(c.prototype); return _regeneratorDefine2(u, "_invoke", function (r, n, o) { var i, c, u, f = 0, p = o || [], y = !1, G = { p: 0, n: 0, v: e, a: d, f: d.bind(e, 4), d: function d(t, r) { return i = t, c = 0, u = e, G.n = r, a; } }; function d(r, n) { for (c = r, u = n, t = 0; !y && f && !o && t < p.length; t++) { var o, i = p[t], d = G.p, l = i[2]; r > 3 ? (o = l === n) && (u = i[(c = i[4]) ? 5 : (c = 3, 3)], i[4] = i[5] = e) : i[0] <= d && ((o = r < 2 && d < i[1]) ? (c = 0, G.v = n, G.n = i[1]) : d < l && (o = r < 3 || i[0] > n || n > l) && (i[4] = r, i[5] = n, G.n = l, c = 0)); } if (o || r > 1) return a; throw y = !0, n; } return function (o, p, l) { if (f > 1) throw TypeError("Generator is already running"); for (y && 1 === p && d(p, l), c = p, u = l; (t = c < 2 ? e : u) || !y;) { i || (c ? c < 3 ? (c > 1 && (G.n = -1), d(c, u)) : G.n = u : G.v = u); try { if (f = 2, i) { if (c || (o = "next"), t = i[o]) { if (!(t = t.call(i, u))) throw TypeError("iterator result is not an object"); if (!t.done) return t; u = t.value, c < 2 && (c = 0); } else 1 === c && (t = i["return"]) && t.call(i), c < 2 && (u = TypeError("The iterator does not provide a '" + o + "' method"), c = 1); i = e; } else if ((t = (y = G.n < 0) ? u : r.call(n, G)) !== a) break; } catch (t) { i = e, c = 1, u = t; } finally { f = 1; } } return { value: t, done: y }; }; }(r, o, i), !0), u; } var a = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} t = Object.getPrototypeOf; var c = [][n] ? t(t([][n]())) : (_regeneratorDefine2(t = {}, n, function () { return this; }), t), u = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(c); function f(e) { return Object.setPrototypeOf ? Object.setPrototypeOf(e, GeneratorFunctionPrototype) : (e.__proto__ = GeneratorFunctionPrototype, _regeneratorDefine2(e, o, "GeneratorFunction")), e.prototype = Object.create(u), e; } return GeneratorFunction.prototype = GeneratorFunctionPrototype, _regeneratorDefine2(u, "constructor", GeneratorFunctionPrototype), _regeneratorDefine2(GeneratorFunctionPrototype, "constructor", GeneratorFunction), GeneratorFunction.displayName = "GeneratorFunction", _regeneratorDefine2(GeneratorFunctionPrototype, o, "GeneratorFunction"), _regeneratorDefine2(u), _regeneratorDefine2(u, o, "Generator"), _regeneratorDefine2(u, n, function () { return this; }), _regeneratorDefine2(u, "toString", function () { return "[object Generator]"; }), (_regenerator = function _regenerator() { return { w: i, m: f }; })(); }
function _regeneratorDefine2(e, r, n, t) { var i = Object.defineProperty; try { i({}, "", {}); } catch (e) { i = 0; } _regeneratorDefine2 = function _regeneratorDefine(e, r, n, t) { if (r) i ? i(e, r, { value: n, enumerable: !t, configurable: !t, writable: !t }) : e[r] = n;else { var o = function o(r, n) { _regeneratorDefine2(e, r, function (e) { return this._invoke(r, n, e); }); }; o("next", 0), o("throw", 1), o("return", 2); } }, _regeneratorDefine2(e, r, n, t); }
function asyncGeneratorStep(n, t, e, r, o, a, c) { try { var i = n[a](c), u = i.value; } catch (n) { return void e(n); } i.done ? t(u) : Promise.resolve(u).then(r, o); }
function _asyncToGenerator(n) { return function () { var t = this, e = arguments; return new Promise(function (r, o) { var a = n.apply(t, e); function _next(n) { asyncGeneratorStep(a, r, o, _next, _throw, "next", n); } function _throw(n) { asyncGeneratorStep(a, r, o, _next, _throw, "throw", n); } _next(void 0); }); }; }















// store Pinia pour gérer le panier


var useCartStore = (0,pinia__WEBPACK_IMPORTED_MODULE_16__.defineStore)('cart', {
  state: function state() {
    return {
      items: [],
      totalQuantity: 0,
      totalPrice: 0,
      isEmpty: true,
      loading: false
    };
  },
  getters: {
    totalHT: function totalHT(state) {
      return state.items.reduce(function (total, item) {
        var priceHT = item.product.price / (1 + item.product.tvaRate / 100);
        return total + priceHT * item.quantity;
      }, 0);
    },
    totalTVA: function totalTVA(state) {
      return state.items.reduce(function (total, item) {
        var priceHT = item.product.price / (1 + item.product.tvaRate / 100);
        var tva = priceHT * (item.product.tvaRate / 100);
        return total + tva * item.quantity;
      }, 0);
    },
    totalTTC: function totalTTC(state) {
      return state.items.reduce(function (total, item) {
        return total + item.product.price * item.quantity;
      }, 0);
    }
  },
  actions: {
    fetchSummary: function fetchSummary() {
      var _this = this;
      return _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee() {
        var data, _t;
        return _regenerator().w(function (_context) {
          while (1) switch (_context.n) {
            case 0:
              _this.loading = true;
              _context.p = 1;
              _context.n = 2;
              return _js_api_cartApi__WEBPACK_IMPORTED_MODULE_15__.getSummary();
            case 2:
              data = _context.v;
              _this.items = data.items;
              _this.totalQuantity = data.totalQuantity;
              _this.totalPrice = data.totalTTC;
              _this.isEmpty = _this.totalQuantity === 0;
              _context.n = 4;
              break;
            case 3:
              _context.p = 3;
              _t = _context.v;
              console.error('Erreur lors de la récupération du panier:', _t);
              _this.totalQuantity = 0;
              _this.isEmpty = true;
            case 4:
              _this.loading = false;
            case 5:
              return _context.a(2);
          }
        }, _callee, null, [[1, 3]]);
      }))();
    },
    addToCart: function addToCart(productId) {
      var _arguments = arguments,
        _this2 = this;
      return _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee2() {
        var quantity, response;
        return _regenerator().w(function (_context2) {
          while (1) switch (_context2.n) {
            case 0:
              quantity = _arguments.length > 1 && _arguments[1] !== undefined ? _arguments[1] : 1;
              _this2.loading = true;
              _context2.n = 1;
              return _js_api_cartApi__WEBPACK_IMPORTED_MODULE_15__.addToCart(productId, quantity);
            case 1:
              response = _context2.v;
              _context2.n = 2;
              return _this2.fetchSummary();
            case 2:
              _this2.loading = false;
              return _context2.a(2, response.message || 'Produit ajouté au panier');
          }
        }, _callee2);
      }))();
    },
    updateQuantity: function updateQuantity(productId, quantity) {
      var _this3 = this;
      return _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee3() {
        return _regenerator().w(function (_context3) {
          while (1) switch (_context3.n) {
            case 0:
              _this3.loading = true;
              // Simuler la mise à jour via suppression puis ajout
              _context3.n = 1;
              return _js_api_cartApi__WEBPACK_IMPORTED_MODULE_15__.removeFromCart(productId);
            case 1:
              if (!(quantity > 0)) {
                _context3.n = 2;
                break;
              }
              _context3.n = 2;
              return _js_api_cartApi__WEBPACK_IMPORTED_MODULE_15__.addToCart(productId, quantity);
            case 2:
              _context3.n = 3;
              return _this3.fetchSummary();
            case 3:
              _this3.loading = false;
            case 4:
              return _context3.a(2);
          }
        }, _callee3);
      }))();
    },
    removeProduct: function removeProduct(productId) {
      var _this4 = this;
      return _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee4() {
        return _regenerator().w(function (_context4) {
          while (1) switch (_context4.n) {
            case 0:
              _this4.loading = true;
              _context4.n = 1;
              return _js_api_cartApi__WEBPACK_IMPORTED_MODULE_15__.removeFromCart(productId);
            case 1:
              _context4.n = 2;
              return _this4.fetchSummary();
            case 2:
              _this4.loading = false;
            case 3:
              return _context4.a(2);
          }
        }, _callee4);
      }))();
    },
    clearCart: function clearCart() {
      var _this5 = this;
      return _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee5() {
        return _regenerator().w(function (_context5) {
          while (1) switch (_context5.n) {
            case 0:
              _this5.loading = true;
              _context5.n = 1;
              return _js_api_cartApi__WEBPACK_IMPORTED_MODULE_15__.clearCart();
            case 1:
              _context5.n = 2;
              return _this5.fetchSummary();
            case 2:
              _this5.loading = false;
            case 3:
              return _context5.a(2);
          }
        }, _callee5);
      }))();
    },
    fetchSummaryForCartPage: function fetchSummaryForCartPage() {
      var _this6 = this;
      return _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee6() {
        var data, _t2;
        return _regenerator().w(function (_context6) {
          while (1) switch (_context6.n) {
            case 0:
              _this6.loading = true;
              _context6.p = 1;
              _context6.n = 2;
              return _js_api_cartApi__WEBPACK_IMPORTED_MODULE_15__.getSummary();
            case 2:
              data = _context6.v;
              _this6.items = data.items;
              _this6.totalQuantity = data.totalQuantity;
              _this6.totalPrice = data.totalTTC;
              _this6.isEmpty = _this6.totalQuantity === 0;
              _context6.n = 4;
              break;
            case 3:
              _context6.p = 3;
              _t2 = _context6.v;
              console.error('Erreur lors de la récupération du panier:', _t2);
              _this6.totalQuantity = 0;
              _this6.isEmpty = true;
            case 4:
              _this6.loading = false;
            case 5:
              return _context6.a(2);
          }
        }, _callee6, null, [[1, 3]]);
      }))();
    },
    proceedToCheckout: function proceedToCheckout() {
      return _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee7() {
        return _regenerator().w(function (_context7) {
          while (1) switch (_context7.n) {
            case 0:
              window.location.href = '/panier/checkout';
            case 1:
              return _context7.a(2);
          }
        }, _callee7);
      }))();
    }
  }
});

/***/ })

}]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,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Q0FBQTtZQUFBO2NBQ3hCMEksTUFBTSxDQUFDQyxRQUFRLENBQUNDLElBQUksR0FBRyxrQkFBa0I7WUFBQTtjQUFBLE9BQUFILFNBQUEsQ0FBQXhILENBQUE7VUFBQTtRQUFBLEdBQUF1SCxRQUFBO01BQUE7SUFDM0M7RUFDRjtBQUNGLENBQUMsQ0FBQyxDIiwic291cmNlcyI6WyJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2FwaS9jYXJ0QXBpLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9zdG9yZXMvY2FydFN0b3JlLmpzIl0sInNvdXJjZXNDb250ZW50IjpbImZ1bmN0aW9uIGdldERlZmF1bHRIZWFkZXJzKCkge1xyXG4gIHJldHVybiB7XHJcbiAgICAnWC1SZXF1ZXN0ZWQtV2l0aCc6ICdYTUxIdHRwUmVxdWVzdCcsXHJcbiAgICAnQ29udGVudC1UeXBlJzogJ2FwcGxpY2F0aW9uL3gtd3d3LWZvcm0tdXJsZW5jb2RlZCcsXHJcbiAgfVxyXG59XHJcblxyXG5mdW5jdGlvbiBnZXRKc29uSGVhZGVycygpIHtcclxuICByZXR1cm4ge1xyXG4gICAgJ1gtUmVxdWVzdGVkLVdpdGgnOiAnWE1MSHR0cFJlcXVlc3QnLFxyXG4gIH1cclxufVxyXG5cclxuYXN5bmMgZnVuY3Rpb24gcGFyc2VKc29uUmVzcG9uc2UocmVzKSB7XHJcbiAgdHJ5IHtcclxuICAgIC8vIFV0aWxpc2VyIGRpcmVjdGVtZW50IC5qc29uKCkgcXVpIGfDqHJlIG1pZXV4IGwnVVRGLThcclxuICAgIGNvbnN0IGRhdGEgPSBhd2FpdCByZXMuanNvbigpXHJcblxyXG4gICAgaWYgKCFyZXMub2sgfHwgZGF0YS5lcnJvcikge1xyXG4gICAgICB0aHJvdyBuZXcgRXJyb3IoZGF0YS5lcnJvciB8fCBgRXJyZXVyIEhUVFAgJHtyZXMuc3RhdHVzfWApXHJcbiAgICB9XHJcblxyXG4gICAgcmV0dXJuIGRhdGFcclxuICB9IGNhdGNoIChlcnIpIHtcclxuICAgIC8vIFNpIGVycmV1ciBkZSBwYXJzaW5nIEpTT05cclxuICAgIGlmIChlcnIgaW5zdGFuY2VvZiBTeW50YXhFcnJvcikge1xyXG4gICAgICBjb25zdCB0ZXh0ID0gYXdhaXQgcmVzLnRleHQoKVxyXG4gICAgICBjb25zb2xlLndhcm4oJ1LDqXBvbnNlIG5vbiBKU09OIHZhbGlkZSA6JywgdGV4dClcclxuICAgICAgdGhyb3cgbmV3IEVycm9yKCdFcnJldXIgaW5hdHRlbmR1ZSA6IHLDqXBvbnNlIG5vbiBKU09OJylcclxuICAgIH1cclxuICAgIC8vIFNpIGMnZXN0IHVuZSBlcnJldXIgbcOpdGllciwgbGEgcHJvcGFnZXJcclxuICAgIHRocm93IGVyclxyXG4gIH1cclxufVxyXG5cclxuLy8g8J+UvCBBSk9VVEVSIEFVIFBBTklFUlxyXG5leHBvcnQgYXN5bmMgZnVuY3Rpb24gYWRkVG9DYXJ0KHByb2R1Y3RJZCwgcXVhbnRpdHkgPSAxKSB7XHJcbiAgY29uc3QgcmVzID0gYXdhaXQgZmV0Y2goYC9wYW5pZXIvYWRkLyR7cHJvZHVjdElkfWAsIHtcclxuICAgIG1ldGhvZDogJ1BPU1QnLFxyXG4gICAgaGVhZGVyczogZ2V0RGVmYXVsdEhlYWRlcnMoKSxcclxuICAgIGJvZHk6IG5ldyBVUkxTZWFyY2hQYXJhbXMoeyBxdWFudGl0eSB9KSxcclxuICB9KVxyXG5cclxuICByZXR1cm4gYXdhaXQgcGFyc2VKc29uUmVzcG9uc2UocmVzKVxyXG59XHJcblxyXG4vLyDwn5S9IFJFVElSRVIgRFUgUEFOSUVSXHJcbmV4cG9ydCBhc3luYyBmdW5jdGlvbiByZW1vdmVGcm9tQ2FydChwcm9kdWN0SWQpIHtcclxuICBjb25zdCByZXMgPSBhd2FpdCBmZXRjaChgL3Bhbmllci9yZW1vdmUvJHtwcm9kdWN0SWR9YCwge1xyXG4gICAgbWV0aG9kOiAnUE9TVCcsXHJcbiAgICBoZWFkZXJzOiBnZXRKc29uSGVhZGVycygpLFxyXG4gIH0pXHJcblxyXG4gIHJldHVybiBhd2FpdCBwYXJzZUpzb25SZXNwb25zZShyZXMpXHJcbn1cclxuXHJcbi8vIFZJREVSIExFIFBBTklFUlxyXG5leHBvcnQgYXN5bmMgZnVuY3Rpb24gY2xlYXJDYXJ0KCkge1xyXG4gIGNvbnN0IHJlcyA9IGF3YWl0IGZldGNoKGAvcGFuaWVyL2NsZWFyYCwge1xyXG4gICAgbWV0aG9kOiAnUE9TVCcsXHJcbiAgICBoZWFkZXJzOiBnZXRKc29uSGVhZGVycygpLFxyXG4gIH0pXHJcblxyXG4gIHJldHVybiBhd2FpdCBwYXJzZUpzb25SZXNwb25zZShyZXMpXHJcbn1cclxuLy8gIFLDiVNVTcOJIERVIFBBTklFUlxyXG5leHBvcnQgYXN5bmMgZnVuY3Rpb24gZ2V0U3VtbWFyeSgpIHtcclxuICBjb25zdCByZXMgPSBhd2FpdCBmZXRjaChgL3Bhbmllci9zdW1tYXJ5YCwge1xyXG4gICAgbWV0aG9kOiAnR0VUJyxcclxuICAgIGhlYWRlcnM6IGdldEpzb25IZWFkZXJzKCksXHJcbiAgfSlcclxuXHJcbiAgcmV0dXJuIGF3YWl0IHBhcnNlSnNvblJlc3BvbnNlKHJlcylcclxufVxyXG4iLCIvLyBzdG9yZSBQaW5pYSBwb3VyIGfDqXJlciBsZSBwYW5pZXJcclxuaW1wb3J0IHsgZGVmaW5lU3RvcmUgfSBmcm9tICdwaW5pYSdcclxuaW1wb3J0ICogYXMgY2FydEFwaSBmcm9tICcuLi9qcy9hcGkvY2FydEFwaSdcclxuXHJcbmV4cG9ydCBjb25zdCB1c2VDYXJ0U3RvcmUgPSBkZWZpbmVTdG9yZSgnY2FydCcsIHtcclxuICBzdGF0ZTogKCkgPT4gKHtcclxuICAgIGl0ZW1zOiBbXSxcclxuICAgIHRvdGFsUXVhbnRpdHk6IDAsXHJcbiAgICB0b3RhbFByaWNlOiAwLFxyXG4gICAgaXNFbXB0eTogdHJ1ZSxcclxuICAgIGxvYWRpbmc6IGZhbHNlXHJcbiAgfSksXHJcblxyXG4gIGdldHRlcnM6IHtcclxuICAgIHRvdGFsSFQ6IChzdGF0ZSkgPT4ge1xyXG4gICAgICByZXR1cm4gc3RhdGUuaXRlbXMucmVkdWNlKCh0b3RhbCwgaXRlbSkgPT4ge1xyXG4gICAgICAgIGNvbnN0IHByaWNlSFQgPSBpdGVtLnByb2R1Y3QucHJpY2UgLyAoMSArIGl0ZW0ucHJvZHVjdC50dmFSYXRlIC8gMTAwKVxyXG4gICAgICAgIHJldHVybiB0b3RhbCArIChwcmljZUhUICogaXRlbS5xdWFudGl0eSlcclxuICAgICAgfSwgMClcclxuICAgIH0sXHJcblxyXG4gICAgdG90YWxUVkE6IChzdGF0ZSkgPT4ge1xyXG4gICAgICByZXR1cm4gc3RhdGUuaXRlbXMucmVkdWNlKCh0b3RhbCwgaXRlbSkgPT4ge1xyXG4gICAgICAgIGNvbnN0IHByaWNlSFQgPSBpdGVtLnByb2R1Y3QucHJpY2UgLyAoMSArIGl0ZW0ucHJvZHVjdC50dmFSYXRlIC8gMTAwKVxyXG4gICAgICAgIGNvbnN0IHR2YSA9IHByaWNlSFQgKiAoaXRlbS5wcm9kdWN0LnR2YVJhdGUgLyAxMDApXHJcbiAgICAgICAgcmV0dXJuIHRvdGFsICsgKHR2YSAqIGl0ZW0ucXVhbnRpdHkpXHJcbiAgICAgIH0sIDApXHJcbiAgICB9LFxyXG5cclxuICAgIHRvdGFsVFRDOiAoc3RhdGUpID0+IHtcclxuICAgICAgcmV0dXJuIHN0YXRlLml0ZW1zLnJlZHVjZSgodG90YWwsIGl0ZW0pID0+IHtcclxuICAgICAgICByZXR1cm4gdG90YWwgKyAoaXRlbS5wcm9kdWN0LnByaWNlICogaXRlbS5xdWFudGl0eSlcclxuICAgICAgfSwgMClcclxuICAgIH1cclxuICB9LFxyXG5cclxuICBhY3Rpb25zOiB7XHJcbiAgICBhc3luYyBmZXRjaFN1bW1hcnkoKSB7XHJcbiAgICAgIHRoaXMubG9hZGluZyA9IHRydWVcclxuICAgICAgdHJ5IHtcclxuICAgICAgICBjb25zdCBkYXRhID0gYXdhaXQgY2FydEFwaS5nZXRTdW1tYXJ5KClcclxuICAgICAgICB0aGlzLml0ZW1zID0gZGF0YS5pdGVtc1xyXG4gICAgICAgIHRoaXMudG90YWxRdWFudGl0eSA9IGRhdGEudG90YWxRdWFudGl0eVxyXG4gICAgICAgIHRoaXMudG90YWxQcmljZSA9IGRhdGEudG90YWxUVENcclxuICAgICAgICB0aGlzLmlzRW1wdHkgPSB0aGlzLnRvdGFsUXVhbnRpdHkgPT09IDBcclxuICAgICAgfSBjYXRjaCAoZXJyb3IpIHtcclxuICAgICAgICBjb25zb2xlLmVycm9yKCdFcnJldXIgbG9ycyBkZSBsYSByw6ljdXDDqXJhdGlvbiBkdSBwYW5pZXI6JywgZXJyb3IpXHJcbiAgICAgICAgdGhpcy50b3RhbFF1YW50aXR5ID0gMFxyXG4gICAgICAgIHRoaXMuaXNFbXB0eSA9IHRydWVcclxuICAgICAgfVxyXG4gICAgICB0aGlzLmxvYWRpbmcgPSBmYWxzZVxyXG4gICAgfSxcclxuXHJcbiAgICBhc3luYyBhZGRUb0NhcnQocHJvZHVjdElkLCBxdWFudGl0eSA9IDEpIHtcclxuICAgICAgdGhpcy5sb2FkaW5nID0gdHJ1ZVxyXG4gICAgICBjb25zdCByZXNwb25zZSA9IGF3YWl0IGNhcnRBcGkuYWRkVG9DYXJ0KHByb2R1Y3RJZCwgcXVhbnRpdHkpXHJcbiAgICAgIGF3YWl0IHRoaXMuZmV0Y2hTdW1tYXJ5KClcclxuICAgICAgdGhpcy5sb2FkaW5nID0gZmFsc2VcclxuICAgICAgcmV0dXJuIHJlc3BvbnNlLm1lc3NhZ2UgfHwgJ1Byb2R1aXQgYWpvdXTDqSBhdSBwYW5pZXInXHJcbiAgICB9LFxyXG5cclxuICAgIGFzeW5jIHVwZGF0ZVF1YW50aXR5KHByb2R1Y3RJZCwgcXVhbnRpdHkpIHtcclxuICAgICAgdGhpcy5sb2FkaW5nID0gdHJ1ZVxyXG4gICAgICAvLyBTaW11bGVyIGxhIG1pc2Ugw6Agam91ciB2aWEgc3VwcHJlc3Npb24gcHVpcyBham91dFxyXG4gICAgICBhd2FpdCBjYXJ0QXBpLnJlbW92ZUZyb21DYXJ0KHByb2R1Y3RJZClcclxuICAgICAgaWYgKHF1YW50aXR5ID4gMCkge1xyXG4gICAgICAgIGF3YWl0IGNhcnRBcGkuYWRkVG9DYXJ0KHByb2R1Y3RJZCwgcXVhbnRpdHkpXHJcbiAgICAgIH1cclxuICAgICAgYXdhaXQgdGhpcy5mZXRjaFN1bW1hcnkoKVxyXG4gICAgICB0aGlzLmxvYWRpbmcgPSBmYWxzZVxyXG4gICAgfSxcclxuXHJcbiAgICBhc3luYyByZW1vdmVQcm9kdWN0KHByb2R1Y3RJZCkge1xyXG4gICAgICB0aGlzLmxvYWRpbmcgPSB0cnVlXHJcbiAgICAgIGF3YWl0IGNhcnRBcGkucmVtb3ZlRnJvbUNhcnQocHJvZHVjdElkKVxyXG4gICAgICBhd2FpdCB0aGlzLmZldGNoU3VtbWFyeSgpXHJcbiAgICAgIHRoaXMubG9hZGluZyA9IGZhbHNlXHJcbiAgICB9LFxyXG5cclxuICAgIGFzeW5jIGNsZWFyQ2FydCgpIHtcclxuICAgICAgdGhpcy5sb2FkaW5nID0gdHJ1ZVxyXG4gICAgICBhd2FpdCBjYXJ0QXBpLmNsZWFyQ2FydCgpXHJcbiAgICAgIGF3YWl0IHRoaXMuZmV0Y2hTdW1tYXJ5KClcclxuICAgICAgdGhpcy5sb2FkaW5nID0gZmFsc2VcclxuICAgIH0sXHJcblxyXG4gICAgYXN5bmMgZmV0Y2hTdW1tYXJ5Rm9yQ2FydFBhZ2UoKSB7XHJcbiAgICAgIHRoaXMubG9hZGluZyA9IHRydWVcclxuICAgICAgdHJ5IHtcclxuICAgICAgICBjb25zdCBkYXRhID0gYXdhaXQgY2FydEFwaS5nZXRTdW1tYXJ5KClcclxuICAgICAgICB0aGlzLml0ZW1zID0gZGF0YS5pdGVtc1xyXG4gICAgICAgIHRoaXMudG90YWxRdWFudGl0eSA9IGRhdGEudG90YWxRdWFudGl0eVxyXG4gICAgICAgIHRoaXMudG90YWxQcmljZSA9IGRhdGEudG90YWxUVENcclxuICAgICAgICB0aGlzLmlzRW1wdHkgPSB0aGlzLnRvdGFsUXVhbnRpdHkgPT09IDBcclxuICAgICAgfSBjYXRjaCAoZXJyb3IpIHtcclxuICAgICAgICBjb25zb2xlLmVycm9yKCdFcnJldXIgbG9ycyBkZSBsYSByw6ljdXDDqXJhdGlvbiBkdSBwYW5pZXI6JywgZXJyb3IpXHJcbiAgICAgICAgdGhpcy50b3RhbFF1YW50aXR5ID0gMFxyXG4gICAgICAgIHRoaXMuaXNFbXB0eSA9IHRydWVcclxuICAgICAgfVxyXG4gICAgICB0aGlzLmxvYWRpbmcgPSBmYWxzZVxyXG4gICAgfSxcclxuXHJcbiAgICBhc3luYyBwcm9jZWVkVG9DaGVja291dCgpIHtcclxuICAgICAgd2luZG93LmxvY2F0aW9uLmhyZWYgPSAnL3Bhbmllci9jaGVja291dCdcclxuICAgIH1cclxuICB9LFxyXG59KVxyXG4iXSwibmFtZXMiOlsiZSIsInQiLCJyIiwiU3ltYm9sIiwibiIsIml0ZXJhdG9yIiwibyIsInRvU3RyaW5nVGFnIiwiaSIsImMiLCJwcm90b3R5cGUiLCJHZW5lcmF0b3IiLCJ1IiwiT2JqZWN0IiwiY3JlYXRlIiwiX3JlZ2VuZXJhdG9yRGVmaW5lMiIsImYiLCJwIiwieSIsIkciLCJ2IiwiYSIsImQiLCJiaW5kIiwibGVuZ3RoIiwibCIsIlR5cGVFcnJvciIsImNhbGwiLCJkb25lIiwidmFsdWUiLCJHZW5lcmF0b3JGdW5jdGlvbiIsIkdlbmVyYXRvckZ1bmN0aW9uUHJvdG90eXBlIiwiZ2V0UHJvdG90eXBlT2YiLCJzZXRQcm90b3R5cGVPZiIsIl9fcHJvdG9fXyIsImRpc3BsYXlOYW1lIiwiX3JlZ2VuZXJhdG9yIiwidyIsIm0iLCJkZWZpbmVQcm9wZXJ0eSIsIl9yZWdlbmVyYXRvckRlZmluZSIsImVudW1lcmFibGUiLCJjb25maWd1cmFibGUiLCJ3cml0YWJsZSIsIl9pbnZva2UiLCJhc3luY0dlbmVyYXRvclN0ZXAiLCJQcm9taXNlIiwicmVzb2x2ZSIsInRoZW4iLCJfYXN5bmNUb0dlbmVyYXRvciIsImFyZ3VtZW50cyIsImFwcGx5IiwiX25leHQiLCJfdGhyb3ciLCJnZXREZWZhdWx0SGVhZGVycyIsImdldEpzb25IZWFkZXJzIiwicGFyc2VKc29uUmVzcG9uc2UiLCJfeCIsIl9wYXJzZUpzb25SZXNwb25zZSIsIl9jYWxsZWUiLCJyZXMiLCJkYXRhIiwidGV4dCIsIl90IiwiX2NvbnRleHQiLCJqc29uIiwib2siLCJlcnJvciIsIkVycm9yIiwiY29uY2F0Iiwic3RhdHVzIiwiU3ludGF4RXJyb3IiLCJjb25zb2xlIiwid2FybiIsImFkZFRvQ2FydCIsIl94MiIsIl9hZGRUb0NhcnQiLCJfY2FsbGVlMiIsInByb2R1Y3RJZCIsInF1YW50aXR5IiwiX2FyZ3MyIiwiX2NvbnRleHQyIiwidW5kZWZpbmVkIiwiZmV0Y2giLCJtZXRob2QiLCJoZWFkZXJzIiwiYm9keSIsIlVSTFNlYXJjaFBhcmFtcyIsInJlbW92ZUZyb21DYXJ0IiwiX3gzIiwiX3JlbW92ZUZyb21DYXJ0IiwiX2NhbGxlZTMiLCJfY29udGV4dDMiLCJjbGVhckNhcnQiLCJfY2xlYXJDYXJ0IiwiX2NhbGxlZTQiLCJfY29udGV4dDQiLCJnZXRTdW1tYXJ5IiwiX2dldFN1bW1hcnkiLCJfY2FsbGVlNSIsIl9jb250ZXh0NSIsImRlZmluZVN0b3JlIiwiY2FydEFwaSIsInVzZUNhcnRTdG9yZSIsInN0YXRlIiwiaXRlbXMiLCJ0b3RhbFF1YW50aXR5IiwidG90YWxQcmljZSIsImlzRW1wdHkiLCJsb2FkaW5nIiwiZ2V0dGVycyIsInRvdGFsSFQiLCJyZWR1Y2UiLCJ0b3RhbCIsIml0ZW0iLCJwcmljZUhUIiwicHJvZHVjdCIsInByaWNlIiwidHZhUmF0ZSIsInRvdGFsVFZBIiwidHZhIiwidG90YWxUVEMiLCJhY3Rpb25zIiwiZmV0Y2hTdW1tYXJ5IiwiX3RoaXMiLCJfYXJndW1lbnRzIiwiX3RoaXMyIiwicmVzcG9uc2UiLCJtZXNzYWdlIiwidXBkYXRlUXVhbnRpdHkiLCJfdGhpczMiLCJyZW1vdmVQcm9kdWN0IiwiX3RoaXM0IiwiX3RoaXM1IiwiZmV0Y2hTdW1tYXJ5Rm9yQ2FydFBhZ2UiLCJfdGhpczYiLCJfY2FsbGVlNiIsIl90MiIsIl9jb250ZXh0NiIsInByb2NlZWRUb0NoZWNrb3V0IiwiX2NhbGxlZTciLCJfY29udGV4dDciLCJ3aW5kb3ciLCJsb2NhdGlvbiIsImhyZWYiXSwic291cmNlUm9vdCI6IiJ9