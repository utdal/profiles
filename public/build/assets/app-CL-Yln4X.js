import { r as requireJquery, g as getDefaultExportFromCjs, a as requireBootstrap, b as requireTagsinput, P as Popper, c as config, l as library, _ as _iconsCache, d as _iconsCache$1, e as _iconsCache$2, f as dom, h as requireBootstrapDatepicker } from "./vendor-Dt-scEca.js";
var jqueryExports = requireJquery();
const $$1 = /* @__PURE__ */ getDefaultExportFromCjs(jqueryExports);
requireBootstrap();
requireTagsinput();
var Sortable$3 = { exports: {} };
/**!
 * Sortable 1.15.6
 * @author	RubaXa   <trash@rubaxa.org>
 * @author	owenm    <owen23355@gmail.com>
 * @license MIT
 */
var Sortable$2 = Sortable$3.exports;
var hasRequiredSortable;
function requireSortable() {
  if (hasRequiredSortable) return Sortable$3.exports;
  hasRequiredSortable = 1;
  (function(module, exports$1) {
    (function(global, factory) {
      module.exports = factory();
    })(Sortable$2, (function() {
      function ownKeys(object, enumerableOnly) {
        var keys = Object.keys(object);
        if (Object.getOwnPropertySymbols) {
          var symbols = Object.getOwnPropertySymbols(object);
          if (enumerableOnly) {
            symbols = symbols.filter(function(sym) {
              return Object.getOwnPropertyDescriptor(object, sym).enumerable;
            });
          }
          keys.push.apply(keys, symbols);
        }
        return keys;
      }
      function _objectSpread2(target) {
        for (var i = 1; i < arguments.length; i++) {
          var source = arguments[i] != null ? arguments[i] : {};
          if (i % 2) {
            ownKeys(Object(source), true).forEach(function(key) {
              _defineProperty(target, key, source[key]);
            });
          } else if (Object.getOwnPropertyDescriptors) {
            Object.defineProperties(target, Object.getOwnPropertyDescriptors(source));
          } else {
            ownKeys(Object(source)).forEach(function(key) {
              Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key));
            });
          }
        }
        return target;
      }
      function _typeof(obj) {
        "@babel/helpers - typeof";
        if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") {
          _typeof = function(obj2) {
            return typeof obj2;
          };
        } else {
          _typeof = function(obj2) {
            return obj2 && typeof Symbol === "function" && obj2.constructor === Symbol && obj2 !== Symbol.prototype ? "symbol" : typeof obj2;
          };
        }
        return _typeof(obj);
      }
      function _defineProperty(obj, key, value) {
        if (key in obj) {
          Object.defineProperty(obj, key, {
            value,
            enumerable: true,
            configurable: true,
            writable: true
          });
        } else {
          obj[key] = value;
        }
        return obj;
      }
      function _extends() {
        _extends = Object.assign || function(target) {
          for (var i = 1; i < arguments.length; i++) {
            var source = arguments[i];
            for (var key in source) {
              if (Object.prototype.hasOwnProperty.call(source, key)) {
                target[key] = source[key];
              }
            }
          }
          return target;
        };
        return _extends.apply(this, arguments);
      }
      function _objectWithoutPropertiesLoose(source, excluded) {
        if (source == null) return {};
        var target = {};
        var sourceKeys = Object.keys(source);
        var key, i;
        for (i = 0; i < sourceKeys.length; i++) {
          key = sourceKeys[i];
          if (excluded.indexOf(key) >= 0) continue;
          target[key] = source[key];
        }
        return target;
      }
      function _objectWithoutProperties(source, excluded) {
        if (source == null) return {};
        var target = _objectWithoutPropertiesLoose(source, excluded);
        var key, i;
        if (Object.getOwnPropertySymbols) {
          var sourceSymbolKeys = Object.getOwnPropertySymbols(source);
          for (i = 0; i < sourceSymbolKeys.length; i++) {
            key = sourceSymbolKeys[i];
            if (excluded.indexOf(key) >= 0) continue;
            if (!Object.prototype.propertyIsEnumerable.call(source, key)) continue;
            target[key] = source[key];
          }
        }
        return target;
      }
      function _toConsumableArray(arr) {
        return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread();
      }
      function _arrayWithoutHoles(arr) {
        if (Array.isArray(arr)) return _arrayLikeToArray(arr);
      }
      function _iterableToArray(iter) {
        if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter);
      }
      function _unsupportedIterableToArray(o, minLen) {
        if (!o) return;
        if (typeof o === "string") return _arrayLikeToArray(o, minLen);
        var n = Object.prototype.toString.call(o).slice(8, -1);
        if (n === "Object" && o.constructor) n = o.constructor.name;
        if (n === "Map" || n === "Set") return Array.from(o);
        if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen);
      }
      function _arrayLikeToArray(arr, len) {
        if (len == null || len > arr.length) len = arr.length;
        for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i];
        return arr2;
      }
      function _nonIterableSpread() {
        throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
      }
      var version = "1.15.6";
      function userAgent(pattern) {
        if (typeof window !== "undefined" && window.navigator) {
          return !!/* @__PURE__ */ navigator.userAgent.match(pattern);
        }
      }
      var IE11OrLess = userAgent(/(?:Trident.*rv[ :]?11\.|msie|iemobile|Windows Phone)/i);
      var Edge = userAgent(/Edge/i);
      var FireFox = userAgent(/firefox/i);
      var Safari = userAgent(/safari/i) && !userAgent(/chrome/i) && !userAgent(/android/i);
      var IOS = userAgent(/iP(ad|od|hone)/i);
      var ChromeForAndroid = userAgent(/chrome/i) && userAgent(/android/i);
      var captureMode = {
        capture: false,
        passive: false
      };
      function on(el, event, fn) {
        el.addEventListener(event, fn, !IE11OrLess && captureMode);
      }
      function off(el, event, fn) {
        el.removeEventListener(event, fn, !IE11OrLess && captureMode);
      }
      function matches(el, selector) {
        if (!selector) return;
        selector[0] === ">" && (selector = selector.substring(1));
        if (el) {
          try {
            if (el.matches) {
              return el.matches(selector);
            } else if (el.msMatchesSelector) {
              return el.msMatchesSelector(selector);
            } else if (el.webkitMatchesSelector) {
              return el.webkitMatchesSelector(selector);
            }
          } catch (_) {
            return false;
          }
        }
        return false;
      }
      function getParentOrHost(el) {
        return el.host && el !== document && el.host.nodeType ? el.host : el.parentNode;
      }
      function closest(el, selector, ctx, includeCTX) {
        if (el) {
          ctx = ctx || document;
          do {
            if (selector != null && (selector[0] === ">" ? el.parentNode === ctx && matches(el, selector) : matches(el, selector)) || includeCTX && el === ctx) {
              return el;
            }
            if (el === ctx) break;
          } while (el = getParentOrHost(el));
        }
        return null;
      }
      var R_SPACE = /\s+/g;
      function toggleClass(el, name, state) {
        if (el && name) {
          if (el.classList) {
            el.classList[state ? "add" : "remove"](name);
          } else {
            var className = (" " + el.className + " ").replace(R_SPACE, " ").replace(" " + name + " ", " ");
            el.className = (className + (state ? " " + name : "")).replace(R_SPACE, " ");
          }
        }
      }
      function css(el, prop, val) {
        var style = el && el.style;
        if (style) {
          if (val === void 0) {
            if (document.defaultView && document.defaultView.getComputedStyle) {
              val = document.defaultView.getComputedStyle(el, "");
            } else if (el.currentStyle) {
              val = el.currentStyle;
            }
            return prop === void 0 ? val : val[prop];
          } else {
            if (!(prop in style) && prop.indexOf("webkit") === -1) {
              prop = "-webkit-" + prop;
            }
            style[prop] = val + (typeof val === "string" ? "" : "px");
          }
        }
      }
      function matrix(el, selfOnly) {
        var appliedTransforms = "";
        if (typeof el === "string") {
          appliedTransforms = el;
        } else {
          do {
            var transform = css(el, "transform");
            if (transform && transform !== "none") {
              appliedTransforms = transform + " " + appliedTransforms;
            }
          } while (!selfOnly && (el = el.parentNode));
        }
        var matrixFn = window.DOMMatrix || window.WebKitCSSMatrix || window.CSSMatrix || window.MSCSSMatrix;
        return matrixFn && new matrixFn(appliedTransforms);
      }
      function find(ctx, tagName, iterator) {
        if (ctx) {
          var list = ctx.getElementsByTagName(tagName), i = 0, n = list.length;
          if (iterator) {
            for (; i < n; i++) {
              iterator(list[i], i);
            }
          }
          return list;
        }
        return [];
      }
      function getWindowScrollingElement() {
        var scrollingElement = document.scrollingElement;
        if (scrollingElement) {
          return scrollingElement;
        } else {
          return document.documentElement;
        }
      }
      function getRect(el, relativeToContainingBlock, relativeToNonStaticParent, undoScale, container) {
        if (!el.getBoundingClientRect && el !== window) return;
        var elRect, top, left, bottom, right, height, width;
        if (el !== window && el.parentNode && el !== getWindowScrollingElement()) {
          elRect = el.getBoundingClientRect();
          top = elRect.top;
          left = elRect.left;
          bottom = elRect.bottom;
          right = elRect.right;
          height = elRect.height;
          width = elRect.width;
        } else {
          top = 0;
          left = 0;
          bottom = window.innerHeight;
          right = window.innerWidth;
          height = window.innerHeight;
          width = window.innerWidth;
        }
        if ((relativeToContainingBlock || relativeToNonStaticParent) && el !== window) {
          container = container || el.parentNode;
          if (!IE11OrLess) {
            do {
              if (container && container.getBoundingClientRect && (css(container, "transform") !== "none" || relativeToNonStaticParent && css(container, "position") !== "static")) {
                var containerRect = container.getBoundingClientRect();
                top -= containerRect.top + parseInt(css(container, "border-top-width"));
                left -= containerRect.left + parseInt(css(container, "border-left-width"));
                bottom = top + elRect.height;
                right = left + elRect.width;
                break;
              }
            } while (container = container.parentNode);
          }
        }
        if (undoScale && el !== window) {
          var elMatrix = matrix(container || el), scaleX = elMatrix && elMatrix.a, scaleY = elMatrix && elMatrix.d;
          if (elMatrix) {
            top /= scaleY;
            left /= scaleX;
            width /= scaleX;
            height /= scaleY;
            bottom = top + height;
            right = left + width;
          }
        }
        return {
          top,
          left,
          bottom,
          right,
          width,
          height
        };
      }
      function isScrolledPast(el, elSide, parentSide) {
        var parent = getParentAutoScrollElement(el, true), elSideVal = getRect(el)[elSide];
        while (parent) {
          var parentSideVal = getRect(parent)[parentSide], visible = void 0;
          {
            visible = elSideVal >= parentSideVal;
          }
          if (!visible) return parent;
          if (parent === getWindowScrollingElement()) break;
          parent = getParentAutoScrollElement(parent, false);
        }
        return false;
      }
      function getChild(el, childNum, options, includeDragEl) {
        var currentChild = 0, i = 0, children = el.children;
        while (i < children.length) {
          if (children[i].style.display !== "none" && children[i] !== Sortable2.ghost && (includeDragEl || children[i] !== Sortable2.dragged) && closest(children[i], options.draggable, el, false)) {
            if (currentChild === childNum) {
              return children[i];
            }
            currentChild++;
          }
          i++;
        }
        return null;
      }
      function lastChild(el, selector) {
        var last = el.lastElementChild;
        while (last && (last === Sortable2.ghost || css(last, "display") === "none" || selector && !matches(last, selector))) {
          last = last.previousElementSibling;
        }
        return last || null;
      }
      function index(el, selector) {
        var index2 = 0;
        if (!el || !el.parentNode) {
          return -1;
        }
        while (el = el.previousElementSibling) {
          if (el.nodeName.toUpperCase() !== "TEMPLATE" && el !== Sortable2.clone && (!selector || matches(el, selector))) {
            index2++;
          }
        }
        return index2;
      }
      function getRelativeScrollOffset(el) {
        var offsetLeft = 0, offsetTop = 0, winScroller = getWindowScrollingElement();
        if (el) {
          do {
            var elMatrix = matrix(el), scaleX = elMatrix.a, scaleY = elMatrix.d;
            offsetLeft += el.scrollLeft * scaleX;
            offsetTop += el.scrollTop * scaleY;
          } while (el !== winScroller && (el = el.parentNode));
        }
        return [offsetLeft, offsetTop];
      }
      function indexOfObject(arr, obj) {
        for (var i in arr) {
          if (!arr.hasOwnProperty(i)) continue;
          for (var key in obj) {
            if (obj.hasOwnProperty(key) && obj[key] === arr[i][key]) return Number(i);
          }
        }
        return -1;
      }
      function getParentAutoScrollElement(el, includeSelf) {
        if (!el || !el.getBoundingClientRect) return getWindowScrollingElement();
        var elem2 = el;
        var gotSelf = false;
        do {
          if (elem2.clientWidth < elem2.scrollWidth || elem2.clientHeight < elem2.scrollHeight) {
            var elemCSS = css(elem2);
            if (elem2.clientWidth < elem2.scrollWidth && (elemCSS.overflowX == "auto" || elemCSS.overflowX == "scroll") || elem2.clientHeight < elem2.scrollHeight && (elemCSS.overflowY == "auto" || elemCSS.overflowY == "scroll")) {
              if (!elem2.getBoundingClientRect || elem2 === document.body) return getWindowScrollingElement();
              if (gotSelf || includeSelf) return elem2;
              gotSelf = true;
            }
          }
        } while (elem2 = elem2.parentNode);
        return getWindowScrollingElement();
      }
      function extend(dst, src) {
        if (dst && src) {
          for (var key in src) {
            if (src.hasOwnProperty(key)) {
              dst[key] = src[key];
            }
          }
        }
        return dst;
      }
      function isRectEqual(rect1, rect2) {
        return Math.round(rect1.top) === Math.round(rect2.top) && Math.round(rect1.left) === Math.round(rect2.left) && Math.round(rect1.height) === Math.round(rect2.height) && Math.round(rect1.width) === Math.round(rect2.width);
      }
      var _throttleTimeout;
      function throttle(callback, ms) {
        return function() {
          if (!_throttleTimeout) {
            var args = arguments, _this = this;
            if (args.length === 1) {
              callback.call(_this, args[0]);
            } else {
              callback.apply(_this, args);
            }
            _throttleTimeout = setTimeout(function() {
              _throttleTimeout = void 0;
            }, ms);
          }
        };
      }
      function cancelThrottle() {
        clearTimeout(_throttleTimeout);
        _throttleTimeout = void 0;
      }
      function scrollBy(el, x, y) {
        el.scrollLeft += x;
        el.scrollTop += y;
      }
      function clone(el) {
        var Polymer = window.Polymer;
        var $2 = window.jQuery || window.Zepto;
        if (Polymer && Polymer.dom) {
          return Polymer.dom(el).cloneNode(true);
        } else if ($2) {
          return $2(el).clone(true)[0];
        } else {
          return el.cloneNode(true);
        }
      }
      function setRect(el, rect) {
        css(el, "position", "absolute");
        css(el, "top", rect.top);
        css(el, "left", rect.left);
        css(el, "width", rect.width);
        css(el, "height", rect.height);
      }
      function unsetRect(el) {
        css(el, "position", "");
        css(el, "top", "");
        css(el, "left", "");
        css(el, "width", "");
        css(el, "height", "");
      }
      function getChildContainingRectFromElement(container, options, ghostEl2) {
        var rect = {};
        Array.from(container.children).forEach(function(child) {
          var _rect$left, _rect$top, _rect$right, _rect$bottom;
          if (!closest(child, options.draggable, container, false) || child.animated || child === ghostEl2) return;
          var childRect = getRect(child);
          rect.left = Math.min((_rect$left = rect.left) !== null && _rect$left !== void 0 ? _rect$left : Infinity, childRect.left);
          rect.top = Math.min((_rect$top = rect.top) !== null && _rect$top !== void 0 ? _rect$top : Infinity, childRect.top);
          rect.right = Math.max((_rect$right = rect.right) !== null && _rect$right !== void 0 ? _rect$right : -Infinity, childRect.right);
          rect.bottom = Math.max((_rect$bottom = rect.bottom) !== null && _rect$bottom !== void 0 ? _rect$bottom : -Infinity, childRect.bottom);
        });
        rect.width = rect.right - rect.left;
        rect.height = rect.bottom - rect.top;
        rect.x = rect.left;
        rect.y = rect.top;
        return rect;
      }
      var expando = "Sortable" + (/* @__PURE__ */ new Date()).getTime();
      function AnimationStateManager() {
        var animationStates = [], animationCallbackId;
        return {
          captureAnimationState: function captureAnimationState() {
            animationStates = [];
            if (!this.options.animation) return;
            var children = [].slice.call(this.el.children);
            children.forEach(function(child) {
              if (css(child, "display") === "none" || child === Sortable2.ghost) return;
              animationStates.push({
                target: child,
                rect: getRect(child)
              });
              var fromRect = _objectSpread2({}, animationStates[animationStates.length - 1].rect);
              if (child.thisAnimationDuration) {
                var childMatrix = matrix(child, true);
                if (childMatrix) {
                  fromRect.top -= childMatrix.f;
                  fromRect.left -= childMatrix.e;
                }
              }
              child.fromRect = fromRect;
            });
          },
          addAnimationState: function addAnimationState(state) {
            animationStates.push(state);
          },
          removeAnimationState: function removeAnimationState(target) {
            animationStates.splice(indexOfObject(animationStates, {
              target
            }), 1);
          },
          animateAll: function animateAll(callback) {
            var _this = this;
            if (!this.options.animation) {
              clearTimeout(animationCallbackId);
              if (typeof callback === "function") callback();
              return;
            }
            var animating = false, animationTime = 0;
            animationStates.forEach(function(state) {
              var time = 0, target = state.target, fromRect = target.fromRect, toRect = getRect(target), prevFromRect = target.prevFromRect, prevToRect = target.prevToRect, animatingRect = state.rect, targetMatrix = matrix(target, true);
              if (targetMatrix) {
                toRect.top -= targetMatrix.f;
                toRect.left -= targetMatrix.e;
              }
              target.toRect = toRect;
              if (target.thisAnimationDuration) {
                if (isRectEqual(prevFromRect, toRect) && !isRectEqual(fromRect, toRect) && // Make sure animatingRect is on line between toRect & fromRect
                (animatingRect.top - toRect.top) / (animatingRect.left - toRect.left) === (fromRect.top - toRect.top) / (fromRect.left - toRect.left)) {
                  time = calculateRealTime(animatingRect, prevFromRect, prevToRect, _this.options);
                }
              }
              if (!isRectEqual(toRect, fromRect)) {
                target.prevFromRect = fromRect;
                target.prevToRect = toRect;
                if (!time) {
                  time = _this.options.animation;
                }
                _this.animate(target, animatingRect, toRect, time);
              }
              if (time) {
                animating = true;
                animationTime = Math.max(animationTime, time);
                clearTimeout(target.animationResetTimer);
                target.animationResetTimer = setTimeout(function() {
                  target.animationTime = 0;
                  target.prevFromRect = null;
                  target.fromRect = null;
                  target.prevToRect = null;
                  target.thisAnimationDuration = null;
                }, time);
                target.thisAnimationDuration = time;
              }
            });
            clearTimeout(animationCallbackId);
            if (!animating) {
              if (typeof callback === "function") callback();
            } else {
              animationCallbackId = setTimeout(function() {
                if (typeof callback === "function") callback();
              }, animationTime);
            }
            animationStates = [];
          },
          animate: function animate(target, currentRect, toRect, duration) {
            if (duration) {
              css(target, "transition", "");
              css(target, "transform", "");
              var elMatrix = matrix(this.el), scaleX = elMatrix && elMatrix.a, scaleY = elMatrix && elMatrix.d, translateX = (currentRect.left - toRect.left) / (scaleX || 1), translateY = (currentRect.top - toRect.top) / (scaleY || 1);
              target.animatingX = !!translateX;
              target.animatingY = !!translateY;
              css(target, "transform", "translate3d(" + translateX + "px," + translateY + "px,0)");
              this.forRepaintDummy = repaint(target);
              css(target, "transition", "transform " + duration + "ms" + (this.options.easing ? " " + this.options.easing : ""));
              css(target, "transform", "translate3d(0,0,0)");
              typeof target.animated === "number" && clearTimeout(target.animated);
              target.animated = setTimeout(function() {
                css(target, "transition", "");
                css(target, "transform", "");
                target.animated = false;
                target.animatingX = false;
                target.animatingY = false;
              }, duration);
            }
          }
        };
      }
      function repaint(target) {
        return target.offsetWidth;
      }
      function calculateRealTime(animatingRect, fromRect, toRect, options) {
        return Math.sqrt(Math.pow(fromRect.top - animatingRect.top, 2) + Math.pow(fromRect.left - animatingRect.left, 2)) / Math.sqrt(Math.pow(fromRect.top - toRect.top, 2) + Math.pow(fromRect.left - toRect.left, 2)) * options.animation;
      }
      var plugins = [];
      var defaults = {
        initializeByDefault: true
      };
      var PluginManager = {
        mount: function mount(plugin) {
          for (var option in defaults) {
            if (defaults.hasOwnProperty(option) && !(option in plugin)) {
              plugin[option] = defaults[option];
            }
          }
          plugins.forEach(function(p) {
            if (p.pluginName === plugin.pluginName) {
              throw "Sortable: Cannot mount plugin ".concat(plugin.pluginName, " more than once");
            }
          });
          plugins.push(plugin);
        },
        pluginEvent: function pluginEvent2(eventName, sortable, evt) {
          var _this = this;
          this.eventCanceled = false;
          evt.cancel = function() {
            _this.eventCanceled = true;
          };
          var eventNameGlobal = eventName + "Global";
          plugins.forEach(function(plugin) {
            if (!sortable[plugin.pluginName]) return;
            if (sortable[plugin.pluginName][eventNameGlobal]) {
              sortable[plugin.pluginName][eventNameGlobal](_objectSpread2({
                sortable
              }, evt));
            }
            if (sortable.options[plugin.pluginName] && sortable[plugin.pluginName][eventName]) {
              sortable[plugin.pluginName][eventName](_objectSpread2({
                sortable
              }, evt));
            }
          });
        },
        initializePlugins: function initializePlugins(sortable, el, defaults2, options) {
          plugins.forEach(function(plugin) {
            var pluginName = plugin.pluginName;
            if (!sortable.options[pluginName] && !plugin.initializeByDefault) return;
            var initialized = new plugin(sortable, el, sortable.options);
            initialized.sortable = sortable;
            initialized.options = sortable.options;
            sortable[pluginName] = initialized;
            _extends(defaults2, initialized.defaults);
          });
          for (var option in sortable.options) {
            if (!sortable.options.hasOwnProperty(option)) continue;
            var modified = this.modifyOption(sortable, option, sortable.options[option]);
            if (typeof modified !== "undefined") {
              sortable.options[option] = modified;
            }
          }
        },
        getEventProperties: function getEventProperties(name, sortable) {
          var eventProperties = {};
          plugins.forEach(function(plugin) {
            if (typeof plugin.eventProperties !== "function") return;
            _extends(eventProperties, plugin.eventProperties.call(sortable[plugin.pluginName], name));
          });
          return eventProperties;
        },
        modifyOption: function modifyOption(sortable, name, value) {
          var modifiedValue;
          plugins.forEach(function(plugin) {
            if (!sortable[plugin.pluginName]) return;
            if (plugin.optionListeners && typeof plugin.optionListeners[name] === "function") {
              modifiedValue = plugin.optionListeners[name].call(sortable[plugin.pluginName], value);
            }
          });
          return modifiedValue;
        }
      };
      function dispatchEvent(_ref) {
        var sortable = _ref.sortable, rootEl2 = _ref.rootEl, name = _ref.name, targetEl = _ref.targetEl, cloneEl2 = _ref.cloneEl, toEl = _ref.toEl, fromEl = _ref.fromEl, oldIndex2 = _ref.oldIndex, newIndex2 = _ref.newIndex, oldDraggableIndex2 = _ref.oldDraggableIndex, newDraggableIndex2 = _ref.newDraggableIndex, originalEvent = _ref.originalEvent, putSortable2 = _ref.putSortable, extraEventProperties = _ref.extraEventProperties;
        sortable = sortable || rootEl2 && rootEl2[expando];
        if (!sortable) return;
        var evt, options = sortable.options, onName = "on" + name.charAt(0).toUpperCase() + name.substr(1);
        if (window.CustomEvent && !IE11OrLess && !Edge) {
          evt = new CustomEvent(name, {
            bubbles: true,
            cancelable: true
          });
        } else {
          evt = document.createEvent("Event");
          evt.initEvent(name, true, true);
        }
        evt.to = toEl || rootEl2;
        evt.from = fromEl || rootEl2;
        evt.item = targetEl || rootEl2;
        evt.clone = cloneEl2;
        evt.oldIndex = oldIndex2;
        evt.newIndex = newIndex2;
        evt.oldDraggableIndex = oldDraggableIndex2;
        evt.newDraggableIndex = newDraggableIndex2;
        evt.originalEvent = originalEvent;
        evt.pullMode = putSortable2 ? putSortable2.lastPutMode : void 0;
        var allEventProperties = _objectSpread2(_objectSpread2({}, extraEventProperties), PluginManager.getEventProperties(name, sortable));
        for (var option in allEventProperties) {
          evt[option] = allEventProperties[option];
        }
        if (rootEl2) {
          rootEl2.dispatchEvent(evt);
        }
        if (options[onName]) {
          options[onName].call(sortable, evt);
        }
      }
      var _excluded = ["evt"];
      var pluginEvent = function pluginEvent2(eventName, sortable) {
        var _ref = arguments.length > 2 && arguments[2] !== void 0 ? arguments[2] : {}, originalEvent = _ref.evt, data = _objectWithoutProperties(_ref, _excluded);
        PluginManager.pluginEvent.bind(Sortable2)(eventName, sortable, _objectSpread2({
          dragEl,
          parentEl,
          ghostEl,
          rootEl,
          nextEl,
          lastDownEl,
          cloneEl,
          cloneHidden,
          dragStarted: moved,
          putSortable,
          activeSortable: Sortable2.active,
          originalEvent,
          oldIndex,
          oldDraggableIndex,
          newIndex,
          newDraggableIndex,
          hideGhostForTarget: _hideGhostForTarget,
          unhideGhostForTarget: _unhideGhostForTarget,
          cloneNowHidden: function cloneNowHidden() {
            cloneHidden = true;
          },
          cloneNowShown: function cloneNowShown() {
            cloneHidden = false;
          },
          dispatchSortableEvent: function dispatchSortableEvent(name) {
            _dispatchEvent({
              sortable,
              name,
              originalEvent
            });
          }
        }, data));
      };
      function _dispatchEvent(info) {
        dispatchEvent(_objectSpread2({
          putSortable,
          cloneEl,
          targetEl: dragEl,
          rootEl,
          oldIndex,
          oldDraggableIndex,
          newIndex,
          newDraggableIndex
        }, info));
      }
      var dragEl, parentEl, ghostEl, rootEl, nextEl, lastDownEl, cloneEl, cloneHidden, oldIndex, newIndex, oldDraggableIndex, newDraggableIndex, activeGroup, putSortable, awaitingDragStarted = false, ignoreNextClick = false, sortables = [], tapEvt, touchEvt, lastDx, lastDy, tapDistanceLeft, tapDistanceTop, moved, lastTarget, lastDirection, pastFirstInvertThresh = false, isCircumstantialInvert = false, targetMoveDistance, ghostRelativeParent, ghostRelativeParentInitialScroll = [], _silent = false, savedInputChecked = [];
      var documentExists = typeof document !== "undefined", PositionGhostAbsolutely = IOS, CSSFloatProperty = Edge || IE11OrLess ? "cssFloat" : "float", supportDraggable = documentExists && !ChromeForAndroid && !IOS && "draggable" in document.createElement("div"), supportCssPointerEvents = (function() {
        if (!documentExists) return;
        if (IE11OrLess) {
          return false;
        }
        var el = document.createElement("x");
        el.style.cssText = "pointer-events:auto";
        return el.style.pointerEvents === "auto";
      })(), _detectDirection = function _detectDirection2(el, options) {
        var elCSS = css(el), elWidth = parseInt(elCSS.width) - parseInt(elCSS.paddingLeft) - parseInt(elCSS.paddingRight) - parseInt(elCSS.borderLeftWidth) - parseInt(elCSS.borderRightWidth), child1 = getChild(el, 0, options), child2 = getChild(el, 1, options), firstChildCSS = child1 && css(child1), secondChildCSS = child2 && css(child2), firstChildWidth = firstChildCSS && parseInt(firstChildCSS.marginLeft) + parseInt(firstChildCSS.marginRight) + getRect(child1).width, secondChildWidth = secondChildCSS && parseInt(secondChildCSS.marginLeft) + parseInt(secondChildCSS.marginRight) + getRect(child2).width;
        if (elCSS.display === "flex") {
          return elCSS.flexDirection === "column" || elCSS.flexDirection === "column-reverse" ? "vertical" : "horizontal";
        }
        if (elCSS.display === "grid") {
          return elCSS.gridTemplateColumns.split(" ").length <= 1 ? "vertical" : "horizontal";
        }
        if (child1 && firstChildCSS["float"] && firstChildCSS["float"] !== "none") {
          var touchingSideChild2 = firstChildCSS["float"] === "left" ? "left" : "right";
          return child2 && (secondChildCSS.clear === "both" || secondChildCSS.clear === touchingSideChild2) ? "vertical" : "horizontal";
        }
        return child1 && (firstChildCSS.display === "block" || firstChildCSS.display === "flex" || firstChildCSS.display === "table" || firstChildCSS.display === "grid" || firstChildWidth >= elWidth && elCSS[CSSFloatProperty] === "none" || child2 && elCSS[CSSFloatProperty] === "none" && firstChildWidth + secondChildWidth > elWidth) ? "vertical" : "horizontal";
      }, _dragElInRowColumn = function _dragElInRowColumn2(dragRect, targetRect, vertical) {
        var dragElS1Opp = vertical ? dragRect.left : dragRect.top, dragElS2Opp = vertical ? dragRect.right : dragRect.bottom, dragElOppLength = vertical ? dragRect.width : dragRect.height, targetS1Opp = vertical ? targetRect.left : targetRect.top, targetS2Opp = vertical ? targetRect.right : targetRect.bottom, targetOppLength = vertical ? targetRect.width : targetRect.height;
        return dragElS1Opp === targetS1Opp || dragElS2Opp === targetS2Opp || dragElS1Opp + dragElOppLength / 2 === targetS1Opp + targetOppLength / 2;
      }, _detectNearestEmptySortable = function _detectNearestEmptySortable2(x, y) {
        var ret;
        sortables.some(function(sortable) {
          var threshold = sortable[expando].options.emptyInsertThreshold;
          if (!threshold || lastChild(sortable)) return;
          var rect = getRect(sortable), insideHorizontally = x >= rect.left - threshold && x <= rect.right + threshold, insideVertically = y >= rect.top - threshold && y <= rect.bottom + threshold;
          if (insideHorizontally && insideVertically) {
            return ret = sortable;
          }
        });
        return ret;
      }, _prepareGroup = function _prepareGroup2(options) {
        function toFn(value, pull) {
          return function(to, from, dragEl2, evt) {
            var sameGroup = to.options.group.name && from.options.group.name && to.options.group.name === from.options.group.name;
            if (value == null && (pull || sameGroup)) {
              return true;
            } else if (value == null || value === false) {
              return false;
            } else if (pull && value === "clone") {
              return value;
            } else if (typeof value === "function") {
              return toFn(value(to, from, dragEl2, evt), pull)(to, from, dragEl2, evt);
            } else {
              var otherGroup = (pull ? to : from).options.group.name;
              return value === true || typeof value === "string" && value === otherGroup || value.join && value.indexOf(otherGroup) > -1;
            }
          };
        }
        var group = {};
        var originalGroup = options.group;
        if (!originalGroup || _typeof(originalGroup) != "object") {
          originalGroup = {
            name: originalGroup
          };
        }
        group.name = originalGroup.name;
        group.checkPull = toFn(originalGroup.pull, true);
        group.checkPut = toFn(originalGroup.put);
        group.revertClone = originalGroup.revertClone;
        options.group = group;
      }, _hideGhostForTarget = function _hideGhostForTarget2() {
        if (!supportCssPointerEvents && ghostEl) {
          css(ghostEl, "display", "none");
        }
      }, _unhideGhostForTarget = function _unhideGhostForTarget2() {
        if (!supportCssPointerEvents && ghostEl) {
          css(ghostEl, "display", "");
        }
      };
      if (documentExists && !ChromeForAndroid) {
        document.addEventListener("click", function(evt) {
          if (ignoreNextClick) {
            evt.preventDefault();
            evt.stopPropagation && evt.stopPropagation();
            evt.stopImmediatePropagation && evt.stopImmediatePropagation();
            ignoreNextClick = false;
            return false;
          }
        }, true);
      }
      var nearestEmptyInsertDetectEvent = function nearestEmptyInsertDetectEvent2(evt) {
        if (dragEl) {
          evt = evt.touches ? evt.touches[0] : evt;
          var nearest = _detectNearestEmptySortable(evt.clientX, evt.clientY);
          if (nearest) {
            var event = {};
            for (var i in evt) {
              if (evt.hasOwnProperty(i)) {
                event[i] = evt[i];
              }
            }
            event.target = event.rootEl = nearest;
            event.preventDefault = void 0;
            event.stopPropagation = void 0;
            nearest[expando]._onDragOver(event);
          }
        }
      };
      var _checkOutsideTargetEl = function _checkOutsideTargetEl2(evt) {
        if (dragEl) {
          dragEl.parentNode[expando]._isOutsideThisEl(evt.target);
        }
      };
      function Sortable2(el, options) {
        if (!(el && el.nodeType && el.nodeType === 1)) {
          throw "Sortable: `el` must be an HTMLElement, not ".concat({}.toString.call(el));
        }
        this.el = el;
        this.options = options = _extends({}, options);
        el[expando] = this;
        var defaults2 = {
          group: null,
          sort: true,
          disabled: false,
          store: null,
          handle: null,
          draggable: /^[uo]l$/i.test(el.nodeName) ? ">li" : ">*",
          swapThreshold: 1,
          // percentage; 0 <= x <= 1
          invertSwap: false,
          // invert always
          invertedSwapThreshold: null,
          // will be set to same as swapThreshold if default
          removeCloneOnHide: true,
          direction: function direction() {
            return _detectDirection(el, this.options);
          },
          ghostClass: "sortable-ghost",
          chosenClass: "sortable-chosen",
          dragClass: "sortable-drag",
          ignore: "a, img",
          filter: null,
          preventOnFilter: true,
          animation: 0,
          easing: null,
          setData: function setData(dataTransfer, dragEl2) {
            dataTransfer.setData("Text", dragEl2.textContent);
          },
          dropBubble: false,
          dragoverBubble: false,
          dataIdAttr: "data-id",
          delay: 0,
          delayOnTouchOnly: false,
          touchStartThreshold: (Number.parseInt ? Number : window).parseInt(window.devicePixelRatio, 10) || 1,
          forceFallback: false,
          fallbackClass: "sortable-fallback",
          fallbackOnBody: false,
          fallbackTolerance: 0,
          fallbackOffset: {
            x: 0,
            y: 0
          },
          // Disabled on Safari: #1571; Enabled on Safari IOS: #2244
          supportPointer: Sortable2.supportPointer !== false && "PointerEvent" in window && (!Safari || IOS),
          emptyInsertThreshold: 5
        };
        PluginManager.initializePlugins(this, el, defaults2);
        for (var name in defaults2) {
          !(name in options) && (options[name] = defaults2[name]);
        }
        _prepareGroup(options);
        for (var fn in this) {
          if (fn.charAt(0) === "_" && typeof this[fn] === "function") {
            this[fn] = this[fn].bind(this);
          }
        }
        this.nativeDraggable = options.forceFallback ? false : supportDraggable;
        if (this.nativeDraggable) {
          this.options.touchStartThreshold = 1;
        }
        if (options.supportPointer) {
          on(el, "pointerdown", this._onTapStart);
        } else {
          on(el, "mousedown", this._onTapStart);
          on(el, "touchstart", this._onTapStart);
        }
        if (this.nativeDraggable) {
          on(el, "dragover", this);
          on(el, "dragenter", this);
        }
        sortables.push(this.el);
        options.store && options.store.get && this.sort(options.store.get(this) || []);
        _extends(this, AnimationStateManager());
      }
      Sortable2.prototype = /** @lends Sortable.prototype */
      {
        constructor: Sortable2,
        _isOutsideThisEl: function _isOutsideThisEl(target) {
          if (!this.el.contains(target) && target !== this.el) {
            lastTarget = null;
          }
        },
        _getDirection: function _getDirection(evt, target) {
          return typeof this.options.direction === "function" ? this.options.direction.call(this, evt, target, dragEl) : this.options.direction;
        },
        _onTapStart: function _onTapStart(evt) {
          if (!evt.cancelable) return;
          var _this = this, el = this.el, options = this.options, preventOnFilter = options.preventOnFilter, type = evt.type, touch = evt.touches && evt.touches[0] || evt.pointerType && evt.pointerType === "touch" && evt, target = (touch || evt).target, originalTarget = evt.target.shadowRoot && (evt.path && evt.path[0] || evt.composedPath && evt.composedPath()[0]) || target, filter = options.filter;
          _saveInputCheckedState(el);
          if (dragEl) {
            return;
          }
          if (/mousedown|pointerdown/.test(type) && evt.button !== 0 || options.disabled) {
            return;
          }
          if (originalTarget.isContentEditable) {
            return;
          }
          if (!this.nativeDraggable && Safari && target && target.tagName.toUpperCase() === "SELECT") {
            return;
          }
          target = closest(target, options.draggable, el, false);
          if (target && target.animated) {
            return;
          }
          if (lastDownEl === target) {
            return;
          }
          oldIndex = index(target);
          oldDraggableIndex = index(target, options.draggable);
          if (typeof filter === "function") {
            if (filter.call(this, evt, target, this)) {
              _dispatchEvent({
                sortable: _this,
                rootEl: originalTarget,
                name: "filter",
                targetEl: target,
                toEl: el,
                fromEl: el
              });
              pluginEvent("filter", _this, {
                evt
              });
              preventOnFilter && evt.preventDefault();
              return;
            }
          } else if (filter) {
            filter = filter.split(",").some(function(criteria) {
              criteria = closest(originalTarget, criteria.trim(), el, false);
              if (criteria) {
                _dispatchEvent({
                  sortable: _this,
                  rootEl: criteria,
                  name: "filter",
                  targetEl: target,
                  fromEl: el,
                  toEl: el
                });
                pluginEvent("filter", _this, {
                  evt
                });
                return true;
              }
            });
            if (filter) {
              preventOnFilter && evt.preventDefault();
              return;
            }
          }
          if (options.handle && !closest(originalTarget, options.handle, el, false)) {
            return;
          }
          this._prepareDragStart(evt, touch, target);
        },
        _prepareDragStart: function _prepareDragStart(evt, touch, target) {
          var _this = this, el = _this.el, options = _this.options, ownerDocument = el.ownerDocument, dragStartFn;
          if (target && !dragEl && target.parentNode === el) {
            var dragRect = getRect(target);
            rootEl = el;
            dragEl = target;
            parentEl = dragEl.parentNode;
            nextEl = dragEl.nextSibling;
            lastDownEl = target;
            activeGroup = options.group;
            Sortable2.dragged = dragEl;
            tapEvt = {
              target: dragEl,
              clientX: (touch || evt).clientX,
              clientY: (touch || evt).clientY
            };
            tapDistanceLeft = tapEvt.clientX - dragRect.left;
            tapDistanceTop = tapEvt.clientY - dragRect.top;
            this._lastX = (touch || evt).clientX;
            this._lastY = (touch || evt).clientY;
            dragEl.style["will-change"] = "all";
            dragStartFn = function dragStartFn2() {
              pluginEvent("delayEnded", _this, {
                evt
              });
              if (Sortable2.eventCanceled) {
                _this._onDrop();
                return;
              }
              _this._disableDelayedDragEvents();
              if (!FireFox && _this.nativeDraggable) {
                dragEl.draggable = true;
              }
              _this._triggerDragStart(evt, touch);
              _dispatchEvent({
                sortable: _this,
                name: "choose",
                originalEvent: evt
              });
              toggleClass(dragEl, options.chosenClass, true);
            };
            options.ignore.split(",").forEach(function(criteria) {
              find(dragEl, criteria.trim(), _disableDraggable);
            });
            on(ownerDocument, "dragover", nearestEmptyInsertDetectEvent);
            on(ownerDocument, "mousemove", nearestEmptyInsertDetectEvent);
            on(ownerDocument, "touchmove", nearestEmptyInsertDetectEvent);
            if (options.supportPointer) {
              on(ownerDocument, "pointerup", _this._onDrop);
              !this.nativeDraggable && on(ownerDocument, "pointercancel", _this._onDrop);
            } else {
              on(ownerDocument, "mouseup", _this._onDrop);
              on(ownerDocument, "touchend", _this._onDrop);
              on(ownerDocument, "touchcancel", _this._onDrop);
            }
            if (FireFox && this.nativeDraggable) {
              this.options.touchStartThreshold = 4;
              dragEl.draggable = true;
            }
            pluginEvent("delayStart", this, {
              evt
            });
            if (options.delay && (!options.delayOnTouchOnly || touch) && (!this.nativeDraggable || !(Edge || IE11OrLess))) {
              if (Sortable2.eventCanceled) {
                this._onDrop();
                return;
              }
              if (options.supportPointer) {
                on(ownerDocument, "pointerup", _this._disableDelayedDrag);
                on(ownerDocument, "pointercancel", _this._disableDelayedDrag);
              } else {
                on(ownerDocument, "mouseup", _this._disableDelayedDrag);
                on(ownerDocument, "touchend", _this._disableDelayedDrag);
                on(ownerDocument, "touchcancel", _this._disableDelayedDrag);
              }
              on(ownerDocument, "mousemove", _this._delayedDragTouchMoveHandler);
              on(ownerDocument, "touchmove", _this._delayedDragTouchMoveHandler);
              options.supportPointer && on(ownerDocument, "pointermove", _this._delayedDragTouchMoveHandler);
              _this._dragStartTimer = setTimeout(dragStartFn, options.delay);
            } else {
              dragStartFn();
            }
          }
        },
        _delayedDragTouchMoveHandler: function _delayedDragTouchMoveHandler(e) {
          var touch = e.touches ? e.touches[0] : e;
          if (Math.max(Math.abs(touch.clientX - this._lastX), Math.abs(touch.clientY - this._lastY)) >= Math.floor(this.options.touchStartThreshold / (this.nativeDraggable && window.devicePixelRatio || 1))) {
            this._disableDelayedDrag();
          }
        },
        _disableDelayedDrag: function _disableDelayedDrag() {
          dragEl && _disableDraggable(dragEl);
          clearTimeout(this._dragStartTimer);
          this._disableDelayedDragEvents();
        },
        _disableDelayedDragEvents: function _disableDelayedDragEvents() {
          var ownerDocument = this.el.ownerDocument;
          off(ownerDocument, "mouseup", this._disableDelayedDrag);
          off(ownerDocument, "touchend", this._disableDelayedDrag);
          off(ownerDocument, "touchcancel", this._disableDelayedDrag);
          off(ownerDocument, "pointerup", this._disableDelayedDrag);
          off(ownerDocument, "pointercancel", this._disableDelayedDrag);
          off(ownerDocument, "mousemove", this._delayedDragTouchMoveHandler);
          off(ownerDocument, "touchmove", this._delayedDragTouchMoveHandler);
          off(ownerDocument, "pointermove", this._delayedDragTouchMoveHandler);
        },
        _triggerDragStart: function _triggerDragStart(evt, touch) {
          touch = touch || evt.pointerType == "touch" && evt;
          if (!this.nativeDraggable || touch) {
            if (this.options.supportPointer) {
              on(document, "pointermove", this._onTouchMove);
            } else if (touch) {
              on(document, "touchmove", this._onTouchMove);
            } else {
              on(document, "mousemove", this._onTouchMove);
            }
          } else {
            on(dragEl, "dragend", this);
            on(rootEl, "dragstart", this._onDragStart);
          }
          try {
            if (document.selection) {
              _nextTick(function() {
                document.selection.empty();
              });
            } else {
              window.getSelection().removeAllRanges();
            }
          } catch (err) {
          }
        },
        _dragStarted: function _dragStarted(fallback, evt) {
          awaitingDragStarted = false;
          if (rootEl && dragEl) {
            pluginEvent("dragStarted", this, {
              evt
            });
            if (this.nativeDraggable) {
              on(document, "dragover", _checkOutsideTargetEl);
            }
            var options = this.options;
            !fallback && toggleClass(dragEl, options.dragClass, false);
            toggleClass(dragEl, options.ghostClass, true);
            Sortable2.active = this;
            fallback && this._appendGhost();
            _dispatchEvent({
              sortable: this,
              name: "start",
              originalEvent: evt
            });
          } else {
            this._nulling();
          }
        },
        _emulateDragOver: function _emulateDragOver() {
          if (touchEvt) {
            this._lastX = touchEvt.clientX;
            this._lastY = touchEvt.clientY;
            _hideGhostForTarget();
            var target = document.elementFromPoint(touchEvt.clientX, touchEvt.clientY);
            var parent = target;
            while (target && target.shadowRoot) {
              target = target.shadowRoot.elementFromPoint(touchEvt.clientX, touchEvt.clientY);
              if (target === parent) break;
              parent = target;
            }
            dragEl.parentNode[expando]._isOutsideThisEl(target);
            if (parent) {
              do {
                if (parent[expando]) {
                  var inserted = void 0;
                  inserted = parent[expando]._onDragOver({
                    clientX: touchEvt.clientX,
                    clientY: touchEvt.clientY,
                    target,
                    rootEl: parent
                  });
                  if (inserted && !this.options.dragoverBubble) {
                    break;
                  }
                }
                target = parent;
              } while (parent = getParentOrHost(parent));
            }
            _unhideGhostForTarget();
          }
        },
        _onTouchMove: function _onTouchMove(evt) {
          if (tapEvt) {
            var options = this.options, fallbackTolerance = options.fallbackTolerance, fallbackOffset = options.fallbackOffset, touch = evt.touches ? evt.touches[0] : evt, ghostMatrix = ghostEl && matrix(ghostEl, true), scaleX = ghostEl && ghostMatrix && ghostMatrix.a, scaleY = ghostEl && ghostMatrix && ghostMatrix.d, relativeScrollOffset = PositionGhostAbsolutely && ghostRelativeParent && getRelativeScrollOffset(ghostRelativeParent), dx = (touch.clientX - tapEvt.clientX + fallbackOffset.x) / (scaleX || 1) + (relativeScrollOffset ? relativeScrollOffset[0] - ghostRelativeParentInitialScroll[0] : 0) / (scaleX || 1), dy = (touch.clientY - tapEvt.clientY + fallbackOffset.y) / (scaleY || 1) + (relativeScrollOffset ? relativeScrollOffset[1] - ghostRelativeParentInitialScroll[1] : 0) / (scaleY || 1);
            if (!Sortable2.active && !awaitingDragStarted) {
              if (fallbackTolerance && Math.max(Math.abs(touch.clientX - this._lastX), Math.abs(touch.clientY - this._lastY)) < fallbackTolerance) {
                return;
              }
              this._onDragStart(evt, true);
            }
            if (ghostEl) {
              if (ghostMatrix) {
                ghostMatrix.e += dx - (lastDx || 0);
                ghostMatrix.f += dy - (lastDy || 0);
              } else {
                ghostMatrix = {
                  a: 1,
                  b: 0,
                  c: 0,
                  d: 1,
                  e: dx,
                  f: dy
                };
              }
              var cssMatrix = "matrix(".concat(ghostMatrix.a, ",").concat(ghostMatrix.b, ",").concat(ghostMatrix.c, ",").concat(ghostMatrix.d, ",").concat(ghostMatrix.e, ",").concat(ghostMatrix.f, ")");
              css(ghostEl, "webkitTransform", cssMatrix);
              css(ghostEl, "mozTransform", cssMatrix);
              css(ghostEl, "msTransform", cssMatrix);
              css(ghostEl, "transform", cssMatrix);
              lastDx = dx;
              lastDy = dy;
              touchEvt = touch;
            }
            evt.cancelable && evt.preventDefault();
          }
        },
        _appendGhost: function _appendGhost() {
          if (!ghostEl) {
            var container = this.options.fallbackOnBody ? document.body : rootEl, rect = getRect(dragEl, true, PositionGhostAbsolutely, true, container), options = this.options;
            if (PositionGhostAbsolutely) {
              ghostRelativeParent = container;
              while (css(ghostRelativeParent, "position") === "static" && css(ghostRelativeParent, "transform") === "none" && ghostRelativeParent !== document) {
                ghostRelativeParent = ghostRelativeParent.parentNode;
              }
              if (ghostRelativeParent !== document.body && ghostRelativeParent !== document.documentElement) {
                if (ghostRelativeParent === document) ghostRelativeParent = getWindowScrollingElement();
                rect.top += ghostRelativeParent.scrollTop;
                rect.left += ghostRelativeParent.scrollLeft;
              } else {
                ghostRelativeParent = getWindowScrollingElement();
              }
              ghostRelativeParentInitialScroll = getRelativeScrollOffset(ghostRelativeParent);
            }
            ghostEl = dragEl.cloneNode(true);
            toggleClass(ghostEl, options.ghostClass, false);
            toggleClass(ghostEl, options.fallbackClass, true);
            toggleClass(ghostEl, options.dragClass, true);
            css(ghostEl, "transition", "");
            css(ghostEl, "transform", "");
            css(ghostEl, "box-sizing", "border-box");
            css(ghostEl, "margin", 0);
            css(ghostEl, "top", rect.top);
            css(ghostEl, "left", rect.left);
            css(ghostEl, "width", rect.width);
            css(ghostEl, "height", rect.height);
            css(ghostEl, "opacity", "0.8");
            css(ghostEl, "position", PositionGhostAbsolutely ? "absolute" : "fixed");
            css(ghostEl, "zIndex", "100000");
            css(ghostEl, "pointerEvents", "none");
            Sortable2.ghost = ghostEl;
            container.appendChild(ghostEl);
            css(ghostEl, "transform-origin", tapDistanceLeft / parseInt(ghostEl.style.width) * 100 + "% " + tapDistanceTop / parseInt(ghostEl.style.height) * 100 + "%");
          }
        },
        _onDragStart: function _onDragStart(evt, fallback) {
          var _this = this;
          var dataTransfer = evt.dataTransfer;
          var options = _this.options;
          pluginEvent("dragStart", this, {
            evt
          });
          if (Sortable2.eventCanceled) {
            this._onDrop();
            return;
          }
          pluginEvent("setupClone", this);
          if (!Sortable2.eventCanceled) {
            cloneEl = clone(dragEl);
            cloneEl.removeAttribute("id");
            cloneEl.draggable = false;
            cloneEl.style["will-change"] = "";
            this._hideClone();
            toggleClass(cloneEl, this.options.chosenClass, false);
            Sortable2.clone = cloneEl;
          }
          _this.cloneId = _nextTick(function() {
            pluginEvent("clone", _this);
            if (Sortable2.eventCanceled) return;
            if (!_this.options.removeCloneOnHide) {
              rootEl.insertBefore(cloneEl, dragEl);
            }
            _this._hideClone();
            _dispatchEvent({
              sortable: _this,
              name: "clone"
            });
          });
          !fallback && toggleClass(dragEl, options.dragClass, true);
          if (fallback) {
            ignoreNextClick = true;
            _this._loopId = setInterval(_this._emulateDragOver, 50);
          } else {
            off(document, "mouseup", _this._onDrop);
            off(document, "touchend", _this._onDrop);
            off(document, "touchcancel", _this._onDrop);
            if (dataTransfer) {
              dataTransfer.effectAllowed = "move";
              options.setData && options.setData.call(_this, dataTransfer, dragEl);
            }
            on(document, "drop", _this);
            css(dragEl, "transform", "translateZ(0)");
          }
          awaitingDragStarted = true;
          _this._dragStartId = _nextTick(_this._dragStarted.bind(_this, fallback, evt));
          on(document, "selectstart", _this);
          moved = true;
          window.getSelection().removeAllRanges();
          if (Safari) {
            css(document.body, "user-select", "none");
          }
        },
        // Returns true - if no further action is needed (either inserted or another condition)
        _onDragOver: function _onDragOver(evt) {
          var el = this.el, target = evt.target, dragRect, targetRect, revert, options = this.options, group = options.group, activeSortable = Sortable2.active, isOwner = activeGroup === group, canSort = options.sort, fromSortable = putSortable || activeSortable, vertical, _this = this, completedFired = false;
          if (_silent) return;
          function dragOverEvent(name, extra) {
            pluginEvent(name, _this, _objectSpread2({
              evt,
              isOwner,
              axis: vertical ? "vertical" : "horizontal",
              revert,
              dragRect,
              targetRect,
              canSort,
              fromSortable,
              target,
              completed,
              onMove: function onMove(target2, after2) {
                return _onMove(rootEl, el, dragEl, dragRect, target2, getRect(target2), evt, after2);
              },
              changed
            }, extra));
          }
          function capture() {
            dragOverEvent("dragOverAnimationCapture");
            _this.captureAnimationState();
            if (_this !== fromSortable) {
              fromSortable.captureAnimationState();
            }
          }
          function completed(insertion) {
            dragOverEvent("dragOverCompleted", {
              insertion
            });
            if (insertion) {
              if (isOwner) {
                activeSortable._hideClone();
              } else {
                activeSortable._showClone(_this);
              }
              if (_this !== fromSortable) {
                toggleClass(dragEl, putSortable ? putSortable.options.ghostClass : activeSortable.options.ghostClass, false);
                toggleClass(dragEl, options.ghostClass, true);
              }
              if (putSortable !== _this && _this !== Sortable2.active) {
                putSortable = _this;
              } else if (_this === Sortable2.active && putSortable) {
                putSortable = null;
              }
              if (fromSortable === _this) {
                _this._ignoreWhileAnimating = target;
              }
              _this.animateAll(function() {
                dragOverEvent("dragOverAnimationComplete");
                _this._ignoreWhileAnimating = null;
              });
              if (_this !== fromSortable) {
                fromSortable.animateAll();
                fromSortable._ignoreWhileAnimating = null;
              }
            }
            if (target === dragEl && !dragEl.animated || target === el && !target.animated) {
              lastTarget = null;
            }
            if (!options.dragoverBubble && !evt.rootEl && target !== document) {
              dragEl.parentNode[expando]._isOutsideThisEl(evt.target);
              !insertion && nearestEmptyInsertDetectEvent(evt);
            }
            !options.dragoverBubble && evt.stopPropagation && evt.stopPropagation();
            return completedFired = true;
          }
          function changed() {
            newIndex = index(dragEl);
            newDraggableIndex = index(dragEl, options.draggable);
            _dispatchEvent({
              sortable: _this,
              name: "change",
              toEl: el,
              newIndex,
              newDraggableIndex,
              originalEvent: evt
            });
          }
          if (evt.preventDefault !== void 0) {
            evt.cancelable && evt.preventDefault();
          }
          target = closest(target, options.draggable, el, true);
          dragOverEvent("dragOver");
          if (Sortable2.eventCanceled) return completedFired;
          if (dragEl.contains(evt.target) || target.animated && target.animatingX && target.animatingY || _this._ignoreWhileAnimating === target) {
            return completed(false);
          }
          ignoreNextClick = false;
          if (activeSortable && !options.disabled && (isOwner ? canSort || (revert = parentEl !== rootEl) : putSortable === this || (this.lastPutMode = activeGroup.checkPull(this, activeSortable, dragEl, evt)) && group.checkPut(this, activeSortable, dragEl, evt))) {
            vertical = this._getDirection(evt, target) === "vertical";
            dragRect = getRect(dragEl);
            dragOverEvent("dragOverValid");
            if (Sortable2.eventCanceled) return completedFired;
            if (revert) {
              parentEl = rootEl;
              capture();
              this._hideClone();
              dragOverEvent("revert");
              if (!Sortable2.eventCanceled) {
                if (nextEl) {
                  rootEl.insertBefore(dragEl, nextEl);
                } else {
                  rootEl.appendChild(dragEl);
                }
              }
              return completed(true);
            }
            var elLastChild = lastChild(el, options.draggable);
            if (!elLastChild || _ghostIsLast(evt, vertical, this) && !elLastChild.animated) {
              if (elLastChild === dragEl) {
                return completed(false);
              }
              if (elLastChild && el === evt.target) {
                target = elLastChild;
              }
              if (target) {
                targetRect = getRect(target);
              }
              if (_onMove(rootEl, el, dragEl, dragRect, target, targetRect, evt, !!target) !== false) {
                capture();
                if (elLastChild && elLastChild.nextSibling) {
                  el.insertBefore(dragEl, elLastChild.nextSibling);
                } else {
                  el.appendChild(dragEl);
                }
                parentEl = el;
                changed();
                return completed(true);
              }
            } else if (elLastChild && _ghostIsFirst(evt, vertical, this)) {
              var firstChild = getChild(el, 0, options, true);
              if (firstChild === dragEl) {
                return completed(false);
              }
              target = firstChild;
              targetRect = getRect(target);
              if (_onMove(rootEl, el, dragEl, dragRect, target, targetRect, evt, false) !== false) {
                capture();
                el.insertBefore(dragEl, firstChild);
                parentEl = el;
                changed();
                return completed(true);
              }
            } else if (target.parentNode === el) {
              targetRect = getRect(target);
              var direction = 0, targetBeforeFirstSwap, differentLevel = dragEl.parentNode !== el, differentRowCol = !_dragElInRowColumn(dragEl.animated && dragEl.toRect || dragRect, target.animated && target.toRect || targetRect, vertical), side1 = vertical ? "top" : "left", scrolledPastTop = isScrolledPast(target, "top", "top") || isScrolledPast(dragEl, "top", "top"), scrollBefore = scrolledPastTop ? scrolledPastTop.scrollTop : void 0;
              if (lastTarget !== target) {
                targetBeforeFirstSwap = targetRect[side1];
                pastFirstInvertThresh = false;
                isCircumstantialInvert = !differentRowCol && options.invertSwap || differentLevel;
              }
              direction = _getSwapDirection(evt, target, targetRect, vertical, differentRowCol ? 1 : options.swapThreshold, options.invertedSwapThreshold == null ? options.swapThreshold : options.invertedSwapThreshold, isCircumstantialInvert, lastTarget === target);
              var sibling;
              if (direction !== 0) {
                var dragIndex = index(dragEl);
                do {
                  dragIndex -= direction;
                  sibling = parentEl.children[dragIndex];
                } while (sibling && (css(sibling, "display") === "none" || sibling === ghostEl));
              }
              if (direction === 0 || sibling === target) {
                return completed(false);
              }
              lastTarget = target;
              lastDirection = direction;
              var nextSibling = target.nextElementSibling, after = false;
              after = direction === 1;
              var moveVector = _onMove(rootEl, el, dragEl, dragRect, target, targetRect, evt, after);
              if (moveVector !== false) {
                if (moveVector === 1 || moveVector === -1) {
                  after = moveVector === 1;
                }
                _silent = true;
                setTimeout(_unsilent, 30);
                capture();
                if (after && !nextSibling) {
                  el.appendChild(dragEl);
                } else {
                  target.parentNode.insertBefore(dragEl, after ? nextSibling : target);
                }
                if (scrolledPastTop) {
                  scrollBy(scrolledPastTop, 0, scrollBefore - scrolledPastTop.scrollTop);
                }
                parentEl = dragEl.parentNode;
                if (targetBeforeFirstSwap !== void 0 && !isCircumstantialInvert) {
                  targetMoveDistance = Math.abs(targetBeforeFirstSwap - getRect(target)[side1]);
                }
                changed();
                return completed(true);
              }
            }
            if (el.contains(dragEl)) {
              return completed(false);
            }
          }
          return false;
        },
        _ignoreWhileAnimating: null,
        _offMoveEvents: function _offMoveEvents() {
          off(document, "mousemove", this._onTouchMove);
          off(document, "touchmove", this._onTouchMove);
          off(document, "pointermove", this._onTouchMove);
          off(document, "dragover", nearestEmptyInsertDetectEvent);
          off(document, "mousemove", nearestEmptyInsertDetectEvent);
          off(document, "touchmove", nearestEmptyInsertDetectEvent);
        },
        _offUpEvents: function _offUpEvents() {
          var ownerDocument = this.el.ownerDocument;
          off(ownerDocument, "mouseup", this._onDrop);
          off(ownerDocument, "touchend", this._onDrop);
          off(ownerDocument, "pointerup", this._onDrop);
          off(ownerDocument, "pointercancel", this._onDrop);
          off(ownerDocument, "touchcancel", this._onDrop);
          off(document, "selectstart", this);
        },
        _onDrop: function _onDrop(evt) {
          var el = this.el, options = this.options;
          newIndex = index(dragEl);
          newDraggableIndex = index(dragEl, options.draggable);
          pluginEvent("drop", this, {
            evt
          });
          parentEl = dragEl && dragEl.parentNode;
          newIndex = index(dragEl);
          newDraggableIndex = index(dragEl, options.draggable);
          if (Sortable2.eventCanceled) {
            this._nulling();
            return;
          }
          awaitingDragStarted = false;
          isCircumstantialInvert = false;
          pastFirstInvertThresh = false;
          clearInterval(this._loopId);
          clearTimeout(this._dragStartTimer);
          _cancelNextTick(this.cloneId);
          _cancelNextTick(this._dragStartId);
          if (this.nativeDraggable) {
            off(document, "drop", this);
            off(el, "dragstart", this._onDragStart);
          }
          this._offMoveEvents();
          this._offUpEvents();
          if (Safari) {
            css(document.body, "user-select", "");
          }
          css(dragEl, "transform", "");
          if (evt) {
            if (moved) {
              evt.cancelable && evt.preventDefault();
              !options.dropBubble && evt.stopPropagation();
            }
            ghostEl && ghostEl.parentNode && ghostEl.parentNode.removeChild(ghostEl);
            if (rootEl === parentEl || putSortable && putSortable.lastPutMode !== "clone") {
              cloneEl && cloneEl.parentNode && cloneEl.parentNode.removeChild(cloneEl);
            }
            if (dragEl) {
              if (this.nativeDraggable) {
                off(dragEl, "dragend", this);
              }
              _disableDraggable(dragEl);
              dragEl.style["will-change"] = "";
              if (moved && !awaitingDragStarted) {
                toggleClass(dragEl, putSortable ? putSortable.options.ghostClass : this.options.ghostClass, false);
              }
              toggleClass(dragEl, this.options.chosenClass, false);
              _dispatchEvent({
                sortable: this,
                name: "unchoose",
                toEl: parentEl,
                newIndex: null,
                newDraggableIndex: null,
                originalEvent: evt
              });
              if (rootEl !== parentEl) {
                if (newIndex >= 0) {
                  _dispatchEvent({
                    rootEl: parentEl,
                    name: "add",
                    toEl: parentEl,
                    fromEl: rootEl,
                    originalEvent: evt
                  });
                  _dispatchEvent({
                    sortable: this,
                    name: "remove",
                    toEl: parentEl,
                    originalEvent: evt
                  });
                  _dispatchEvent({
                    rootEl: parentEl,
                    name: "sort",
                    toEl: parentEl,
                    fromEl: rootEl,
                    originalEvent: evt
                  });
                  _dispatchEvent({
                    sortable: this,
                    name: "sort",
                    toEl: parentEl,
                    originalEvent: evt
                  });
                }
                putSortable && putSortable.save();
              } else {
                if (newIndex !== oldIndex) {
                  if (newIndex >= 0) {
                    _dispatchEvent({
                      sortable: this,
                      name: "update",
                      toEl: parentEl,
                      originalEvent: evt
                    });
                    _dispatchEvent({
                      sortable: this,
                      name: "sort",
                      toEl: parentEl,
                      originalEvent: evt
                    });
                  }
                }
              }
              if (Sortable2.active) {
                if (newIndex == null || newIndex === -1) {
                  newIndex = oldIndex;
                  newDraggableIndex = oldDraggableIndex;
                }
                _dispatchEvent({
                  sortable: this,
                  name: "end",
                  toEl: parentEl,
                  originalEvent: evt
                });
                this.save();
              }
            }
          }
          this._nulling();
        },
        _nulling: function _nulling() {
          pluginEvent("nulling", this);
          rootEl = dragEl = parentEl = ghostEl = nextEl = cloneEl = lastDownEl = cloneHidden = tapEvt = touchEvt = moved = newIndex = newDraggableIndex = oldIndex = oldDraggableIndex = lastTarget = lastDirection = putSortable = activeGroup = Sortable2.dragged = Sortable2.ghost = Sortable2.clone = Sortable2.active = null;
          savedInputChecked.forEach(function(el) {
            el.checked = true;
          });
          savedInputChecked.length = lastDx = lastDy = 0;
        },
        handleEvent: function handleEvent(evt) {
          switch (evt.type) {
            case "drop":
            case "dragend":
              this._onDrop(evt);
              break;
            case "dragenter":
            case "dragover":
              if (dragEl) {
                this._onDragOver(evt);
                _globalDragOver(evt);
              }
              break;
            case "selectstart":
              evt.preventDefault();
              break;
          }
        },
        /**
         * Serializes the item into an array of string.
         * @returns {String[]}
         */
        toArray: function toArray() {
          var order = [], el, children = this.el.children, i = 0, n = children.length, options = this.options;
          for (; i < n; i++) {
            el = children[i];
            if (closest(el, options.draggable, this.el, false)) {
              order.push(el.getAttribute(options.dataIdAttr) || _generateId(el));
            }
          }
          return order;
        },
        /**
         * Sorts the elements according to the array.
         * @param  {String[]}  order  order of the items
         */
        sort: function sort(order, useAnimation) {
          var items = {}, rootEl2 = this.el;
          this.toArray().forEach(function(id, i) {
            var el = rootEl2.children[i];
            if (closest(el, this.options.draggable, rootEl2, false)) {
              items[id] = el;
            }
          }, this);
          useAnimation && this.captureAnimationState();
          order.forEach(function(id) {
            if (items[id]) {
              rootEl2.removeChild(items[id]);
              rootEl2.appendChild(items[id]);
            }
          });
          useAnimation && this.animateAll();
        },
        /**
         * Save the current sorting
         */
        save: function save() {
          var store = this.options.store;
          store && store.set && store.set(this);
        },
        /**
         * For each element in the set, get the first element that matches the selector by testing the element itself and traversing up through its ancestors in the DOM tree.
         * @param   {HTMLElement}  el
         * @param   {String}       [selector]  default: `options.draggable`
         * @returns {HTMLElement|null}
         */
        closest: function closest$1(el, selector) {
          return closest(el, selector || this.options.draggable, this.el, false);
        },
        /**
         * Set/get option
         * @param   {string} name
         * @param   {*}      [value]
         * @returns {*}
         */
        option: function option(name, value) {
          var options = this.options;
          if (value === void 0) {
            return options[name];
          } else {
            var modifiedValue = PluginManager.modifyOption(this, name, value);
            if (typeof modifiedValue !== "undefined") {
              options[name] = modifiedValue;
            } else {
              options[name] = value;
            }
            if (name === "group") {
              _prepareGroup(options);
            }
          }
        },
        /**
         * Destroy
         */
        destroy: function destroy() {
          pluginEvent("destroy", this);
          var el = this.el;
          el[expando] = null;
          off(el, "mousedown", this._onTapStart);
          off(el, "touchstart", this._onTapStart);
          off(el, "pointerdown", this._onTapStart);
          if (this.nativeDraggable) {
            off(el, "dragover", this);
            off(el, "dragenter", this);
          }
          Array.prototype.forEach.call(el.querySelectorAll("[draggable]"), function(el2) {
            el2.removeAttribute("draggable");
          });
          this._onDrop();
          this._disableDelayedDragEvents();
          sortables.splice(sortables.indexOf(this.el), 1);
          this.el = el = null;
        },
        _hideClone: function _hideClone() {
          if (!cloneHidden) {
            pluginEvent("hideClone", this);
            if (Sortable2.eventCanceled) return;
            css(cloneEl, "display", "none");
            if (this.options.removeCloneOnHide && cloneEl.parentNode) {
              cloneEl.parentNode.removeChild(cloneEl);
            }
            cloneHidden = true;
          }
        },
        _showClone: function _showClone(putSortable2) {
          if (putSortable2.lastPutMode !== "clone") {
            this._hideClone();
            return;
          }
          if (cloneHidden) {
            pluginEvent("showClone", this);
            if (Sortable2.eventCanceled) return;
            if (dragEl.parentNode == rootEl && !this.options.group.revertClone) {
              rootEl.insertBefore(cloneEl, dragEl);
            } else if (nextEl) {
              rootEl.insertBefore(cloneEl, nextEl);
            } else {
              rootEl.appendChild(cloneEl);
            }
            if (this.options.group.revertClone) {
              this.animate(dragEl, cloneEl);
            }
            css(cloneEl, "display", "");
            cloneHidden = false;
          }
        }
      };
      function _globalDragOver(evt) {
        if (evt.dataTransfer) {
          evt.dataTransfer.dropEffect = "move";
        }
        evt.cancelable && evt.preventDefault();
      }
      function _onMove(fromEl, toEl, dragEl2, dragRect, targetEl, targetRect, originalEvent, willInsertAfter) {
        var evt, sortable = fromEl[expando], onMoveFn = sortable.options.onMove, retVal;
        if (window.CustomEvent && !IE11OrLess && !Edge) {
          evt = new CustomEvent("move", {
            bubbles: true,
            cancelable: true
          });
        } else {
          evt = document.createEvent("Event");
          evt.initEvent("move", true, true);
        }
        evt.to = toEl;
        evt.from = fromEl;
        evt.dragged = dragEl2;
        evt.draggedRect = dragRect;
        evt.related = targetEl || toEl;
        evt.relatedRect = targetRect || getRect(toEl);
        evt.willInsertAfter = willInsertAfter;
        evt.originalEvent = originalEvent;
        fromEl.dispatchEvent(evt);
        if (onMoveFn) {
          retVal = onMoveFn.call(sortable, evt, originalEvent);
        }
        return retVal;
      }
      function _disableDraggable(el) {
        el.draggable = false;
      }
      function _unsilent() {
        _silent = false;
      }
      function _ghostIsFirst(evt, vertical, sortable) {
        var firstElRect = getRect(getChild(sortable.el, 0, sortable.options, true));
        var childContainingRect = getChildContainingRectFromElement(sortable.el, sortable.options, ghostEl);
        var spacer = 10;
        return vertical ? evt.clientX < childContainingRect.left - spacer || evt.clientY < firstElRect.top && evt.clientX < firstElRect.right : evt.clientY < childContainingRect.top - spacer || evt.clientY < firstElRect.bottom && evt.clientX < firstElRect.left;
      }
      function _ghostIsLast(evt, vertical, sortable) {
        var lastElRect = getRect(lastChild(sortable.el, sortable.options.draggable));
        var childContainingRect = getChildContainingRectFromElement(sortable.el, sortable.options, ghostEl);
        var spacer = 10;
        return vertical ? evt.clientX > childContainingRect.right + spacer || evt.clientY > lastElRect.bottom && evt.clientX > lastElRect.left : evt.clientY > childContainingRect.bottom + spacer || evt.clientX > lastElRect.right && evt.clientY > lastElRect.top;
      }
      function _getSwapDirection(evt, target, targetRect, vertical, swapThreshold, invertedSwapThreshold, invertSwap, isLastTarget) {
        var mouseOnAxis = vertical ? evt.clientY : evt.clientX, targetLength = vertical ? targetRect.height : targetRect.width, targetS1 = vertical ? targetRect.top : targetRect.left, targetS2 = vertical ? targetRect.bottom : targetRect.right, invert = false;
        if (!invertSwap) {
          if (isLastTarget && targetMoveDistance < targetLength * swapThreshold) {
            if (!pastFirstInvertThresh && (lastDirection === 1 ? mouseOnAxis > targetS1 + targetLength * invertedSwapThreshold / 2 : mouseOnAxis < targetS2 - targetLength * invertedSwapThreshold / 2)) {
              pastFirstInvertThresh = true;
            }
            if (!pastFirstInvertThresh) {
              if (lastDirection === 1 ? mouseOnAxis < targetS1 + targetMoveDistance : mouseOnAxis > targetS2 - targetMoveDistance) {
                return -lastDirection;
              }
            } else {
              invert = true;
            }
          } else {
            if (mouseOnAxis > targetS1 + targetLength * (1 - swapThreshold) / 2 && mouseOnAxis < targetS2 - targetLength * (1 - swapThreshold) / 2) {
              return _getInsertDirection(target);
            }
          }
        }
        invert = invert || invertSwap;
        if (invert) {
          if (mouseOnAxis < targetS1 + targetLength * invertedSwapThreshold / 2 || mouseOnAxis > targetS2 - targetLength * invertedSwapThreshold / 2) {
            return mouseOnAxis > targetS1 + targetLength / 2 ? 1 : -1;
          }
        }
        return 0;
      }
      function _getInsertDirection(target) {
        if (index(dragEl) < index(target)) {
          return 1;
        } else {
          return -1;
        }
      }
      function _generateId(el) {
        var str = el.tagName + el.className + el.src + el.href + el.textContent, i = str.length, sum = 0;
        while (i--) {
          sum += str.charCodeAt(i);
        }
        return sum.toString(36);
      }
      function _saveInputCheckedState(root) {
        savedInputChecked.length = 0;
        var inputs = root.getElementsByTagName("input");
        var idx = inputs.length;
        while (idx--) {
          var el = inputs[idx];
          el.checked && savedInputChecked.push(el);
        }
      }
      function _nextTick(fn) {
        return setTimeout(fn, 0);
      }
      function _cancelNextTick(id) {
        return clearTimeout(id);
      }
      if (documentExists) {
        on(document, "touchmove", function(evt) {
          if ((Sortable2.active || awaitingDragStarted) && evt.cancelable) {
            evt.preventDefault();
          }
        });
      }
      Sortable2.utils = {
        on,
        off,
        css,
        find,
        is: function is(el, selector) {
          return !!closest(el, selector, el, false);
        },
        extend,
        throttle,
        closest,
        toggleClass,
        clone,
        index,
        nextTick: _nextTick,
        cancelNextTick: _cancelNextTick,
        detectDirection: _detectDirection,
        getChild,
        expando
      };
      Sortable2.get = function(element) {
        return element[expando];
      };
      Sortable2.mount = function() {
        for (var _len = arguments.length, plugins2 = new Array(_len), _key = 0; _key < _len; _key++) {
          plugins2[_key] = arguments[_key];
        }
        if (plugins2[0].constructor === Array) plugins2 = plugins2[0];
        plugins2.forEach(function(plugin) {
          if (!plugin.prototype || !plugin.prototype.constructor) {
            throw "Sortable: Mounted plugin must be a constructor function, not ".concat({}.toString.call(plugin));
          }
          if (plugin.utils) Sortable2.utils = _objectSpread2(_objectSpread2({}, Sortable2.utils), plugin.utils);
          PluginManager.mount(plugin);
        });
      };
      Sortable2.create = function(el, options) {
        return new Sortable2(el, options);
      };
      Sortable2.version = version;
      var autoScrolls = [], scrollEl, scrollRootEl, scrolling = false, lastAutoScrollX, lastAutoScrollY, touchEvt$1, pointerElemChangedInterval;
      function AutoScrollPlugin() {
        function AutoScroll() {
          this.defaults = {
            scroll: true,
            forceAutoScrollFallback: false,
            scrollSensitivity: 30,
            scrollSpeed: 10,
            bubbleScroll: true
          };
          for (var fn in this) {
            if (fn.charAt(0) === "_" && typeof this[fn] === "function") {
              this[fn] = this[fn].bind(this);
            }
          }
        }
        AutoScroll.prototype = {
          dragStarted: function dragStarted2(_ref) {
            var originalEvent = _ref.originalEvent;
            if (this.sortable.nativeDraggable) {
              on(document, "dragover", this._handleAutoScroll);
            } else {
              if (this.options.supportPointer) {
                on(document, "pointermove", this._handleFallbackAutoScroll);
              } else if (originalEvent.touches) {
                on(document, "touchmove", this._handleFallbackAutoScroll);
              } else {
                on(document, "mousemove", this._handleFallbackAutoScroll);
              }
            }
          },
          dragOverCompleted: function dragOverCompleted(_ref2) {
            var originalEvent = _ref2.originalEvent;
            if (!this.options.dragOverBubble && !originalEvent.rootEl) {
              this._handleAutoScroll(originalEvent);
            }
          },
          drop: function drop2() {
            if (this.sortable.nativeDraggable) {
              off(document, "dragover", this._handleAutoScroll);
            } else {
              off(document, "pointermove", this._handleFallbackAutoScroll);
              off(document, "touchmove", this._handleFallbackAutoScroll);
              off(document, "mousemove", this._handleFallbackAutoScroll);
            }
            clearPointerElemChangedInterval();
            clearAutoScrolls();
            cancelThrottle();
          },
          nulling: function nulling() {
            touchEvt$1 = scrollRootEl = scrollEl = scrolling = pointerElemChangedInterval = lastAutoScrollX = lastAutoScrollY = null;
            autoScrolls.length = 0;
          },
          _handleFallbackAutoScroll: function _handleFallbackAutoScroll(evt) {
            this._handleAutoScroll(evt, true);
          },
          _handleAutoScroll: function _handleAutoScroll(evt, fallback) {
            var _this = this;
            var x = (evt.touches ? evt.touches[0] : evt).clientX, y = (evt.touches ? evt.touches[0] : evt).clientY, elem2 = document.elementFromPoint(x, y);
            touchEvt$1 = evt;
            if (fallback || this.options.forceAutoScrollFallback || Edge || IE11OrLess || Safari) {
              autoScroll(evt, this.options, elem2, fallback);
              var ogElemScroller = getParentAutoScrollElement(elem2, true);
              if (scrolling && (!pointerElemChangedInterval || x !== lastAutoScrollX || y !== lastAutoScrollY)) {
                pointerElemChangedInterval && clearPointerElemChangedInterval();
                pointerElemChangedInterval = setInterval(function() {
                  var newElem = getParentAutoScrollElement(document.elementFromPoint(x, y), true);
                  if (newElem !== ogElemScroller) {
                    ogElemScroller = newElem;
                    clearAutoScrolls();
                  }
                  autoScroll(evt, _this.options, newElem, fallback);
                }, 10);
                lastAutoScrollX = x;
                lastAutoScrollY = y;
              }
            } else {
              if (!this.options.bubbleScroll || getParentAutoScrollElement(elem2, true) === getWindowScrollingElement()) {
                clearAutoScrolls();
                return;
              }
              autoScroll(evt, this.options, getParentAutoScrollElement(elem2, false), false);
            }
          }
        };
        return _extends(AutoScroll, {
          pluginName: "scroll",
          initializeByDefault: true
        });
      }
      function clearAutoScrolls() {
        autoScrolls.forEach(function(autoScroll2) {
          clearInterval(autoScroll2.pid);
        });
        autoScrolls = [];
      }
      function clearPointerElemChangedInterval() {
        clearInterval(pointerElemChangedInterval);
      }
      var autoScroll = throttle(function(evt, options, rootEl2, isFallback) {
        if (!options.scroll) return;
        var x = (evt.touches ? evt.touches[0] : evt).clientX, y = (evt.touches ? evt.touches[0] : evt).clientY, sens = options.scrollSensitivity, speed = options.scrollSpeed, winScroller = getWindowScrollingElement();
        var scrollThisInstance = false, scrollCustomFn;
        if (scrollRootEl !== rootEl2) {
          scrollRootEl = rootEl2;
          clearAutoScrolls();
          scrollEl = options.scroll;
          scrollCustomFn = options.scrollFn;
          if (scrollEl === true) {
            scrollEl = getParentAutoScrollElement(rootEl2, true);
          }
        }
        var layersOut = 0;
        var currentParent = scrollEl;
        do {
          var el = currentParent, rect = getRect(el), top = rect.top, bottom = rect.bottom, left = rect.left, right = rect.right, width = rect.width, height = rect.height, canScrollX = void 0, canScrollY = void 0, scrollWidth = el.scrollWidth, scrollHeight = el.scrollHeight, elCSS = css(el), scrollPosX = el.scrollLeft, scrollPosY = el.scrollTop;
          if (el === winScroller) {
            canScrollX = width < scrollWidth && (elCSS.overflowX === "auto" || elCSS.overflowX === "scroll" || elCSS.overflowX === "visible");
            canScrollY = height < scrollHeight && (elCSS.overflowY === "auto" || elCSS.overflowY === "scroll" || elCSS.overflowY === "visible");
          } else {
            canScrollX = width < scrollWidth && (elCSS.overflowX === "auto" || elCSS.overflowX === "scroll");
            canScrollY = height < scrollHeight && (elCSS.overflowY === "auto" || elCSS.overflowY === "scroll");
          }
          var vx = canScrollX && (Math.abs(right - x) <= sens && scrollPosX + width < scrollWidth) - (Math.abs(left - x) <= sens && !!scrollPosX);
          var vy = canScrollY && (Math.abs(bottom - y) <= sens && scrollPosY + height < scrollHeight) - (Math.abs(top - y) <= sens && !!scrollPosY);
          if (!autoScrolls[layersOut]) {
            for (var i = 0; i <= layersOut; i++) {
              if (!autoScrolls[i]) {
                autoScrolls[i] = {};
              }
            }
          }
          if (autoScrolls[layersOut].vx != vx || autoScrolls[layersOut].vy != vy || autoScrolls[layersOut].el !== el) {
            autoScrolls[layersOut].el = el;
            autoScrolls[layersOut].vx = vx;
            autoScrolls[layersOut].vy = vy;
            clearInterval(autoScrolls[layersOut].pid);
            if (vx != 0 || vy != 0) {
              scrollThisInstance = true;
              autoScrolls[layersOut].pid = setInterval((function() {
                if (isFallback && this.layer === 0) {
                  Sortable2.active._onTouchMove(touchEvt$1);
                }
                var scrollOffsetY = autoScrolls[this.layer].vy ? autoScrolls[this.layer].vy * speed : 0;
                var scrollOffsetX = autoScrolls[this.layer].vx ? autoScrolls[this.layer].vx * speed : 0;
                if (typeof scrollCustomFn === "function") {
                  if (scrollCustomFn.call(Sortable2.dragged.parentNode[expando], scrollOffsetX, scrollOffsetY, evt, touchEvt$1, autoScrolls[this.layer].el) !== "continue") {
                    return;
                  }
                }
                scrollBy(autoScrolls[this.layer].el, scrollOffsetX, scrollOffsetY);
              }).bind({
                layer: layersOut
              }), 24);
            }
          }
          layersOut++;
        } while (options.bubbleScroll && currentParent !== winScroller && (currentParent = getParentAutoScrollElement(currentParent, false)));
        scrolling = scrollThisInstance;
      }, 30);
      var drop = function drop2(_ref) {
        var originalEvent = _ref.originalEvent, putSortable2 = _ref.putSortable, dragEl2 = _ref.dragEl, activeSortable = _ref.activeSortable, dispatchSortableEvent = _ref.dispatchSortableEvent, hideGhostForTarget = _ref.hideGhostForTarget, unhideGhostForTarget = _ref.unhideGhostForTarget;
        if (!originalEvent) return;
        var toSortable = putSortable2 || activeSortable;
        hideGhostForTarget();
        var touch = originalEvent.changedTouches && originalEvent.changedTouches.length ? originalEvent.changedTouches[0] : originalEvent;
        var target = document.elementFromPoint(touch.clientX, touch.clientY);
        unhideGhostForTarget();
        if (toSortable && !toSortable.el.contains(target)) {
          dispatchSortableEvent("spill");
          this.onSpill({
            dragEl: dragEl2,
            putSortable: putSortable2
          });
        }
      };
      function Revert() {
      }
      Revert.prototype = {
        startIndex: null,
        dragStart: function dragStart(_ref2) {
          var oldDraggableIndex2 = _ref2.oldDraggableIndex;
          this.startIndex = oldDraggableIndex2;
        },
        onSpill: function onSpill(_ref3) {
          var dragEl2 = _ref3.dragEl, putSortable2 = _ref3.putSortable;
          this.sortable.captureAnimationState();
          if (putSortable2) {
            putSortable2.captureAnimationState();
          }
          var nextSibling = getChild(this.sortable.el, this.startIndex, this.options);
          if (nextSibling) {
            this.sortable.el.insertBefore(dragEl2, nextSibling);
          } else {
            this.sortable.el.appendChild(dragEl2);
          }
          this.sortable.animateAll();
          if (putSortable2) {
            putSortable2.animateAll();
          }
        },
        drop
      };
      _extends(Revert, {
        pluginName: "revertOnSpill"
      });
      function Remove() {
      }
      Remove.prototype = {
        onSpill: function onSpill(_ref4) {
          var dragEl2 = _ref4.dragEl, putSortable2 = _ref4.putSortable;
          var parentSortable = putSortable2 || this.sortable;
          parentSortable.captureAnimationState();
          dragEl2.parentNode && dragEl2.parentNode.removeChild(dragEl2);
          parentSortable.animateAll();
        },
        drop
      };
      _extends(Remove, {
        pluginName: "removeOnSpill"
      });
      var lastSwapEl;
      function SwapPlugin() {
        function Swap() {
          this.defaults = {
            swapClass: "sortable-swap-highlight"
          };
        }
        Swap.prototype = {
          dragStart: function dragStart(_ref) {
            var dragEl2 = _ref.dragEl;
            lastSwapEl = dragEl2;
          },
          dragOverValid: function dragOverValid(_ref2) {
            var completed = _ref2.completed, target = _ref2.target, onMove = _ref2.onMove, activeSortable = _ref2.activeSortable, changed = _ref2.changed, cancel = _ref2.cancel;
            if (!activeSortable.options.swap) return;
            var el = this.sortable.el, options = this.options;
            if (target && target !== el) {
              var prevSwapEl = lastSwapEl;
              if (onMove(target) !== false) {
                toggleClass(target, options.swapClass, true);
                lastSwapEl = target;
              } else {
                lastSwapEl = null;
              }
              if (prevSwapEl && prevSwapEl !== lastSwapEl) {
                toggleClass(prevSwapEl, options.swapClass, false);
              }
            }
            changed();
            completed(true);
            cancel();
          },
          drop: function drop2(_ref3) {
            var activeSortable = _ref3.activeSortable, putSortable2 = _ref3.putSortable, dragEl2 = _ref3.dragEl;
            var toSortable = putSortable2 || this.sortable;
            var options = this.options;
            lastSwapEl && toggleClass(lastSwapEl, options.swapClass, false);
            if (lastSwapEl && (options.swap || putSortable2 && putSortable2.options.swap)) {
              if (dragEl2 !== lastSwapEl) {
                toSortable.captureAnimationState();
                if (toSortable !== activeSortable) activeSortable.captureAnimationState();
                swapNodes(dragEl2, lastSwapEl);
                toSortable.animateAll();
                if (toSortable !== activeSortable) activeSortable.animateAll();
              }
            }
          },
          nulling: function nulling() {
            lastSwapEl = null;
          }
        };
        return _extends(Swap, {
          pluginName: "swap",
          eventProperties: function eventProperties() {
            return {
              swapItem: lastSwapEl
            };
          }
        });
      }
      function swapNodes(n1, n2) {
        var p1 = n1.parentNode, p2 = n2.parentNode, i1, i2;
        if (!p1 || !p2 || p1.isEqualNode(n2) || p2.isEqualNode(n1)) return;
        i1 = index(n1);
        i2 = index(n2);
        if (p1.isEqualNode(p2) && i1 < i2) {
          i2++;
        }
        p1.insertBefore(n2, p1.children[i1]);
        p2.insertBefore(n1, p2.children[i2]);
      }
      var multiDragElements = [], multiDragClones = [], lastMultiDragSelect, multiDragSortable, initialFolding = false, folding = false, dragStarted = false, dragEl$1, clonesFromRect, clonesHidden;
      function MultiDragPlugin() {
        function MultiDrag(sortable) {
          for (var fn in this) {
            if (fn.charAt(0) === "_" && typeof this[fn] === "function") {
              this[fn] = this[fn].bind(this);
            }
          }
          if (!sortable.options.avoidImplicitDeselect) {
            if (sortable.options.supportPointer) {
              on(document, "pointerup", this._deselectMultiDrag);
            } else {
              on(document, "mouseup", this._deselectMultiDrag);
              on(document, "touchend", this._deselectMultiDrag);
            }
          }
          on(document, "keydown", this._checkKeyDown);
          on(document, "keyup", this._checkKeyUp);
          this.defaults = {
            selectedClass: "sortable-selected",
            multiDragKey: null,
            avoidImplicitDeselect: false,
            setData: function setData(dataTransfer, dragEl2) {
              var data = "";
              if (multiDragElements.length && multiDragSortable === sortable) {
                multiDragElements.forEach(function(multiDragElement, i) {
                  data += (!i ? "" : ", ") + multiDragElement.textContent;
                });
              } else {
                data = dragEl2.textContent;
              }
              dataTransfer.setData("Text", data);
            }
          };
        }
        MultiDrag.prototype = {
          multiDragKeyDown: false,
          isMultiDrag: false,
          delayStartGlobal: function delayStartGlobal(_ref) {
            var dragged = _ref.dragEl;
            dragEl$1 = dragged;
          },
          delayEnded: function delayEnded() {
            this.isMultiDrag = ~multiDragElements.indexOf(dragEl$1);
          },
          setupClone: function setupClone(_ref2) {
            var sortable = _ref2.sortable, cancel = _ref2.cancel;
            if (!this.isMultiDrag) return;
            for (var i = 0; i < multiDragElements.length; i++) {
              multiDragClones.push(clone(multiDragElements[i]));
              multiDragClones[i].sortableIndex = multiDragElements[i].sortableIndex;
              multiDragClones[i].draggable = false;
              multiDragClones[i].style["will-change"] = "";
              toggleClass(multiDragClones[i], this.options.selectedClass, false);
              multiDragElements[i] === dragEl$1 && toggleClass(multiDragClones[i], this.options.chosenClass, false);
            }
            sortable._hideClone();
            cancel();
          },
          clone: function clone2(_ref3) {
            var sortable = _ref3.sortable, rootEl2 = _ref3.rootEl, dispatchSortableEvent = _ref3.dispatchSortableEvent, cancel = _ref3.cancel;
            if (!this.isMultiDrag) return;
            if (!this.options.removeCloneOnHide) {
              if (multiDragElements.length && multiDragSortable === sortable) {
                insertMultiDragClones(true, rootEl2);
                dispatchSortableEvent("clone");
                cancel();
              }
            }
          },
          showClone: function showClone(_ref4) {
            var cloneNowShown = _ref4.cloneNowShown, rootEl2 = _ref4.rootEl, cancel = _ref4.cancel;
            if (!this.isMultiDrag) return;
            insertMultiDragClones(false, rootEl2);
            multiDragClones.forEach(function(clone2) {
              css(clone2, "display", "");
            });
            cloneNowShown();
            clonesHidden = false;
            cancel();
          },
          hideClone: function hideClone(_ref5) {
            var _this = this;
            _ref5.sortable;
            var cloneNowHidden = _ref5.cloneNowHidden, cancel = _ref5.cancel;
            if (!this.isMultiDrag) return;
            multiDragClones.forEach(function(clone2) {
              css(clone2, "display", "none");
              if (_this.options.removeCloneOnHide && clone2.parentNode) {
                clone2.parentNode.removeChild(clone2);
              }
            });
            cloneNowHidden();
            clonesHidden = true;
            cancel();
          },
          dragStartGlobal: function dragStartGlobal(_ref6) {
            _ref6.sortable;
            if (!this.isMultiDrag && multiDragSortable) {
              multiDragSortable.multiDrag._deselectMultiDrag();
            }
            multiDragElements.forEach(function(multiDragElement) {
              multiDragElement.sortableIndex = index(multiDragElement);
            });
            multiDragElements = multiDragElements.sort(function(a, b) {
              return a.sortableIndex - b.sortableIndex;
            });
            dragStarted = true;
          },
          dragStarted: function dragStarted2(_ref7) {
            var _this2 = this;
            var sortable = _ref7.sortable;
            if (!this.isMultiDrag) return;
            if (this.options.sort) {
              sortable.captureAnimationState();
              if (this.options.animation) {
                multiDragElements.forEach(function(multiDragElement) {
                  if (multiDragElement === dragEl$1) return;
                  css(multiDragElement, "position", "absolute");
                });
                var dragRect = getRect(dragEl$1, false, true, true);
                multiDragElements.forEach(function(multiDragElement) {
                  if (multiDragElement === dragEl$1) return;
                  setRect(multiDragElement, dragRect);
                });
                folding = true;
                initialFolding = true;
              }
            }
            sortable.animateAll(function() {
              folding = false;
              initialFolding = false;
              if (_this2.options.animation) {
                multiDragElements.forEach(function(multiDragElement) {
                  unsetRect(multiDragElement);
                });
              }
              if (_this2.options.sort) {
                removeMultiDragElements();
              }
            });
          },
          dragOver: function dragOver(_ref8) {
            var target = _ref8.target, completed = _ref8.completed, cancel = _ref8.cancel;
            if (folding && ~multiDragElements.indexOf(target)) {
              completed(false);
              cancel();
            }
          },
          revert: function revert(_ref9) {
            var fromSortable = _ref9.fromSortable, rootEl2 = _ref9.rootEl, sortable = _ref9.sortable, dragRect = _ref9.dragRect;
            if (multiDragElements.length > 1) {
              multiDragElements.forEach(function(multiDragElement) {
                sortable.addAnimationState({
                  target: multiDragElement,
                  rect: folding ? getRect(multiDragElement) : dragRect
                });
                unsetRect(multiDragElement);
                multiDragElement.fromRect = dragRect;
                fromSortable.removeAnimationState(multiDragElement);
              });
              folding = false;
              insertMultiDragElements(!this.options.removeCloneOnHide, rootEl2);
            }
          },
          dragOverCompleted: function dragOverCompleted(_ref10) {
            var sortable = _ref10.sortable, isOwner = _ref10.isOwner, insertion = _ref10.insertion, activeSortable = _ref10.activeSortable, parentEl2 = _ref10.parentEl, putSortable2 = _ref10.putSortable;
            var options = this.options;
            if (insertion) {
              if (isOwner) {
                activeSortable._hideClone();
              }
              initialFolding = false;
              if (options.animation && multiDragElements.length > 1 && (folding || !isOwner && !activeSortable.options.sort && !putSortable2)) {
                var dragRectAbsolute = getRect(dragEl$1, false, true, true);
                multiDragElements.forEach(function(multiDragElement) {
                  if (multiDragElement === dragEl$1) return;
                  setRect(multiDragElement, dragRectAbsolute);
                  parentEl2.appendChild(multiDragElement);
                });
                folding = true;
              }
              if (!isOwner) {
                if (!folding) {
                  removeMultiDragElements();
                }
                if (multiDragElements.length > 1) {
                  var clonesHiddenBefore = clonesHidden;
                  activeSortable._showClone(sortable);
                  if (activeSortable.options.animation && !clonesHidden && clonesHiddenBefore) {
                    multiDragClones.forEach(function(clone2) {
                      activeSortable.addAnimationState({
                        target: clone2,
                        rect: clonesFromRect
                      });
                      clone2.fromRect = clonesFromRect;
                      clone2.thisAnimationDuration = null;
                    });
                  }
                } else {
                  activeSortable._showClone(sortable);
                }
              }
            }
          },
          dragOverAnimationCapture: function dragOverAnimationCapture(_ref11) {
            var dragRect = _ref11.dragRect, isOwner = _ref11.isOwner, activeSortable = _ref11.activeSortable;
            multiDragElements.forEach(function(multiDragElement) {
              multiDragElement.thisAnimationDuration = null;
            });
            if (activeSortable.options.animation && !isOwner && activeSortable.multiDrag.isMultiDrag) {
              clonesFromRect = _extends({}, dragRect);
              var dragMatrix = matrix(dragEl$1, true);
              clonesFromRect.top -= dragMatrix.f;
              clonesFromRect.left -= dragMatrix.e;
            }
          },
          dragOverAnimationComplete: function dragOverAnimationComplete() {
            if (folding) {
              folding = false;
              removeMultiDragElements();
            }
          },
          drop: function drop2(_ref12) {
            var evt = _ref12.originalEvent, rootEl2 = _ref12.rootEl, parentEl2 = _ref12.parentEl, sortable = _ref12.sortable, dispatchSortableEvent = _ref12.dispatchSortableEvent, oldIndex2 = _ref12.oldIndex, putSortable2 = _ref12.putSortable;
            var toSortable = putSortable2 || this.sortable;
            if (!evt) return;
            var options = this.options, children = parentEl2.children;
            if (!dragStarted) {
              if (options.multiDragKey && !this.multiDragKeyDown) {
                this._deselectMultiDrag();
              }
              toggleClass(dragEl$1, options.selectedClass, !~multiDragElements.indexOf(dragEl$1));
              if (!~multiDragElements.indexOf(dragEl$1)) {
                multiDragElements.push(dragEl$1);
                dispatchEvent({
                  sortable,
                  rootEl: rootEl2,
                  name: "select",
                  targetEl: dragEl$1,
                  originalEvent: evt
                });
                if (evt.shiftKey && lastMultiDragSelect && sortable.el.contains(lastMultiDragSelect)) {
                  var lastIndex = index(lastMultiDragSelect), currentIndex = index(dragEl$1);
                  if (~lastIndex && ~currentIndex && lastIndex !== currentIndex) {
                    (function() {
                      var n, i;
                      if (currentIndex > lastIndex) {
                        i = lastIndex;
                        n = currentIndex;
                      } else {
                        i = currentIndex;
                        n = lastIndex + 1;
                      }
                      var filter = options.filter;
                      for (; i < n; i++) {
                        if (~multiDragElements.indexOf(children[i])) continue;
                        if (!closest(children[i], options.draggable, parentEl2, false)) continue;
                        var filtered = filter && (typeof filter === "function" ? filter.call(sortable, evt, children[i], sortable) : filter.split(",").some(function(criteria) {
                          return closest(children[i], criteria.trim(), parentEl2, false);
                        }));
                        if (filtered) continue;
                        toggleClass(children[i], options.selectedClass, true);
                        multiDragElements.push(children[i]);
                        dispatchEvent({
                          sortable,
                          rootEl: rootEl2,
                          name: "select",
                          targetEl: children[i],
                          originalEvent: evt
                        });
                      }
                    })();
                  }
                } else {
                  lastMultiDragSelect = dragEl$1;
                }
                multiDragSortable = toSortable;
              } else {
                multiDragElements.splice(multiDragElements.indexOf(dragEl$1), 1);
                lastMultiDragSelect = null;
                dispatchEvent({
                  sortable,
                  rootEl: rootEl2,
                  name: "deselect",
                  targetEl: dragEl$1,
                  originalEvent: evt
                });
              }
            }
            if (dragStarted && this.isMultiDrag) {
              folding = false;
              if ((parentEl2[expando].options.sort || parentEl2 !== rootEl2) && multiDragElements.length > 1) {
                var dragRect = getRect(dragEl$1), multiDragIndex = index(dragEl$1, ":not(." + this.options.selectedClass + ")");
                if (!initialFolding && options.animation) dragEl$1.thisAnimationDuration = null;
                toSortable.captureAnimationState();
                if (!initialFolding) {
                  if (options.animation) {
                    dragEl$1.fromRect = dragRect;
                    multiDragElements.forEach(function(multiDragElement) {
                      multiDragElement.thisAnimationDuration = null;
                      if (multiDragElement !== dragEl$1) {
                        var rect = folding ? getRect(multiDragElement) : dragRect;
                        multiDragElement.fromRect = rect;
                        toSortable.addAnimationState({
                          target: multiDragElement,
                          rect
                        });
                      }
                    });
                  }
                  removeMultiDragElements();
                  multiDragElements.forEach(function(multiDragElement) {
                    if (children[multiDragIndex]) {
                      parentEl2.insertBefore(multiDragElement, children[multiDragIndex]);
                    } else {
                      parentEl2.appendChild(multiDragElement);
                    }
                    multiDragIndex++;
                  });
                  if (oldIndex2 === index(dragEl$1)) {
                    var update = false;
                    multiDragElements.forEach(function(multiDragElement) {
                      if (multiDragElement.sortableIndex !== index(multiDragElement)) {
                        update = true;
                        return;
                      }
                    });
                    if (update) {
                      dispatchSortableEvent("update");
                      dispatchSortableEvent("sort");
                    }
                  }
                }
                multiDragElements.forEach(function(multiDragElement) {
                  unsetRect(multiDragElement);
                });
                toSortable.animateAll();
              }
              multiDragSortable = toSortable;
            }
            if (rootEl2 === parentEl2 || putSortable2 && putSortable2.lastPutMode !== "clone") {
              multiDragClones.forEach(function(clone2) {
                clone2.parentNode && clone2.parentNode.removeChild(clone2);
              });
            }
          },
          nullingGlobal: function nullingGlobal() {
            this.isMultiDrag = dragStarted = false;
            multiDragClones.length = 0;
          },
          destroyGlobal: function destroyGlobal() {
            this._deselectMultiDrag();
            off(document, "pointerup", this._deselectMultiDrag);
            off(document, "mouseup", this._deselectMultiDrag);
            off(document, "touchend", this._deselectMultiDrag);
            off(document, "keydown", this._checkKeyDown);
            off(document, "keyup", this._checkKeyUp);
          },
          _deselectMultiDrag: function _deselectMultiDrag(evt) {
            if (typeof dragStarted !== "undefined" && dragStarted) return;
            if (multiDragSortable !== this.sortable) return;
            if (evt && closest(evt.target, this.options.draggable, this.sortable.el, false)) return;
            if (evt && evt.button !== 0) return;
            while (multiDragElements.length) {
              var el = multiDragElements[0];
              toggleClass(el, this.options.selectedClass, false);
              multiDragElements.shift();
              dispatchEvent({
                sortable: this.sortable,
                rootEl: this.sortable.el,
                name: "deselect",
                targetEl: el,
                originalEvent: evt
              });
            }
          },
          _checkKeyDown: function _checkKeyDown(evt) {
            if (evt.key === this.options.multiDragKey) {
              this.multiDragKeyDown = true;
            }
          },
          _checkKeyUp: function _checkKeyUp(evt) {
            if (evt.key === this.options.multiDragKey) {
              this.multiDragKeyDown = false;
            }
          }
        };
        return _extends(MultiDrag, {
          // Static methods & properties
          pluginName: "multiDrag",
          utils: {
            /**
             * Selects the provided multi-drag item
             * @param  {HTMLElement} el    The element to be selected
             */
            select: function select(el) {
              var sortable = el.parentNode[expando];
              if (!sortable || !sortable.options.multiDrag || ~multiDragElements.indexOf(el)) return;
              if (multiDragSortable && multiDragSortable !== sortable) {
                multiDragSortable.multiDrag._deselectMultiDrag();
                multiDragSortable = sortable;
              }
              toggleClass(el, sortable.options.selectedClass, true);
              multiDragElements.push(el);
            },
            /**
             * Deselects the provided multi-drag item
             * @param  {HTMLElement} el    The element to be deselected
             */
            deselect: function deselect(el) {
              var sortable = el.parentNode[expando], index2 = multiDragElements.indexOf(el);
              if (!sortable || !sortable.options.multiDrag || !~index2) return;
              toggleClass(el, sortable.options.selectedClass, false);
              multiDragElements.splice(index2, 1);
            }
          },
          eventProperties: function eventProperties() {
            var _this3 = this;
            var oldIndicies = [], newIndicies = [];
            multiDragElements.forEach(function(multiDragElement) {
              oldIndicies.push({
                multiDragElement,
                index: multiDragElement.sortableIndex
              });
              var newIndex2;
              if (folding && multiDragElement !== dragEl$1) {
                newIndex2 = -1;
              } else if (folding) {
                newIndex2 = index(multiDragElement, ":not(." + _this3.options.selectedClass + ")");
              } else {
                newIndex2 = index(multiDragElement);
              }
              newIndicies.push({
                multiDragElement,
                index: newIndex2
              });
            });
            return {
              items: _toConsumableArray(multiDragElements),
              clones: [].concat(multiDragClones),
              oldIndicies,
              newIndicies
            };
          },
          optionListeners: {
            multiDragKey: function multiDragKey(key) {
              key = key.toLowerCase();
              if (key === "ctrl") {
                key = "Control";
              } else if (key.length > 1) {
                key = key.charAt(0).toUpperCase() + key.substr(1);
              }
              return key;
            }
          }
        });
      }
      function insertMultiDragElements(clonesInserted, rootEl2) {
        multiDragElements.forEach(function(multiDragElement, i) {
          var target = rootEl2.children[multiDragElement.sortableIndex + (clonesInserted ? Number(i) : 0)];
          if (target) {
            rootEl2.insertBefore(multiDragElement, target);
          } else {
            rootEl2.appendChild(multiDragElement);
          }
        });
      }
      function insertMultiDragClones(elementsInserted, rootEl2) {
        multiDragClones.forEach(function(clone2, i) {
          var target = rootEl2.children[clone2.sortableIndex + (elementsInserted ? Number(i) : 0)];
          if (target) {
            rootEl2.insertBefore(clone2, target);
          } else {
            rootEl2.appendChild(clone2);
          }
        });
      }
      function removeMultiDragElements() {
        multiDragElements.forEach(function(multiDragElement) {
          if (multiDragElement === dragEl$1) return;
          multiDragElement.parentNode && multiDragElement.parentNode.removeChild(multiDragElement);
        });
      }
      Sortable2.mount(new AutoScrollPlugin());
      Sortable2.mount(Remove, Revert);
      Sortable2.mount(new SwapPlugin());
      Sortable2.mount(new MultiDragPlugin());
      return Sortable2;
    }));
  })(Sortable$3);
  return Sortable$3.exports;
}
var SortableExports = requireSortable();
const Sortable$1 = /* @__PURE__ */ getDefaultExportFromCjs(SortableExports);
var typeahead_jquery$1 = { exports: {} };
/*!
 * typeahead.js 1.3.3
 * https://github.com/corejavascript/typeahead.js
 * Copyright 2013-2024 Twitter, Inc. and other contributors; Licensed MIT
 */
var typeahead_jquery = typeahead_jquery$1.exports;
var hasRequiredTypeahead_jquery;
function requireTypeahead_jquery() {
  if (hasRequiredTypeahead_jquery) return typeahead_jquery$1.exports;
  hasRequiredTypeahead_jquery = 1;
  (function(module) {
    (function(root, factory) {
      if (module.exports) {
        module.exports = factory(requireJquery());
      } else {
        factory(root["jQuery"]);
      }
    })(typeahead_jquery, function($2) {
      var _ = (function() {
        return {
          isMsie: function() {
            return /(msie|trident)/i.test(navigator.userAgent) ? navigator.userAgent.match(/(msie |rv:)(\d+(.\d+)?)/i)[2] : false;
          },
          isBlankString: function(str) {
            return !str || /^\s*$/.test(str);
          },
          escapeRegExChars: function(str) {
            return str.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
          },
          isString: function(obj) {
            return typeof obj === "string";
          },
          isNumber: function(obj) {
            return typeof obj === "number";
          },
          isArray: $2.isArray,
          isFunction: $2.isFunction,
          isObject: $2.isPlainObject,
          isUndefined: function(obj) {
            return typeof obj === "undefined";
          },
          isElement: function(obj) {
            return !!(obj && obj.nodeType === 1);
          },
          isJQuery: function(obj) {
            return obj instanceof $2;
          },
          toStr: function toStr(s) {
            return _.isUndefined(s) || s === null ? "" : s + "";
          },
          bind: $2.proxy,
          each: function(collection, cb) {
            $2.each(collection, reverseArgs);
            function reverseArgs(index, value) {
              return cb(value, index);
            }
          },
          map: $2.map,
          filter: $2.grep,
          every: function(obj, test) {
            var result = true;
            if (!obj) {
              return result;
            }
            $2.each(obj, function(key, val) {
              if (!(result = test.call(null, val, key, obj))) {
                return false;
              }
            });
            return !!result;
          },
          some: function(obj, test) {
            var result = false;
            if (!obj) {
              return result;
            }
            $2.each(obj, function(key, val) {
              if (result = test.call(null, val, key, obj)) {
                return false;
              }
            });
            return !!result;
          },
          mixin: $2.extend,
          identity: function(x) {
            return x;
          },
          clone: function(obj) {
            return $2.extend(true, {}, obj);
          },
          getIdGenerator: function() {
            var counter = 0;
            return function() {
              return counter++;
            };
          },
          templatify: function templatify(obj) {
            return $2.isFunction(obj) ? obj : template;
            function template() {
              return String(obj);
            }
          },
          defer: function(fn) {
            setTimeout(fn, 0);
          },
          debounce: function(func, wait, immediate) {
            var timeout, result;
            return function() {
              var context = this, args = arguments, later, callNow;
              later = function() {
                timeout = null;
                if (!immediate) {
                  result = func.apply(context, args);
                }
              };
              callNow = immediate && !timeout;
              clearTimeout(timeout);
              timeout = setTimeout(later, wait);
              if (callNow) {
                result = func.apply(context, args);
              }
              return result;
            };
          },
          throttle: function(func, wait) {
            var context, args, timeout, result, previous, later;
            previous = 0;
            later = function() {
              previous = /* @__PURE__ */ new Date();
              timeout = null;
              result = func.apply(context, args);
            };
            return function() {
              var now = /* @__PURE__ */ new Date(), remaining = wait - (now - previous);
              context = this;
              args = arguments;
              if (remaining <= 0) {
                clearTimeout(timeout);
                timeout = null;
                previous = now;
                result = func.apply(context, args);
              } else if (!timeout) {
                timeout = setTimeout(later, remaining);
              }
              return result;
            };
          },
          stringify: function(val) {
            return _.isString(val) ? val : JSON.stringify(val);
          },
          guid: function() {
            function _p8(s) {
              var p = (Math.random().toString(16) + "000000000").substr(2, 8);
              return s ? "-" + p.substr(0, 4) + "-" + p.substr(4, 4) : p;
            }
            return "tt-" + _p8() + _p8(true) + _p8(true) + _p8();
          },
          noop: function() {
          }
        };
      })();
      var WWW = /* @__PURE__ */ (function() {
        var defaultClassNames = {
          wrapper: "twitter-typeahead",
          input: "tt-input",
          hint: "tt-hint",
          menu: "tt-menu",
          dataset: "tt-dataset",
          suggestion: "tt-suggestion",
          selectable: "tt-selectable",
          empty: "tt-empty",
          open: "tt-open",
          cursor: "tt-cursor",
          highlight: "tt-highlight"
        };
        return build;
        function build(o) {
          var www, classes;
          classes = _.mixin({}, defaultClassNames, o);
          www = {
            css: buildCss(),
            classes,
            html: buildHtml(classes),
            selectors: buildSelectors(classes)
          };
          return {
            css: www.css,
            html: www.html,
            classes: www.classes,
            selectors: www.selectors,
            mixin: function(o2) {
              _.mixin(o2, www);
            }
          };
        }
        function buildHtml(c) {
          return {
            wrapper: '<span class="' + c.wrapper + '"></span>',
            menu: '<div role="listbox" class="' + c.menu + '"></div>'
          };
        }
        function buildSelectors(classes) {
          var selectors = {};
          _.each(classes, function(v, k) {
            selectors[k] = "." + v;
          });
          return selectors;
        }
        function buildCss() {
          var css = {
            wrapper: {
              position: "relative",
              display: "inline-block"
            },
            hint: {
              position: "absolute",
              top: "0",
              left: "0",
              borderColor: "transparent",
              boxShadow: "none",
              opacity: "1"
            },
            input: {
              position: "relative",
              verticalAlign: "top",
              backgroundColor: "transparent"
            },
            inputWithNoHint: {
              position: "relative",
              verticalAlign: "top"
            },
            menu: {
              position: "absolute",
              top: "100%",
              left: "0",
              zIndex: "100",
              display: "none"
            },
            ltr: {
              left: "0",
              right: "auto"
            },
            rtl: {
              left: "auto",
              right: " 0"
            }
          };
          if (_.isMsie()) {
            _.mixin(css.input, {
              backgroundImage: "url(data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7)"
            });
          }
          return css;
        }
      })();
      var EventBus = (function() {
        var namespace, deprecationMap;
        namespace = "typeahead:";
        deprecationMap = {
          render: "rendered",
          cursorchange: "cursorchanged",
          select: "selected",
          autocomplete: "autocompleted"
        };
        function EventBus2(o) {
          if (!o || !o.el) {
            $2.error("EventBus initialized without el");
          }
          this.$el = $2(o.el);
        }
        _.mixin(EventBus2.prototype, {
          _trigger: function(type, args) {
            var $e = $2.Event(namespace + type);
            this.$el.trigger.call(this.$el, $e, args || []);
            return $e;
          },
          before: function(type) {
            var args, $e;
            args = [].slice.call(arguments, 1);
            $e = this._trigger("before" + type, args);
            return $e.isDefaultPrevented();
          },
          trigger: function(type) {
            var deprecatedType;
            this._trigger(type, [].slice.call(arguments, 1));
            if (deprecatedType = deprecationMap[type]) {
              this._trigger(deprecatedType, [].slice.call(arguments, 1));
            }
          }
        });
        return EventBus2;
      })();
      var EventEmitter = (function() {
        var splitter = /\s+/, nextTick = getNextTick();
        return {
          onSync,
          onAsync,
          off,
          trigger
        };
        function on(method, types, cb, context) {
          var type;
          if (!cb) {
            return this;
          }
          types = types.split(splitter);
          cb = context ? bindContext(cb, context) : cb;
          this._callbacks = this._callbacks || {};
          while (type = types.shift()) {
            this._callbacks[type] = this._callbacks[type] || {
              sync: [],
              async: []
            };
            this._callbacks[type][method].push(cb);
          }
          return this;
        }
        function onAsync(types, cb, context) {
          return on.call(this, "async", types, cb, context);
        }
        function onSync(types, cb, context) {
          return on.call(this, "sync", types, cb, context);
        }
        function off(types) {
          var type;
          if (!this._callbacks) {
            return this;
          }
          types = types.split(splitter);
          while (type = types.shift()) {
            delete this._callbacks[type];
          }
          return this;
        }
        function trigger(types) {
          var type, callbacks, args, syncFlush, asyncFlush;
          if (!this._callbacks) {
            return this;
          }
          types = types.split(splitter);
          args = [].slice.call(arguments, 1);
          while ((type = types.shift()) && (callbacks = this._callbacks[type])) {
            syncFlush = getFlush(callbacks.sync, this, [type].concat(args));
            asyncFlush = getFlush(callbacks.async, this, [type].concat(args));
            syncFlush() && nextTick(asyncFlush);
          }
          return this;
        }
        function getFlush(callbacks, context, args) {
          return flush;
          function flush() {
            var cancelled;
            for (var i = 0, len = callbacks.length; !cancelled && i < len; i += 1) {
              cancelled = callbacks[i].apply(context, args) === false;
            }
            return !cancelled;
          }
        }
        function getNextTick() {
          var nextTickFn;
          if (window.setImmediate) {
            nextTickFn = function nextTickSetImmediate(fn) {
              setImmediate(function() {
                fn();
              });
            };
          } else {
            nextTickFn = function nextTickSetTimeout(fn) {
              setTimeout(function() {
                fn();
              }, 0);
            };
          }
          return nextTickFn;
        }
        function bindContext(fn, context) {
          return fn.bind ? fn.bind(context) : function() {
            fn.apply(context, [].slice.call(arguments, 0));
          };
        }
      })();
      var highlight = /* @__PURE__ */ (function(doc) {
        var defaults = {
          node: null,
          pattern: null,
          tagName: "strong",
          className: null,
          wordsOnly: false,
          caseSensitive: false,
          diacriticInsensitive: false
        };
        var accented = {
          A: "[AaªÀ-Åà-åĀ-ąǍǎȀ-ȃȦȧᴬᵃḀḁẚẠ-ảₐ℀℁℻⒜Ⓐⓐ㍱-㍴㎀-㎄㎈㎉㎩-㎯㏂㏊㏟㏿Ａａ]",
          B: "[BbᴮᵇḂ-ḇℬ⒝Ⓑⓑ㍴㎅-㎇㏃㏈㏔㏝Ｂｂ]",
          C: "[CcÇçĆ-čᶜ℀ℂ℃℅℆ℭⅭⅽ⒞Ⓒⓒ㍶㎈㎉㎝㎠㎤㏄-㏇Ｃｃ]",
          D: "[DdĎďǄ-ǆǱ-ǳᴰᵈḊ-ḓⅅⅆⅮⅾ⒟Ⓓⓓ㋏㍲㍷-㍹㎗㎭-㎯㏅㏈Ｄｄ]",
          E: "[EeÈ-Ëè-ëĒ-ěȄ-ȇȨȩᴱᵉḘ-ḛẸ-ẽₑ℡ℯℰⅇ⒠Ⓔⓔ㉐㋍㋎Ｅｅ]",
          F: "[FfᶠḞḟ℉ℱ℻⒡Ⓕⓕ㎊-㎌㎙ﬀ-ﬄＦｆ]",
          G: "[GgĜ-ģǦǧǴǵᴳᵍḠḡℊ⒢Ⓖⓖ㋌㋍㎇㎍-㎏㎓㎬㏆㏉㏒㏿Ｇｇ]",
          H: "[HhĤĥȞȟʰᴴḢ-ḫẖℋ-ℎ⒣Ⓗⓗ㋌㍱㎐-㎔㏊㏋㏗Ｈｈ]",
          I: "[IiÌ-Ïì-ïĨ-İĲĳǏǐȈ-ȋᴵᵢḬḭỈ-ịⁱℐℑℹⅈⅠ-ⅣⅥ-ⅨⅪⅫⅰ-ⅳⅵ-ⅸⅺⅻ⒤Ⓘⓘ㍺㏌㏕ﬁﬃＩｉ]",
          J: "[JjĲ-ĵǇ-ǌǰʲᴶⅉ⒥ⒿⓙⱼＪｊ]",
          K: "[KkĶķǨǩᴷᵏḰ-ḵK⒦Ⓚⓚ㎄㎅㎉㎏㎑㎘㎞㎢㎦㎪㎸㎾㏀㏆㏍-㏏Ｋｋ]",
          L: "[LlĹ-ŀǇ-ǉˡᴸḶḷḺ-ḽℒℓ℡Ⅼⅼ⒧Ⓛⓛ㋏㎈㎉㏐-㏓㏕㏖㏿ﬂﬄＬｌ]",
          M: "[MmᴹᵐḾ-ṃ℠™ℳⅯⅿ⒨Ⓜⓜ㍷-㍹㎃㎆㎎㎒㎖㎙-㎨㎫㎳㎷㎹㎽㎿㏁㏂㏎㏐㏔-㏖㏘㏙㏞㏟Ｍｍ]",
          N: "[NnÑñŃ-ŉǊ-ǌǸǹᴺṄ-ṋⁿℕ№⒩Ⓝⓝ㎁㎋㎚㎱㎵㎻㏌㏑Ｎｎ]",
          O: "[OoºÒ-Öò-öŌ-őƠơǑǒǪǫȌ-ȏȮȯᴼᵒỌ-ỏₒ℅№ℴ⒪Ⓞⓞ㍵㏇㏒㏖Ｏｏ]",
          P: "[PpᴾᵖṔ-ṗℙ⒫Ⓟⓟ㉐㍱㍶㎀㎊㎩-㎬㎰㎴㎺㏋㏗-㏚Ｐｐ]",
          Q: "[Qqℚ⒬Ⓠⓠ㏃Ｑｑ]",
          R: "[RrŔ-řȐ-ȓʳᴿᵣṘ-ṛṞṟ₨ℛ-ℝ⒭Ⓡⓡ㋍㍴㎭-㎯㏚㏛Ｒｒ]",
          S: "[SsŚ-šſȘșˢṠ-ṣ₨℁℠⒮Ⓢⓢ㎧㎨㎮-㎳㏛㏜ﬆＳｓ]",
          T: "[TtŢ-ťȚțᵀᵗṪ-ṱẗ℡™⒯Ⓣⓣ㉐㋏㎔㏏ﬅﬆＴｔ]",
          U: "[UuÙ-Üù-üŨ-ųƯưǓǔȔ-ȗᵁᵘᵤṲ-ṷỤ-ủ℆⒰Ⓤⓤ㍳㍺Ｕｕ]",
          V: "[VvᵛᵥṼ-ṿⅣ-Ⅷⅳ-ⅷ⒱Ⓥⓥⱽ㋎㍵㎴-㎹㏜㏞Ｖｖ]",
          W: "[WwŴŵʷᵂẀ-ẉẘ⒲Ⓦⓦ㎺-㎿㏝Ｗｗ]",
          X: "[XxˣẊ-ẍₓ℻Ⅸ-Ⅻⅸ-ⅻ⒳Ⓧⓧ㏓Ｘｘ]",
          Y: "[YyÝýÿŶ-ŸȲȳʸẎẏẙỲ-ỹ⒴Ⓨⓨ㏉Ｙｙ]",
          Z: "[ZzŹ-žǱ-ǳᶻẐ-ẕℤℨ⒵Ⓩⓩ㎐-㎔Ｚｚ]"
        };
        return function hightlight(o) {
          var regex;
          o = _.mixin({}, defaults, o);
          if (!o.node || !o.pattern) {
            return;
          }
          o.pattern = _.isArray(o.pattern) ? o.pattern : [o.pattern];
          regex = getRegex(o.pattern, o.caseSensitive, o.wordsOnly, o.diacriticInsensitive);
          traverse(o.node, hightlightTextNode);
          function hightlightTextNode(textNode) {
            var match, patternNode, wrapperNode;
            if (match = regex.exec(textNode.data)) {
              wrapperNode = doc.createElement(o.tagName);
              o.className && (wrapperNode.className = o.className);
              patternNode = textNode.splitText(match.index);
              patternNode.splitText(match[0].length);
              wrapperNode.appendChild(patternNode.cloneNode(true));
              textNode.parentNode.replaceChild(wrapperNode, patternNode);
            }
            return !!match;
          }
          function traverse(el, hightlightTextNode2) {
            var childNode, TEXT_NODE_TYPE = 3;
            for (var i = 0; i < el.childNodes.length; i++) {
              childNode = el.childNodes[i];
              if (childNode.nodeType === TEXT_NODE_TYPE) {
                i += hightlightTextNode2(childNode) ? 1 : 0;
              } else {
                traverse(childNode, hightlightTextNode2);
              }
            }
          }
        };
        function accent_replacer(chr) {
          return accented[chr.toUpperCase()] || chr;
        }
        function getRegex(patterns, caseSensitive, wordsOnly, diacriticInsensitive) {
          var escapedPatterns = [], regexStr;
          for (var i = 0, len = patterns.length; i < len; i++) {
            var escapedWord = _.escapeRegExChars(patterns[i]);
            if (diacriticInsensitive) {
              escapedWord = escapedWord.replace(/\S/g, accent_replacer);
            }
            escapedPatterns.push(escapedWord);
          }
          regexStr = wordsOnly ? "\\b(" + escapedPatterns.join("|") + ")\\b" : "(" + escapedPatterns.join("|") + ")";
          return caseSensitive ? new RegExp(regexStr) : new RegExp(regexStr, "i");
        }
      })(window.document);
      var Input = (function() {
        var specialKeyCodeMap;
        specialKeyCodeMap = {
          9: "tab",
          27: "esc",
          37: "left",
          39: "right",
          13: "enter",
          38: "up",
          40: "down"
        };
        function Input2(o, www) {
          var id;
          o = o || {};
          if (!o.input) {
            $2.error("input is missing");
          }
          www.mixin(this);
          this.$hint = $2(o.hint);
          this.$input = $2(o.input);
          this.$menu = $2(o.menu);
          id = this.$input.attr("id") || _.guid();
          this.$menu.attr("id", id + "_listbox");
          this.$hint.attr({
            "aria-hidden": true
          });
          this.$input.attr({
            "aria-owns": id + "_listbox",
            "aria-controls": id + "_listbox",
            role: "combobox",
            "aria-autocomplete": "list",
            "aria-expanded": false
          });
          this.query = this.$input.val();
          this.queryWhenFocused = this.hasFocus() ? this.query : null;
          this.$overflowHelper = buildOverflowHelper(this.$input);
          this._checkLanguageDirection();
          if (this.$hint.length === 0) {
            this.setHint = this.getHint = this.clearHint = this.clearHintIfInvalid = _.noop;
          }
          this.onSync("cursorchange", this._updateDescendent);
        }
        Input2.normalizeQuery = function(str) {
          return _.toStr(str).replace(/^\s*/g, "").replace(/\s{2,}/g, " ");
        };
        _.mixin(Input2.prototype, EventEmitter, {
          _onBlur: function onBlur() {
            this.resetInputValue();
            this.trigger("blurred");
          },
          _onFocus: function onFocus() {
            this.queryWhenFocused = this.query;
            this.trigger("focused");
          },
          _onKeydown: function onKeydown($e) {
            var keyName = specialKeyCodeMap[$e.which || $e.keyCode];
            this._managePreventDefault(keyName, $e);
            if (keyName && this._shouldTrigger(keyName, $e)) {
              this.trigger(keyName + "Keyed", $e);
            }
          },
          _onInput: function onInput() {
            this._setQuery(this.getInputValue());
            this.clearHintIfInvalid();
            this._checkLanguageDirection();
          },
          _managePreventDefault: function managePreventDefault(keyName, $e) {
            var preventDefault;
            switch (keyName) {
              case "up":
              case "down":
                preventDefault = !withModifier($e);
                break;
              default:
                preventDefault = false;
            }
            preventDefault && $e.preventDefault();
          },
          _shouldTrigger: function shouldTrigger(keyName, $e) {
            var trigger;
            switch (keyName) {
              case "tab":
                trigger = !withModifier($e);
                break;
              default:
                trigger = true;
            }
            return trigger;
          },
          _checkLanguageDirection: function checkLanguageDirection() {
            var dir = (this.$input.css("direction") || "ltr").toLowerCase();
            if (this.dir !== dir) {
              this.dir = dir;
              this.$hint.attr("dir", dir);
              this.trigger("langDirChanged", dir);
            }
          },
          _setQuery: function setQuery(val, silent) {
            var areEquivalent, hasDifferentWhitespace;
            areEquivalent = areQueriesEquivalent(val, this.query);
            hasDifferentWhitespace = areEquivalent ? this.query.length !== val.length : false;
            this.query = val;
            if (!silent && !areEquivalent) {
              this.trigger("queryChanged", this.query);
            } else if (!silent && hasDifferentWhitespace) {
              this.trigger("whitespaceChanged", this.query);
            }
          },
          _updateDescendent: function updateDescendent(event, id) {
            this.$input.attr("aria-activedescendant", id);
          },
          bind: function() {
            var that = this, onBlur, onFocus, onKeydown, onInput;
            onBlur = _.bind(this._onBlur, this);
            onFocus = _.bind(this._onFocus, this);
            onKeydown = _.bind(this._onKeydown, this);
            onInput = _.bind(this._onInput, this);
            this.$input.on("blur.tt", onBlur).on("focus.tt", onFocus).on("keydown.tt", onKeydown);
            if (!_.isMsie() || _.isMsie() > 9) {
              this.$input.on("input.tt", onInput);
            } else {
              this.$input.on("keydown.tt keypress.tt cut.tt paste.tt", function($e) {
                if (specialKeyCodeMap[$e.which || $e.keyCode]) {
                  return;
                }
                _.defer(_.bind(that._onInput, that, $e));
              });
            }
            return this;
          },
          focus: function focus() {
            this.$input.focus();
          },
          blur: function blur() {
            this.$input.blur();
          },
          getLangDir: function getLangDir() {
            return this.dir;
          },
          getQuery: function getQuery() {
            return this.query || "";
          },
          setQuery: function setQuery(val, silent) {
            this.setInputValue(val);
            this._setQuery(val, silent);
          },
          hasQueryChangedSinceLastFocus: function hasQueryChangedSinceLastFocus() {
            return this.query !== this.queryWhenFocused;
          },
          getInputValue: function getInputValue() {
            return this.$input.val();
          },
          setInputValue: function setInputValue(value) {
            this.$input.val(value);
            this.clearHintIfInvalid();
            this._checkLanguageDirection();
          },
          resetInputValue: function resetInputValue() {
            this.setInputValue(this.query);
          },
          getHint: function getHint() {
            return this.$hint.val();
          },
          setHint: function setHint(value) {
            this.$hint.val(value);
          },
          clearHint: function clearHint() {
            this.setHint("");
          },
          clearHintIfInvalid: function clearHintIfInvalid() {
            var val, hint, valIsPrefixOfHint, isValid;
            val = this.getInputValue();
            hint = this.getHint();
            valIsPrefixOfHint = val !== hint && hint.indexOf(val) === 0;
            isValid = val !== "" && valIsPrefixOfHint && !this.hasOverflow();
            !isValid && this.clearHint();
          },
          hasFocus: function hasFocus() {
            return this.$input.is(":focus");
          },
          hasOverflow: function hasOverflow() {
            var constraint = this.$input.width() - 2;
            this.$overflowHelper.text(this.getInputValue());
            return this.$overflowHelper.width() >= constraint;
          },
          isCursorAtEnd: function() {
            var valueLength, selectionStart, range;
            valueLength = this.$input.val().length;
            selectionStart = this.$input[0].selectionStart;
            if (_.isNumber(selectionStart)) {
              return selectionStart === valueLength;
            } else if (document.selection) {
              range = document.selection.createRange();
              range.moveStart("character", -valueLength);
              return valueLength === range.text.length;
            }
            return true;
          },
          destroy: function destroy() {
            this.$hint.off(".tt");
            this.$input.off(".tt");
            this.$overflowHelper.remove();
            this.$hint = this.$input = this.$overflowHelper = $2("<div>");
          },
          setAriaExpanded: function setAriaExpanded(value) {
            this.$input.attr("aria-expanded", value);
          }
        });
        return Input2;
        function buildOverflowHelper($input) {
          return $2('<pre aria-hidden="true"></pre>').css({
            position: "absolute",
            visibility: "hidden",
            whiteSpace: "pre",
            fontFamily: $input.css("font-family"),
            fontSize: $input.css("font-size"),
            fontStyle: $input.css("font-style"),
            fontVariant: $input.css("font-variant"),
            fontWeight: $input.css("font-weight"),
            wordSpacing: $input.css("word-spacing"),
            letterSpacing: $input.css("letter-spacing"),
            textIndent: $input.css("text-indent"),
            textRendering: $input.css("text-rendering"),
            textTransform: $input.css("text-transform")
          }).insertAfter($input);
        }
        function areQueriesEquivalent(a, b) {
          return Input2.normalizeQuery(a) === Input2.normalizeQuery(b);
        }
        function withModifier($e) {
          return $e.altKey || $e.ctrlKey || $e.metaKey || $e.shiftKey;
        }
      })();
      var Dataset = (function() {
        var keys, nameGenerator;
        keys = {
          dataset: "tt-selectable-dataset",
          val: "tt-selectable-display",
          obj: "tt-selectable-object"
        };
        nameGenerator = _.getIdGenerator();
        function Dataset2(o, www) {
          o = o || {};
          o.templates = o.templates || {};
          o.templates.notFound = o.templates.notFound || o.templates.empty;
          if (!o.source) {
            $2.error("missing source");
          }
          if (!o.node) {
            $2.error("missing node");
          }
          if (o.name && !isValidName(o.name)) {
            $2.error("invalid dataset name: " + o.name);
          }
          www.mixin(this);
          this.highlight = !!o.highlight;
          this.name = _.toStr(o.name || nameGenerator());
          this.limit = o.limit || 5;
          this.displayFn = getDisplayFn(o.display || o.displayKey);
          this.templates = getTemplates(o.templates, this.displayFn);
          this.source = o.source.__ttAdapter ? o.source.__ttAdapter() : o.source;
          this.async = _.isUndefined(o.async) ? this.source.length > 2 : !!o.async;
          this._resetLastSuggestion();
          this.$el = $2(o.node).attr("role", "presentation").addClass(this.classes.dataset).addClass(this.classes.dataset + "-" + this.name);
        }
        Dataset2.extractData = function extractData(el) {
          var $el = $2(el);
          if ($el.data(keys.obj)) {
            return {
              dataset: $el.data(keys.dataset) || "",
              val: $el.data(keys.val) || "",
              obj: $el.data(keys.obj) || null
            };
          }
          return null;
        };
        _.mixin(Dataset2.prototype, EventEmitter, {
          _overwrite: function overwrite(query, suggestions) {
            suggestions = suggestions || [];
            if (suggestions.length) {
              this._renderSuggestions(query, suggestions);
            } else if (this.async && this.templates.pending) {
              this._renderPending(query);
            } else if (!this.async && this.templates.notFound) {
              this._renderNotFound(query);
            } else {
              this._empty();
            }
            this.trigger("rendered", suggestions, false, this.name);
          },
          _append: function append(query, suggestions) {
            suggestions = suggestions || [];
            if (suggestions.length && this.$lastSuggestion.length) {
              this._appendSuggestions(query, suggestions);
            } else if (suggestions.length) {
              this._renderSuggestions(query, suggestions);
            } else if (!this.$lastSuggestion.length && this.templates.notFound) {
              this._renderNotFound(query);
            }
            this.trigger("rendered", suggestions, true, this.name);
          },
          _renderSuggestions: function renderSuggestions(query, suggestions) {
            var $fragment;
            $fragment = this._getSuggestionsFragment(query, suggestions);
            this.$lastSuggestion = $fragment.children().last();
            this.$el.html($fragment).prepend(this._getHeader(query, suggestions)).append(this._getFooter(query, suggestions));
          },
          _appendSuggestions: function appendSuggestions(query, suggestions) {
            var $fragment, $lastSuggestion;
            $fragment = this._getSuggestionsFragment(query, suggestions);
            $lastSuggestion = $fragment.children().last();
            this.$lastSuggestion.after($fragment);
            this.$lastSuggestion = $lastSuggestion;
          },
          _renderPending: function renderPending(query) {
            var template = this.templates.pending;
            this._resetLastSuggestion();
            template && this.$el.html(template({
              query,
              dataset: this.name
            }));
          },
          _renderNotFound: function renderNotFound(query) {
            var template = this.templates.notFound;
            this._resetLastSuggestion();
            template && this.$el.html(template({
              query,
              dataset: this.name
            }));
          },
          _empty: function empty() {
            this.$el.empty();
            this._resetLastSuggestion();
          },
          _getSuggestionsFragment: function getSuggestionsFragment(query, suggestions) {
            var that = this, fragment;
            fragment = document.createDocumentFragment();
            _.each(suggestions, function getSuggestionNode(suggestion) {
              var $el, context;
              context = that._injectQuery(query, suggestion);
              $el = $2(that.templates.suggestion(context)).data(keys.dataset, that.name).data(keys.obj, suggestion).data(keys.val, that.displayFn(suggestion)).addClass(that.classes.suggestion + " " + that.classes.selectable);
              fragment.appendChild($el[0]);
            });
            this.highlight && highlight({
              className: this.classes.highlight,
              node: fragment,
              pattern: query
            });
            return $2(fragment);
          },
          _getFooter: function getFooter(query, suggestions) {
            return this.templates.footer ? this.templates.footer({
              query,
              suggestions,
              dataset: this.name
            }) : null;
          },
          _getHeader: function getHeader(query, suggestions) {
            return this.templates.header ? this.templates.header({
              query,
              suggestions,
              dataset: this.name
            }) : null;
          },
          _resetLastSuggestion: function resetLastSuggestion() {
            this.$lastSuggestion = $2();
          },
          _injectQuery: function injectQuery(query, obj) {
            return _.isObject(obj) ? _.mixin({
              _query: query
            }, obj) : obj;
          },
          update: function update(query) {
            var that = this, canceled = false, syncCalled = false, rendered = 0;
            this.cancel();
            this.cancel = function cancel() {
              canceled = true;
              that.cancel = $2.noop;
              that.async && that.trigger("asyncCanceled", query, that.name);
            };
            this.source(query, sync, async);
            !syncCalled && sync([]);
            function sync(suggestions) {
              if (syncCalled) {
                return;
              }
              syncCalled = true;
              suggestions = (suggestions || []).slice(0, that.limit);
              rendered = suggestions.length;
              that._overwrite(query, suggestions);
              if (rendered < that.limit && that.async) {
                that.trigger("asyncRequested", query, that.name);
              }
            }
            function async(suggestions) {
              suggestions = suggestions || [];
              if (!canceled && rendered < that.limit) {
                that.cancel = $2.noop;
                var idx = Math.abs(rendered - that.limit);
                rendered += idx;
                that._append(query, suggestions.slice(0, idx));
                that.async && that.trigger("asyncReceived", query, that.name);
              }
            }
          },
          cancel: $2.noop,
          clear: function clear() {
            this._empty();
            this.cancel();
            this.trigger("cleared");
          },
          isEmpty: function isEmpty() {
            return this.$el.is(":empty");
          },
          destroy: function destroy() {
            this.$el = $2("<div>");
          }
        });
        return Dataset2;
        function getDisplayFn(display) {
          display = display || _.stringify;
          return _.isFunction(display) ? display : displayFn;
          function displayFn(obj) {
            return obj[display];
          }
        }
        function getTemplates(templates, displayFn) {
          return {
            notFound: templates.notFound && _.templatify(templates.notFound),
            pending: templates.pending && _.templatify(templates.pending),
            header: templates.header && _.templatify(templates.header),
            footer: templates.footer && _.templatify(templates.footer),
            suggestion: templates.suggestion ? userSuggestionTemplate : suggestionTemplate
          };
          function userSuggestionTemplate(context) {
            var template = templates.suggestion;
            return $2(template(context)).attr("id", _.guid());
          }
          function suggestionTemplate(context) {
            return $2('<div role="option">').attr("id", _.guid()).text(displayFn(context));
          }
        }
        function isValidName(str) {
          return /^[_a-zA-Z0-9-]+$/.test(str);
        }
      })();
      var Menu = (function() {
        function Menu2(o, www) {
          var that = this;
          o = o || {};
          if (!o.node) {
            $2.error("node is required");
          }
          www.mixin(this);
          this.$node = $2(o.node);
          this.query = null;
          this.datasets = _.map(o.datasets, initializeDataset);
          function initializeDataset(oDataset) {
            var node = that.$node.find(oDataset.node).first();
            oDataset.node = node.length ? node : $2("<div>").appendTo(that.$node);
            return new Dataset(oDataset, www);
          }
        }
        _.mixin(Menu2.prototype, EventEmitter, {
          _onSelectableClick: function onSelectableClick($e) {
            this.trigger("selectableClicked", $2($e.currentTarget));
          },
          _onRendered: function onRendered(type, dataset, suggestions, async) {
            this.$node.toggleClass(this.classes.empty, this._allDatasetsEmpty());
            this.trigger("datasetRendered", dataset, suggestions, async);
          },
          _onCleared: function onCleared() {
            this.$node.toggleClass(this.classes.empty, this._allDatasetsEmpty());
            this.trigger("datasetCleared");
          },
          _propagate: function propagate() {
            this.trigger.apply(this, arguments);
          },
          _allDatasetsEmpty: function allDatasetsEmpty() {
            return _.every(this.datasets, _.bind(function isDatasetEmpty(dataset) {
              var isEmpty = dataset.isEmpty();
              this.$node.attr("aria-expanded", !isEmpty);
              return isEmpty;
            }, this));
          },
          _getSelectables: function getSelectables() {
            return this.$node.find(this.selectors.selectable);
          },
          _removeCursor: function _removeCursor() {
            var $selectable = this.getActiveSelectable();
            $selectable && $selectable.removeClass(this.classes.cursor);
          },
          _ensureVisible: function ensureVisible($el) {
            var elTop, elBottom, nodeScrollTop, nodeHeight;
            elTop = $el.position().top;
            elBottom = elTop + $el.outerHeight(true);
            nodeScrollTop = this.$node.scrollTop();
            nodeHeight = this.$node.height() + parseInt(this.$node.css("paddingTop"), 10) + parseInt(this.$node.css("paddingBottom"), 10);
            if (elTop < 0) {
              this.$node.scrollTop(nodeScrollTop + elTop);
            } else if (nodeHeight < elBottom) {
              this.$node.scrollTop(nodeScrollTop + (elBottom - nodeHeight));
            }
          },
          bind: function() {
            var that = this, onSelectableClick;
            onSelectableClick = _.bind(this._onSelectableClick, this);
            this.$node.on("click.tt", this.selectors.selectable, onSelectableClick);
            this.$node.on("mouseover", this.selectors.selectable, function() {
              that.setCursor($2(this));
            });
            this.$node.on("mouseleave", function() {
              that._removeCursor();
            });
            _.each(this.datasets, function(dataset) {
              dataset.onSync("asyncRequested", that._propagate, that).onSync("asyncCanceled", that._propagate, that).onSync("asyncReceived", that._propagate, that).onSync("rendered", that._onRendered, that).onSync("cleared", that._onCleared, that);
            });
            return this;
          },
          isOpen: function isOpen() {
            return this.$node.hasClass(this.classes.open);
          },
          open: function open() {
            this.$node.scrollTop(0);
            this.$node.addClass(this.classes.open);
          },
          close: function close() {
            this.$node.attr("aria-expanded", false);
            this.$node.removeClass(this.classes.open);
            this._removeCursor();
          },
          setLanguageDirection: function setLanguageDirection(dir) {
            this.$node.attr("dir", dir);
          },
          selectableRelativeToCursor: function selectableRelativeToCursor(delta) {
            var $selectables, $oldCursor, oldIndex, newIndex;
            $oldCursor = this.getActiveSelectable();
            $selectables = this._getSelectables();
            oldIndex = $oldCursor ? $selectables.index($oldCursor) : -1;
            newIndex = oldIndex + delta;
            newIndex = (newIndex + 1) % ($selectables.length + 1) - 1;
            newIndex = newIndex < -1 ? $selectables.length - 1 : newIndex;
            return newIndex === -1 ? null : $selectables.eq(newIndex);
          },
          setCursor: function setCursor($selectable) {
            this._removeCursor();
            if ($selectable = $selectable && $selectable.first()) {
              $selectable.addClass(this.classes.cursor);
              this._ensureVisible($selectable);
            }
          },
          getSelectableData: function getSelectableData($el) {
            return $el && $el.length ? Dataset.extractData($el) : null;
          },
          getActiveSelectable: function getActiveSelectable() {
            var $selectable = this._getSelectables().filter(this.selectors.cursor).first();
            return $selectable.length ? $selectable : null;
          },
          getTopSelectable: function getTopSelectable() {
            var $selectable = this._getSelectables().first();
            return $selectable.length ? $selectable : null;
          },
          update: function update(query) {
            var isValidUpdate = query !== this.query;
            if (isValidUpdate) {
              this.query = query;
              _.each(this.datasets, updateDataset);
            }
            return isValidUpdate;
            function updateDataset(dataset) {
              dataset.update(query);
            }
          },
          empty: function empty() {
            _.each(this.datasets, clearDataset);
            this.query = null;
            this.$node.addClass(this.classes.empty);
            function clearDataset(dataset) {
              dataset.clear();
            }
          },
          destroy: function destroy() {
            this.$node.off(".tt");
            this.$node = $2("<div>");
            _.each(this.datasets, destroyDataset);
            function destroyDataset(dataset) {
              dataset.destroy();
            }
          }
        });
        return Menu2;
      })();
      var Status = (function() {
        function Status2(options) {
          this.$el = $2("<span></span>", {
            role: "status",
            "aria-live": "polite"
          }).css({
            position: "absolute",
            padding: "0",
            border: "0",
            height: "1px",
            width: "1px",
            "margin-bottom": "-1px",
            "margin-right": "-1px",
            overflow: "hidden",
            clip: "rect(0 0 0 0)",
            "white-space": "nowrap"
          });
          options.$input.after(this.$el);
          _.each(options.menu.datasets, _.bind(function(dataset) {
            if (dataset.onSync) {
              dataset.onSync("rendered", _.bind(this.update, this));
              dataset.onSync("cleared", _.bind(this.cleared, this));
            }
          }, this));
        }
        _.mixin(Status2.prototype, {
          update: function update(event, suggestions) {
            var length = suggestions.length;
            var words;
            if (length === 1) {
              words = {
                result: "result",
                is: "is"
              };
            } else {
              words = {
                result: "results",
                is: "are"
              };
            }
            this.$el.text(length + " " + words.result + " " + words.is + " available, use up and down arrow keys to navigate.");
          },
          cleared: function() {
            this.$el.text("");
          }
        });
        return Status2;
      })();
      var DefaultMenu = (function() {
        var s = Menu.prototype;
        function DefaultMenu2() {
          Menu.apply(this, [].slice.call(arguments, 0));
        }
        _.mixin(DefaultMenu2.prototype, Menu.prototype, {
          open: function open() {
            !this._allDatasetsEmpty() && this._show();
            return s.open.apply(this, [].slice.call(arguments, 0));
          },
          close: function close() {
            this._hide();
            return s.close.apply(this, [].slice.call(arguments, 0));
          },
          _onRendered: function onRendered() {
            if (this._allDatasetsEmpty()) {
              this._hide();
            } else {
              this.isOpen() && this._show();
            }
            return s._onRendered.apply(this, [].slice.call(arguments, 0));
          },
          _onCleared: function onCleared() {
            if (this._allDatasetsEmpty()) {
              this._hide();
            } else {
              this.isOpen() && this._show();
            }
            return s._onCleared.apply(this, [].slice.call(arguments, 0));
          },
          setLanguageDirection: function setLanguageDirection(dir) {
            this.$node.css(dir === "ltr" ? this.css.ltr : this.css.rtl);
            return s.setLanguageDirection.apply(this, [].slice.call(arguments, 0));
          },
          _hide: function hide() {
            this.$node.hide();
          },
          _show: function show() {
            this.$node.css("display", "block");
          }
        });
        return DefaultMenu2;
      })();
      var Typeahead = (function() {
        function Typeahead2(o, www) {
          var onFocused, onBlurred, onEnterKeyed, onTabKeyed, onEscKeyed, onUpKeyed, onDownKeyed, onLeftKeyed, onRightKeyed, onQueryChanged, onWhitespaceChanged;
          o = o || {};
          if (!o.input) {
            $2.error("missing input");
          }
          if (!o.menu) {
            $2.error("missing menu");
          }
          if (!o.eventBus) {
            $2.error("missing event bus");
          }
          www.mixin(this);
          this.eventBus = o.eventBus;
          this.minLength = _.isNumber(o.minLength) ? o.minLength : 1;
          this.input = o.input;
          this.menu = o.menu;
          this.enabled = true;
          this.autoselect = !!o.autoselect;
          this.active = false;
          this.input.hasFocus() && this.activate();
          this.dir = this.input.getLangDir();
          this._hacks();
          this.menu.bind().onSync("selectableClicked", this._onSelectableClicked, this).onSync("asyncRequested", this._onAsyncRequested, this).onSync("asyncCanceled", this._onAsyncCanceled, this).onSync("asyncReceived", this._onAsyncReceived, this).onSync("datasetRendered", this._onDatasetRendered, this).onSync("datasetCleared", this._onDatasetCleared, this);
          onFocused = c(this, "activate", "open", "_onFocused");
          onBlurred = c(this, "deactivate", "_onBlurred");
          onEnterKeyed = c(this, "isActive", "isOpen", "_onEnterKeyed");
          onTabKeyed = c(this, "isActive", "isOpen", "_onTabKeyed");
          onEscKeyed = c(this, "isActive", "_onEscKeyed");
          onUpKeyed = c(this, "isActive", "open", "_onUpKeyed");
          onDownKeyed = c(this, "isActive", "open", "_onDownKeyed");
          onLeftKeyed = c(this, "isActive", "isOpen", "_onLeftKeyed");
          onRightKeyed = c(this, "isActive", "isOpen", "_onRightKeyed");
          onQueryChanged = c(this, "_openIfActive", "_onQueryChanged");
          onWhitespaceChanged = c(this, "_openIfActive", "_onWhitespaceChanged");
          this.input.bind().onSync("focused", onFocused, this).onSync("blurred", onBlurred, this).onSync("enterKeyed", onEnterKeyed, this).onSync("tabKeyed", onTabKeyed, this).onSync("escKeyed", onEscKeyed, this).onSync("upKeyed", onUpKeyed, this).onSync("downKeyed", onDownKeyed, this).onSync("leftKeyed", onLeftKeyed, this).onSync("rightKeyed", onRightKeyed, this).onSync("queryChanged", onQueryChanged, this).onSync("whitespaceChanged", onWhitespaceChanged, this).onSync("langDirChanged", this._onLangDirChanged, this);
        }
        _.mixin(Typeahead2.prototype, {
          _hacks: function hacks() {
            var $input, $menu;
            $input = this.input.$input || $2("<div>");
            $menu = this.menu.$node || $2("<div>");
            $input.on("blur.tt", function($e) {
              var active, isActive, hasActive;
              active = document.activeElement;
              isActive = $menu.is(active);
              hasActive = $menu.has(active).length > 0;
              if (_.isMsie() && (isActive || hasActive)) {
                $e.preventDefault();
                $e.stopImmediatePropagation();
                _.defer(function() {
                  $input.focus();
                });
              }
            });
            $menu.on("mousedown.tt", function($e) {
              $e.preventDefault();
            });
          },
          _onSelectableClicked: function onSelectableClicked(type, $el) {
            this.select($el);
          },
          _onDatasetCleared: function onDatasetCleared() {
            this._updateHint();
          },
          _onDatasetRendered: function onDatasetRendered(type, suggestions, async, dataset) {
            this._updateHint();
            if (this.autoselect) {
              var cursorClass = this.selectors.cursor.substr(1);
              this.menu.$node.find(this.selectors.suggestion).first().addClass(cursorClass);
            }
            this.eventBus.trigger("render", suggestions, async, dataset);
          },
          _onAsyncRequested: function onAsyncRequested(type, dataset, query) {
            this.eventBus.trigger("asyncrequest", query, dataset);
          },
          _onAsyncCanceled: function onAsyncCanceled(type, dataset, query) {
            this.eventBus.trigger("asynccancel", query, dataset);
          },
          _onAsyncReceived: function onAsyncReceived(type, dataset, query) {
            this.eventBus.trigger("asyncreceive", query, dataset);
          },
          _onFocused: function onFocused() {
            this._minLengthMet() && this.menu.update(this.input.getQuery());
          },
          _onBlurred: function onBlurred() {
            if (this.input.hasQueryChangedSinceLastFocus()) {
              this.eventBus.trigger("change", this.input.getQuery());
            }
          },
          _onEnterKeyed: function onEnterKeyed(type, $e) {
            var $selectable;
            if ($selectable = this.menu.getActiveSelectable()) {
              if (this.select($selectable)) {
                $e.preventDefault();
                $e.stopPropagation();
              }
            } else if (this.autoselect) {
              if (this.select(this.menu.getTopSelectable())) {
                $e.preventDefault();
                $e.stopPropagation();
              }
            }
          },
          _onTabKeyed: function onTabKeyed(type, $e) {
            var $selectable;
            if ($selectable = this.menu.getActiveSelectable()) {
              this.select($selectable) && $e.preventDefault();
            } else if (this.autoselect) {
              if ($selectable = this.menu.getTopSelectable()) {
                this.autocomplete($selectable) && $e.preventDefault();
              }
            }
          },
          _onEscKeyed: function onEscKeyed() {
            this.close();
          },
          _onUpKeyed: function onUpKeyed() {
            this.moveCursor(-1);
          },
          _onDownKeyed: function onDownKeyed() {
            this.moveCursor(1);
          },
          _onLeftKeyed: function onLeftKeyed() {
            if (this.dir === "rtl" && this.input.isCursorAtEnd()) {
              this.autocomplete(this.menu.getActiveSelectable() || this.menu.getTopSelectable());
            }
          },
          _onRightKeyed: function onRightKeyed() {
            if (this.dir === "ltr" && this.input.isCursorAtEnd()) {
              this.autocomplete(this.menu.getActiveSelectable() || this.menu.getTopSelectable());
            }
          },
          _onQueryChanged: function onQueryChanged(e, query) {
            this._minLengthMet(query) ? this.menu.update(query) : this.menu.empty();
          },
          _onWhitespaceChanged: function onWhitespaceChanged() {
            this._updateHint();
          },
          _onLangDirChanged: function onLangDirChanged(e, dir) {
            if (this.dir !== dir) {
              this.dir = dir;
              this.menu.setLanguageDirection(dir);
            }
          },
          _openIfActive: function openIfActive() {
            this.isActive() && this.open();
          },
          _minLengthMet: function minLengthMet(query) {
            query = _.isString(query) ? query : this.input.getQuery() || "";
            return query.length >= this.minLength;
          },
          _updateHint: function updateHint() {
            var $selectable, data, val, query, escapedQuery, frontMatchRegEx, match;
            $selectable = this.menu.getTopSelectable();
            data = this.menu.getSelectableData($selectable);
            val = this.input.getInputValue();
            if (data && !_.isBlankString(val) && !this.input.hasOverflow()) {
              query = Input.normalizeQuery(val);
              escapedQuery = _.escapeRegExChars(query);
              frontMatchRegEx = new RegExp("^(?:" + escapedQuery + ")(.+$)", "i");
              match = frontMatchRegEx.exec(data.val);
              match && this.input.setHint(val + match[1]);
            } else {
              this.input.clearHint();
            }
          },
          isEnabled: function isEnabled() {
            return this.enabled;
          },
          enable: function enable() {
            this.enabled = true;
          },
          disable: function disable() {
            this.enabled = false;
          },
          isActive: function isActive() {
            return this.active;
          },
          activate: function activate() {
            if (this.isActive()) {
              return true;
            } else if (!this.isEnabled() || this.eventBus.before("active")) {
              return false;
            } else {
              this.active = true;
              this.eventBus.trigger("active");
              return true;
            }
          },
          deactivate: function deactivate() {
            if (!this.isActive()) {
              return true;
            } else if (this.eventBus.before("idle")) {
              return false;
            } else {
              this.active = false;
              this.close();
              this.eventBus.trigger("idle");
              return true;
            }
          },
          isOpen: function isOpen() {
            return this.menu.isOpen();
          },
          open: function open() {
            if (!this.isOpen() && !this.eventBus.before("open")) {
              this.input.setAriaExpanded(true);
              this.menu.open();
              this._updateHint();
              this.eventBus.trigger("open");
            }
            return this.isOpen();
          },
          close: function close() {
            if (this.isOpen() && !this.eventBus.before("close")) {
              this.input.setAriaExpanded(false);
              this.menu.close();
              this.input.clearHint();
              this.input.resetInputValue();
              this.eventBus.trigger("close");
            }
            return !this.isOpen();
          },
          setVal: function setVal(val) {
            this.input.setQuery(_.toStr(val));
          },
          getVal: function getVal() {
            return this.input.getQuery();
          },
          select: function select($selectable) {
            var data = this.menu.getSelectableData($selectable);
            if (data && !this.eventBus.before("select", data.obj, data.dataset)) {
              this.input.setQuery(data.val, true);
              this.eventBus.trigger("select", data.obj, data.dataset);
              this.close();
              return true;
            }
            return false;
          },
          autocomplete: function autocomplete($selectable) {
            var query, data, isValid;
            query = this.input.getQuery();
            data = this.menu.getSelectableData($selectable);
            isValid = data && query !== data.val;
            if (isValid && !this.eventBus.before("autocomplete", data.obj, data.dataset)) {
              this.input.setQuery(data.val);
              this.eventBus.trigger("autocomplete", data.obj, data.dataset);
              return true;
            }
            return false;
          },
          moveCursor: function moveCursor(delta) {
            var query, $candidate, data, suggestion, datasetName, cancelMove, id;
            query = this.input.getQuery();
            $candidate = this.menu.selectableRelativeToCursor(delta);
            data = this.menu.getSelectableData($candidate);
            suggestion = data ? data.obj : null;
            datasetName = data ? data.dataset : null;
            id = $candidate ? $candidate.attr("id") : null;
            this.input.trigger("cursorchange", id);
            cancelMove = this._minLengthMet() && this.menu.update(query);
            if (!cancelMove && !this.eventBus.before("cursorchange", suggestion, datasetName)) {
              this.menu.setCursor($candidate);
              if (data) {
                if (typeof data.val === "string") {
                  this.input.setInputValue(data.val);
                }
              } else {
                this.input.resetInputValue();
                this._updateHint();
              }
              this.eventBus.trigger("cursorchange", suggestion, datasetName);
              return true;
            }
            return false;
          },
          destroy: function destroy() {
            this.input.destroy();
            this.menu.destroy();
          }
        });
        return Typeahead2;
        function c(ctx) {
          var methods = [].slice.call(arguments, 1);
          return function() {
            var args = [].slice.call(arguments);
            _.each(methods, function(method) {
              return ctx[method].apply(ctx, args);
            });
          };
        }
      })();
      (function() {
        var old, keys, methods;
        old = $2.fn.typeahead;
        keys = {
          www: "tt-www",
          attrs: "tt-attrs",
          typeahead: "tt-typeahead"
        };
        methods = {
          initialize: function initialize(o, datasets) {
            var www;
            datasets = _.isArray(datasets) ? datasets : [].slice.call(arguments, 1);
            o = o || {};
            www = WWW(o.classNames);
            return this.each(attach);
            function attach() {
              var $input, $wrapper, $hint, $menu, defaultHint, defaultMenu, eventBus, input, menu, typeahead, MenuConstructor;
              _.each(datasets, function(d) {
                d.highlight = !!o.highlight;
              });
              $input = $2(this);
              $wrapper = $2(www.html.wrapper);
              $hint = $elOrNull(o.hint);
              $menu = $elOrNull(o.menu);
              defaultHint = o.hint !== false && !$hint;
              defaultMenu = o.menu !== false && !$menu;
              defaultHint && ($hint = buildHintFromInput($input, www));
              defaultMenu && ($menu = $2(www.html.menu).css(www.css.menu));
              $hint && $hint.val("");
              $input = prepInput($input, www);
              if (defaultHint || defaultMenu) {
                $wrapper.css(www.css.wrapper);
                $input.css(defaultHint ? www.css.input : www.css.inputWithNoHint);
                $input.wrap($wrapper).parent().prepend(defaultHint ? $hint : null).append(defaultMenu ? $menu : null);
              }
              MenuConstructor = defaultMenu ? DefaultMenu : Menu;
              eventBus = new EventBus({
                el: $input
              });
              input = new Input({
                hint: $hint,
                input: $input,
                menu: $menu
              }, www);
              menu = new MenuConstructor({
                node: $menu,
                datasets
              }, www);
              new Status({
                $input,
                menu
              });
              typeahead = new Typeahead({
                input,
                menu,
                eventBus,
                minLength: o.minLength,
                autoselect: o.autoselect
              }, www);
              $input.data(keys.www, www);
              $input.data(keys.typeahead, typeahead);
            }
          },
          isEnabled: function isEnabled() {
            var enabled;
            ttEach(this.first(), function(t) {
              enabled = t.isEnabled();
            });
            return enabled;
          },
          enable: function enable() {
            ttEach(this, function(t) {
              t.enable();
            });
            return this;
          },
          disable: function disable() {
            ttEach(this, function(t) {
              t.disable();
            });
            return this;
          },
          isActive: function isActive() {
            var active;
            ttEach(this.first(), function(t) {
              active = t.isActive();
            });
            return active;
          },
          activate: function activate() {
            ttEach(this, function(t) {
              t.activate();
            });
            return this;
          },
          deactivate: function deactivate() {
            ttEach(this, function(t) {
              t.deactivate();
            });
            return this;
          },
          isOpen: function isOpen() {
            var open;
            ttEach(this.first(), function(t) {
              open = t.isOpen();
            });
            return open;
          },
          open: function open() {
            ttEach(this, function(t) {
              t.open();
            });
            return this;
          },
          close: function close() {
            ttEach(this, function(t) {
              t.close();
            });
            return this;
          },
          select: function select(el) {
            var success = false, $el = $2(el);
            ttEach(this.first(), function(t) {
              success = t.select($el);
            });
            return success;
          },
          autocomplete: function autocomplete(el) {
            var success = false, $el = $2(el);
            ttEach(this.first(), function(t) {
              success = t.autocomplete($el);
            });
            return success;
          },
          moveCursor: function moveCursoe(delta) {
            var success = false;
            ttEach(this.first(), function(t) {
              success = t.moveCursor(delta);
            });
            return success;
          },
          val: function val(newVal) {
            var query;
            if (!arguments.length) {
              ttEach(this.first(), function(t) {
                query = t.getVal();
              });
              return query;
            } else {
              ttEach(this, function(t) {
                t.setVal(_.toStr(newVal));
              });
              return this;
            }
          },
          destroy: function destroy() {
            ttEach(this, function(typeahead, $input) {
              revert($input);
              typeahead.destroy();
            });
            return this;
          }
        };
        $2.fn.typeahead = function(method) {
          if (methods[method]) {
            return methods[method].apply(this, [].slice.call(arguments, 1));
          } else {
            return methods.initialize.apply(this, arguments);
          }
        };
        $2.fn.typeahead.noConflict = function noConflict() {
          $2.fn.typeahead = old;
          return this;
        };
        function ttEach($els, fn) {
          $els.each(function() {
            var $input = $2(this), typeahead;
            (typeahead = $input.data(keys.typeahead)) && fn(typeahead, $input);
          });
        }
        function buildHintFromInput($input, www) {
          return $input.clone().addClass(www.classes.hint).removeData().css(www.css.hint).css(getBackgroundStyles($input)).prop({
            readonly: true,
            required: false
          }).removeAttr("id name placeholder").removeClass("required").attr({
            spellcheck: "false",
            tabindex: -1
          });
        }
        function prepInput($input, www) {
          $input.data(keys.attrs, {
            dir: $input.attr("dir"),
            autocomplete: $input.attr("autocomplete"),
            spellcheck: $input.attr("spellcheck"),
            style: $input.attr("style")
          });
          $input.addClass(www.classes.input).attr({
            spellcheck: false
          });
          try {
            !$input.attr("dir") && $input.attr("dir", "auto");
          } catch (e) {
          }
          return $input;
        }
        function getBackgroundStyles($el) {
          return {
            backgroundAttachment: $el.css("background-attachment"),
            backgroundClip: $el.css("background-clip"),
            backgroundColor: $el.css("background-color"),
            backgroundImage: $el.css("background-image"),
            backgroundOrigin: $el.css("background-origin"),
            backgroundPosition: $el.css("background-position"),
            backgroundRepeat: $el.css("background-repeat"),
            backgroundSize: $el.css("background-size")
          };
        }
        function revert($input) {
          var www, $wrapper;
          www = $input.data(keys.www);
          $wrapper = $input.parent().filter(www.selectors.wrapper);
          _.each($input.data(keys.attrs), function(val, key) {
            _.isUndefined(val) ? $input.removeAttr(key) : $input.attr(key, val);
          });
          $input.removeData(keys.typeahead).removeData(keys.www).removeData(keys.attr).removeClass(www.classes.input);
          if ($wrapper.length) {
            $input.detach().insertAfter($wrapper);
            $wrapper.remove();
          }
        }
        function $elOrNull(obj) {
          var isValid, $el;
          isValid = _.isJQuery(obj) || _.isElement(obj);
          $el = isValid ? $2(obj).first() : [];
          return $el.length ? $el : null;
        }
      })();
    });
  })(typeahead_jquery$1);
  return typeahead_jquery$1.exports;
}
requireTypeahead_jquery();
var bloodhound$1 = { exports: {} };
/*!
 * typeahead.js 1.3.3
 * https://github.com/corejavascript/typeahead.js
 * Copyright 2013-2024 Twitter, Inc. and other contributors; Licensed MIT
 */
var bloodhound = bloodhound$1.exports;
var hasRequiredBloodhound;
function requireBloodhound() {
  if (hasRequiredBloodhound) return bloodhound$1.exports;
  hasRequiredBloodhound = 1;
  (function(module) {
    (function(root, factory) {
      if (module.exports) {
        module.exports = factory(requireJquery());
      } else {
        root["Bloodhound"] = factory(root["jQuery"]);
      }
    })(bloodhound, function($2) {
      var _ = (function() {
        return {
          isMsie: function() {
            return /(msie|trident)/i.test(navigator.userAgent) ? navigator.userAgent.match(/(msie |rv:)(\d+(.\d+)?)/i)[2] : false;
          },
          isBlankString: function(str) {
            return !str || /^\s*$/.test(str);
          },
          escapeRegExChars: function(str) {
            return str.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
          },
          isString: function(obj) {
            return typeof obj === "string";
          },
          isNumber: function(obj) {
            return typeof obj === "number";
          },
          isArray: $2.isArray,
          isFunction: $2.isFunction,
          isObject: $2.isPlainObject,
          isUndefined: function(obj) {
            return typeof obj === "undefined";
          },
          isElement: function(obj) {
            return !!(obj && obj.nodeType === 1);
          },
          isJQuery: function(obj) {
            return obj instanceof $2;
          },
          toStr: function toStr(s) {
            return _.isUndefined(s) || s === null ? "" : s + "";
          },
          bind: $2.proxy,
          each: function(collection, cb) {
            $2.each(collection, reverseArgs);
            function reverseArgs(index, value) {
              return cb(value, index);
            }
          },
          map: $2.map,
          filter: $2.grep,
          every: function(obj, test) {
            var result = true;
            if (!obj) {
              return result;
            }
            $2.each(obj, function(key, val) {
              if (!(result = test.call(null, val, key, obj))) {
                return false;
              }
            });
            return !!result;
          },
          some: function(obj, test) {
            var result = false;
            if (!obj) {
              return result;
            }
            $2.each(obj, function(key, val) {
              if (result = test.call(null, val, key, obj)) {
                return false;
              }
            });
            return !!result;
          },
          mixin: $2.extend,
          identity: function(x) {
            return x;
          },
          clone: function(obj) {
            return $2.extend(true, {}, obj);
          },
          getIdGenerator: function() {
            var counter = 0;
            return function() {
              return counter++;
            };
          },
          templatify: function templatify(obj) {
            return $2.isFunction(obj) ? obj : template;
            function template() {
              return String(obj);
            }
          },
          defer: function(fn) {
            setTimeout(fn, 0);
          },
          debounce: function(func, wait, immediate) {
            var timeout, result;
            return function() {
              var context = this, args = arguments, later, callNow;
              later = function() {
                timeout = null;
                if (!immediate) {
                  result = func.apply(context, args);
                }
              };
              callNow = immediate && !timeout;
              clearTimeout(timeout);
              timeout = setTimeout(later, wait);
              if (callNow) {
                result = func.apply(context, args);
              }
              return result;
            };
          },
          throttle: function(func, wait) {
            var context, args, timeout, result, previous, later;
            previous = 0;
            later = function() {
              previous = /* @__PURE__ */ new Date();
              timeout = null;
              result = func.apply(context, args);
            };
            return function() {
              var now = /* @__PURE__ */ new Date(), remaining = wait - (now - previous);
              context = this;
              args = arguments;
              if (remaining <= 0) {
                clearTimeout(timeout);
                timeout = null;
                previous = now;
                result = func.apply(context, args);
              } else if (!timeout) {
                timeout = setTimeout(later, remaining);
              }
              return result;
            };
          },
          stringify: function(val) {
            return _.isString(val) ? val : JSON.stringify(val);
          },
          guid: function() {
            function _p8(s) {
              var p = (Math.random().toString(16) + "000000000").substr(2, 8);
              return s ? "-" + p.substr(0, 4) + "-" + p.substr(4, 4) : p;
            }
            return "tt-" + _p8() + _p8(true) + _p8(true) + _p8();
          },
          noop: function() {
          }
        };
      })();
      var VERSION = "1.3.3";
      var tokenizers = (function() {
        return {
          nonword,
          whitespace,
          ngram,
          obj: {
            nonword: getObjTokenizer(nonword),
            whitespace: getObjTokenizer(whitespace),
            ngram: getObjTokenizer(ngram)
          }
        };
        function whitespace(str) {
          str = _.toStr(str);
          return str ? str.split(/\s+/) : [];
        }
        function nonword(str) {
          str = _.toStr(str);
          return str ? str.split(/\W+/) : [];
        }
        function ngram(str) {
          str = _.toStr(str);
          var tokens = [], word = "";
          _.each(str.split(""), function(char) {
            if (char.match(/\s+/)) {
              word = "";
            } else {
              tokens.push(word + char);
              word += char;
            }
          });
          return tokens;
        }
        function getObjTokenizer(tokenizer) {
          return function setKey(keys) {
            keys = _.isArray(keys) ? keys : [].slice.call(arguments, 0);
            return function tokenize(o) {
              var tokens = [];
              _.each(keys, function(k) {
                tokens = tokens.concat(tokenizer(_.toStr(o[k])));
              });
              return tokens;
            };
          };
        }
      })();
      var LruCache = (function() {
        function LruCache2(maxSize) {
          this.maxSize = _.isNumber(maxSize) ? maxSize : 100;
          this.reset();
          if (this.maxSize <= 0) {
            this.set = this.get = $2.noop;
          }
        }
        _.mixin(LruCache2.prototype, {
          set: function set(key, val) {
            var tailItem = this.list.tail, node;
            if (this.size >= this.maxSize) {
              this.list.remove(tailItem);
              delete this.hash[tailItem.key];
              this.size--;
            }
            if (node = this.hash[key]) {
              node.val = val;
              this.list.moveToFront(node);
            } else {
              node = new Node(key, val);
              this.list.add(node);
              this.hash[key] = node;
              this.size++;
            }
          },
          get: function get(key) {
            var node = this.hash[key];
            if (node) {
              this.list.moveToFront(node);
              return node.val;
            }
          },
          reset: function reset() {
            this.size = 0;
            this.hash = {};
            this.list = new List();
          }
        });
        function List() {
          this.head = this.tail = null;
        }
        _.mixin(List.prototype, {
          add: function add(node) {
            if (this.head) {
              node.next = this.head;
              this.head.prev = node;
            }
            this.head = node;
            this.tail = this.tail || node;
          },
          remove: function remove(node) {
            node.prev ? node.prev.next = node.next : this.head = node.next;
            node.next ? node.next.prev = node.prev : this.tail = node.prev;
          },
          moveToFront: function(node) {
            this.remove(node);
            this.add(node);
          }
        });
        function Node(key, val) {
          this.key = key;
          this.val = val;
          this.prev = this.next = null;
        }
        return LruCache2;
      })();
      var PersistentStorage = (function() {
        var LOCAL_STORAGE;
        try {
          LOCAL_STORAGE = window.localStorage;
          LOCAL_STORAGE.setItem("~~~", "!");
          LOCAL_STORAGE.removeItem("~~~");
        } catch (err) {
          LOCAL_STORAGE = null;
        }
        function PersistentStorage2(namespace, override) {
          this.prefix = ["__", namespace, "__"].join("");
          this.ttlKey = "__ttl__";
          this.keyMatcher = new RegExp("^" + _.escapeRegExChars(this.prefix));
          this.ls = override || LOCAL_STORAGE;
          !this.ls && this._noop();
        }
        _.mixin(PersistentStorage2.prototype, {
          _prefix: function(key) {
            return this.prefix + key;
          },
          _ttlKey: function(key) {
            return this._prefix(key) + this.ttlKey;
          },
          _noop: function() {
            this.get = this.set = this.remove = this.clear = this.isExpired = _.noop;
          },
          _safeSet: function(key, val) {
            try {
              this.ls.setItem(key, val);
            } catch (err) {
              if (err.name === "QuotaExceededError") {
                this.clear();
                this._noop();
              }
            }
          },
          get: function(key) {
            if (this.isExpired(key)) {
              this.remove(key);
            }
            return decode(this.ls.getItem(this._prefix(key)));
          },
          set: function(key, val, ttl) {
            if (_.isNumber(ttl)) {
              this._safeSet(this._ttlKey(key), encode(now() + ttl));
            } else {
              this.ls.removeItem(this._ttlKey(key));
            }
            return this._safeSet(this._prefix(key), encode(val));
          },
          remove: function(key) {
            this.ls.removeItem(this._ttlKey(key));
            this.ls.removeItem(this._prefix(key));
            return this;
          },
          clear: function() {
            var i, keys = gatherMatchingKeys(this.keyMatcher);
            for (i = keys.length; i--; ) {
              this.remove(keys[i]);
            }
            return this;
          },
          isExpired: function(key) {
            var ttl = decode(this.ls.getItem(this._ttlKey(key)));
            return _.isNumber(ttl) && now() > ttl ? true : false;
          }
        });
        return PersistentStorage2;
        function now() {
          return (/* @__PURE__ */ new Date()).getTime();
        }
        function encode(val) {
          return JSON.stringify(_.isUndefined(val) ? null : val);
        }
        function decode(val) {
          return $2.parseJSON(val);
        }
        function gatherMatchingKeys(keyMatcher) {
          var i, key, keys = [], len = LOCAL_STORAGE.length;
          for (i = 0; i < len; i++) {
            if ((key = LOCAL_STORAGE.key(i)).match(keyMatcher)) {
              keys.push(key.replace(keyMatcher, ""));
            }
          }
          return keys;
        }
      })();
      var Transport = (function() {
        var pendingRequestsCount = 0, pendingRequests = {}, sharedCache = new LruCache(10);
        function Transport2(o) {
          o = o || {};
          this.maxPendingRequests = o.maxPendingRequests || 6;
          this.cancelled = false;
          this.lastReq = null;
          this._send = o.transport;
          this._get = o.limiter ? o.limiter(this._get) : this._get;
          this._cache = o.cache === false ? new LruCache(0) : sharedCache;
        }
        Transport2.setMaxPendingRequests = function setMaxPendingRequests(num) {
          this.maxPendingRequests = num;
        };
        Transport2.resetCache = function resetCache() {
          sharedCache.reset();
        };
        _.mixin(Transport2.prototype, {
          _fingerprint: function fingerprint(o) {
            o = o || {};
            return o.url + o.type + $2.param(o.data || {});
          },
          _get: function(o, cb) {
            var that = this, fingerprint, jqXhr;
            fingerprint = this._fingerprint(o);
            if (this.cancelled || fingerprint !== this.lastReq) {
              return;
            }
            if (jqXhr = pendingRequests[fingerprint]) {
              jqXhr.done(done).fail(fail);
            } else if (pendingRequestsCount < this.maxPendingRequests) {
              pendingRequestsCount++;
              pendingRequests[fingerprint] = this._send(o).done(done).fail(fail).always(always);
            } else {
              this.onDeckRequestArgs = [].slice.call(arguments, 0);
            }
            function done(resp) {
              cb(null, resp);
              that._cache.set(fingerprint, resp);
            }
            function fail() {
              cb(true);
            }
            function always() {
              pendingRequestsCount--;
              delete pendingRequests[fingerprint];
              if (that.onDeckRequestArgs) {
                that._get.apply(that, that.onDeckRequestArgs);
                that.onDeckRequestArgs = null;
              }
            }
          },
          get: function(o, cb) {
            var resp, fingerprint;
            cb = cb || $2.noop;
            o = _.isString(o) ? {
              url: o
            } : o || {};
            fingerprint = this._fingerprint(o);
            this.cancelled = false;
            this.lastReq = fingerprint;
            if (resp = this._cache.get(fingerprint)) {
              cb(null, resp);
            } else {
              this._get(o, cb);
            }
          },
          cancel: function() {
            this.cancelled = true;
          }
        });
        return Transport2;
      })();
      var SearchIndex = window.SearchIndex = (function() {
        var CHILDREN = "c", IDS = "i";
        function SearchIndex2(o) {
          o = o || {};
          if (!o.datumTokenizer || !o.queryTokenizer) {
            $2.error("datumTokenizer and queryTokenizer are both required");
          }
          this.identify = o.identify || _.stringify;
          this.datumTokenizer = o.datumTokenizer;
          this.queryTokenizer = o.queryTokenizer;
          this.matchAnyQueryToken = o.matchAnyQueryToken;
          this.reset();
        }
        _.mixin(SearchIndex2.prototype, {
          bootstrap: function bootstrap(o) {
            this.datums = o.datums;
            this.trie = o.trie;
          },
          add: function(data) {
            var that = this;
            data = _.isArray(data) ? data : [data];
            _.each(data, function(datum) {
              var id, tokens;
              that.datums[id = that.identify(datum)] = datum;
              tokens = normalizeTokens(that.datumTokenizer(datum));
              _.each(tokens, function(token) {
                var node, chars, ch;
                node = that.trie;
                chars = token.split("");
                while (ch = chars.shift()) {
                  node = node[CHILDREN][ch] || (node[CHILDREN][ch] = newNode());
                  node[IDS].push(id);
                }
              });
            });
          },
          get: function get(ids) {
            var that = this;
            return _.map(ids, function(id) {
              return that.datums[id];
            });
          },
          search: function search(query) {
            var that = this, tokens, matches;
            tokens = normalizeTokens(this.queryTokenizer(query));
            _.each(tokens, function(token) {
              var node, chars, ch, ids;
              if (matches && matches.length === 0 && !that.matchAnyQueryToken) {
                return false;
              }
              node = that.trie;
              chars = token.split("");
              while (node && (ch = chars.shift())) {
                node = node[CHILDREN][ch];
              }
              if (node && chars.length === 0) {
                ids = node[IDS].slice(0);
                matches = matches ? getIntersection(matches, ids) : ids;
              } else {
                if (!that.matchAnyQueryToken) {
                  matches = [];
                  return false;
                }
              }
            });
            return matches ? _.map(unique(matches), function(id) {
              return that.datums[id];
            }) : [];
          },
          all: function all() {
            var values = [];
            for (var key in this.datums) {
              values.push(this.datums[key]);
            }
            return values;
          },
          reset: function reset() {
            this.datums = {};
            this.trie = newNode();
          },
          serialize: function serialize() {
            return {
              datums: this.datums,
              trie: this.trie
            };
          }
        });
        return SearchIndex2;
        function normalizeTokens(tokens) {
          tokens = _.filter(tokens, function(token) {
            return !!token;
          });
          tokens = _.map(tokens, function(token) {
            return token.toLowerCase();
          });
          return tokens;
        }
        function newNode() {
          var node = {};
          node[IDS] = [];
          node[CHILDREN] = {};
          return node;
        }
        function unique(array) {
          var seen = {}, uniques = [];
          for (var i = 0, len = array.length; i < len; i++) {
            if (!seen[array[i]]) {
              seen[array[i]] = true;
              uniques.push(array[i]);
            }
          }
          return uniques;
        }
        function getIntersection(arrayA, arrayB) {
          var ai = 0, bi = 0, intersection = [];
          arrayA = arrayA.sort();
          arrayB = arrayB.sort();
          var lenArrayA = arrayA.length, lenArrayB = arrayB.length;
          while (ai < lenArrayA && bi < lenArrayB) {
            if (arrayA[ai] < arrayB[bi]) {
              ai++;
            } else if (arrayA[ai] > arrayB[bi]) {
              bi++;
            } else {
              intersection.push(arrayA[ai]);
              ai++;
              bi++;
            }
          }
          return intersection;
        }
      })();
      var Prefetch = (function() {
        var keys;
        keys = {
          data: "data",
          protocol: "protocol",
          thumbprint: "thumbprint"
        };
        function Prefetch2(o) {
          this.url = o.url;
          this.ttl = o.ttl;
          this.cache = o.cache;
          this.prepare = o.prepare;
          this.transform = o.transform;
          this.transport = o.transport;
          this.thumbprint = o.thumbprint;
          this.storage = new PersistentStorage(o.cacheKey);
        }
        _.mixin(Prefetch2.prototype, {
          _settings: function settings() {
            return {
              url: this.url,
              type: "GET",
              dataType: "json"
            };
          },
          store: function store(data) {
            if (!this.cache) {
              return;
            }
            this.storage.set(keys.data, data, this.ttl);
            this.storage.set(keys.protocol, location.protocol, this.ttl);
            this.storage.set(keys.thumbprint, this.thumbprint, this.ttl);
          },
          fromCache: function fromCache() {
            var stored = {}, isExpired;
            if (!this.cache) {
              return null;
            }
            stored.data = this.storage.get(keys.data);
            stored.protocol = this.storage.get(keys.protocol);
            stored.thumbprint = this.storage.get(keys.thumbprint);
            isExpired = stored.thumbprint !== this.thumbprint || stored.protocol !== location.protocol;
            return stored.data && !isExpired ? stored.data : null;
          },
          fromNetwork: function(cb) {
            var that = this, settings;
            if (!cb) {
              return;
            }
            settings = this.prepare(this._settings());
            this.transport(settings).fail(onError).done(onResponse);
            function onError() {
              cb(true);
            }
            function onResponse(resp) {
              cb(null, that.transform(resp));
            }
          },
          clear: function clear() {
            this.storage.clear();
            return this;
          }
        });
        return Prefetch2;
      })();
      var Remote = (function() {
        function Remote2(o) {
          this.url = o.url;
          this.prepare = o.prepare;
          this.transform = o.transform;
          this.indexResponse = o.indexResponse;
          this.transport = new Transport({
            cache: o.cache,
            limiter: o.limiter,
            transport: o.transport,
            maxPendingRequests: o.maxPendingRequests
          });
        }
        _.mixin(Remote2.prototype, {
          _settings: function settings() {
            return {
              url: this.url,
              type: "GET",
              dataType: "json"
            };
          },
          get: function get(query, cb) {
            var that = this, settings;
            if (!cb) {
              return;
            }
            query = query || "";
            settings = this.prepare(query, this._settings());
            return this.transport.get(settings, onResponse);
            function onResponse(err, resp) {
              err ? cb([]) : cb(that.transform(resp));
            }
          },
          cancelLastRequest: function cancelLastRequest() {
            this.transport.cancel();
          }
        });
        return Remote2;
      })();
      var oParser = /* @__PURE__ */ (function() {
        return function parse(o) {
          var defaults, sorter;
          defaults = {
            initialize: true,
            identify: _.stringify,
            datumTokenizer: null,
            queryTokenizer: null,
            matchAnyQueryToken: false,
            sufficient: 5,
            indexRemote: false,
            sorter: null,
            local: [],
            prefetch: null,
            remote: null
          };
          o = _.mixin(defaults, o || {});
          !o.datumTokenizer && $2.error("datumTokenizer is required");
          !o.queryTokenizer && $2.error("queryTokenizer is required");
          sorter = o.sorter;
          o.sorter = sorter ? function(x) {
            return x.sort(sorter);
          } : _.identity;
          o.local = _.isFunction(o.local) ? o.local() : o.local;
          o.prefetch = parsePrefetch(o.prefetch);
          o.remote = parseRemote(o.remote);
          return o;
        };
        function parsePrefetch(o) {
          var defaults;
          if (!o) {
            return null;
          }
          defaults = {
            url: null,
            ttl: 24 * 60 * 60 * 1e3,
            cache: true,
            cacheKey: null,
            thumbprint: "",
            prepare: _.identity,
            transform: _.identity,
            transport: null
          };
          o = _.isString(o) ? {
            url: o
          } : o;
          o = _.mixin(defaults, o);
          !o.url && $2.error("prefetch requires url to be set");
          o.transform = o.filter || o.transform;
          o.cacheKey = o.cacheKey || o.url;
          o.thumbprint = VERSION + o.thumbprint;
          o.transport = o.transport ? callbackToDeferred(o.transport) : $2.ajax;
          return o;
        }
        function parseRemote(o) {
          var defaults;
          if (!o) {
            return;
          }
          defaults = {
            url: null,
            cache: true,
            prepare: null,
            replace: null,
            wildcard: null,
            limiter: null,
            rateLimitBy: "debounce",
            rateLimitWait: 300,
            transform: _.identity,
            transport: null
          };
          o = _.isString(o) ? {
            url: o
          } : o;
          o = _.mixin(defaults, o);
          !o.url && $2.error("remote requires url to be set");
          o.transform = o.filter || o.transform;
          o.prepare = toRemotePrepare(o);
          o.limiter = toLimiter(o);
          o.transport = o.transport ? callbackToDeferred(o.transport) : $2.ajax;
          delete o.replace;
          delete o.wildcard;
          delete o.rateLimitBy;
          delete o.rateLimitWait;
          return o;
        }
        function toRemotePrepare(o) {
          var prepare, replace, wildcard;
          prepare = o.prepare;
          replace = o.replace;
          wildcard = o.wildcard;
          if (prepare) {
            return prepare;
          }
          if (replace) {
            prepare = prepareByReplace;
          } else if (o.wildcard) {
            prepare = prepareByWildcard;
          } else {
            prepare = identityPrepare;
          }
          return prepare;
          function prepareByReplace(query, settings) {
            settings.url = replace(settings.url, query);
            return settings;
          }
          function prepareByWildcard(query, settings) {
            settings.url = settings.url.replace(wildcard, encodeURIComponent(query));
            return settings;
          }
          function identityPrepare(query, settings) {
            return settings;
          }
        }
        function toLimiter(o) {
          var limiter, method, wait;
          limiter = o.limiter;
          method = o.rateLimitBy;
          wait = o.rateLimitWait;
          if (!limiter) {
            limiter = /^throttle$/i.test(method) ? throttle(wait) : debounce(wait);
          }
          return limiter;
          function debounce(wait2) {
            return function debounce2(fn) {
              return _.debounce(fn, wait2);
            };
          }
          function throttle(wait2) {
            return function throttle2(fn) {
              return _.throttle(fn, wait2);
            };
          }
        }
        function callbackToDeferred(fn) {
          return function wrapper(o) {
            var deferred = $2.Deferred();
            fn(o, onSuccess, onError);
            return deferred;
            function onSuccess(resp) {
              _.defer(function() {
                deferred.resolve(resp);
              });
            }
            function onError(err) {
              _.defer(function() {
                deferred.reject(err);
              });
            }
          };
        }
      })();
      var Bloodhound2 = (function() {
        var old;
        old = window && window.Bloodhound;
        function Bloodhound3(o) {
          o = oParser(o);
          this.sorter = o.sorter;
          this.identify = o.identify;
          this.sufficient = o.sufficient;
          this.indexRemote = o.indexRemote;
          this.local = o.local;
          this.remote = o.remote ? new Remote(o.remote) : null;
          this.prefetch = o.prefetch ? new Prefetch(o.prefetch) : null;
          this.index = new SearchIndex({
            identify: this.identify,
            datumTokenizer: o.datumTokenizer,
            queryTokenizer: o.queryTokenizer
          });
          o.initialize !== false && this.initialize();
        }
        Bloodhound3.noConflict = function noConflict() {
          window && (window.Bloodhound = old);
          return Bloodhound3;
        };
        Bloodhound3.tokenizers = tokenizers;
        _.mixin(Bloodhound3.prototype, {
          __ttAdapter: function ttAdapter() {
            var that = this;
            return this.remote ? withAsync : withoutAsync;
            function withAsync(query, sync, async) {
              return that.search(query, sync, async);
            }
            function withoutAsync(query, sync) {
              return that.search(query, sync);
            }
          },
          _loadPrefetch: function loadPrefetch() {
            var that = this, deferred, serialized;
            deferred = $2.Deferred();
            if (!this.prefetch) {
              deferred.resolve();
            } else if (serialized = this.prefetch.fromCache()) {
              this.index.bootstrap(serialized);
              deferred.resolve();
            } else {
              this.prefetch.fromNetwork(done);
            }
            return deferred.promise();
            function done(err, data) {
              if (err) {
                return deferred.reject();
              }
              that.add(data);
              that.prefetch.store(that.index.serialize());
              deferred.resolve();
            }
          },
          _initialize: function initialize() {
            var that = this;
            this.clear();
            (this.initPromise = this._loadPrefetch()).done(addLocalToIndex);
            return this.initPromise;
            function addLocalToIndex() {
              that.add(that.local);
            }
          },
          initialize: function initialize(force) {
            return !this.initPromise || force ? this._initialize() : this.initPromise;
          },
          add: function add(data) {
            this.index.add(data);
            return this;
          },
          get: function get(ids) {
            ids = _.isArray(ids) ? ids : [].slice.call(arguments);
            return this.index.get(ids);
          },
          search: function search(query, sync, async) {
            var that = this, local;
            sync = sync || _.noop;
            async = async || _.noop;
            local = this.sorter(this.index.search(query));
            sync(this.remote ? local.slice() : local);
            if (this.remote && local.length < this.sufficient) {
              this.remote.get(query, processRemote);
            } else if (this.remote) {
              this.remote.cancelLastRequest();
            }
            return this;
            function processRemote(remote) {
              var nonDuplicates = [];
              _.each(remote, function(r) {
                !_.some(local, function(l) {
                  return that.identify(r) === that.identify(l);
                }) && nonDuplicates.push(r);
              });
              that.indexRemote && that.add(nonDuplicates);
              async(nonDuplicates);
            }
          },
          all: function all() {
            return this.index.all();
          },
          clear: function clear() {
            this.index.reset();
            return this;
          },
          clearPrefetchCache: function clearPrefetchCache() {
            this.prefetch && this.prefetch.clear();
            return this;
          },
          clearRemoteCache: function clearRemoteCache() {
            Transport.resetCache();
            return this;
          },
          ttAdapter: function ttAdapter() {
            return this.__ttAdapter();
          }
        });
        return Bloodhound3;
      })();
      return Bloodhound2;
    });
  })(bloodhound$1);
  return bloodhound$1.exports;
}
var bloodhoundExports = requireBloodhound();
const Bloodhound$1 = /* @__PURE__ */ getDefaultExportFromCjs(bloodhoundExports);
window.Popper = Popper;
window.$ = window.jQuery = $$1;
config.autoReplaceSvg = "nest";
library.add(_iconsCache, _iconsCache$1, _iconsCache$2);
dom.watch();
window.FontAwesomeDom = dom;
window.Sortable = Sortable$1;
window.Bloodhound = Bloodhound$1;
requireBootstrapDatepicker();
window.this_url = window.this_url || "";
var profiles = (function($2, undefined$1) {
  var this_url = window.this_url;
  let config2 = {
    datepicker: {
      year: {
        autoclose: true,
        assumeNearbyYear: true,
        clearBtn: true,
        forceParse: false,
        keepEmptyValues: true,
        minViewMode: 2,
        format: "yyyy"
      },
      month: {
        autoclose: true,
        assumeNearbyYear: true,
        clearBtn: true,
        forceParse: false,
        keepEmptyValues: true,
        minViewMode: 1,
        format: "yyyy/mm"
      }
    }
  };
  var _input_is_empty = function(input) {
    if (!(input instanceof HTMLInputElement)) {
      return true;
    }
    switch (input.getAttribute("type")) {
      case "file":
        return input.files.length == 0;
      case "checkbox":
        return !input.checked;
      // @todo: other input types
      default:
        return input.value == null || input.value == "";
    }
  };
  const preview_selected_image = function(event) {
    const file_input = event.target;
    let id = file_input.id.replace(/\[/g, "\\[").replace(/\]/g, "\\]");
    $2(`label[for="${id}"]`).addClass("active").text(file_input.files[0].name);
    $2(`#${id}-img`).attr("src", window.URL.createObjectURL(file_input.files[0]));
    $2(file_input).siblings(".invalid-feedback").removeClass("d-block");
  };
  const reindex_sorted_list = (list_items) => {
    for (let i = 0; i < list_items.length; i++) {
      if (list_items[i].dataset.rowId) {
        list_items[i].dataset.rowId = i;
      }
      const search_for = /\[\d+\]/g;
      const replace_with = `[${i}]`;
      for (const field of list_items[i].querySelectorAll("[name]")) {
        field.name = field.name.replace(search_for, replace_with);
      }
      for (const field of list_items[i].querySelectorAll("[id]")) {
        field.id = field.id.replace(search_for, replace_with);
      }
      for (const field of list_items[i].querySelectorAll("label[for]")) {
        field.htmlFor = field.htmlFor.replace(search_for, replace_with);
      }
    }
  };
  const on_list_updated = (el, actions) => {
    if (typeof actions !== "string") {
      return;
    }
    for (const action of actions.split(",").map((i) => i.trim())) {
      if (action === "reindex") {
        reindex_sorted_list(el.children);
      }
      if (action === "reset-next-row-id") {
        el.dataset.nextRowId = String(Number(el.dataset.nextRowId) > 0 ? el.children.length : -1);
      }
    }
  };
  const wait_when_submitting = function(form) {
    elem = form.querySelector("button[type=submit]");
    elem_text = elem.innerHTML.replace(/<i[^>]*>(.*?)<\/i>/g, "");
    elem.innerHTML = `<i class="fas fa-spinner fa-spin fa-fw"></i> ${elem_text}`;
    elem.classList.add("btn-primary", "disabled");
    elem.classList.remove("btn-light", "btn-dark", "btn-secondary", "btn-info", "btn-success", "btn-warning", "btn-danger");
    elem.disabled = true;
  };
  const add_row = function(event) {
    var _a, _b, _c, _d, _e, _f, _g, _h, _i, _j;
    const options = event.target.dataset;
    const item_template = document.querySelector(options.template ?? "form .record");
    const item_container = document.querySelector(options.insertInto) ?? item_template.parentElement;
    if (item_template) {
      const old_id = item_template.dataset.rowId;
      let new_id;
      if (Number(item_container.dataset.nextRowId) >= 0) {
        new_id = String(item_container.dataset.nextRowId++);
      } else {
        new_id = String(item_container.dataset.nextRowId--);
      }
      let new_item = item_template.cloneNode(true);
      new_item.dataset.rowId = new_id;
      (_a = new_item.querySelectorAll('input:not([type="button"]), textarea, select')) == null ? void 0 : _a.forEach((el) => {
        el.id = el.id.replace(old_id, new_id);
        el.setAttribute("name", el.name.replace(old_id, new_id));
        el.setAttribute("value", "");
        el.value = "";
      });
      (_b = new_item.querySelectorAll(`input[type="hidden"][name$="[id]"]`)) == null ? void 0 : _b.forEach((el) => {
        el.id = el.name;
        el.value = new_id;
      });
      (_c = new_item.querySelectorAll("label")) == null ? void 0 : _c.forEach((el) => {
        var _a2;
        el.setAttribute("for", (_a2 = el.getAttribute("for")) == null ? void 0 : _a2.replace(old_id, new_id));
      });
      (_d = new_item.querySelectorAll("trix-editor")) == null ? void 0 : _d.forEach((el) => {
        el.setAttribute("input", el.getAttribute("input").replace(old_id, new_id));
      });
      (_e = new_item.querySelectorAll("img")) == null ? void 0 : _e.forEach((el) => {
        el.id = el.id.replace(old_id, new_id);
        el.src = "";
      });
      (_f = new_item.querySelectorAll(".custom-file-label")) == null ? void 0 : _f.forEach((el) => {
        el.id = el.id.replace(old_id, new_id);
        el.innerHTML = "Select an image";
      });
      (_g = new_item.querySelectorAll(".actions .trash")) == null ? void 0 : _g.forEach((el) => {
        $2(el).on("click", () => clear_row(el));
      });
      (_h = new_item.querySelectorAll('input[type="file"][accept^="image"]')) == null ? void 0 : _h.forEach((el) => {
        $2(el).on("change", (event2) => preview_selected_image(event2));
      });
      (_i = new_item.querySelectorAll(".datepicker.year")) == null ? void 0 : _i.forEach((el) => {
        $2(el).datepicker(config2.datepicker.year);
      });
      (_j = new_item.querySelectorAll(".datepicker.month")) == null ? void 0 : _j.forEach((el) => {
        $2(el).datepicker(config2.datepicker.month);
      });
      $2(new_item).hide();
      if ("insertType" in options && options.insertType === "prepend") {
        item_container.prepend(new_item);
      } else {
        item_container.append(new_item);
      }
      $2(new_item).slideDown();
    }
  };
  var clear_row = function(elem2) {
    parent_elem = $2(elem2).parent().parent();
    parent_elem.slideUp().find("input[type=text], input[type=url], input[type=month], input.clearable, textarea, select").val("");
    const list_container = parent_elem[0].parentElement;
    if (elem2.dataset.remove === "true") {
      parent_elem.remove();
    }
    if (elem2.dataset.onRemove) {
      on_list_updated(list_container, elem2.dataset.onRemove);
    }
  };
  var toggle_class = function(evt) {
    var $this = $2(this);
    var $target = $this.data("target") ? $2($this.data("target")) : $this;
    $target.toggleClass($this.data("toggle-class"));
  };
  var replace_icon = function(evt) {
    if (this.dataset.inputrequired && _input_is_empty(document.querySelector(this.dataset.inputrequired))) {
      return;
    }
    var target = this.dataset.target ? document.querySelector(this.dataset.target) : this;
    target.querySelector("[data-fa-i2svg]").className = this.dataset.newicon;
    if (this.getAttribute("type") === "submit") {
      $2(this).closest("form").submit();
    }
  };
  let toast = (message, type) => {
    let flash_container = document.querySelector(".flash-container");
    if (!flash_container) {
      flash_container = document.createElement("div");
      flash_container.classList = "flash-container";
      document.body.appendChild(flash_container);
    }
    let flash_message = document.createElement("div");
    flash_message.classList = "flash-message alert-dismissable alert-" + (type || "success");
    flash_message.setAttribute("role", "alert");
    flash_message.setAttribute("aria-live", "assertive");
    flash_message.setAttribute("aria-atomic", "true");
    flash_message.innerHTML = message;
    flash_container.appendChild(flash_message);
    flash_message.addEventListener("click", (e) => {
      e.target.style.display = "none";
    });
    $2(flash_message).animate({ opacity: 0 }, {
      duration: 5e3,
      complete: () => flash_message.style.display = "none"
    });
  };
  let deobfuscate_mail = (obfuscated_mail_address) => {
    return obfuscated_mail_address.replace(/[a-z]/gi, (c) => String.fromCharCode((c <= "Z" ? 90 : 122) >= (c = c.charCodeAt(0) + 13) ? c : c - 26)).replace("☄️", "@").split("@").reverse().join("@");
  };
  let deobfuscate_mail_links = (i, el) => {
    el.innerText = deobfuscate_mail(el.id);
    el.href = "mailto:" + el.innerText;
  };
  var toggle_show = function(evt) {
    var $this = $2(this);
    var target = $this.data("toggle-target") || this;
    var toggle_value = $this.data("toggle-value") || true;
    var current_value = $this.val();
    if ($this.is("input[type=radio], input[type=checkbox]")) {
      current_value = $this.prop("checked");
    }
    if (current_value == toggle_value) {
      $2(target).slideDown(200).find(":input").prop("disabled", false);
    } else {
      $2(target).slideUp(200).find(":input").prop("disabled", true);
    }
  };
  let registerProfilePicker = (selector, api) => {
    if (typeof api === "undefined") api = this_url + "/api/v1?with_data=1&data_type=information&public=1";
    let $select = $2(selector);
    if ($select.length === 0) return;
    if ($select.data("school")) {
      api += "&from_school=" + $select.data("school");
    }
    if ($select.data("accepting-undergrad")) {
      api += "&accepting_undergrad=" + $select.data("accepting-undergrad");
    }
    let profileSearch = new Bloodhound({
      datumTokenizer: (profiles2) => Bloodhound.tokenizers.whitespace(profiles2.value),
      queryTokenizer: Bloodhound.tokenizers.whitespace,
      limit: 50,
      remote: {
        url: api + "&search_names=%QUERY",
        wildcard: "%QUERY",
        transform: (response) => response.profile
      }
    });
    $select.tagsinput({
      typeaheadjs: {
        name: "profileslist",
        displayKey: "full_name",
        limit: 75,
        source: profileSearch.ttAdapter(),
        templates: {
          suggestion: (profile) => "<p><strong>" + profile.full_name + "</strong>, <em>" + (profile.information[0].data.title || "") + "</em></p>"
        }
      },
      freeInput: false,
      itemValue: (profile) => profile.id,
      itemText: (profile) => profile.full_name,
      onTagExists: (item, $tag) => $tag.css({ opacity: 0 }).animate({ opacity: 1 }, 500),
      // blink once
      afterSelect: () => $select.tagsinput("input").val("")
    });
    $select.find("option").each((i, option) => $select.tagsinput("add", {
      "id": option.value,
      "full_name": option.text
    }));
    $select.tagsinput("input").on("typeahead:asyncrequest", function() {
      $2(this).closest(".twitter-typeahead").css("background", "no-repeat center url(" + this_url + "/img/ajax-loader.gif)");
    }).on("typeahead:asyncreceive typeahead:asynccancel", function() {
      $2(this).closest(".twitter-typeahead").css("background-image", "none");
    });
  };
  let registerProfilePickers = () => {
    $2(".profile-picker").each((i, picker) => {
      if (picker.querySelector("select")) {
        registerProfilePicker("#" + picker.querySelector("select").id.replace("[]", "\\[\\]"));
      }
    });
  };
  var registerTagEditors = function() {
    $2(".tags-editor").each(function(i, editor) {
      registerTagPicker("#" + editor.querySelector("select").id.replace("[]", "\\[\\]"));
    });
  };
  var registerTagPicker = function(selector, api) {
    if (typeof api === "undefined") api = this_url + "/tags/api";
    var $select = $2(selector);
    if ($select.length === 0) return;
    var tagSearch = new Bloodhound({
      datumTokenizer: Bloodhound.tokenizers.obj.whitespace("tag"),
      queryTokenizer: Bloodhound.tokenizers.whitespace,
      limit: 50,
      remote: {
        url: api + "/search?name=%QUERY",
        wildcard: "%QUERY"
      }
    });
    $select.tagsinput({
      typeaheadjs: {
        name: "taglist",
        displayKey: "name".en,
        limit: 75,
        source: tagSearch.ttAdapter()
      },
      freeInput: true,
      onTagExists: (item, $tag) => $tag.css({ opacity: 0 }).animate({ opacity: 1 }, 500),
      // blink once
      afterSelect: () => $select.tagsinput("input").val("")
    });
    $select.tagsinput("input").on("typeahead:asyncrequest", function() {
      $2(this).closest(".twitter-typeahead").css("background", "no-repeat center url(" + this_url + "/img/ajax-loader.gif)");
    }).on("typeahead:asyncreceive typeahead:asynccancel", function() {
      $2(this).closest(".twitter-typeahead").css("background-image", "none");
    });
    $select.closest(".modal-content").find(".tagsInsertBtn").click(function(event) {
      postTags($select);
    });
  };
  var postTags = function($select) {
    var tags = $select.tagsinput("items");
    var formData = new FormData();
    formData.append("_token", $select.data("token"));
    formData.append("model", $select.data("model"));
    formData.append("id", $select.data("model-id"));
    for (var i = 0; i < tags.length; i++) {
      formData.append("tags[]", tags[i]);
    }
    $2.ajax({
      method: "POST",
      url: $select.data("url"),
      dataType: "json",
      processData: false,
      contentType: false,
      data: formData,
      success: function(data, textStatus) {
        $2("#" + $select.data("model-name") + "_tags_editor").modal("hide");
        $2("#" + $select.data("model-name") + "_current_tags").html(data.view);
      },
      error: function(xHr, textStatus, errorThrown) {
        toast(`Error updating tags: ${errorThrown}`, "danger");
      }
    });
  };
  const registerVideoControls = function() {
    const play_pause_buttons = document.querySelectorAll("button.video-control.play-pause");
    const prefers_reduced_motion = window.matchMedia(`(prefers-reduced-motion: reduce)`);
    play_pause_buttons.forEach((bt) => bt.addEventListener("click", (evt) => {
      const button = evt.currentTarget;
      const video = document.getElementById(button == null ? void 0 : button.getAttribute("aria-controls"));
      if (video instanceof HTMLVideoElement && button instanceof HTMLButtonElement) {
        toggleVideoPlay(video, button);
      }
    }));
    if (prefers_reduced_motion.matches) {
      play_pause_buttons.forEach((bt) => bt.click());
    }
  };
  const toggleVideoPlay = function(vid, btn) {
    const icon = btn.querySelector("[data-fa-i2svg],.fas");
    if (vid.paused) {
      vid.play();
      btn.ariaPressed = "true";
      icon.className = "fas fa-pause";
    } else {
      vid.pause();
      btn.ariaPressed = "false";
      icon.className = "fas fa-play";
    }
  };
  return {
    add_row,
    clear_row,
    config: config2,
    deobfuscate_mail_links,
    on_list_updated,
    preview_selected_image,
    replace_icon,
    registerTagEditors,
    registerProfilePickers,
    toast,
    toggle_class,
    toggle_show,
    wait_when_submitting,
    registerVideoControls
  };
})(jQuery);
window.profiles = profiles;
$(function() {
  $(".datepicker.year").datepicker(profiles.config.datepicker.year);
  $(".datepicker.month").datepicker(profiles.config.datepicker.month);
  $('input[type="file"]').on("change", (e) => profiles.preview_selected_image(e));
  if ($(".sortable").length > 0) {
    Sortable.create($(".sortable")[0], {
      handle: ".handle",
      scroll: true,
      scrollSpeed: 50,
      ghostClass: "sortable-ghost",
      onUpdate: (evt) => profiles.on_list_updated(evt.target, evt.target.dataset.onsort ?? "")
    });
  }
  $(".actions .trash").on("click", function(e) {
    profiles.clear_row(this);
  });
  $('[data-toggle="add_row"]').on("click", (e) => profiles.add_row(e));
  $(".back.btn").on("click", function(e) {
    window.history.go(-1);
  });
  $(".flash-message").on("click", function() {
    $(this).hide();
  }).animate({
    opacity: 0
  }, 5e3);
  $('a[href^="#"]:not([href="#"]):not([data-scrollto-anchor="false"])').on("click", function(event) {
    var target = $($(this).attr("href"));
    if (target.length) {
      event.preventDefault();
      $("html, body").animate({
        scrollTop: target.offset().top
      }, 1e3);
    }
  });
  if (typeof $.fn.tagsinput === "function" && typeof Bloodhound === "function") {
    profiles.registerTagEditors();
    profiles.registerProfilePickers();
  }
  $("[data-toggle=class]").on("click", profiles.toggle_class);
  $("[data-toggle=replace-icon]").on("click", profiles.replace_icon);
  $("[data-toggle=show]").on("change page_up", profiles.toggle_show).trigger("change");
  $("[data-evaluate=profile-eml]").each(profiles.deobfuscate_mail_links);
  $('[data-toggle="tooltip"]').tooltip();
  $('[data-toggle="popover"]').popover({
    html: true,
    content: function() {
      const content = $(this).data("popover-content");
      return typeof content === "string" && $(content).length ? $(content).html() : "";
    }
  });
  if (document.querySelectorAll(".video-cover video").length > 0 && document.querySelectorAll(".video-cover .video-control").length > 0) {
    profiles.registerVideoControls();
  }
});
if (typeof Trix === "object") {
  document.addEventListener("trix-initialize", (e) => {
    document.querySelector("trix-toolbar .trix-button-group--history-tools").remove();
    document.querySelector("trix-toolbar .trix-button-group--file-tools").remove();
  });
  document.addEventListener("trix-file-accept", (e) => {
    e.preventDefault();
    e.stopPropagation();
  });
}
if (typeof Livewire === "object") {
  if (typeof FontAwesomeDom === "object") {
    document.addEventListener("DOMContentLoaded", () => {
      Livewire.hook("message.processed", () => FontAwesomeDom.i2svg());
    });
  }
  Livewire.on("alert", (message, type) => profiles.toast(message, type));
  Livewire.onError((status, response) => {
    if (status === 403) {
      profiles.toast("⛔️ Sorry, you are not authorized to do that.", "danger");
      return false;
    }
  });
}
//# sourceMappingURL=app-CL-Yln4X.js.map
