import $ from './pquery.js';

class Deferred
{
	constructor(executor)
	{
		this.resolve = [];
		this.reject = [];
		
		const self = this;
		
		executor(function() {
			$.each(self.resolve, (i, resolve) => { resolve.apply(this, arguments); });
		}, function() {
			$.each(self.reject, (i, reject) => { reject.apply(this, arguments); });
		});
	}
	
	done()
	{
		this.resolve = this.resolve.concat.apply(this.resolve, arguments)
		return this;
	}
	
	fail()
	{
		this.reject = this.reject.concat.apply(this.reject, arguments)
		return this;
	}
	
	then(resolve, reject)
	{
		resolve && this.resolve.push(resolve);
		reject && this.reject.push(reject);
		return this;
	}
	
	always(cb)
	{
		if (cb) {
			this.resolve.push(cb);
			this.reject.push(cb);
		}
		return this;
	}
}

function ajax(input)
{
	const options = $.extend({
		method: 'GET',
		data: null,
		headers: {
			'X-Requested-With': 'XMLHttpRequest',
		},
		contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
	}, $.isString(input) ? {url: input} : input);
	
	return $.Deferred((resolve, reject) => {
		const req = new XMLHttpRequest();
		
		req.addEventListener('error', (evt) => {
			reject.call(req, req, req.statusText, evt);
		});
		req.addEventListener('load', (evt) => {
			if (req.status > 199 && req.status < 300) {
				resolve.call(req, req.response, req.statusText, req);
			} else {
				reject.call(req, req, req.statusText, evt);
			}
		});
		req.open(options.method, options.url);
		
		if (options.data && ! $.isString(options.data)) {
			options.data = new URLSearchParams(options.data);
		}
		options.headers['Content-Type'] = options.contentType;
		for (let x in options.headers) {
			req.setRequestHeader(x, options.headers[x]);
		}
		
		req.send(options.data);
	});
}

export default ($ => {
	
	$.Deferred = function(executor)
	{
		return new Deferred(executor);
	}

	$.ajax = ajax;
	
	return $;
});
