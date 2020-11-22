/*!
 * 
 *         This file is part of the Buttonizer plugin that is downloadable through Wordpress.org,
 *         please do not redistribute this plugin or the files without any written permission of the author.
 *         
 *         If you need support, contact us at support@buttonizer.pro or visit our community website
 *         https://community.buttonizer.pro/
 *         
 *         Buttonizer is Freemium software. The free version (build) does not contain premium functionality.
 *         
 *         (C) 2017-2020 Buttonizer dev-version
 *         
 */
/*!
 * 
 *         This file is part of the Buttonizer plugin that is downloadable through Wordpress.org,
 *         please do not redistribute this plugin or the files without any written permission of the author.
 *         
 *         If you need support, contact us at support@buttonizer.pro or visit our community website
 *         https://community.buttonizer.pro/
 *         
 *         Buttonizer is Freemium software. The free version (build) does not contain premium functionality.
 *         
 *         (C) 2017-2020 Buttonizer dev-version
 *         
 */
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
/******/ 	return __webpack_require__(__webpack_require__.s = 923);
/******/ })
/************************************************************************/
/******/ ({

/***/ 102:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(231);

/***/ }),

/***/ 1296:
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ 162:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = function bind(fn, thisArg) {
  return function wrap() {
    var args = new Array(arguments.length);
    for (var i = 0; i < args.length; i++) {
      args[i] = arguments[i];
    }
    return fn.apply(thisArg, args);
  };
};


/***/ }),

/***/ 163:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(26);

function encode(val) {
  return encodeURIComponent(val).
    replace(/%40/gi, '@').
    replace(/%3A/gi, ':').
    replace(/%24/g, '$').
    replace(/%2C/gi, ',').
    replace(/%20/g, '+').
    replace(/%5B/gi, '[').
    replace(/%5D/gi, ']');
}

/**
 * Build a URL by appending params to the end
 *
 * @param {string} url The base of the url (e.g., http://www.google.com)
 * @param {object} [params] The params to be appended
 * @returns {string} The formatted url
 */
module.exports = function buildURL(url, params, paramsSerializer) {
  /*eslint no-param-reassign:0*/
  if (!params) {
    return url;
  }

  var serializedParams;
  if (paramsSerializer) {
    serializedParams = paramsSerializer(params);
  } else if (utils.isURLSearchParams(params)) {
    serializedParams = params.toString();
  } else {
    var parts = [];

    utils.forEach(params, function serialize(val, key) {
      if (val === null || typeof val === 'undefined') {
        return;
      }

      if (utils.isArray(val)) {
        key = key + '[]';
      } else {
        val = [val];
      }

      utils.forEach(val, function parseValue(v) {
        if (utils.isDate(v)) {
          v = v.toISOString();
        } else if (utils.isObject(v)) {
          v = JSON.stringify(v);
        }
        parts.push(encode(key) + '=' + encode(v));
      });
    });

    serializedParams = parts.join('&');
  }

  if (serializedParams) {
    var hashmarkIndex = url.indexOf('#');
    if (hashmarkIndex !== -1) {
      url = url.slice(0, hashmarkIndex);
    }

    url += (url.indexOf('?') === -1 ? '?' : '&') + serializedParams;
  }

  return url;
};


/***/ }),

/***/ 164:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = function isCancel(value) {
  return !!(value && value.__CANCEL__);
};


/***/ }),

/***/ 165:
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/* WEBPACK VAR INJECTION */(function(process) {

var utils = __webpack_require__(26);
var normalizeHeaderName = __webpack_require__(236);

var DEFAULT_CONTENT_TYPE = {
  'Content-Type': 'application/x-www-form-urlencoded'
};

function setContentTypeIfUnset(headers, value) {
  if (!utils.isUndefined(headers) && utils.isUndefined(headers['Content-Type'])) {
    headers['Content-Type'] = value;
  }
}

function getDefaultAdapter() {
  var adapter;
  if (typeof XMLHttpRequest !== 'undefined') {
    // For browsers use XHR adapter
    adapter = __webpack_require__(167);
  } else if (typeof process !== 'undefined' && Object.prototype.toString.call(process) === '[object process]') {
    // For node use HTTP adapter
    adapter = __webpack_require__(167);
  }
  return adapter;
}

var defaults = {
  adapter: getDefaultAdapter(),

  transformRequest: [function transformRequest(data, headers) {
    normalizeHeaderName(headers, 'Accept');
    normalizeHeaderName(headers, 'Content-Type');
    if (utils.isFormData(data) ||
      utils.isArrayBuffer(data) ||
      utils.isBuffer(data) ||
      utils.isStream(data) ||
      utils.isFile(data) ||
      utils.isBlob(data)
    ) {
      return data;
    }
    if (utils.isArrayBufferView(data)) {
      return data.buffer;
    }
    if (utils.isURLSearchParams(data)) {
      setContentTypeIfUnset(headers, 'application/x-www-form-urlencoded;charset=utf-8');
      return data.toString();
    }
    if (utils.isObject(data)) {
      setContentTypeIfUnset(headers, 'application/json;charset=utf-8');
      return JSON.stringify(data);
    }
    return data;
  }],

  transformResponse: [function transformResponse(data) {
    /*eslint no-param-reassign:0*/
    if (typeof data === 'string') {
      try {
        data = JSON.parse(data);
      } catch (e) { /* Ignore */ }
    }
    return data;
  }],

  /**
   * A timeout in milliseconds to abort a request. If set to 0 (default) a
   * timeout is not created.
   */
  timeout: 0,

  xsrfCookieName: 'XSRF-TOKEN',
  xsrfHeaderName: 'X-XSRF-TOKEN',

  maxContentLength: -1,

  validateStatus: function validateStatus(status) {
    return status >= 200 && status < 300;
  }
};

defaults.headers = {
  common: {
    'Accept': 'application/json, text/plain, */*'
  }
};

utils.forEach(['delete', 'get', 'head'], function forEachMethodNoData(method) {
  defaults.headers[method] = {};
});

utils.forEach(['post', 'put', 'patch'], function forEachMethodWithData(method) {
  defaults.headers[method] = utils.merge(DEFAULT_CONTENT_TYPE);
});

module.exports = defaults;

/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(166)))

/***/ }),

/***/ 166:
/***/ (function(module, exports) {

// shim for using process in browser
var process = module.exports = {};

// cached from whatever global is present so that test runners that stub it
// don't break things.  But we need to wrap it in a try catch in case it is
// wrapped in strict mode code which doesn't define any globals.  It's inside a
// function because try/catches deoptimize in certain engines.

var cachedSetTimeout;
var cachedClearTimeout;

function defaultSetTimout() {
    throw new Error('setTimeout has not been defined');
}
function defaultClearTimeout () {
    throw new Error('clearTimeout has not been defined');
}
(function () {
    try {
        if (typeof setTimeout === 'function') {
            cachedSetTimeout = setTimeout;
        } else {
            cachedSetTimeout = defaultSetTimout;
        }
    } catch (e) {
        cachedSetTimeout = defaultSetTimout;
    }
    try {
        if (typeof clearTimeout === 'function') {
            cachedClearTimeout = clearTimeout;
        } else {
            cachedClearTimeout = defaultClearTimeout;
        }
    } catch (e) {
        cachedClearTimeout = defaultClearTimeout;
    }
} ())
function runTimeout(fun) {
    if (cachedSetTimeout === setTimeout) {
        //normal enviroments in sane situations
        return setTimeout(fun, 0);
    }
    // if setTimeout wasn't available but was latter defined
    if ((cachedSetTimeout === defaultSetTimout || !cachedSetTimeout) && setTimeout) {
        cachedSetTimeout = setTimeout;
        return setTimeout(fun, 0);
    }
    try {
        // when when somebody has screwed with setTimeout but no I.E. maddness
        return cachedSetTimeout(fun, 0);
    } catch(e){
        try {
            // When we are in I.E. but the script has been evaled so I.E. doesn't trust the global object when called normally
            return cachedSetTimeout.call(null, fun, 0);
        } catch(e){
            // same as above but when it's a version of I.E. that must have the global object for 'this', hopfully our context correct otherwise it will throw a global error
            return cachedSetTimeout.call(this, fun, 0);
        }
    }


}
function runClearTimeout(marker) {
    if (cachedClearTimeout === clearTimeout) {
        //normal enviroments in sane situations
        return clearTimeout(marker);
    }
    // if clearTimeout wasn't available but was latter defined
    if ((cachedClearTimeout === defaultClearTimeout || !cachedClearTimeout) && clearTimeout) {
        cachedClearTimeout = clearTimeout;
        return clearTimeout(marker);
    }
    try {
        // when when somebody has screwed with setTimeout but no I.E. maddness
        return cachedClearTimeout(marker);
    } catch (e){
        try {
            // When we are in I.E. but the script has been evaled so I.E. doesn't  trust the global object when called normally
            return cachedClearTimeout.call(null, marker);
        } catch (e){
            // same as above but when it's a version of I.E. that must have the global object for 'this', hopfully our context correct otherwise it will throw a global error.
            // Some versions of I.E. have different rules for clearTimeout vs setTimeout
            return cachedClearTimeout.call(this, marker);
        }
    }



}
var queue = [];
var draining = false;
var currentQueue;
var queueIndex = -1;

function cleanUpNextTick() {
    if (!draining || !currentQueue) {
        return;
    }
    draining = false;
    if (currentQueue.length) {
        queue = currentQueue.concat(queue);
    } else {
        queueIndex = -1;
    }
    if (queue.length) {
        drainQueue();
    }
}

function drainQueue() {
    if (draining) {
        return;
    }
    var timeout = runTimeout(cleanUpNextTick);
    draining = true;

    var len = queue.length;
    while(len) {
        currentQueue = queue;
        queue = [];
        while (++queueIndex < len) {
            if (currentQueue) {
                currentQueue[queueIndex].run();
            }
        }
        queueIndex = -1;
        len = queue.length;
    }
    currentQueue = null;
    draining = false;
    runClearTimeout(timeout);
}

process.nextTick = function (fun) {
    var args = new Array(arguments.length - 1);
    if (arguments.length > 1) {
        for (var i = 1; i < arguments.length; i++) {
            args[i - 1] = arguments[i];
        }
    }
    queue.push(new Item(fun, args));
    if (queue.length === 1 && !draining) {
        runTimeout(drainQueue);
    }
};

// v8 likes predictible objects
function Item(fun, array) {
    this.fun = fun;
    this.array = array;
}
Item.prototype.run = function () {
    this.fun.apply(null, this.array);
};
process.title = 'browser';
process.browser = true;
process.env = {};
process.argv = [];
process.version = ''; // empty string to avoid regexp issues
process.versions = {};

function noop() {}

process.on = noop;
process.addListener = noop;
process.once = noop;
process.off = noop;
process.removeListener = noop;
process.removeAllListeners = noop;
process.emit = noop;
process.prependListener = noop;
process.prependOnceListener = noop;

process.listeners = function (name) { return [] }

process.binding = function (name) {
    throw new Error('process.binding is not supported');
};

process.cwd = function () { return '/' };
process.chdir = function (dir) {
    throw new Error('process.chdir is not supported');
};
process.umask = function() { return 0; };


/***/ }),

/***/ 167:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(26);
var settle = __webpack_require__(237);
var buildURL = __webpack_require__(163);
var buildFullPath = __webpack_require__(239);
var parseHeaders = __webpack_require__(242);
var isURLSameOrigin = __webpack_require__(243);
var createError = __webpack_require__(168);

module.exports = function xhrAdapter(config) {
  return new Promise(function dispatchXhrRequest(resolve, reject) {
    var requestData = config.data;
    var requestHeaders = config.headers;

    if (utils.isFormData(requestData)) {
      delete requestHeaders['Content-Type']; // Let the browser set it
    }

    var request = new XMLHttpRequest();

    // HTTP basic authentication
    if (config.auth) {
      var username = config.auth.username || '';
      var password = config.auth.password || '';
      requestHeaders.Authorization = 'Basic ' + btoa(username + ':' + password);
    }

    var fullPath = buildFullPath(config.baseURL, config.url);
    request.open(config.method.toUpperCase(), buildURL(fullPath, config.params, config.paramsSerializer), true);

    // Set the request timeout in MS
    request.timeout = config.timeout;

    // Listen for ready state
    request.onreadystatechange = function handleLoad() {
      if (!request || request.readyState !== 4) {
        return;
      }

      // The request errored out and we didn't get a response, this will be
      // handled by onerror instead
      // With one exception: request that using file: protocol, most browsers
      // will return status as 0 even though it's a successful request
      if (request.status === 0 && !(request.responseURL && request.responseURL.indexOf('file:') === 0)) {
        return;
      }

      // Prepare the response
      var responseHeaders = 'getAllResponseHeaders' in request ? parseHeaders(request.getAllResponseHeaders()) : null;
      var responseData = !config.responseType || config.responseType === 'text' ? request.responseText : request.response;
      var response = {
        data: responseData,
        status: request.status,
        statusText: request.statusText,
        headers: responseHeaders,
        config: config,
        request: request
      };

      settle(resolve, reject, response);

      // Clean up request
      request = null;
    };

    // Handle browser request cancellation (as opposed to a manual cancellation)
    request.onabort = function handleAbort() {
      if (!request) {
        return;
      }

      reject(createError('Request aborted', config, 'ECONNABORTED', request));

      // Clean up request
      request = null;
    };

    // Handle low level network errors
    request.onerror = function handleError() {
      // Real errors are hidden from us by the browser
      // onerror should only fire if it's a network error
      reject(createError('Network Error', config, null, request));

      // Clean up request
      request = null;
    };

    // Handle timeout
    request.ontimeout = function handleTimeout() {
      var timeoutErrorMessage = 'timeout of ' + config.timeout + 'ms exceeded';
      if (config.timeoutErrorMessage) {
        timeoutErrorMessage = config.timeoutErrorMessage;
      }
      reject(createError(timeoutErrorMessage, config, 'ECONNABORTED',
        request));

      // Clean up request
      request = null;
    };

    // Add xsrf header
    // This is only done if running in a standard browser environment.
    // Specifically not if we're in a web worker, or react-native.
    if (utils.isStandardBrowserEnv()) {
      var cookies = __webpack_require__(244);

      // Add xsrf header
      var xsrfValue = (config.withCredentials || isURLSameOrigin(fullPath)) && config.xsrfCookieName ?
        cookies.read(config.xsrfCookieName) :
        undefined;

      if (xsrfValue) {
        requestHeaders[config.xsrfHeaderName] = xsrfValue;
      }
    }

    // Add headers to the request
    if ('setRequestHeader' in request) {
      utils.forEach(requestHeaders, function setRequestHeader(val, key) {
        if (typeof requestData === 'undefined' && key.toLowerCase() === 'content-type') {
          // Remove Content-Type if data is undefined
          delete requestHeaders[key];
        } else {
          // Otherwise add header to the request
          request.setRequestHeader(key, val);
        }
      });
    }

    // Add withCredentials to request if needed
    if (!utils.isUndefined(config.withCredentials)) {
      request.withCredentials = !!config.withCredentials;
    }

    // Add responseType to request if needed
    if (config.responseType) {
      try {
        request.responseType = config.responseType;
      } catch (e) {
        // Expected DOMException thrown by browsers not compatible XMLHttpRequest Level 2.
        // But, this can be suppressed for 'json' type as it can be parsed by default 'transformResponse' function.
        if (config.responseType !== 'json') {
          throw e;
        }
      }
    }

    // Handle progress if needed
    if (typeof config.onDownloadProgress === 'function') {
      request.addEventListener('progress', config.onDownloadProgress);
    }

    // Not all browsers support upload events
    if (typeof config.onUploadProgress === 'function' && request.upload) {
      request.upload.addEventListener('progress', config.onUploadProgress);
    }

    if (config.cancelToken) {
      // Handle cancellation
      config.cancelToken.promise.then(function onCanceled(cancel) {
        if (!request) {
          return;
        }

        request.abort();
        reject(cancel);
        // Clean up request
        request = null;
      });
    }

    if (requestData === undefined) {
      requestData = null;
    }

    // Send the request
    request.send(requestData);
  });
};


/***/ }),

/***/ 168:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var enhanceError = __webpack_require__(238);

/**
 * Create an Error with the specified message, config, error code, request and response.
 *
 * @param {string} message The error message.
 * @param {Object} config The config.
 * @param {string} [code] The error code (for example, 'ECONNABORTED').
 * @param {Object} [request] The request.
 * @param {Object} [response] The response.
 * @returns {Error} The created error.
 */
module.exports = function createError(message, config, code, request, response) {
  var error = new Error(message);
  return enhanceError(error, config, code, request, response);
};


/***/ }),

/***/ 169:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(26);

/**
 * Config-specific merge-function which creates a new config-object
 * by merging two configuration objects together.
 *
 * @param {Object} config1
 * @param {Object} config2
 * @returns {Object} New object resulting from merging config2 to config1
 */
module.exports = function mergeConfig(config1, config2) {
  // eslint-disable-next-line no-param-reassign
  config2 = config2 || {};
  var config = {};

  var valueFromConfig2Keys = ['url', 'method', 'params', 'data'];
  var mergeDeepPropertiesKeys = ['headers', 'auth', 'proxy'];
  var defaultToConfig2Keys = [
    'baseURL', 'url', 'transformRequest', 'transformResponse', 'paramsSerializer',
    'timeout', 'withCredentials', 'adapter', 'responseType', 'xsrfCookieName',
    'xsrfHeaderName', 'onUploadProgress', 'onDownloadProgress',
    'maxContentLength', 'validateStatus', 'maxRedirects', 'httpAgent',
    'httpsAgent', 'cancelToken', 'socketPath'
  ];

  utils.forEach(valueFromConfig2Keys, function valueFromConfig2(prop) {
    if (typeof config2[prop] !== 'undefined') {
      config[prop] = config2[prop];
    }
  });

  utils.forEach(mergeDeepPropertiesKeys, function mergeDeepProperties(prop) {
    if (utils.isObject(config2[prop])) {
      config[prop] = utils.deepMerge(config1[prop], config2[prop]);
    } else if (typeof config2[prop] !== 'undefined') {
      config[prop] = config2[prop];
    } else if (utils.isObject(config1[prop])) {
      config[prop] = utils.deepMerge(config1[prop]);
    } else if (typeof config1[prop] !== 'undefined') {
      config[prop] = config1[prop];
    }
  });

  utils.forEach(defaultToConfig2Keys, function defaultToConfig2(prop) {
    if (typeof config2[prop] !== 'undefined') {
      config[prop] = config2[prop];
    } else if (typeof config1[prop] !== 'undefined') {
      config[prop] = config1[prop];
    }
  });

  var axiosKeys = valueFromConfig2Keys
    .concat(mergeDeepPropertiesKeys)
    .concat(defaultToConfig2Keys);

  var otherKeys = Object
    .keys(config2)
    .filter(function filterAxiosKeys(key) {
      return axiosKeys.indexOf(key) === -1;
    });

  utils.forEach(otherKeys, function otherKeysDefaultToConfig2(prop) {
    if (typeof config2[prop] !== 'undefined') {
      config[prop] = config2[prop];
    } else if (typeof config1[prop] !== 'undefined') {
      config[prop] = config1[prop];
    }
  });

  return config;
};


/***/ }),

/***/ 170:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * A `Cancel` is an object that is thrown when an operation is canceled.
 *
 * @class
 * @param {string=} message The message.
 */
function Cancel(message) {
  this.message = message;
}

Cancel.prototype.toString = function toString() {
  return 'Cancel' + (this.message ? ': ' + this.message : '');
};

Cancel.prototype.__CANCEL__ = true;

module.exports = Cancel;


/***/ }),

/***/ 231:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(26);
var bind = __webpack_require__(162);
var Axios = __webpack_require__(232);
var mergeConfig = __webpack_require__(169);
var defaults = __webpack_require__(165);

/**
 * Create an instance of Axios
 *
 * @param {Object} defaultConfig The default config for the instance
 * @return {Axios} A new instance of Axios
 */
function createInstance(defaultConfig) {
  var context = new Axios(defaultConfig);
  var instance = bind(Axios.prototype.request, context);

  // Copy axios.prototype to instance
  utils.extend(instance, Axios.prototype, context);

  // Copy context to instance
  utils.extend(instance, context);

  return instance;
}

// Create the default instance to be exported
var axios = createInstance(defaults);

// Expose Axios class to allow class inheritance
axios.Axios = Axios;

// Factory for creating new instances
axios.create = function create(instanceConfig) {
  return createInstance(mergeConfig(axios.defaults, instanceConfig));
};

// Expose Cancel & CancelToken
axios.Cancel = __webpack_require__(170);
axios.CancelToken = __webpack_require__(245);
axios.isCancel = __webpack_require__(164);

// Expose all/spread
axios.all = function all(promises) {
  return Promise.all(promises);
};
axios.spread = __webpack_require__(246);

module.exports = axios;

// Allow use of default import syntax in TypeScript
module.exports.default = axios;


/***/ }),

/***/ 232:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(26);
var buildURL = __webpack_require__(163);
var InterceptorManager = __webpack_require__(233);
var dispatchRequest = __webpack_require__(234);
var mergeConfig = __webpack_require__(169);

/**
 * Create a new instance of Axios
 *
 * @param {Object} instanceConfig The default config for the instance
 */
function Axios(instanceConfig) {
  this.defaults = instanceConfig;
  this.interceptors = {
    request: new InterceptorManager(),
    response: new InterceptorManager()
  };
}

/**
 * Dispatch a request
 *
 * @param {Object} config The config specific for this request (merged with this.defaults)
 */
Axios.prototype.request = function request(config) {
  /*eslint no-param-reassign:0*/
  // Allow for axios('example/url'[, config]) a la fetch API
  if (typeof config === 'string') {
    config = arguments[1] || {};
    config.url = arguments[0];
  } else {
    config = config || {};
  }

  config = mergeConfig(this.defaults, config);

  // Set config.method
  if (config.method) {
    config.method = config.method.toLowerCase();
  } else if (this.defaults.method) {
    config.method = this.defaults.method.toLowerCase();
  } else {
    config.method = 'get';
  }

  // Hook up interceptors middleware
  var chain = [dispatchRequest, undefined];
  var promise = Promise.resolve(config);

  this.interceptors.request.forEach(function unshiftRequestInterceptors(interceptor) {
    chain.unshift(interceptor.fulfilled, interceptor.rejected);
  });

  this.interceptors.response.forEach(function pushResponseInterceptors(interceptor) {
    chain.push(interceptor.fulfilled, interceptor.rejected);
  });

  while (chain.length) {
    promise = promise.then(chain.shift(), chain.shift());
  }

  return promise;
};

Axios.prototype.getUri = function getUri(config) {
  config = mergeConfig(this.defaults, config);
  return buildURL(config.url, config.params, config.paramsSerializer).replace(/^\?/, '');
};

// Provide aliases for supported request methods
utils.forEach(['delete', 'get', 'head', 'options'], function forEachMethodNoData(method) {
  /*eslint func-names:0*/
  Axios.prototype[method] = function(url, config) {
    return this.request(utils.merge(config || {}, {
      method: method,
      url: url
    }));
  };
});

utils.forEach(['post', 'put', 'patch'], function forEachMethodWithData(method) {
  /*eslint func-names:0*/
  Axios.prototype[method] = function(url, data, config) {
    return this.request(utils.merge(config || {}, {
      method: method,
      url: url,
      data: data
    }));
  };
});

module.exports = Axios;


/***/ }),

/***/ 233:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(26);

function InterceptorManager() {
  this.handlers = [];
}

/**
 * Add a new interceptor to the stack
 *
 * @param {Function} fulfilled The function to handle `then` for a `Promise`
 * @param {Function} rejected The function to handle `reject` for a `Promise`
 *
 * @return {Number} An ID used to remove interceptor later
 */
InterceptorManager.prototype.use = function use(fulfilled, rejected) {
  this.handlers.push({
    fulfilled: fulfilled,
    rejected: rejected
  });
  return this.handlers.length - 1;
};

/**
 * Remove an interceptor from the stack
 *
 * @param {Number} id The ID that was returned by `use`
 */
InterceptorManager.prototype.eject = function eject(id) {
  if (this.handlers[id]) {
    this.handlers[id] = null;
  }
};

/**
 * Iterate over all the registered interceptors
 *
 * This method is particularly useful for skipping over any
 * interceptors that may have become `null` calling `eject`.
 *
 * @param {Function} fn The function to call for each interceptor
 */
InterceptorManager.prototype.forEach = function forEach(fn) {
  utils.forEach(this.handlers, function forEachHandler(h) {
    if (h !== null) {
      fn(h);
    }
  });
};

module.exports = InterceptorManager;


/***/ }),

/***/ 234:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(26);
var transformData = __webpack_require__(235);
var isCancel = __webpack_require__(164);
var defaults = __webpack_require__(165);

/**
 * Throws a `Cancel` if cancellation has been requested.
 */
function throwIfCancellationRequested(config) {
  if (config.cancelToken) {
    config.cancelToken.throwIfRequested();
  }
}

/**
 * Dispatch a request to the server using the configured adapter.
 *
 * @param {object} config The config that is to be used for the request
 * @returns {Promise} The Promise to be fulfilled
 */
module.exports = function dispatchRequest(config) {
  throwIfCancellationRequested(config);

  // Ensure headers exist
  config.headers = config.headers || {};

  // Transform request data
  config.data = transformData(
    config.data,
    config.headers,
    config.transformRequest
  );

  // Flatten headers
  config.headers = utils.merge(
    config.headers.common || {},
    config.headers[config.method] || {},
    config.headers
  );

  utils.forEach(
    ['delete', 'get', 'head', 'post', 'put', 'patch', 'common'],
    function cleanHeaderConfig(method) {
      delete config.headers[method];
    }
  );

  var adapter = config.adapter || defaults.adapter;

  return adapter(config).then(function onAdapterResolution(response) {
    throwIfCancellationRequested(config);

    // Transform response data
    response.data = transformData(
      response.data,
      response.headers,
      config.transformResponse
    );

    return response;
  }, function onAdapterRejection(reason) {
    if (!isCancel(reason)) {
      throwIfCancellationRequested(config);

      // Transform response data
      if (reason && reason.response) {
        reason.response.data = transformData(
          reason.response.data,
          reason.response.headers,
          config.transformResponse
        );
      }
    }

    return Promise.reject(reason);
  });
};


/***/ }),

/***/ 235:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(26);

/**
 * Transform the data for a request or a response
 *
 * @param {Object|String} data The data to be transformed
 * @param {Array} headers The headers for the request or response
 * @param {Array|Function} fns A single function or Array of functions
 * @returns {*} The resulting transformed data
 */
module.exports = function transformData(data, headers, fns) {
  /*eslint no-param-reassign:0*/
  utils.forEach(fns, function transform(fn) {
    data = fn(data, headers);
  });

  return data;
};


/***/ }),

/***/ 236:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(26);

module.exports = function normalizeHeaderName(headers, normalizedName) {
  utils.forEach(headers, function processHeader(value, name) {
    if (name !== normalizedName && name.toUpperCase() === normalizedName.toUpperCase()) {
      headers[normalizedName] = value;
      delete headers[name];
    }
  });
};


/***/ }),

/***/ 237:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var createError = __webpack_require__(168);

/**
 * Resolve or reject a Promise based on response status.
 *
 * @param {Function} resolve A function that resolves the promise.
 * @param {Function} reject A function that rejects the promise.
 * @param {object} response The response.
 */
module.exports = function settle(resolve, reject, response) {
  var validateStatus = response.config.validateStatus;
  if (!validateStatus || validateStatus(response.status)) {
    resolve(response);
  } else {
    reject(createError(
      'Request failed with status code ' + response.status,
      response.config,
      null,
      response.request,
      response
    ));
  }
};


/***/ }),

/***/ 238:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * Update an Error with the specified config, error code, and response.
 *
 * @param {Error} error The error to update.
 * @param {Object} config The config.
 * @param {string} [code] The error code (for example, 'ECONNABORTED').
 * @param {Object} [request] The request.
 * @param {Object} [response] The response.
 * @returns {Error} The error.
 */
module.exports = function enhanceError(error, config, code, request, response) {
  error.config = config;
  if (code) {
    error.code = code;
  }

  error.request = request;
  error.response = response;
  error.isAxiosError = true;

  error.toJSON = function() {
    return {
      // Standard
      message: this.message,
      name: this.name,
      // Microsoft
      description: this.description,
      number: this.number,
      // Mozilla
      fileName: this.fileName,
      lineNumber: this.lineNumber,
      columnNumber: this.columnNumber,
      stack: this.stack,
      // Axios
      config: this.config,
      code: this.code
    };
  };
  return error;
};


/***/ }),

/***/ 239:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var isAbsoluteURL = __webpack_require__(240);
var combineURLs = __webpack_require__(241);

/**
 * Creates a new URL by combining the baseURL with the requestedURL,
 * only when the requestedURL is not already an absolute URL.
 * If the requestURL is absolute, this function returns the requestedURL untouched.
 *
 * @param {string} baseURL The base URL
 * @param {string} requestedURL Absolute or relative URL to combine
 * @returns {string} The combined full path
 */
module.exports = function buildFullPath(baseURL, requestedURL) {
  if (baseURL && !isAbsoluteURL(requestedURL)) {
    return combineURLs(baseURL, requestedURL);
  }
  return requestedURL;
};


/***/ }),

/***/ 240:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * Determines whether the specified URL is absolute
 *
 * @param {string} url The URL to test
 * @returns {boolean} True if the specified URL is absolute, otherwise false
 */
module.exports = function isAbsoluteURL(url) {
  // A URL is considered absolute if it begins with "<scheme>://" or "//" (protocol-relative URL).
  // RFC 3986 defines scheme name as a sequence of characters beginning with a letter and followed
  // by any combination of letters, digits, plus, period, or hyphen.
  return /^([a-z][a-z\d\+\-\.]*:)?\/\//i.test(url);
};


/***/ }),

/***/ 241:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * Creates a new URL by combining the specified URLs
 *
 * @param {string} baseURL The base URL
 * @param {string} relativeURL The relative URL
 * @returns {string} The combined URL
 */
module.exports = function combineURLs(baseURL, relativeURL) {
  return relativeURL
    ? baseURL.replace(/\/+$/, '') + '/' + relativeURL.replace(/^\/+/, '')
    : baseURL;
};


/***/ }),

/***/ 242:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(26);

// Headers whose duplicates are ignored by node
// c.f. https://nodejs.org/api/http.html#http_message_headers
var ignoreDuplicateOf = [
  'age', 'authorization', 'content-length', 'content-type', 'etag',
  'expires', 'from', 'host', 'if-modified-since', 'if-unmodified-since',
  'last-modified', 'location', 'max-forwards', 'proxy-authorization',
  'referer', 'retry-after', 'user-agent'
];

/**
 * Parse headers into an object
 *
 * ```
 * Date: Wed, 27 Aug 2014 08:58:49 GMT
 * Content-Type: application/json
 * Connection: keep-alive
 * Transfer-Encoding: chunked
 * ```
 *
 * @param {String} headers Headers needing to be parsed
 * @returns {Object} Headers parsed into an object
 */
module.exports = function parseHeaders(headers) {
  var parsed = {};
  var key;
  var val;
  var i;

  if (!headers) { return parsed; }

  utils.forEach(headers.split('\n'), function parser(line) {
    i = line.indexOf(':');
    key = utils.trim(line.substr(0, i)).toLowerCase();
    val = utils.trim(line.substr(i + 1));

    if (key) {
      if (parsed[key] && ignoreDuplicateOf.indexOf(key) >= 0) {
        return;
      }
      if (key === 'set-cookie') {
        parsed[key] = (parsed[key] ? parsed[key] : []).concat([val]);
      } else {
        parsed[key] = parsed[key] ? parsed[key] + ', ' + val : val;
      }
    }
  });

  return parsed;
};


/***/ }),

/***/ 243:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(26);

module.exports = (
  utils.isStandardBrowserEnv() ?

  // Standard browser envs have full support of the APIs needed to test
  // whether the request URL is of the same origin as current location.
    (function standardBrowserEnv() {
      var msie = /(msie|trident)/i.test(navigator.userAgent);
      var urlParsingNode = document.createElement('a');
      var originURL;

      /**
    * Parse a URL to discover it's components
    *
    * @param {String} url The URL to be parsed
    * @returns {Object}
    */
      function resolveURL(url) {
        var href = url;

        if (msie) {
        // IE needs attribute set twice to normalize properties
          urlParsingNode.setAttribute('href', href);
          href = urlParsingNode.href;
        }

        urlParsingNode.setAttribute('href', href);

        // urlParsingNode provides the UrlUtils interface - http://url.spec.whatwg.org/#urlutils
        return {
          href: urlParsingNode.href,
          protocol: urlParsingNode.protocol ? urlParsingNode.protocol.replace(/:$/, '') : '',
          host: urlParsingNode.host,
          search: urlParsingNode.search ? urlParsingNode.search.replace(/^\?/, '') : '',
          hash: urlParsingNode.hash ? urlParsingNode.hash.replace(/^#/, '') : '',
          hostname: urlParsingNode.hostname,
          port: urlParsingNode.port,
          pathname: (urlParsingNode.pathname.charAt(0) === '/') ?
            urlParsingNode.pathname :
            '/' + urlParsingNode.pathname
        };
      }

      originURL = resolveURL(window.location.href);

      /**
    * Determine if a URL shares the same origin as the current location
    *
    * @param {String} requestURL The URL to test
    * @returns {boolean} True if URL shares the same origin, otherwise false
    */
      return function isURLSameOrigin(requestURL) {
        var parsed = (utils.isString(requestURL)) ? resolveURL(requestURL) : requestURL;
        return (parsed.protocol === originURL.protocol &&
            parsed.host === originURL.host);
      };
    })() :

  // Non standard browser envs (web workers, react-native) lack needed support.
    (function nonStandardBrowserEnv() {
      return function isURLSameOrigin() {
        return true;
      };
    })()
);


/***/ }),

/***/ 244:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(26);

module.exports = (
  utils.isStandardBrowserEnv() ?

  // Standard browser envs support document.cookie
    (function standardBrowserEnv() {
      return {
        write: function write(name, value, expires, path, domain, secure) {
          var cookie = [];
          cookie.push(name + '=' + encodeURIComponent(value));

          if (utils.isNumber(expires)) {
            cookie.push('expires=' + new Date(expires).toGMTString());
          }

          if (utils.isString(path)) {
            cookie.push('path=' + path);
          }

          if (utils.isString(domain)) {
            cookie.push('domain=' + domain);
          }

          if (secure === true) {
            cookie.push('secure');
          }

          document.cookie = cookie.join('; ');
        },

        read: function read(name) {
          var match = document.cookie.match(new RegExp('(^|;\\s*)(' + name + ')=([^;]*)'));
          return (match ? decodeURIComponent(match[3]) : null);
        },

        remove: function remove(name) {
          this.write(name, '', Date.now() - 86400000);
        }
      };
    })() :

  // Non standard browser env (web workers, react-native) lack needed support.
    (function nonStandardBrowserEnv() {
      return {
        write: function write() {},
        read: function read() { return null; },
        remove: function remove() {}
      };
    })()
);


/***/ }),

/***/ 245:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var Cancel = __webpack_require__(170);

/**
 * A `CancelToken` is an object that can be used to request cancellation of an operation.
 *
 * @class
 * @param {Function} executor The executor function.
 */
function CancelToken(executor) {
  if (typeof executor !== 'function') {
    throw new TypeError('executor must be a function.');
  }

  var resolvePromise;
  this.promise = new Promise(function promiseExecutor(resolve) {
    resolvePromise = resolve;
  });

  var token = this;
  executor(function cancel(message) {
    if (token.reason) {
      // Cancellation has already been requested
      return;
    }

    token.reason = new Cancel(message);
    resolvePromise(token.reason);
  });
}

/**
 * Throws a `Cancel` if cancellation has been requested.
 */
CancelToken.prototype.throwIfRequested = function throwIfRequested() {
  if (this.reason) {
    throw this.reason;
  }
};

/**
 * Returns an object that contains a new `CancelToken` and a function that, when called,
 * cancels the `CancelToken`.
 */
CancelToken.source = function source() {
  var cancel;
  var token = new CancelToken(function executor(c) {
    cancel = c;
  });
  return {
    token: token,
    cancel: cancel
  };
};

module.exports = CancelToken;


/***/ }),

/***/ 246:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * Syntactic sugar for invoking a function and expanding an array for arguments.
 *
 * Common use case would be to use `Function.prototype.apply`.
 *
 *  ```js
 *  function f(x, y, z) {}
 *  var args = [1, 2, 3];
 *  f.apply(null, args);
 *  ```
 *
 * With `spread` this example can be re-written.
 *
 *  ```js
 *  spread(function(x, y, z) {})([1, 2, 3]);
 *  ```
 *
 * @param {Function} callback
 * @returns {Function}
 */
module.exports = function spread(callback) {
  return function wrap(arr) {
    return callback.apply(null, arr);
  };
};


/***/ }),

/***/ 26:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var bind = __webpack_require__(162);

/*global toString:true*/

// utils is a library of generic helper functions non-specific to axios

var toString = Object.prototype.toString;

/**
 * Determine if a value is an Array
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is an Array, otherwise false
 */
function isArray(val) {
  return toString.call(val) === '[object Array]';
}

/**
 * Determine if a value is undefined
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if the value is undefined, otherwise false
 */
function isUndefined(val) {
  return typeof val === 'undefined';
}

/**
 * Determine if a value is a Buffer
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a Buffer, otherwise false
 */
function isBuffer(val) {
  return val !== null && !isUndefined(val) && val.constructor !== null && !isUndefined(val.constructor)
    && typeof val.constructor.isBuffer === 'function' && val.constructor.isBuffer(val);
}

/**
 * Determine if a value is an ArrayBuffer
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is an ArrayBuffer, otherwise false
 */
function isArrayBuffer(val) {
  return toString.call(val) === '[object ArrayBuffer]';
}

/**
 * Determine if a value is a FormData
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is an FormData, otherwise false
 */
function isFormData(val) {
  return (typeof FormData !== 'undefined') && (val instanceof FormData);
}

/**
 * Determine if a value is a view on an ArrayBuffer
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a view on an ArrayBuffer, otherwise false
 */
function isArrayBufferView(val) {
  var result;
  if ((typeof ArrayBuffer !== 'undefined') && (ArrayBuffer.isView)) {
    result = ArrayBuffer.isView(val);
  } else {
    result = (val) && (val.buffer) && (val.buffer instanceof ArrayBuffer);
  }
  return result;
}

/**
 * Determine if a value is a String
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a String, otherwise false
 */
function isString(val) {
  return typeof val === 'string';
}

/**
 * Determine if a value is a Number
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a Number, otherwise false
 */
function isNumber(val) {
  return typeof val === 'number';
}

/**
 * Determine if a value is an Object
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is an Object, otherwise false
 */
function isObject(val) {
  return val !== null && typeof val === 'object';
}

/**
 * Determine if a value is a Date
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a Date, otherwise false
 */
function isDate(val) {
  return toString.call(val) === '[object Date]';
}

/**
 * Determine if a value is a File
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a File, otherwise false
 */
function isFile(val) {
  return toString.call(val) === '[object File]';
}

/**
 * Determine if a value is a Blob
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a Blob, otherwise false
 */
function isBlob(val) {
  return toString.call(val) === '[object Blob]';
}

/**
 * Determine if a value is a Function
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a Function, otherwise false
 */
function isFunction(val) {
  return toString.call(val) === '[object Function]';
}

/**
 * Determine if a value is a Stream
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a Stream, otherwise false
 */
function isStream(val) {
  return isObject(val) && isFunction(val.pipe);
}

/**
 * Determine if a value is a URLSearchParams object
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a URLSearchParams object, otherwise false
 */
function isURLSearchParams(val) {
  return typeof URLSearchParams !== 'undefined' && val instanceof URLSearchParams;
}

/**
 * Trim excess whitespace off the beginning and end of a string
 *
 * @param {String} str The String to trim
 * @returns {String} The String freed of excess whitespace
 */
function trim(str) {
  return str.replace(/^\s*/, '').replace(/\s*$/, '');
}

/**
 * Determine if we're running in a standard browser environment
 *
 * This allows axios to run in a web worker, and react-native.
 * Both environments support XMLHttpRequest, but not fully standard globals.
 *
 * web workers:
 *  typeof window -> undefined
 *  typeof document -> undefined
 *
 * react-native:
 *  navigator.product -> 'ReactNative'
 * nativescript
 *  navigator.product -> 'NativeScript' or 'NS'
 */
function isStandardBrowserEnv() {
  if (typeof navigator !== 'undefined' && (navigator.product === 'ReactNative' ||
                                           navigator.product === 'NativeScript' ||
                                           navigator.product === 'NS')) {
    return false;
  }
  return (
    typeof window !== 'undefined' &&
    typeof document !== 'undefined'
  );
}

/**
 * Iterate over an Array or an Object invoking a function for each item.
 *
 * If `obj` is an Array callback will be called passing
 * the value, index, and complete array for each item.
 *
 * If 'obj' is an Object callback will be called passing
 * the value, key, and complete object for each property.
 *
 * @param {Object|Array} obj The object to iterate
 * @param {Function} fn The callback to invoke for each item
 */
function forEach(obj, fn) {
  // Don't bother if no value provided
  if (obj === null || typeof obj === 'undefined') {
    return;
  }

  // Force an array if not already something iterable
  if (typeof obj !== 'object') {
    /*eslint no-param-reassign:0*/
    obj = [obj];
  }

  if (isArray(obj)) {
    // Iterate over array values
    for (var i = 0, l = obj.length; i < l; i++) {
      fn.call(null, obj[i], i, obj);
    }
  } else {
    // Iterate over object keys
    for (var key in obj) {
      if (Object.prototype.hasOwnProperty.call(obj, key)) {
        fn.call(null, obj[key], key, obj);
      }
    }
  }
}

/**
 * Accepts varargs expecting each argument to be an object, then
 * immutably merges the properties of each object and returns result.
 *
 * When multiple objects contain the same key the later object in
 * the arguments list will take precedence.
 *
 * Example:
 *
 * ```js
 * var result = merge({foo: 123}, {foo: 456});
 * console.log(result.foo); // outputs 456
 * ```
 *
 * @param {Object} obj1 Object to merge
 * @returns {Object} Result of all merge properties
 */
function merge(/* obj1, obj2, obj3, ... */) {
  var result = {};
  function assignValue(val, key) {
    if (typeof result[key] === 'object' && typeof val === 'object') {
      result[key] = merge(result[key], val);
    } else {
      result[key] = val;
    }
  }

  for (var i = 0, l = arguments.length; i < l; i++) {
    forEach(arguments[i], assignValue);
  }
  return result;
}

/**
 * Function equal to merge with the difference being that no reference
 * to original objects is kept.
 *
 * @see merge
 * @param {Object} obj1 Object to merge
 * @returns {Object} Result of all merge properties
 */
function deepMerge(/* obj1, obj2, obj3, ... */) {
  var result = {};
  function assignValue(val, key) {
    if (typeof result[key] === 'object' && typeof val === 'object') {
      result[key] = deepMerge(result[key], val);
    } else if (typeof val === 'object') {
      result[key] = deepMerge({}, val);
    } else {
      result[key] = val;
    }
  }

  for (var i = 0, l = arguments.length; i < l; i++) {
    forEach(arguments[i], assignValue);
  }
  return result;
}

/**
 * Extends object a by mutably adding to it the properties of object b.
 *
 * @param {Object} a The object to be extended
 * @param {Object} b The object to copy properties from
 * @param {Object} thisArg The object to bind function to
 * @return {Object} The resulting value of object a
 */
function extend(a, b, thisArg) {
  forEach(b, function assignValue(val, key) {
    if (thisArg && typeof val === 'function') {
      a[key] = bind(val, thisArg);
    } else {
      a[key] = val;
    }
  });
  return a;
}

module.exports = {
  isArray: isArray,
  isArrayBuffer: isArrayBuffer,
  isBuffer: isBuffer,
  isFormData: isFormData,
  isArrayBufferView: isArrayBufferView,
  isString: isString,
  isNumber: isNumber,
  isObject: isObject,
  isUndefined: isUndefined,
  isDate: isDate,
  isFile: isFile,
  isBlob: isBlob,
  isFunction: isFunction,
  isStream: isStream,
  isURLSearchParams: isURLSearchParams,
  isStandardBrowserEnv: isStandardBrowserEnv,
  forEach: forEach,
  merge: merge,
  deepMerge: deepMerge,
  extend: extend,
  trim: trim
};


/***/ }),

/***/ 304:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var has = Object.prototype.hasOwnProperty;
var isArray = Array.isArray;

var hexTable = (function () {
    var array = [];
    for (var i = 0; i < 256; ++i) {
        array.push('%' + ((i < 16 ? '0' : '') + i.toString(16)).toUpperCase());
    }

    return array;
}());

var compactQueue = function compactQueue(queue) {
    while (queue.length > 1) {
        var item = queue.pop();
        var obj = item.obj[item.prop];

        if (isArray(obj)) {
            var compacted = [];

            for (var j = 0; j < obj.length; ++j) {
                if (typeof obj[j] !== 'undefined') {
                    compacted.push(obj[j]);
                }
            }

            item.obj[item.prop] = compacted;
        }
    }
};

var arrayToObject = function arrayToObject(source, options) {
    var obj = options && options.plainObjects ? Object.create(null) : {};
    for (var i = 0; i < source.length; ++i) {
        if (typeof source[i] !== 'undefined') {
            obj[i] = source[i];
        }
    }

    return obj;
};

var merge = function merge(target, source, options) {
    /* eslint no-param-reassign: 0 */
    if (!source) {
        return target;
    }

    if (typeof source !== 'object') {
        if (isArray(target)) {
            target.push(source);
        } else if (target && typeof target === 'object') {
            if ((options && (options.plainObjects || options.allowPrototypes)) || !has.call(Object.prototype, source)) {
                target[source] = true;
            }
        } else {
            return [target, source];
        }

        return target;
    }

    if (!target || typeof target !== 'object') {
        return [target].concat(source);
    }

    var mergeTarget = target;
    if (isArray(target) && !isArray(source)) {
        mergeTarget = arrayToObject(target, options);
    }

    if (isArray(target) && isArray(source)) {
        source.forEach(function (item, i) {
            if (has.call(target, i)) {
                var targetItem = target[i];
                if (targetItem && typeof targetItem === 'object' && item && typeof item === 'object') {
                    target[i] = merge(targetItem, item, options);
                } else {
                    target.push(item);
                }
            } else {
                target[i] = item;
            }
        });
        return target;
    }

    return Object.keys(source).reduce(function (acc, key) {
        var value = source[key];

        if (has.call(acc, key)) {
            acc[key] = merge(acc[key], value, options);
        } else {
            acc[key] = value;
        }
        return acc;
    }, mergeTarget);
};

var assign = function assignSingleSource(target, source) {
    return Object.keys(source).reduce(function (acc, key) {
        acc[key] = source[key];
        return acc;
    }, target);
};

var decode = function (str, decoder, charset) {
    var strWithoutPlus = str.replace(/\+/g, ' ');
    if (charset === 'iso-8859-1') {
        // unescape never throws, no try...catch needed:
        return strWithoutPlus.replace(/%[0-9a-f]{2}/gi, unescape);
    }
    // utf-8
    try {
        return decodeURIComponent(strWithoutPlus);
    } catch (e) {
        return strWithoutPlus;
    }
};

var encode = function encode(str, defaultEncoder, charset) {
    // This code was originally written by Brian White (mscdex) for the io.js core querystring library.
    // It has been adapted here for stricter adherence to RFC 3986
    if (str.length === 0) {
        return str;
    }

    var string = str;
    if (typeof str === 'symbol') {
        string = Symbol.prototype.toString.call(str);
    } else if (typeof str !== 'string') {
        string = String(str);
    }

    if (charset === 'iso-8859-1') {
        return escape(string).replace(/%u[0-9a-f]{4}/gi, function ($0) {
            return '%26%23' + parseInt($0.slice(2), 16) + '%3B';
        });
    }

    var out = '';
    for (var i = 0; i < string.length; ++i) {
        var c = string.charCodeAt(i);

        if (
            c === 0x2D // -
            || c === 0x2E // .
            || c === 0x5F // _
            || c === 0x7E // ~
            || (c >= 0x30 && c <= 0x39) // 0-9
            || (c >= 0x41 && c <= 0x5A) // a-z
            || (c >= 0x61 && c <= 0x7A) // A-Z
        ) {
            out += string.charAt(i);
            continue;
        }

        if (c < 0x80) {
            out = out + hexTable[c];
            continue;
        }

        if (c < 0x800) {
            out = out + (hexTable[0xC0 | (c >> 6)] + hexTable[0x80 | (c & 0x3F)]);
            continue;
        }

        if (c < 0xD800 || c >= 0xE000) {
            out = out + (hexTable[0xE0 | (c >> 12)] + hexTable[0x80 | ((c >> 6) & 0x3F)] + hexTable[0x80 | (c & 0x3F)]);
            continue;
        }

        i += 1;
        c = 0x10000 + (((c & 0x3FF) << 10) | (string.charCodeAt(i) & 0x3FF));
        out += hexTable[0xF0 | (c >> 18)]
            + hexTable[0x80 | ((c >> 12) & 0x3F)]
            + hexTable[0x80 | ((c >> 6) & 0x3F)]
            + hexTable[0x80 | (c & 0x3F)];
    }

    return out;
};

var compact = function compact(value) {
    var queue = [{ obj: { o: value }, prop: 'o' }];
    var refs = [];

    for (var i = 0; i < queue.length; ++i) {
        var item = queue[i];
        var obj = item.obj[item.prop];

        var keys = Object.keys(obj);
        for (var j = 0; j < keys.length; ++j) {
            var key = keys[j];
            var val = obj[key];
            if (typeof val === 'object' && val !== null && refs.indexOf(val) === -1) {
                queue.push({ obj: obj, prop: key });
                refs.push(val);
            }
        }
    }

    compactQueue(queue);

    return value;
};

var isRegExp = function isRegExp(obj) {
    return Object.prototype.toString.call(obj) === '[object RegExp]';
};

var isBuffer = function isBuffer(obj) {
    if (!obj || typeof obj !== 'object') {
        return false;
    }

    return !!(obj.constructor && obj.constructor.isBuffer && obj.constructor.isBuffer(obj));
};

var combine = function combine(a, b) {
    return [].concat(a, b);
};

var maybeMap = function maybeMap(val, fn) {
    if (isArray(val)) {
        var mapped = [];
        for (var i = 0; i < val.length; i += 1) {
            mapped.push(fn(val[i]));
        }
        return mapped;
    }
    return fn(val);
};

module.exports = {
    arrayToObject: arrayToObject,
    assign: assign,
    combine: combine,
    compact: compact,
    decode: decode,
    encode: encode,
    isBuffer: isBuffer,
    isRegExp: isRegExp,
    maybeMap: maybeMap,
    merge: merge
};


/***/ }),

/***/ 430:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var replace = String.prototype.replace;
var percentTwenties = /%20/g;

var util = __webpack_require__(304);

var Format = {
    RFC1738: 'RFC1738',
    RFC3986: 'RFC3986'
};

module.exports = util.assign(
    {
        'default': Format.RFC3986,
        formatters: {
            RFC1738: function (value) {
                return replace.call(value, percentTwenties, '+');
            },
            RFC3986: function (value) {
                return String(value);
            }
        }
    },
    Format
);


/***/ }),

/***/ 451:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var stringify = __webpack_require__(919);
var parse = __webpack_require__(920);
var formats = __webpack_require__(430);

module.exports = {
    formats: formats,
    parse: parse,
    stringify: stringify
};


/***/ }),

/***/ 919:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(304);
var formats = __webpack_require__(430);
var has = Object.prototype.hasOwnProperty;

var arrayPrefixGenerators = {
    brackets: function brackets(prefix) {
        return prefix + '[]';
    },
    comma: 'comma',
    indices: function indices(prefix, key) {
        return prefix + '[' + key + ']';
    },
    repeat: function repeat(prefix) {
        return prefix;
    }
};

var isArray = Array.isArray;
var push = Array.prototype.push;
var pushToArray = function (arr, valueOrArray) {
    push.apply(arr, isArray(valueOrArray) ? valueOrArray : [valueOrArray]);
};

var toISO = Date.prototype.toISOString;

var defaultFormat = formats['default'];
var defaults = {
    addQueryPrefix: false,
    allowDots: false,
    charset: 'utf-8',
    charsetSentinel: false,
    delimiter: '&',
    encode: true,
    encoder: utils.encode,
    encodeValuesOnly: false,
    format: defaultFormat,
    formatter: formats.formatters[defaultFormat],
    // deprecated
    indices: false,
    serializeDate: function serializeDate(date) {
        return toISO.call(date);
    },
    skipNulls: false,
    strictNullHandling: false
};

var isNonNullishPrimitive = function isNonNullishPrimitive(v) {
    return typeof v === 'string'
        || typeof v === 'number'
        || typeof v === 'boolean'
        || typeof v === 'symbol'
        || typeof v === 'bigint';
};

var stringify = function stringify(
    object,
    prefix,
    generateArrayPrefix,
    strictNullHandling,
    skipNulls,
    encoder,
    filter,
    sort,
    allowDots,
    serializeDate,
    formatter,
    encodeValuesOnly,
    charset
) {
    var obj = object;
    if (typeof filter === 'function') {
        obj = filter(prefix, obj);
    } else if (obj instanceof Date) {
        obj = serializeDate(obj);
    } else if (generateArrayPrefix === 'comma' && isArray(obj)) {
        obj = utils.maybeMap(obj, function (value) {
            if (value instanceof Date) {
                return serializeDate(value);
            }
            return value;
        }).join(',');
    }

    if (obj === null) {
        if (strictNullHandling) {
            return encoder && !encodeValuesOnly ? encoder(prefix, defaults.encoder, charset, 'key') : prefix;
        }

        obj = '';
    }

    if (isNonNullishPrimitive(obj) || utils.isBuffer(obj)) {
        if (encoder) {
            var keyValue = encodeValuesOnly ? prefix : encoder(prefix, defaults.encoder, charset, 'key');
            return [formatter(keyValue) + '=' + formatter(encoder(obj, defaults.encoder, charset, 'value'))];
        }
        return [formatter(prefix) + '=' + formatter(String(obj))];
    }

    var values = [];

    if (typeof obj === 'undefined') {
        return values;
    }

    var objKeys;
    if (isArray(filter)) {
        objKeys = filter;
    } else {
        var keys = Object.keys(obj);
        objKeys = sort ? keys.sort(sort) : keys;
    }

    for (var i = 0; i < objKeys.length; ++i) {
        var key = objKeys[i];
        var value = obj[key];

        if (skipNulls && value === null) {
            continue;
        }

        var keyPrefix = isArray(obj)
            ? typeof generateArrayPrefix === 'function' ? generateArrayPrefix(prefix, key) : prefix
            : prefix + (allowDots ? '.' + key : '[' + key + ']');

        pushToArray(values, stringify(
            value,
            keyPrefix,
            generateArrayPrefix,
            strictNullHandling,
            skipNulls,
            encoder,
            filter,
            sort,
            allowDots,
            serializeDate,
            formatter,
            encodeValuesOnly,
            charset
        ));
    }

    return values;
};

var normalizeStringifyOptions = function normalizeStringifyOptions(opts) {
    if (!opts) {
        return defaults;
    }

    if (opts.encoder !== null && opts.encoder !== undefined && typeof opts.encoder !== 'function') {
        throw new TypeError('Encoder has to be a function.');
    }

    var charset = opts.charset || defaults.charset;
    if (typeof opts.charset !== 'undefined' && opts.charset !== 'utf-8' && opts.charset !== 'iso-8859-1') {
        throw new TypeError('The charset option must be either utf-8, iso-8859-1, or undefined');
    }

    var format = formats['default'];
    if (typeof opts.format !== 'undefined') {
        if (!has.call(formats.formatters, opts.format)) {
            throw new TypeError('Unknown format option provided.');
        }
        format = opts.format;
    }
    var formatter = formats.formatters[format];

    var filter = defaults.filter;
    if (typeof opts.filter === 'function' || isArray(opts.filter)) {
        filter = opts.filter;
    }

    return {
        addQueryPrefix: typeof opts.addQueryPrefix === 'boolean' ? opts.addQueryPrefix : defaults.addQueryPrefix,
        allowDots: typeof opts.allowDots === 'undefined' ? defaults.allowDots : !!opts.allowDots,
        charset: charset,
        charsetSentinel: typeof opts.charsetSentinel === 'boolean' ? opts.charsetSentinel : defaults.charsetSentinel,
        delimiter: typeof opts.delimiter === 'undefined' ? defaults.delimiter : opts.delimiter,
        encode: typeof opts.encode === 'boolean' ? opts.encode : defaults.encode,
        encoder: typeof opts.encoder === 'function' ? opts.encoder : defaults.encoder,
        encodeValuesOnly: typeof opts.encodeValuesOnly === 'boolean' ? opts.encodeValuesOnly : defaults.encodeValuesOnly,
        filter: filter,
        formatter: formatter,
        serializeDate: typeof opts.serializeDate === 'function' ? opts.serializeDate : defaults.serializeDate,
        skipNulls: typeof opts.skipNulls === 'boolean' ? opts.skipNulls : defaults.skipNulls,
        sort: typeof opts.sort === 'function' ? opts.sort : null,
        strictNullHandling: typeof opts.strictNullHandling === 'boolean' ? opts.strictNullHandling : defaults.strictNullHandling
    };
};

module.exports = function (object, opts) {
    var obj = object;
    var options = normalizeStringifyOptions(opts);

    var objKeys;
    var filter;

    if (typeof options.filter === 'function') {
        filter = options.filter;
        obj = filter('', obj);
    } else if (isArray(options.filter)) {
        filter = options.filter;
        objKeys = filter;
    }

    var keys = [];

    if (typeof obj !== 'object' || obj === null) {
        return '';
    }

    var arrayFormat;
    if (opts && opts.arrayFormat in arrayPrefixGenerators) {
        arrayFormat = opts.arrayFormat;
    } else if (opts && 'indices' in opts) {
        arrayFormat = opts.indices ? 'indices' : 'repeat';
    } else {
        arrayFormat = 'indices';
    }

    var generateArrayPrefix = arrayPrefixGenerators[arrayFormat];

    if (!objKeys) {
        objKeys = Object.keys(obj);
    }

    if (options.sort) {
        objKeys.sort(options.sort);
    }

    for (var i = 0; i < objKeys.length; ++i) {
        var key = objKeys[i];

        if (options.skipNulls && obj[key] === null) {
            continue;
        }
        pushToArray(keys, stringify(
            obj[key],
            key,
            generateArrayPrefix,
            options.strictNullHandling,
            options.skipNulls,
            options.encode ? options.encoder : null,
            options.filter,
            options.sort,
            options.allowDots,
            options.serializeDate,
            options.formatter,
            options.encodeValuesOnly,
            options.charset
        ));
    }

    var joined = keys.join(options.delimiter);
    var prefix = options.addQueryPrefix === true ? '?' : '';

    if (options.charsetSentinel) {
        if (options.charset === 'iso-8859-1') {
            // encodeURIComponent('&#10003;'), the "numeric entity" representation of a checkmark
            prefix += 'utf8=%26%2310003%3B&';
        } else {
            // encodeURIComponent('✓')
            prefix += 'utf8=%E2%9C%93&';
        }
    }

    return joined.length > 0 ? prefix + joined : '';
};


/***/ }),

/***/ 920:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(304);

var has = Object.prototype.hasOwnProperty;
var isArray = Array.isArray;

var defaults = {
    allowDots: false,
    allowPrototypes: false,
    arrayLimit: 20,
    charset: 'utf-8',
    charsetSentinel: false,
    comma: false,
    decoder: utils.decode,
    delimiter: '&',
    depth: 5,
    ignoreQueryPrefix: false,
    interpretNumericEntities: false,
    parameterLimit: 1000,
    parseArrays: true,
    plainObjects: false,
    strictNullHandling: false
};

var interpretNumericEntities = function (str) {
    return str.replace(/&#(\d+);/g, function ($0, numberStr) {
        return String.fromCharCode(parseInt(numberStr, 10));
    });
};

var parseArrayValue = function (val, options) {
    if (val && typeof val === 'string' && options.comma && val.indexOf(',') > -1) {
        return val.split(',');
    }

    return val;
};

// This is what browsers will submit when the ✓ character occurs in an
// application/x-www-form-urlencoded body and the encoding of the page containing
// the form is iso-8859-1, or when the submitted form has an accept-charset
// attribute of iso-8859-1. Presumably also with other charsets that do not contain
// the ✓ character, such as us-ascii.
var isoSentinel = 'utf8=%26%2310003%3B'; // encodeURIComponent('&#10003;')

// These are the percent-encoded utf-8 octets representing a checkmark, indicating that the request actually is utf-8 encoded.
var charsetSentinel = 'utf8=%E2%9C%93'; // encodeURIComponent('✓')

var parseValues = function parseQueryStringValues(str, options) {
    var obj = {};
    var cleanStr = options.ignoreQueryPrefix ? str.replace(/^\?/, '') : str;
    var limit = options.parameterLimit === Infinity ? undefined : options.parameterLimit;
    var parts = cleanStr.split(options.delimiter, limit);
    var skipIndex = -1; // Keep track of where the utf8 sentinel was found
    var i;

    var charset = options.charset;
    if (options.charsetSentinel) {
        for (i = 0; i < parts.length; ++i) {
            if (parts[i].indexOf('utf8=') === 0) {
                if (parts[i] === charsetSentinel) {
                    charset = 'utf-8';
                } else if (parts[i] === isoSentinel) {
                    charset = 'iso-8859-1';
                }
                skipIndex = i;
                i = parts.length; // The eslint settings do not allow break;
            }
        }
    }

    for (i = 0; i < parts.length; ++i) {
        if (i === skipIndex) {
            continue;
        }
        var part = parts[i];

        var bracketEqualsPos = part.indexOf(']=');
        var pos = bracketEqualsPos === -1 ? part.indexOf('=') : bracketEqualsPos + 1;

        var key, val;
        if (pos === -1) {
            key = options.decoder(part, defaults.decoder, charset, 'key');
            val = options.strictNullHandling ? null : '';
        } else {
            key = options.decoder(part.slice(0, pos), defaults.decoder, charset, 'key');
            val = utils.maybeMap(
                parseArrayValue(part.slice(pos + 1), options),
                function (encodedVal) {
                    return options.decoder(encodedVal, defaults.decoder, charset, 'value');
                }
            );
        }

        if (val && options.interpretNumericEntities && charset === 'iso-8859-1') {
            val = interpretNumericEntities(val);
        }

        if (part.indexOf('[]=') > -1) {
            val = isArray(val) ? [val] : val;
        }

        if (has.call(obj, key)) {
            obj[key] = utils.combine(obj[key], val);
        } else {
            obj[key] = val;
        }
    }

    return obj;
};

var parseObject = function (chain, val, options, valuesParsed) {
    var leaf = valuesParsed ? val : parseArrayValue(val, options);

    for (var i = chain.length - 1; i >= 0; --i) {
        var obj;
        var root = chain[i];

        if (root === '[]' && options.parseArrays) {
            obj = [].concat(leaf);
        } else {
            obj = options.plainObjects ? Object.create(null) : {};
            var cleanRoot = root.charAt(0) === '[' && root.charAt(root.length - 1) === ']' ? root.slice(1, -1) : root;
            var index = parseInt(cleanRoot, 10);
            if (!options.parseArrays && cleanRoot === '') {
                obj = { 0: leaf };
            } else if (
                !isNaN(index)
                && root !== cleanRoot
                && String(index) === cleanRoot
                && index >= 0
                && (options.parseArrays && index <= options.arrayLimit)
            ) {
                obj = [];
                obj[index] = leaf;
            } else {
                obj[cleanRoot] = leaf;
            }
        }

        leaf = obj; // eslint-disable-line no-param-reassign
    }

    return leaf;
};

var parseKeys = function parseQueryStringKeys(givenKey, val, options, valuesParsed) {
    if (!givenKey) {
        return;
    }

    // Transform dot notation to bracket notation
    var key = options.allowDots ? givenKey.replace(/\.([^.[]+)/g, '[$1]') : givenKey;

    // The regex chunks

    var brackets = /(\[[^[\]]*])/;
    var child = /(\[[^[\]]*])/g;

    // Get the parent

    var segment = options.depth > 0 && brackets.exec(key);
    var parent = segment ? key.slice(0, segment.index) : key;

    // Stash the parent if it exists

    var keys = [];
    if (parent) {
        // If we aren't using plain objects, optionally prefix keys that would overwrite object prototype properties
        if (!options.plainObjects && has.call(Object.prototype, parent)) {
            if (!options.allowPrototypes) {
                return;
            }
        }

        keys.push(parent);
    }

    // Loop through children appending to the array until we hit depth

    var i = 0;
    while (options.depth > 0 && (segment = child.exec(key)) !== null && i < options.depth) {
        i += 1;
        if (!options.plainObjects && has.call(Object.prototype, segment[1].slice(1, -1))) {
            if (!options.allowPrototypes) {
                return;
            }
        }
        keys.push(segment[1]);
    }

    // If there's a remainder, just add whatever is left

    if (segment) {
        keys.push('[' + key.slice(segment.index) + ']');
    }

    return parseObject(keys, val, options, valuesParsed);
};

var normalizeParseOptions = function normalizeParseOptions(opts) {
    if (!opts) {
        return defaults;
    }

    if (opts.decoder !== null && opts.decoder !== undefined && typeof opts.decoder !== 'function') {
        throw new TypeError('Decoder has to be a function.');
    }

    if (typeof opts.charset !== 'undefined' && opts.charset !== 'utf-8' && opts.charset !== 'iso-8859-1') {
        throw new TypeError('The charset option must be either utf-8, iso-8859-1, or undefined');
    }
    var charset = typeof opts.charset === 'undefined' ? defaults.charset : opts.charset;

    return {
        allowDots: typeof opts.allowDots === 'undefined' ? defaults.allowDots : !!opts.allowDots,
        allowPrototypes: typeof opts.allowPrototypes === 'boolean' ? opts.allowPrototypes : defaults.allowPrototypes,
        arrayLimit: typeof opts.arrayLimit === 'number' ? opts.arrayLimit : defaults.arrayLimit,
        charset: charset,
        charsetSentinel: typeof opts.charsetSentinel === 'boolean' ? opts.charsetSentinel : defaults.charsetSentinel,
        comma: typeof opts.comma === 'boolean' ? opts.comma : defaults.comma,
        decoder: typeof opts.decoder === 'function' ? opts.decoder : defaults.decoder,
        delimiter: typeof opts.delimiter === 'string' || utils.isRegExp(opts.delimiter) ? opts.delimiter : defaults.delimiter,
        // eslint-disable-next-line no-implicit-coercion, no-extra-parens
        depth: (typeof opts.depth === 'number' || opts.depth === false) ? +opts.depth : defaults.depth,
        ignoreQueryPrefix: opts.ignoreQueryPrefix === true,
        interpretNumericEntities: typeof opts.interpretNumericEntities === 'boolean' ? opts.interpretNumericEntities : defaults.interpretNumericEntities,
        parameterLimit: typeof opts.parameterLimit === 'number' ? opts.parameterLimit : defaults.parameterLimit,
        parseArrays: opts.parseArrays !== false,
        plainObjects: typeof opts.plainObjects === 'boolean' ? opts.plainObjects : defaults.plainObjects,
        strictNullHandling: typeof opts.strictNullHandling === 'boolean' ? opts.strictNullHandling : defaults.strictNullHandling
    };
};

module.exports = function (str, opts) {
    var options = normalizeParseOptions(opts);

    if (str === '' || str === null || typeof str === 'undefined') {
        return options.plainObjects ? Object.create(null) : {};
    }

    var tempObj = typeof str === 'string' ? parseValues(str, options) : str;
    var obj = options.plainObjects ? Object.create(null) : {};

    // Iterate over the keys and setup the new object

    var keys = Object.keys(tempObj);
    for (var i = 0; i < keys.length; ++i) {
        var key = keys[i];
        var newObj = parseKeys(key, tempObj[key], options, typeof str === 'string');
        obj = utils.merge(obj, newObj, options);
    }

    return utils.compact(obj);
};


/***/ }),

/***/ 921:
/***/ (function(module, exports) {

/**
 * Exit intent
 */

/***/ }),

/***/ 922:
/***/ (function(module, exports) {

/**
 * Show on scroll
 */

/***/ }),

/***/ 923:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
// ESM COMPAT FLAG
__webpack_require__.r(__webpack_exports__);

// CONCATENATED MODULE: ./src/js/frontend/Utils/sliding-scroll.js
/*
 * Source: https://github.com/Robbendebiene/Sliding-Scroll/
 * y: the y coordinate to scroll, 0 = top
 * duration: scroll duration in milliseconds; default is 0 (no transition)
 * element: the html element that should be scrolled ; default is the main scrolling element
 */
function scrollToY(y) {
  var duration = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 0;
  var element = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : document.scrollingElement;
  // cancel if already on target position
  if (element.scrollTop === y) return;
  var cosParameter = (element.scrollTop - y) / 2;
  var scrollCount = 0,
      oldTimestamp = null;

  function step(newTimestamp) {
    if (oldTimestamp !== null) {
      // if duration is 0 scrollCount will be Infinity
      scrollCount += Math.PI * (newTimestamp - oldTimestamp) / duration;
      if (scrollCount >= Math.PI) return element.scrollTop = y;
      element.scrollTop = cosParameter + y + cosParameter * Math.cos(scrollCount);
    }

    oldTimestamp = newTimestamp;
    window.requestAnimationFrame(step);
  }

  window.requestAnimationFrame(step);
}
/*
 * id: the id of the element as a string that should be scrolled to
 * duration: scroll duration in milliseconds; default is 0 (no transition)
 * this function is using the scrollToY function on the main scrolling element
 */

function scrollToId(id) {
  var duration = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 0;
  var offset = Math.round(document.getElementById(id).getBoundingClientRect().top);
  scrollToY(document.scrollingElement.scrollTop + offset, duration);
}
// CONCATENATED MODULE: ./src/js/frontend/Group/Button/Button.js
function _createForOfIteratorHelper(o) { if (typeof Symbol === "undefined" || o[Symbol.iterator] == null) { if (Array.isArray(o) || (o = _unsupportedIterableToArray(o))) { var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var it, normalCompletion = true, didErr = false, err; return { s: function s() { it = o[Symbol.iterator](); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it["return"] != null) it["return"](); } finally { if (didErr) throw err; } } }; }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(n); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }



var Button_Button = /*#__PURE__*/function () {
  function Button(groupObject, data) {
    _classCallCheck(this, Button);

    this.group = groupObject;
    this.data = data;
    this.icon = data.icon.buttonIcon;
    this.show_mobile = data.device.show_mobile;
    this.show_desktop = data.device.show_desktop;
    this.style = "";
    this.button;
    this.init(); // Creates random string for this button

    this.unique = "buttonizer-button-" + Array.apply(0, Array(15)).map(function () {
      return function (charset) {
        return charset.charAt(Math.floor(Math.random() * charset.length));
      }("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789");
    }).join("");
  }
  /**
   * Initialize Button
   */


  _createClass(Button, [{
    key: "init",
    value: function init() {// Nothing to do
    }
  }, {
    key: "build",
    value: function build() {
      var _this = this;

      var button = document.createElement("a");
      button.className = "buttonizer-button";
      button.setAttribute("data-buttonizer", this.unique);

      if (this.data.action.type === "url" || this.data.action.type === "poptin") {
        button.href = this.data.action.action; // If open new tab = true, use target=_blank

        if (this.data.action.type === "url" && this.data.action.action_new_tab === true) {
          button.target = "_blank";
        }
      } else {
        button.href = "javascript:void(0)";
      } // If action is Elementor popup, add attribute


      if (this.data.action.type === "elementor_popup" || this.data.action.type === "popup_maker") {
        button.href = "#" + this.data.action.action;
      } // Check if set to mobile or desktop only
      // Just show on mobile phones


      if (this.show_mobile === true && this.show_desktop === false) {
        this.group.mobileButtonCount++;
        button.className += " button-mobile-" + this.group.mobileButtonCount + " button-hide-desktop";
      } // Just show on desktop
      else if (this.show_mobile === false && this.show_desktop === true) {
          this.group.desktopButtonCount++;
          button.className += " button-desktop-" + this.group.desktopButtonCount + " button-hide-mobile";
        } // Do not show the button
        else if (this.show_mobile === false && this.show_desktop === false) {
            button.className += " button-hide-desktop button-hide-mobile";
          } // Show on all devices
          else {
              this.group.mobileButtonCount++;
              this.group.desktopButtonCount++;
              button.className += " button-mobile-" + this.group.mobileButtonCount + " button-desktop-" + this.group.desktopButtonCount;
            } // Check which setting Show label is on desktop, then add class


      if (this.data.label.show_label_desktop === "hover") {
        button.className += " show-label-desktop-hover";
      } else if (this.data.label.show_label_desktop === "hide") {
        button.className += " label-desktop-hidden";
      } // Check which setting Show label is on mobile, then add class


      if (this.data.label.show_label_mobile === "hide") {
        button.className += " label-mobile-hidden";
      } // If button action type is facebook messenger chat, add necessary elements


      if (this.data.action.type === "messenger_chat") {
        this.addMessengerWindow();
      } // Add button label


      if (this.data.label.label.length > 0) {
        var label = document.createElement("div");
        label.className = "buttonizer-label";
        label.innerText = this.data.label.label;
        button.appendChild(label);
      } else if (this.data.label.label === "" && this.group.data.styling.menu.style === "rectangle" || this.data.label.label === "" && this.group.data.styling.menu.style === "text" || this.data.label.label === "" && this.group.data.styling.menu.style === "text-icon") {
        var _label = document.createElement("div");

        _label.className = "buttonizer-label";
        _label.innerText = this.data.name + "'s label";
        button.appendChild(_label);
      }

      var icon;

      (function () {
        icon = document.createElement("i");
        icon.className = _typeof(_this.icon) !== undefined ? _this.icon : "fa fa-user";
        button.appendChild(icon);
      })();

      button.addEventListener("click", function (e) {
        return _this.onButtonClick(e);
      }); // Edit button

      if (buttonizer_ajax.in_preview === "1") {
        // Add action button
        var editButtonAction = document.createElement("div");
        editButtonAction.className = "buttonizer-button-admin-action buttonizer-edit-action";
        editButtonAction.addEventListener("click", function (e) {
          return _this.onButtonClick(e);
        });
        editButtonAction.innerHTML = '<i class="fa fa-pencil-alt fa fa-pencil" data-no-action="true"></i>';
        editButtonAction.setAttribute("data-no-action", true);
        editButtonAction.title = "Edit button";
        editButtonAction.addEventListener("click", function () {
          window.Buttonizer.messageButtonizerAdminEditor("admin-link-redirect", {
            type: "to-button",
            data: {
              group: _this.data._group_id,
              button: _this.data._id
            }
          });
        });
        button.appendChild(editButtonAction);
      } // Generate style for this button


      this.generateStyle();
      this.button = button;
      return this.button;
    }
    /**
     *
     * @param e
     */

  }, {
    key: "onButtonClick",
    value: function onButtonClick(e) {
      if (buttonizer_ajax.in_preview === "1") {
        if (e.target.hasAttribute("data-no-action")) {
          e.preventDefault();
          return;
        }
      } // Track event


      window.Buttonizer.googleAnalyticsEvent({
        type: "button-click",
        groupName: this.group.data.name,
        buttonName: this.data.name
      });

      if (this.data.action.type === "url" || this.data.action.type === "poptin") {
        if (buttonizer_ajax.in_preview === "1") {
          if (this.data.action.action_new_tab === true) {
            window.open(this.data.action.action);
          } else {
            document.location.href = this.data.action.action;
          }
        }

        return;
      } else if (this.data.action.type === "phone") {
        document.location.href = "tel:".concat(this.data.action.action);
        return;
      } else if (this.data.action.type === "mail") {
        var mail = "mailto:".concat(this.data.action.action, "?subject=").concat(encodeURIComponent(this.data.text.subject || "Subject"), "&body=").concat(encodeURIComponent(this.data.text.body));
        document.location.href = mail;
        return;
      } else if (this.data.action.type === "backtotop") {
        scrollToY(0, 1000);
        return;
      } else if (this.data.action.type === "gotobottom") {
        // Calculate the max height of this page and scroll to it
        scrollToY(Math.max(document.body.scrollHeight, document.body.offsetHeight, document.documentElement.clientHeight, document.documentElement.scrollHeight, document.documentElement.offsetHeight), 1000);
        return;
      } else if (this.data.action.type === "gobackpage") {
        window.history.back();
        return;
      } else if (this.data.action.type === "socialsharing") {
        if (this.data.action.action === "facebook") {
          window.open("http://www.facebook.com/sharer.php?u=" + document.location.href + "&t=" + document.title + "", "popupFacebook", "width=610, height=480, resizable=0, toolbar=0, menubar=0, status=0, location=0, scrollbars=0");
          return false;
        } else if (this.data.action.action === "twitter") {
          window.open("https://twitter.com/intent/tweet?text=" + encodeURI(document.title) + " Hey! Check out this link:" + "&url=" + document.location.href + "", "popupTwitter", "width=610, height=480, resizable=0, toolbar=0, menubar=0, status=0, location=0, scrollbars=0");
          return false;
        } else if (this.data.action.action === "whatsapp") {
          window.open("https://api.whatsapp.com/send?text=" + encodeURI(document.title + " Hey! Check out this link:" + document.location.href));
        } else if (this.data.action.action === "linkedin") {
          window.open("http://www.linkedin.com/shareArticle?mini=true&url=" + document.location.href + "&title=" + encodeURI(document.title) + "&summary=" + encodeURI(document.title) + "", "popupLinkedIn", "width=610, height=480, resizable=0, toolbar=0, menubar=0, status=0, location=0, scrollbars=0");
          return false;
        } else if (this.data.action.action === "pinterest") {
          window.open("http://pinterest.com/pin/create/button/?url=".concat(document.location.href));
          return false;
        } else if (this.data.action.action === "mail") {
          window.location.href = "mailto:?subject=" + encodeURI(document.title.replace(/&/g, "").trim()) + "&body= Hey! Check out this link: " + encodeURI(document.location.href.replace(/&/g, "").trim());
        } // New actions
        else if (this.data.action.action === "reddit") {
            var reddit = "https://www.reddit.com/submit?url=".concat(encodeURI("Hey! Check out this link: " + document.location.href), "&title=").concat(encodeURI(document.title));
            window.open(reddit);
            return false;
          } else if (this.data.action.action === "tumblr") {
            window.open("https://www.tumblr.com/widgets/share/tool?shareSource=legacy&canonicalUrl=".concat(encodeURI(document.location.href), "&posttype=link"));
            return false;
          } else if (this.data.action.action === "digg") {
            window.open("http://digg.com/submit?url=".concat(encodeURI(document.location.href)));
            return false;
          } else if (this.data.action.action === "weibo") {
            window.open("http://service.weibo.com/share/share.php?url=".concat(encodeURI(document.location.href), "&title=").concat(encodeURI(document.title), "&pic=https://plus.google.com/_/favicon?domain=").concat(document.location.origin));
            return false;
          } else if (this.data.action.action === "vk") {
            window.open("https://vk.com/share.php?url=".concat(encodeURI(document.location.href), "&title=").concat(encodeURI(document.title), "&comment=Hey%20Check%20this%20out!"));
            return false;
          } else if (this.data.action.action === "ok") {
            window.open("https://connect.ok.ru/dk?st.cmd=WidgetSharePreview&st.shareUrl=".concat(encodeURI(document.location.href)));
            return false;
          } else if (this.data.action.action === "xing") {
            window.open("https://www.xing.com/spi/shares/new?url=".concat(encodeURI(document.location.href)));
            return false;
          } else if (this.data.action.action === "blogger") {
            window.open("https://www.blogger.com/blog-this.g?u=".concat(encodeURI(document.location.href), "&n=").concat(encodeURI(document.title), "&t=Check%20this%20out!"));
            return false;
          } else if (this.data.action.action === "flipboard") {
            window.open("https://share.flipboard.com/bookmarklet/popout?v=2&title=".concat(encodeURI(document.title), "&url=").concat(encodeURI(document.location.href)));
            return false;
          } else if (this.data.action.action === "sms") {
            window.open("sms:?&body=".concat(encodeURI(document.title + "Hey! Check out this link: " + document.location.href)));
            return false;
          }

        return;
      } else if (this.data.action.type === "whatsapp_pro" || this.data.action.type === "whatsapp") {
        var whatsapp = "https://wa.me/".concat(this.data.action.action).concat(this.data.text.body !== "" ? "?text=" + encodeURIComponent(this.data.text.body) : "");
        window.open(whatsapp);
        return;
      } else if (this.data.action.type === "skype") {
        /* Social Media actions */
        document.location.href = "skype:".concat(this.data.action.action, "?chat");
        return;
      } else if (this.data.action.type === "messenger") {
        window.open(this.data.action.action);
        return;
      } else if (this.data.action.type === "sms") {
        var sms = "sms:".concat(this.data.action.action).concat(this.data.text.body !== "" ? ";?&body=" + encodeURIComponent(this.data.text.body) : "");
        document.location.href = sms;
        return;
      } else if (this.data.action.type === "telegram") {
        window.open("https://telegram.me/".concat(this.data.action.action));
        return;
      } else if (this.data.action.type === "facebook") {
        window.open("https://www.facebook.com/".concat(this.data.action.action));
        return;
      } else if (this.data.action.type === "instagram") {
        window.open("https://www.instagram.com/".concat(this.data.action.action));
        return;
      } else if (this.data.action.type === "line") {
        window.open("https://line.me/R/ti/p/".concat(this.data.action.action));
        return;
      } else if (this.data.action.type === "twitter") {
        window.open("https://twitter.com/".concat(this.data.action.action));
        return;
      } else if (this.data.action.type === "twitter_dm") {
        var dms = "https://twitter.com/messages/compose?recipient_id=".concat(this.data.action.action).concat(this.data.text.body !== "" ? "&text=" + encodeURIComponent(this.data.text.body) : "");
        window.open(dms);
        return;
      } else if (this.data.action.type === "snapchat") {
        window.open("https://www.snapchat.com/add/".concat(this.data.action.action));
        return;
      } else if (this.data.action.type === "linkedin") {
        window.open("https://www.linkedin.com/".concat(this.data.action.action));
        return;
      } else if (this.data.action.type === "viber") {
        document.location.href = "viber://chat?number=".concat(this.data.action.action);
        return;
      } else if (this.data.action.type === "vk") {
        window.open("https://vk.me/".concat(this.data.action.action));
        return;
      } else if (this.data.action.type === "popup_maker") {
        return;
      } else if (this.data.action.type === "elementor_popup") {
        return;
      } else if (this.data.action.type === "popups") {
        var remove = this.data.action.action; // is NaN

        if (isNaN(remove)) {
          remove = remove.replace(/\D/g, "");
        } // Show popup


        window.SPU.show(remove);
        return;
      } else if (this.data.action.type === "waze") {
        document.location.href = this.data.action.action;
        return;
      } else if (this.data.action.type === "wechat") {
        document.location.href = "weixin://dl/chat?".concat(this.data.action.action);
        return;
      } else if (this.data.action.type === "clipboard") {
        this.copyClipboard();
        return;
      } else if (this.data.action.type === "print") {
        window.print();
        return;
      } else if (this.data.action.type === "messenger_chat") {
        if (typeof window.Buttonizer.initializedFacebookChat !== "undefined" && document.querySelectorAll(".fb-customerchat").length > 0) {
          if (document.querySelector(".fb-customerchat").querySelector("iframe").style.maxHeight === "0px") {
            FB.CustomerChat.showDialog();
          } else if (document.querySelector(".fb-customerchat").querySelector("iframe").style.maxHeight === "100%") {
            FB.CustomerChat.hideDialog();
          }
        } else {
          if (window.Buttonizer.previewInitialized) {
            window.Buttonizer.messageButtonizerAdminEditor("warning", "Facebook Messenger button is not found, it may be blocked or this domain is not allowed to load the Facebook widget.");
          } else {
            alert("Sorry, we were unable to open Facebook Messenger! Check the console for more information.");
          }
        }

        return;
      }

      console.error("Buttonizer: Error! Unknown button action!");
    }
  }, {
    key: "generateStyle",
    value: function generateStyle() {
      if (!this.data.styling.main_style) {
        // Label styling
        if (this.data.styling.label) {
          var labelStyle = "";
          var labelInteraction = "";

          if (this.data.styling.label.size) {
            labelStyle += "\n            font-size: ".concat(this.data.styling.label.size, ";\n          ");
          }

          if (this.data.styling.label.radius) {
            if (this.group.data.styling.menu.style === "square") {
              var numberPattern = /\d+/g;
              var unit = this.data.styling.label.radius.toString().includes("px") ? "px" : "%";
              var values = "50%";

              if (this.data.styling.label.radius.toString().match(numberPattern) !== null) {
                values = this.data.styling.label.radius.toString().match(numberPattern);
              }

              var topLeft = parseInt(values[0]);
              var bottomLeft = values[3] ? parseInt(values[0]) : parseInt(values[0]);
              this.group.stylesheet += "\n              [data-buttonizer=\"".concat(this.group.unique, "\"] [data-buttonizer=\"").concat(this.unique, "\"].right.buttonizer-button .buttonizer-label {\n                border-radius: ").concat(topLeft).concat(unit, " 0 0 ").concat(bottomLeft).concat(unit, " !important;\n                -moz-border-radius: ").concat(topLeft).concat(unit, " 0 0 ").concat(bottomLeft).concat(unit, " !important;\n                -webkit-border-radius: ").concat(topLeft).concat(unit, " 0 0 ").concat(bottomLeft).concat(unit, " !important;\n                -o-border-radius: ").concat(topLeft).concat(unit, " 0 0 ").concat(bottomLeft).concat(unit, " !important;\n              }\n              [data-buttonizer=\"").concat(this.group.unique, "\"] [data-buttonizer=\"").concat(this.unique, "\"].left.buttonizer-button .buttonizer-label {\n                border-radius: 0 ").concat(topLeft).concat(unit, " ").concat(bottomLeft).concat(unit, " 0 !important;\n                -moz-border-radius: 0 ").concat(topLeft).concat(unit, " ").concat(bottomLeft).concat(unit, " 0 !important;\n                -webkit-border-radius: 0 ").concat(topLeft).concat(unit, " ").concat(bottomLeft).concat(unit, " 0 !important;\n                -o-border-radius: 0 ").concat(topLeft).concat(unit, " ").concat(bottomLeft).concat(unit, " 0 !important;\n              }\n            ");
            } else {
              labelStyle += "\n            border-radius: ".concat(this.data.styling.label.radius, " !important;\n            -moz-border-radius: ").concat(this.data.styling.label.radius, " !important;\n            -webkit-border-radius: ").concat(this.data.styling.label.radius, " !important;\n            -o-border-radius: ").concat(this.data.styling.label.radius, " !important;\n          ");
            }
          }

          if (this.data.styling.label.background_interaction) {
            labelInteraction += "\n            background: ".concat(this.data.styling.label.background_interaction, ";\n          ");
          }

          if (this.data.styling.label.text_interaction) {
            labelInteraction += "\n            color: ".concat(this.data.styling.label.text_interaction, " !important;\n          ");
          }

          if (this.data.styling.label.font_family) {
            var font = "";

            var _iterator = _createForOfIteratorHelper(this.data.styling.label.font_family),
                _step;

            try {
              for (_iterator.s(); !(_step = _iterator.n()).done;) {
                var fonts = _step.value;

                if (fonts === this.data.styling.label.font_family[this.data.styling.label.font_family.length - 1]) {
                  font += fonts.value;
                } else {
                  font += fonts.value + ",";
                }
              }
            } catch (err) {
              _iterator.e(err);
            } finally {
              _iterator.f();
            }

            labelStyle += "\n            font-family: ".concat(font, ";\n          ");
          } // If it has label base styling, only append label base styling


          if (labelStyle) {
            this.group.stylesheet += "\n            [data-buttonizer=\"".concat(this.group.unique, "\"] [data-buttonizer=\"").concat(this.unique, "\"].buttonizer-button .buttonizer-label {\n              ").concat(labelStyle, "\n            }\n          ");
          } // If it has label interaction styling, only append label interaction styling


          if (labelInteraction) {
            this.group.stylesheet += "\n            [data-buttonizer=\"".concat(this.group.unique, "\"] [data-buttonizer=\"").concat(this.unique, "\"].buttonizer-button:hover .buttonizer-label {\n              ").concat(labelInteraction, "\n            }\n          ");
          }
        } // Button styling


        if (this.data.styling.button) {
          var extraStyle = "";

          if (this.data.styling.button.radius) {
            extraStyle += "\n                  border-radius: ".concat(this.data.styling.button.radius, ";\n                  ");
          }

          this.group.stylesheet += "\n              [data-buttonizer=\"".concat(this.group.unique, "\"] [data-buttonizer=\"").concat(this.unique, "\"] {\n                  background-color: ").concat(this.data.styling.button.color, ";\n                  ").concat(this.data.styling.button.color.substr(-2) === "00" ? "box-shadow: none;" : "", ";\n                  ").concat(extraStyle, "\n              }\n              \n              [data-buttonizer=\"").concat(this.group.unique, "\"] [data-buttonizer=\"").concat(this.unique, "\"]:hover {\n                  background-color: ").concat(this.data.styling.button.interaction, "\n              }\n              \n              [data-buttonizer=\"").concat(this.group.unique, "\"] [data-buttonizer=\"").concat(this.unique, "\"] .buttonizer-label {\n                  background-color: ").concat(this.data.styling.label.background, ";\n                  color: ").concat(this.data.styling.label.text, " !important;\n              }");
        }

        if (this.data.styling.button) {
          this.group.stylesheet += "\n              [data-buttonizer=\"".concat(this.group.unique, "\"].attention-animation-true.buttonizer-animation-pulse.buttonizer-desktop-has-1.buttonizer-mobile-has-1 [data-buttonizer=\"").concat(this.unique, "\"]:before, \n              [data-buttonizer=\"").concat(this.group.unique, "\"].attention-animation-true.buttonizer-animation-pulse.buttonizer-desktop-has-1.buttonizer-mobile-has-1 [data-buttonizer=\"").concat(this.unique, "\"]:after {\n                  background-color: ").concat(this.data.styling.button.color, ";\n              }\n          ");
        }

        this.group.stylesheet += "\n        [data-buttonizer=\"".concat(this.group.unique, "\"] [data-buttonizer=\"").concat(this.unique, "\"] i {\n            color: ").concat(this.data.styling.icon.color, ";\n        }\n        \n        [data-buttonizer=\"").concat(this.group.unique, "\"] [data-buttonizer=\"").concat(this.unique, "\"]:hover i{\n          color: ").concat(this.data.styling.icon.interaction, ";\n\n          transition: all 0.2s ease-in-out;\n          -moz-transition: all 0.2s ease-in-out;\n          -webkit-transition: all 0.2s ease-in-out;\n          -o-transition: all 0.2s ease-in-out;\n        }\n      ");
      }

      this.group.stylesheet += "\n        [data-buttonizer=\"".concat(this.group.unique, "\"] [data-buttonizer=\"").concat(this.unique, "\"] i {\n            font-size: ").concat(this.data.styling.icon.size, ";\n\n            transition: all 0.2s ease-in-out;\n            -moz-transition: all 0.2s ease-in-out;\n            -webkit-transition: all 0.2s ease-in-out;\n            -o-transition: all 0.2s ease-in-out;\n        }\n        ");
    }
    /**
     * Got a request to destroy this button
     * Try to remove as much references as possible
     */

  }, {
    key: "destroy",
    value: function destroy() {
      this.button.remove(); // Remove object

      this.group.removeButton(this); // Remove button from group

      this.group = null; // Remove group reference

      this.data = null; // Remove data reference

      this.button = null; // Remove button reference

      delete this;
    } // Messenger

  }, {
    key: "addMessengerWindow",
    value: function addMessengerWindow() {
      if (typeof window.Buttonizer.initializedFacebookChat !== "undefined") {
        // Already done
        return;
      }

      window.Buttonizer.initializedFacebookChat = this.data.action.action === "" ? undefined : this.data.action.action; // Initialize first

      window.fbAsyncInit = function () {
        FB.init({
          xfbml: true,
          version: "v3.3"
        });
      }; // Add script


      var fbMessengerScript = document.createElement("script");
      fbMessengerScript.innerHTML = "\n             (function(d, s, id) {\n              var js, fjs = d.getElementsByTagName(s)[0];\n              if (d.getElementById(id)) return;\n              js = d.createElement(s); js.id = id;\n              js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';\n              fjs.parentNode.insertBefore(js, fjs);\n            }(document, 'script', 'facebook-jssdk'));";
      document.head.appendChild(fbMessengerScript);
    }
  }, {
    key: "copyClipboard",
    value: function copyClipboard() {
      var dummy = document.createElement("input"),
          text = window.location.href;
      document.body.appendChild(dummy);
      dummy.value = text;
      dummy.select();
      document.execCommand("copy");
      document.body.removeChild(dummy);
      var label = document.createElement("div");
      label.className = "buttonizer-label buttonizer-label-popup";
      label.innerText = "Copied!";
      this.button.appendChild(label);
      setTimeout(function () {
        label.remove();
      }, 2000);
    }
  }]);

  return Button;
}();


// CONCATENATED MODULE: ./src/js/frontend/Group/Group.js
function Group_createForOfIteratorHelper(o) { if (typeof Symbol === "undefined" || o[Symbol.iterator] == null) { if (Array.isArray(o) || (o = Group_unsupportedIterableToArray(o))) { var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var it, normalCompletion = true, didErr = false, err; return { s: function s() { it = o[Symbol.iterator](); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it["return"] != null) it["return"](); } finally { if (didErr) throw err; } } }; }

function Group_unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return Group_arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(n); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return Group_arrayLikeToArray(o, minLen); }

function Group_arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

function Group_typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { Group_typeof = function _typeof(obj) { return typeof obj; }; } else { Group_typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return Group_typeof(obj); }

function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function Group_classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function Group_defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function Group_createClass(Constructor, protoProps, staticProps) { if (protoProps) Group_defineProperties(Constructor.prototype, protoProps); if (staticProps) Group_defineProperties(Constructor, staticProps); return Constructor; }



var Group_Group = /*#__PURE__*/function () {
  function Group(data, index) {
    Group_classCallCheck(this, Group);

    this.data = data;
    this.groupIndex = index;
    this.buttons = []; // Default parameters

    this.isBuild = false;
    this.opened = false; // Exit intent

    this.usesExitIntent = false;
    this.exitIntentExecuted = false;
    this.usesOnSroll = false;
    this.show_mobile = this.data.device.show_mobile;
    this.show_desktop = this.data.device.show_desktop;
    this.single_button_mode = this.data.single_button_mode;
    this.element = null;
    this.position_vertical = _objectSpread({
      value: 5,
      type: "%",
      mode: "bottom"
    }, this.getPositionParameters(this.data.position.vertical));
    this.position_horizontal = _objectSpread({
      value: 5,
      type: "%",
      mode: "right"
    }, this.getPositionParameters(this.data.position.horizontal)); // Menu styling

    this.groupStyle = this.data.styling.menu.style;
    this.groupAnimation = this.data.styling.menu.animation;
    this.stylesheet = "";
    this.mobileButtonCount = 0;
    this.desktopButtonCount = 0; // Creates random string for this group

    this.unique = "buttonizer-" + Array.apply(0, Array(15)).map(function () {
      return function (charset) {
        return charset.charAt(Math.floor(Math.random() * charset.length));
      }("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789");
    }).join(""); // Initialize group

    this.init();
  }
  /**
   * Initialize group
   */


  Group_createClass(Group, [{
    key: "init",
    value: function init() {
      // Create buttons
      for (var i = 0; i < this.data.buttons.length; i++) {
        var button = new Button_Button(this, this.data.buttons[i]);
        this.buttons.push(button);
      } // Build group


      this.build(); // Animate main button

      this.animate();
    }
    /**
     * Build group
     */

  }, {
    key: "build",
    value: function build() {
      var _this = this;

      // No buttons
      if (this.isBuild === true || this.buttons.length === 0) {
        return;
      } // Quick fix for migration mistake


      if (this.groupStyle === "fade-left-to-right") {
        this.groupStyle = "faded";
      }

      var group = document.createElement("div");
      group.className = "buttonizer buttonizer-group buttonizer-style-".concat(this.groupStyle, " buttonizer-animation-").concat(this.groupAnimation, " ").concat(this.position_vertical.mode, " ").concat(this.position_horizontal.mode);
      group.setAttribute("data-buttonizer", this.unique); // Check if set to mobile or desktop only

      if (this.show_mobile === true && this.show_desktop === false) {
        group.className += " button-mobile-only";
      } else if (this.show_mobile === false && this.show_desktop === true) {
        group.className += " button-desktop-only";
      } else if (this.show_mobile === false && this.show_desktop === false) {
        group.className += " button-hide";
      } // Do we need to make a array of buttons?


      if (this.buttons.length > 1) {
        group.classList.add("buttonizer-is-menu"); //check Always open

        if (this.data.openedByDefault === true) {
          if (!document.cookie.match("buttonizer_open=")) {
            setTimeout(function () {
              group.classList.add("opened");
            }, 100);
            this.opened = !this.opened;
          } else if (document.cookie.match("buttonizer_open=closed")) {} else if (document.cookie.match("buttonizer_open=opened")) {
            group.classList.add("opened");
            this.opened = true;
          }
        } // When in preview modus and button was opened


        if (buttonizer_ajax.in_preview === "1" && document.cookie.match("buttonizer_preview_" + this.groupIndex + "=opened")) {
          this.opened = true;
          group.classList.add("opened");
        }

        if (this.data.styling.menu.style === "square" || this.data.styling.menu.style === "rectangle") {
          group.classList.add("opened");
        } // Button list


        var buttonList = document.createElement("div");
        buttonList.className = "buttonizer-button-list"; // Create group button

        var mainButton = document.createElement("a");
        mainButton.href = "javascript:void(0)";
        mainButton.className = "buttonizer-button buttonizer-head"; // Check which setting Show label is on (desktop), then add class... or not...

        if (this.data.label.show_label_desktop === "hover") {
          mainButton.className += " show-label-desktop-hover";
        } else if (this.data.label.show_label_desktop === "hide") {
          mainButton.className += " label-desktop-hidden";
        } // Check which setting Show label is on (mobile), then add class... or not...


        if (this.data.label.show_label_mobile === "hide") {
          mainButton.className += " label-mobile-hidden";
        } // If label is empty, don't add label.


        var mainButtonLabel = "<div class=\"buttonizer-label\">".concat(this.data.label.groupLabel, "</div>");
        var mainButtonIcon;
        mainButtonIcon = "<i class=\"".concat(Group_typeof(this.data.icon.groupIcon) !== undefined ? this.data.icon.groupIcon : "fa fa-plus", "\"></i>");

        if (this.data.label.groupLabel.length <= 0) {
          mainButton.innerHTML = mainButtonIcon;
        } else {
          mainButton.innerHTML = mainButtonLabel + mainButtonIcon;
        }

        mainButton.addEventListener("click", function (e) {
          return _this.toggleMenu(e);
        }); // Add edit button

        if (buttonizer_ajax.in_preview === "1") {
          // Add action button
          var editGroupAction = document.createElement("div");
          editGroupAction.className = "buttonizer-button-admin-action buttonizer-edit-action";
          editGroupAction.addEventListener("click", function (e) {
            e.preventDefault();

            _this.toggleMenu();

            window.Buttonizer.messageButtonizerAdminEditor("admin-link-redirect", {
              type: "to-group",
              data: {
                group: _this.data._id
              }
            });
          });
          editGroupAction.innerHTML = '<i class="fa fa-pencil-alt fa fa-pencil"></i>';
          editGroupAction.title = "Edit group";
          mainButton.appendChild(editGroupAction);
        }

        group.appendChild(buttonList);
        group.appendChild(mainButton); // Add all buttons to page

        for (var i = 0; i < this.buttons.length; i++) {
          // check if a buttons is using messenger, add messenger widget.
          if (this.buttons[i].data.action.type === "messenger_chat") {
            var messengerDiv = document.createElement("div");
            messengerDiv.className = "fb-customerchat buttonizer-facebook-messenger-overwrite-".concat(this.unique);
            messengerDiv.setAttribute("page-id", "".concat(this.buttons[i].data.action.action));
            messengerDiv.setAttribute("greeting_dialog_display", "hide");
            group.appendChild(messengerDiv);
          }

          buttonList.appendChild(this.buttons[i].build()); // Build button
        }
      } else {
        group.appendChild(this.buttons[0].build()); // Build button
        // check if a button is using messenger, add messenger widget.

        if (this.buttons[0].data.action.type === "messenger_chat") {
          var _messengerDiv = document.createElement("div");

          _messengerDiv.className = "fb-customerchat buttonizer-facebook-messenger-overwrite-".concat(this.unique);

          _messengerDiv.setAttribute("page-id", "".concat(this.buttons[0].data.action.action));

          _messengerDiv.setAttribute("greeting_dialog_display", "hide");

          group.appendChild(_messengerDiv);
        }
      }

      group.className += " buttonizer-desktop-has-".concat(this.desktopButtonCount, " buttonizer-mobile-has-").concat(this.mobileButtonCount); // Write group

      this.element = group; // Register element

      document.body.appendChild(this.element); // Write group to body

      if (buttonizer_ajax.in_preview !== "1" && this.data.styling.menu.style !== "square" && this.data.styling.menu.style !== "rectangle" && this.data.styling.menu.style !== "text" && this.data.styling.menu.style !== "text-icon") {
        window.addEventListener("click", function () {
          if (_this.opened && !group.contains(event.target)) {
            _this.toggleMenu();
          }
        });
      }

      this.isBuild = true;
      this.writeCSS();

      if (this.data.styling.menu.style === "rectangle") {
        this.maxLabelWidth(group, "rectangle");
      }
    }
    /**
     * Toggle
     * @param e
     */

  }, {
    key: "toggleMenu",
    value: function toggleMenu() {
      var e = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;

      if (e) {
        e.preventDefault();
      } // Track event


      window.Buttonizer.googleAnalyticsEvent({
        type: "group-open-close",
        name: this.data.name,
        interaction: this.opened ? "close" : "open"
      }); // Update

      if (this.opened) {
        this.element.classList.remove("opened");
      } else {
        this.element.classList.add("opened");
      } // Update opened to opposite


      this.opened = !this.opened; // Set cookie closed-opened

      if (this.data.openedByDefault === true) {
        if (!document.cookie.match("buttonizer_open=")) {
          document.cookie = "buttonizer_open=closed";
        } else if (document.cookie.match("buttonizer_open=closed")) {
          document.cookie = "buttonizer_open=opened";
        } else if (document.cookie.match("buttonizer_open=opened")) {
          document.cookie = "buttonizer_open=closed";
        }
      } // Toggle preview status


      if (buttonizer_ajax.in_preview === "1") {
        this.togglePreviewOpened();
      }
    }
    /**
     * Toggle preview opened
     */

  }, {
    key: "togglePreviewOpened",
    value: function togglePreviewOpened() {
      if (this.opened) {
        document.cookie = "buttonizer_preview_" + this.groupIndex + "=opened";
      } else {
        document.cookie = "buttonizer_preview_" + this.groupIndex + "=closed";
      }
    }
  }, {
    key: "writeCSS",
    value: function writeCSS() {
      var stylesheet = document.createElement("style");
      stylesheet.id = this.unique;
      var css = "            \n            [data-buttonizer=\"".concat(this.unique, "\"] {\n              ").concat(this.position_horizontal.mode, ": ").concat(this.position_horizontal.value).concat(this.position_horizontal.type, ";\n              ").concat(this.position_vertical.mode, ": ").concat(this.position_vertical.value).concat(this.position_vertical.type, ";\n            }\n            \n            [data-buttonizer=\"").concat(this.unique, "\"] .buttonizer-button {\n                background-color: ").concat(this.data.styling.button.color, ";\n                color: ").concat(this.data.styling.icon.color, ";\n                border-radius: ").concat(this.data.styling.button.radius, ";\n                ").concat(this.data.styling.button.color.replace(/\D/g, "").substr(-1) === "0" ? "box-shadow: none;" : "", "\n            }\n            \n            [data-buttonizer=\"").concat(this.unique, "\"] .buttonizer-button:hover {\n                background-color: ").concat(this.data.styling.button.interaction, ";\n                color: ").concat(this.data.styling.icon.color, "\n            }\n            \n            [data-buttonizer=\"").concat(this.unique, "\"] .buttonizer-button .buttonizer-label {\n                background-color: ").concat(this.data.styling.label.background, ";\n                color: ").concat(this.data.styling.label.text, ";\n                font-size: ").concat(this.data.styling.label.size, ";\n                border-radius: ").concat(this.data.styling.label.radius, ";\n            }\n\n            [data-buttonizer=\"").concat(this.unique, "\"] .buttonizer-button i, [data-buttonizer=\"").concat(this.unique, "\"] .buttonizer-button svg {\n                color: ").concat(this.data.styling.icon.color, ";\n                font-size: ").concat(this.data.styling.icon.size, ";\n            }\n\n            [data-buttonizer=\"").concat(this.unique, "\"] .buttonizer-button:hover i, [data-buttonizer=\"").concat(this.unique, "\"] .buttonizer-button:hover svg {\n                color: ").concat(this.data.styling.icon.interaction, ";\n            }\n\n            [data-buttonizer=\"").concat(this.unique, "\"].attention-animation-true.buttonizer-animation-pulse .buttonizer-head:before, \n            [data-buttonizer=\"").concat(this.unique, "\"].attention-animation-true.buttonizer-animation-pulse .buttonizer-head:after {\n                background-color: ").concat(this.data.styling.button.color, ";\n            }\n        ");

      if (this.data.styling.label.background_interaction) {
        css += "\n            [data-buttonizer=\"".concat(this.unique, "\"] .buttonizer-button:hover .buttonizer-label {\n              background: ").concat(this.data.styling.label.background_interaction, ";\n            }");
      }

      if (this.data.styling.label.text_interaction) {
        css += "\n            [data-buttonizer=\"".concat(this.unique, "\"] .buttonizer-button:hover .buttonizer-label {\n              color: ").concat(this.data.styling.label.text_interaction, ";\n            }");
      }

      if (this.data.styling.label.font_family) {
        var font = "";

        var _iterator = Group_createForOfIteratorHelper(this.data.styling.label.font_family),
            _step;

        try {
          for (_iterator.s(); !(_step = _iterator.n()).done;) {
            var fonts = _step.value;

            if (fonts === this.data.styling.label.font_family[this.data.styling.label.font_family.length - 1]) {
              font += fonts.value;
            } else {
              font += fonts.value + ",";
            }
          }
        } catch (err) {
          _iterator.e(err);
        } finally {
          _iterator.f();
        }

        css += "\n            [data-buttonizer=\"".concat(this.unique, "\"] .buttonizer-button .buttonizer-label {\n              font-family: ").concat(font, ";\n            }");
      }

      if (this.data.buttons.length === 1 && this.groupAnimation === "pulse") {
        css += "\n                [data-buttonizer=\"".concat(this.unique, "\"].attention-animation-true.buttonizer-animation-pulse > .buttonizer-button.button-desktop-1:before, \n                [data-buttonizer=\"").concat(this.unique, "\"].attention-animation-true.buttonizer-animation-pulse > .buttonizer-button.button-desktop-1:after {\n                    background-color: ").concat(this.data.styling.button.color, ";\n                }\n\n                [data-buttonizer=\"").concat(this.unique, "\"].attention-animation-true.buttonizer-animation-pulse > .buttonizer-button.button-mobile-1:before, \n                [data-buttonizer=\"").concat(this.unique, "\"].attention-animation-true.buttonizer-animation-pulse > .buttonizer-button.button-mobile-1:after {\n                    background-color: ").concat(this.data.styling.button.color, ";\n                }\n            ");
      }

      if (typeof window.Buttonizer.initializedFacebookChat !== "undefined") {
        css += "\n                .fb_dialog {\n                    display: none !important;\n                }\n\n                .buttonizer-facebook-messenger-overwrite-".concat(this.unique, " span iframe {\n                    ").concat(this.position_horizontal.mode, ": ").concat(this.position_horizontal.value - 1).concat(this.position_horizontal.type, " !important;\n                    ").concat(this.position_vertical.mode, ": ").concat(this.position_vertical.value + 4).concat(this.position_vertical.type, " !important;\n                }\n\n                @media screen and (max-width: 769px){\n                    .buttonizer-facebook-messenger-overwrite-").concat(this.unique, " span iframe {\n                      ").concat(this.position_horizontal.mode, ": ").concat(this.position_horizontal.value - 5).concat(this.position_horizontal.type, " !important;\n                                    ").concat(this.position_vertical.mode, ": ").concat(this.position_vertical.value + 4).concat(this.position_vertical.type, " !important;\n                    }\n                }\n\n                .buttonizer-facebook-messenger-overwrite-").concat(this.unique, " span .fb_customer_chat_bounce_in_v2 {\n                    animation-duration: 300ms;\n                    animation-name: fb_bounce_in_v3 !important;\n                    transition-timing-function: ease-in-out;   \n                }\n\n                .buttonizer-facebook-messenger-overwrite-").concat(this.unique, " span .fb_customer_chat_bounce_out_v2 {\n                    max-height: 0px !important;\n                }\n\n                @keyframes fb_bounce_in_v3 {\n                    0% {\n                        opacity: 0;\n                        transform: scale(0, 0);\n                        transform-origin: bottom;\n                    }\n                    50% {\n                        transform: scale(1.03, 1.03);\n                        transform-origin: bottom;\n                    }\n                    100% {\n                        opacity: 1;\n                        transform: scale(1, 1);\n                        transform-origin: bottom;\n                    }\n                }\n            ");
      }

      css += this.stylesheet;
      stylesheet.innerHTML = css;
      document.head.appendChild(stylesheet);
    }
    /**
     * Animate main button
     */

  }, {
    key: "animate",
    value: function animate() {
      var _this2 = this;

      if (this.element === null) return;

      if (this.groupAnimation !== "none") {
        if (!this.element.classList.contains("opened")) {
          this.element.classList.add("attention-animation-true");
          setTimeout(function () {
            if (_this2.element === null) return;

            _this2.element.classList.remove("attention-animation-true");
          }, 2000);
        }

        setTimeout(function () {
          return _this2.animate();
        }, 10000);
      }
    }
    /**
     * Set show after timeout
     */

    /**
     * Set show on scroll
     */

    /**
     * Got a request to destroy this group
     * Try to remove as much references as possible
     */

  }, {
    key: "destroy",
    value: function destroy() {
      // Destroy button objects
      for (var i = 0; i < this.buttons.length; i++) {
        this.buttons[i].destroy();
      } // Remove group


      this.element.remove();
      this.element = null; // Remove stylesheet

      document.getElementById(this.unique).remove(); // Goodbye cruel world

      window.Buttonizer.destroyGroup(this);
      delete this;
    }
    /**
     * Remove button from array
     *
     * @param button
     */

  }, {
    key: "removeButton",
    value: function removeButton(button) {
      var buttonIndex = this.buttons.indexOf(button);

      if (buttonIndex >= 0) {
        this.buttons.splice(buttonIndex, 1);
      }
    }
  }, {
    key: "maxLabelWidth",
    value: function maxLabelWidth(group, style) {
      // Calculate width
      var arr = [];

      var _iterator2 = Group_createForOfIteratorHelper(group.querySelectorAll(".buttonizer-label")),
          _step2;

      try {
        for (_iterator2.s(); !(_step2 = _iterator2.n()).done;) {
          var labels = _step2.value;
          arr.push(labels.offsetWidth);
        }
      } catch (err) {
        _iterator2.e(err);
      } finally {
        _iterator2.f();
      }

      var max = Math.max.apply(Math, arr); // Add style to stylesheet

      var css = "\n                [data-buttonizer=\"".concat(this.unique, "\"].buttonizer-style-").concat(style, " .buttonizer-button .buttonizer-label {\n                        min-width: ").concat(max, "px;\n                        text-align: ").concat(this.position_horizontal.mode === "right" ? "right" : "left", ";\n                }\n            ");
      document.getElementById(this.unique).innerHTML += css;
    }
  }, {
    key: "getPositionParameters",
    value: function getPositionParameters(input) {
      if (typeof input !== "string") {
        // its a number, thus old version
        // if (input >= 50) return { value: 100 - input, mode: mirrored };
        return {
          value: input
        };
      }

      var result = input.match(/(left|right|top|bottom):\s?(\d{1,3})\s?.+;?/i);
      if (result === null) return {};
      return {
        value: typeof Number(result[2]) === "number" ? Number(result[2]) : 5,
        mode: result[1].toLowerCase()
      };
    }
    /**
     * Exit intent
     */

  }]);

  return Group;
}();


// EXTERNAL MODULE: ./node_modules/qs/lib/index.js
var lib = __webpack_require__(451);
var lib_default = /*#__PURE__*/__webpack_require__.n(lib);

// EXTERNAL MODULE: ./src/sass/frontend/frontend.scss
var frontend = __webpack_require__(1296);

// EXTERNAL MODULE: ./src/js/frontend/Utils/ExitIntent.js
var ExitIntent = __webpack_require__(921);

// EXTERNAL MODULE: ./src/js/frontend/Utils/OnScroll.js
var OnScroll = __webpack_require__(922);

// EXTERNAL MODULE: ./node_modules/axios/index.js
var axios = __webpack_require__(102);
var axios_default = /*#__PURE__*/__webpack_require__.n(axios);

// CONCATENATED MODULE: ./src/js/frontend/Buttonizer.js
function Buttonizer_typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { Buttonizer_typeof = function _typeof(obj) { return typeof obj; }; } else { Buttonizer_typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return Buttonizer_typeof(obj); }

function Buttonizer_classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function Buttonizer_defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function Buttonizer_createClass(Constructor, protoProps, staticProps) { if (protoProps) Buttonizer_defineProperties(Constructor.prototype, protoProps); if (staticProps) Buttonizer_defineProperties(Constructor, staticProps); return Constructor; }


 // Import Buttonizer Dashboard style






var Buttonizer_Buttonizer = /*#__PURE__*/function () {
  function Buttonizer() {
    Buttonizer_classCallCheck(this, Buttonizer);

    this.firstTimeInitialize = true;
    this.write = HTMLElement;
    this.previewInitialized = false;
    this.settingsLoading = false;
    this.isInPreviewContainer = false;
    this.premium = false;
    this.groups = [];
    this.exitIntent = null;
    this.onscroll = null;
    this.settings = {
      // Google analytics
      ga: null
    };

    if (buttonizer_ajax.in_preview) {
      var stylesheet = document.createElement("style");
      stylesheet.innerHTML = "html { margin-top: 0 !important; }";
      window.document.head.appendChild(stylesheet);
    }
  }
  /**
   * Get Buttonizer
   */


  Buttonizer_createClass(Buttonizer, [{
    key: "getSettings",
    value: function getSettings() {
      var _this = this;

      // Cool, we already have the data!
      if (typeof buttonizer_data !== "undefined") {
        this.init(buttonizer_data);
        return;
      } // Add current url


      buttonizer_ajax.current.url = document.location.href;
      this.settingsLoading = true;
      axios_default()({
        url: buttonizer_ajax.ajaxurl + "?action=buttonizer",
        params: {
          qpu: buttonizer_ajax.is_admin ? Date.now() : buttonizer_ajax.cache,
          preview: buttonizer_ajax.in_preview ? 1 : 0,
          data: buttonizer_ajax.current
        },
        paramsSerializer: function paramsSerializer(params) {
          return lib_default.a.stringify(params, {
            arrayFormat: "brackets"
          });
        }
      }).then(function (_ref) {
        var data = _ref.data;

        if (data.status === "success") {
          _this.init(data);
        } else {
          console.error("Buttonizer: Something went wrong! Buttonizer not loaded", data);
        }
      })["catch"](function (e) {
        _this.settingsLoading = false;
        console.error("Buttonizer: OH NO! ERROR: '" + e.statusText + "'. That's all we know... Please check your PHP logs or contact Buttonizer support if you need help.");
        console.error("Buttonizer: Visit our community on https://community.buttonizer.pro/");
      });
    }
  }, {
    key: "init",
    value: function init(data) {
      var _this2 = this;

      // Listen to admin
      if (buttonizer_ajax.in_preview && !this.previewInitialized) {
        this.isInPreviewContainer = true;
        this.listenToPreview();

        window.onerror = function () {
          var err = arguments.length <= 4 ? undefined : arguments[4];

          _this2.messageButtonizerAdminEditor("error", {
            name: err.name,
            message: err.message,
            column: err.column,
            line: err.line,
            sourceURL: err.sourceURL,
            stack: err.stack
          });
        };
      }

      if (buttonizer_ajax.in_preview === "" && // See if buttonizer preview cookie exists
      document.cookie.indexOf("buttonizer_preview_") !== -1) {
        document.cookie = // Get full cookie name of buttonizer preview then expire.
        document.cookie.substring(document.cookie.indexOf("buttonizer_preview_")).substring(0, document.cookie.substring(document.cookie.indexOf("buttonizer_preview_")).indexOf("=")) + "=;expires=Thu, 01 Jan 1970 00:00:01 GMT;";
      } // No buttons


      if (data.result.length > 0) {
        // // First visit
        // if (this.getCookie("buttonizer-first-visit") === "") {
        //   document.cookie = "buttonizer-first-visit=" + new Date().getTime();
        // }
        // Loop through the group(s)
        (function () {
          _this2.groups.push(new Group_Group(data.result[0], 0));
        })();

        if (this.firstTimeInitialize) {
          this.buttonizerInitialized();
        }
      } // Send message to admin panel


      if (buttonizer_ajax.in_preview && this.previewInitialized) {
        this.messageButtonizerAdminEditor("(re)loaded", true);
        this.messageButtonizerAdminEditor("warning", data.warning);
      }

      this.settingsLoading = false;
    }
    /**
     * Feature to message the admin buttonizer editor
     *
     * @param message
     */

  }, {
    key: "messageButtonizerAdminEditor",
    value: function messageButtonizerAdminEditor(type, message) {
      try {
        window.parent.postMessage({
          eventType: "buttonizer",
          messageType: type,
          message: message
        }, document.location.origin);
      } catch (e) {
        console.error("Buttonizer tried to warn you in the front-end editor. But the message didn't came through. Well. Doesn't matter, it's just an extra function. It's nice to have.");
        console.error(e);
      }
    }
    /**
     * Feature to receive messages from the admin buttonizer editor
     */

  }, {
    key: "listenToPreview",
    value: function listenToPreview() {
      var _this3 = this;

      this.previewInitialized = true;
      window.addEventListener("message", function (event) {
        if (!event.isTrusted || typeof event.data.eventType === "undefined" || event.data.eventType !== "buttonizer") return;
        console.log("[Buttonizer] Buttonizer preview - Data received:", event.data.messageType);

        if (buttonizer_ajax.in_preview && event.data.messageType === "preview-reload") {
          _this3.reload();
        }
      }, false);
    }
    /**
     * Reload Buttonizer
     */

  }, {
    key: "reload",
    value: function reload() {
      var _this4 = this;

      if (this.settingsLoading) {
        console.log("[Buttonizer] Request too quick, first finish the previous one");
        setTimeout(function () {
          return _this4.reload();
        }, 100);
        return;
      }

      this.settingsLoading = true;

      for (var i = 0; i < this.groups.length; i++) {
        this.groups[i].destroy();
      } // Todo: Try to find a better fix for this, why doesn't the group remove itself sometimes?


      var findButtonizer = document.querySelectorAll(".buttonizer-group");

      for (var b = 0; b < findButtonizer.length; b++) {
        findButtonizer[b].remove();
      }

      setTimeout(function () {
        _this4.groups = [];

        _this4.getSettings();
      }, 50);
    }
    /**
     * Google analytics event
     *
     * @param object
     */

  }, {
    key: "googleAnalyticsEvent",
    value: function googleAnalyticsEvent(object) {
      if (typeof ga === "function" || typeof gtag === "function" || Buttonizer_typeof(window.dataLayer) === "object" && typeof window.dataLayer.push === "function") {
        var actionData = {}; // Opening or closing a group

        if (object.type === "group-open-close") {
          actionData.groupName = object.name;
          actionData.action = object.interaction;
        } else if (object.type === "button-click") {
          actionData.groupName = object.groupName;
          actionData.action = "Clicked button: " + object.buttonName;
        } // Gtag support


        if ("gtag" in window && typeof gtag === "function") {
          // Work with Google Tag Manager
          gtag("event", "Buttonizer", {
            event_category: "Buttonizer group: " + actionData.groupName,
            event_action: actionData.action,
            event_label: document.title,
            page_url: document.location.href
          });
        } else if ("ga" in window) {
          try {
            // Fallback to tracker
            var tracker = ga.getAll()[0];

            if (tracker) {
              tracker.send("event", "Buttonizer group: " + actionData.groupName, actionData.action, document.title);
            } else {
              throw "No tracker found";
            }
          } catch (e) {
            console.error("Buttonizer Google Analytics: Last try to push to Google Analytics.");
            console.error("What does this mean?", "https://community.buttonizer.pro/knowledgebase/17"); // Fallback to old Google Analytics

            ga("send", "event", {
              eventCategory: "Buttonizer group: " + actionData.groupName,
              eventAction: actionData.action,
              eventLabel: document.title
            });
          }
        } else {
          console.error("Buttonizer Google Analytics: Unable to push data to Google Analytics");
          console.error("What does this mean?", "https://community.buttonizer.pro/knowledgebase/17");
        }
      }
    }
  }, {
    key: "getCookie",
    value: function getCookie(cname) {
      var name = cname + "=";
      var decodedCookie = decodeURIComponent(document.cookie);
      var ca = decodedCookie.split(";");

      for (var i = 0; i < ca.length; i++) {
        var c = ca[i];

        while (c.charAt(0) == " ") {
          c = c.substring(1);
        }

        if (c.indexOf(name) == 0) {
          return c.substring(name.length, c.length);
        }
      }

      return "";
    }
    /**
     * Remove button from array
     *
     * @param group
     */

  }, {
    key: "destroyGroup",
    value: function destroyGroup(group) {
      var groupIndex = this.groups.indexOf(group);

      if (groupIndex >= 0) {
        this.groups.splice(groupIndex, 1);
      }
    }
  }, {
    key: "hasPremium",
    value: function hasPremium() {
      return this.premium;
    }
    /**
     * Buttonizer is initialized, call function
     */

  }, {
    key: "buttonizerInitialized",
    value: function buttonizerInitialized() {
      // Execute only once
      if (!this.firstTimeInitialize) {
        return;
      } // Call function


      if (typeof window.buttonizerInitialized === "function") {
        window.buttonizerInitialized();
      } //If user is using Messenger and FB is not defined, call parse


      if (typeof FB === "undefined" && typeof this.initializedFacebookChat !== "undefined" && !this.isInPreviewContainer) {
        console.log("Facebook Messenger is still not initilized: RUN FB.XFBLM.PARSE");

        try {
          FB.XFBML.parse();
        } catch (err) {
          console.log("FB is not defined. \n        Is your site whitelisted correctly?\n        Is your Facebook Messenger ID correct?");
        }
      } // FB is defined, check if widget is rendered.
      else if (typeof this.initializedFacebookChat !== "undefined" && !this.isInPreviewContainer && document.querySelector(".fb-customerchat")) {
          if (document.querySelector(".fb-customerchat").querySelector("iframe") === null) {
            try {
              FB.XFBML.parse();
            } catch (err) {
              console.log("FB is defined but not rendering Messenger chat. \n              Is tracking blocked in your browser?\n              Do you have another Facebook SDK on your site?\n              \n              Error message: ".concat(err));
            }
          }
        }

      this.firstTimeInitialize = false;
    }
    /**
     * Is in preview?
     *
     * @returns {boolean}
     */

  }, {
    key: "inPreview",
    value: function inPreview() {
      return this.isInPreviewContainer;
    }
    /**
     * Start exit intent and on scroll
     */

  }]);

  return Buttonizer;
}();

window.Buttonizer = new Buttonizer_Buttonizer();
window.Buttonizer.getSettings();

/***/ })

/******/ });