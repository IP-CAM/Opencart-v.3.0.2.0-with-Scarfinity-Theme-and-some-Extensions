var callEvent = function(event, list, args) {
	_.forEach(_.filter(list, function(listener) {
		return listener.event === event;
	}), function(listener) {
		listener.callback(args);
	});
}

var cart_2_0 = {
	'productsCount': 0,
	'add': function(count = 1) {
		this.productsCount += count;
		callEvent('afterAdd', this.listeners, { productsCount: this.productsCount });
	},
	'listeners': [],
	'addEventListener': function(event, callback) {
		this.listeners.push({ 'event': event, 'callback': callback });
	}
};

cart_2_0.addEventListener('afterAdd', function(data) {
	console.log('event: ', data);
});