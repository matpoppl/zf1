(()=> {
	
	window.addEventListener('load', () => {
		setTimeout(() => {
			
			const { requestStart, responseEnd } = performance.timing;
			
			document.title = (responseEnd - requestStart) / 1000;
			
		}, 0);
	});
	
})();