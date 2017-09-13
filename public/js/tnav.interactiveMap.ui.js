tnav.interactiveMap.ui = {
	e: {
		mapResultsCont: document.getElementById("map_results_cont"),
		map: document.getElementById("map"),
		results: document.getElementById("results")
	},
	evtCallbacks: {
		windowResized: function() {
			this.adjustAll();
		}
	}
};

tnav.interactiveMap.ui.adjustAll = function() {
	if (tnav.isMobile()) {
		this.adjustMapResultsContMobile();
        if (tnav.interactiveMap.map) {
            tnav.interactiveMap.map.setZoom(tnav.interactiveMap.MOBILE_ZOOM);
        }
		return;
	}
    else {
        if (tnav.interactiveMap.map) {
            tnav.interactiveMap.map.setZoom(tnav.interactiveMap.DESKTOP_ZOOM);
        }
    }

	this.adjustMapResultsContDesktop();
};

tnav.interactiveMap.ui.adjustMapResultsContDesktop = function() {
	let offsetTop = this.e.mapResultsCont.offsetTop;
	let windowHeight = window.innerHeight;
	let calculatedHeight = windowHeight - offsetTop;

	this.e.mapResultsCont.style.height = calculatedHeight + "px";
};

tnav.interactiveMap.ui.adjustMapResultsContMobile = function() {
	this.e.mapResultsCont.style.height = "400px";
};

tnav.interactiveMap.ui.generateResultCont = function(result, resultNum) {
	/* Make DIVs */
	let containerDIV = document.createElement("div");
	let headerDIV = document.createElement("div");
	let nameDIV = document.createElement("div");
	let starsContDIV = document.createElement("div");
	let tagsContDIV = document.createElement("div");
	let descDIV = document.createElement("div");
	let photosContDIV = document.createElement("div");
    let startNavigationDIV = document.createElement("div");

	/* Options for containerDIV */
	containerDIV.className = "result_container";

	/* Options for headerDIV */
	headerDIV.className = "result_header";

	/* Options for nameDIV */
	nameDIV.className = "result_rr_name";
	nameDIV.appendChild(document.createTextNode(resultNum + ". " + result.name));

	/* Options for starsContDIV */
	starsContDIV.className = "result_stars_cont";
	let fullStars = 0 | result.rating;
	let emptyStars = 5 - fullStars;

	/* Add full stars */
	for (let i = 0; i < fullStars; i++) {
		let star = document.createElement("span");
		star.className = "glyphicon glyphicon-star";
		starsContDIV.appendChild(star);
	}

	/* Add empty stars */
	for (let i = 0; i < emptyStars; i++) {
		let star = document.createElement("span");
		star.className = "glyphicon glyphicon-star-empty";
		starsContDIV.appendChild(star);
	}

    if (result.tagUrls) {
    	/* Options for tags */
    	tagsContDIV.className = "result_tag_cont";
    	for (let i = 0; i < result.tagUrls.length; i++) {
    		let tagImage = document.createElement("img");
    		tagImage.setAttribute("src", result.tagUrls[i]);
    		tagImage.setAttribute("alt", "tag");
    		tagsContDIV.appendChild(tagImage);
    	}
    }
	/* Options for descDIV */
	descDIV.className = "result_desc";
	descDIV.appendChild(document.createTextNode(result.description));

	/* Options for photosContDIV */
	photosContDIV.className = "result_photos_cont";
    
    if (result.photoUrls) {
        for (let i = 0; i < result.photoUrls.length; i++) {
            let photoImg = document.createElement ("img");
            photoImg.setAttribute ("src", result.photoUrls[i]);
            photoImg.setAttribute("alt", "photo");
            photoImg.className = "restroom_img";
            photosContDIV.appendChild(photoImg);
        }
    }
    
    /* Options for startNavigationDIV */
    startNavigationDIV.className = "result_start_nav_div";
    
    let startNavButton = document.createElement("button");
    startNavButton.className = "btn btn-primary start_nav_btn";
    startNavButton.appendChild(document.createTextNode("Navigate"));
    startNavButton.addEventListener("click", function() {
        tnav.interactiveMap.navigation.startNavigation(new google.maps.LatLng(
            result.lat,
            result.lng
        ));
    });
    
    startNavigationDIV.appendChild(startNavButton);

	/* Add/append childs */
	headerDIV.appendChild(nameDIV);
	headerDIV.appendChild(starsContDIV);
	containerDIV.appendChild(headerDIV);
	containerDIV.appendChild(tagsContDIV);
	containerDIV.appendChild(descDIV);
	containerDIV.appendChild(photosContDIV);
    containerDIV.appendChild(startNavigationDIV);
    
	return containerDIV;
};

tnav.interactiveMap.ui.addResultCont = function(resultCont) {
	this.e.results.appendChild(resultCont);
};

tnav.interactiveMap.ui.clearResults = function() {
    while (this.e.results.firstChild) {
        this.e.results.removeChild(this.e.results.firstChild);
    }
};

/* Accepts an array of results (restrooms) and updates the panel to include them
in the results */
tnav.interactiveMap.ui.addNewResultSet = function(results) {
    this.clearResults();

    let resultCont;
    for (let i = 0; i < results.length; i++) {
        resultCont = this.generateResultCont(results[i], i + 1);
        this.addResultCont(resultCont);
    }
    
    imageViewer.addListeners();
};

tnav.interactiveMap.ui.init = function() {
	this.adjustAll();
	this.addListeners();
};

tnav.interactiveMap.ui.addListeners = function() {
	window.addEventListener("resize", this.evtCallbacks.windowResized.bind(this));
};

tnav.interactiveMap.ui.init();