let tnav = {
	MOBILE_WIDTH: 600,
    DEFAULT_MAPS_POS: {
        lat: -37.816,
        lng: 144.969
    },
	/* Returns true/false if user is on mobile */
	isMobile: function() {
		return (window.innerWidth <= this.MOBILE_WIDTH);
	}
};
