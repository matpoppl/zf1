
/**
 * @param {string|Array|pQueryData} selector
 * @param {Node} context
 */
function pQuery(selector, context)
{
	let data = [];
	
	if ($.isArray(selector)) {
		data = selector;
	} else if ($.isString(selector)) {
		if (0 === selector.indexOf('<')) {
			data = parseHTML(selector);
		} else {
			data = (context || document).querySelectorAll(selector);
		}
	} else if (selector instanceof pQueryData) {
		return selector;
	} else if (null != selector) {
		data = [selector];
	}
	
	return new pQueryData(data);
}

class pQueryData
{
	constructor(data) {
		this.length = data.length;
		$.each(data, (i, val) => {
			this[i] = val;
		});
	}
	
	/**
	 * @param {Function} cb
	 * @returns {pQueryData}
	 */
	each(cb) {
		$.each(this, cb);
		return this;
	}
	
	/**
	 * @param {Function} cb
	 * @returns {pQueryData}
	 */
	filter(cb) {
		return $( $.filter(this, cb) );
	}
	
	/**
	 * @returns {pQueryData}
	 */
	first() {
		let ret = null;
		$.each(this, (i, item) => { ret = item; return false; });
		return $(ret);
	}
	
	/**
	 * @param {string} type
	 * @param {Function} cb
	 * @returns {pQueryData}
	 */
	on(type, cb) {
		
		cb[type] = cb[type] || new WeakMap();

		function tmp(evt) {
			if (false === cb.call(this, evt)) {
				evt.preventDefault();
			}
		}

		/**
		 * @param {number} i
		 * @param {EventTarget} target
		 */
		return this.each((i, target) => {
			let listener = tmp.bind(target);
			cb[type].set(target, listener);
			target.addEventListener(type, listener);
		});
	}
	
	/**
	 * @param {string} type
	 * @param {Function} cb
	 * @returns {pQueryData}
	 */
	off(type, cb) {
		// WeakMap not initialized
		if ('object' !== cb[type] && !(cb[type] instanceof WeakMap)) {
			return this;
		}
		
		/**
		 * @param {number} i
		 * @param {EventTarget} target
		 */
		return this.each((i, target) => {
			target.removeEventListener(type, cb[type].get(target));
		});
	}
	
	/**
	 * @param {string} name
	 * @param {string} val
	 * @returns {pQueryData|string}
	 */
	attr(name, val) {
		if (null === val) {
			return this.removeAttr(name);
		}
		if (undefined === val) {
			return $.map(this.first(), (elem) => elem.getAttribute(name)).pop();
		}
		/**
		 * @param {number} i
		 * @param {Element} elem
		 */
		return this.each((i, elem) => elem.setAttribute(name, val));
	}
	
	/**
	 * @param {string} name
	 * @returns {pQueryData}
	 */
	removeAttr(name) {
		/**
		 * @param {number} i
		 * @param {Element} elem
		 */
		return this.each((i, elem) => elem.removeAttribute(name));
	}
	
	/**
	 * @param {string} name
	 * @returns {pQueryData}
	 */
	addClass(name) {
		/**
		 * @param {number} i
		 * @param {Element} elem
		 */
		return this.each((i, elem) => elem.classList.add(name));
	}
	
	/**
	 * @param {string} name
	 * @returns {pQueryData}
	 */
	removeClass(name) {
		/**
		 * @param {number} i
		 * @param {Element} elem
		 */
		return this.each((i, elem) => elem.classList.remove(name));
	}
	
	/**
	 * @param {string} name
	 * @returns {pQueryData}
	 */
	toggleClass(name) {
		/**
		 * @param {number} i
		 * @param {Element} elem
		 */
		return this.each((i, elem) => elem.classList.toggle(name));
	}
	
	/**
	 * @returns {pQueryData}
	 */
	empty() {
		/**
		 * @param {number} i
		 * @param {Node} node
		 */
		return this.each((i, node) => {
			while(node.firstChild) {
				node.removeChild(node.firstChild);
			}
		});
	}
	
  /**
   * @returns {pQueryData}
   */
  remove() {
    /**
     * @param {number} i
     * @param {Node} node
     */
    return this.each((i, node) => {
      while(node.parentNode) {
        node.parentNode.removeChild(node);
      }
    });
  }
  
	/**
	 * @returns {pQueryData}
	 */
	find(selector) {
		const ret = new Array(this.length);
		/**
		 * @param {number} i
		 * @param {Node} node
		 */
		$.each(this, (i, node) => {
			ret[i] = $(selector, node);
		});
		return [].contact.apply(null, ret);
	}
	
	/**
	 * @param {string} textContent
	 * @returns {pQueryData}
	 */
	text(textContent) {
		return firstProp(this, 'textContent', textContent);
	}
	
	/**
	 * @param {string} innerHTML
	 * @returns {pQueryData}
	 */
	html(innerHTML) {
		return firstProp(this, 'innerHTML', innerHTML);
	}
	
	/**
	 * @param {string|pQueryData|Node[]|Node} children
	 * @returns {pQueryData}
	 */
	append(children)
	{
		return this.each((i, parent) => {
			$(children).each((j, child) => { parent.appendChild(child); });
		});
	}
	
	/**
	 * @param {string|pQueryData|Node[]|Node} parents
	 * @returns {pQueryData}
	 */
	appendTo(parents)
	{
		return this.each((i, child) => {
			$(parents).each((j, parent) => { parent.appendChild(child); });
		});
	}
}

/**
 * @param {pQuery} $data
 * @param {string} propName
 * @param {string} value
 * @returns {string}
 */
function firstProp($data, propName, value)
{
	if (undefined === value) {
		return $.map($data.first(), (node) => node[propName]).pop();
	}
	
	return $data.each((i, node) => { node[propName] = value; });
}

/**
 * @param {string} html
 * @returns {NodeList}
 */
function parseHTML(html)
{
	const div = document.createElement('div');
	div.innerHTML = html;
	return div.childNodes;
}

const $ = pQuery;

$.isArray = (val) => (val instanceof Array);
$.isString = (val) => ('string' === typeof(val) || val instanceof Array);
$.isFunction = (val) => ('function' === typeof(val));

/**
 * @param {Array} data
 * @param {Function} cb
 */
$.each = function(data, cb)
{
	for (let i = 0, z = data.length; i < z; i++) {
		if (false === cb.call(data[i], i, data[i], data)) break;
	}
}

/**
 * @param {Array} data
 * @param {Function} cb
 * @returns {Array}
 */
$.map = function(data, cb)
{
	return Array.prototype.map.call(data, cb);
}

/**
 * @param {Array} data
 * @param {Function} cb
 * @returns {Array}
 */
$.filter = function(data, cb)
{
	return Array.prototype.filter.call(data, cb);
}

/**
 * @param {Object} obj
 * @returns {Object}
 */
$.extend = function()
{
	return Object.assign.apply({}, arguments);
}

export default $;
