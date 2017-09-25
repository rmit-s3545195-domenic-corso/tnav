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

tnav.interactiveMap.ui.getStarsHTML = function(n) {
    if (n == undefined) n = 0;

    let html = "";

    let fullStars = 0 || n;
    let emptyStars = 5 - n;

    for (let i = 0; i < fullStars; i++) {
        html += "<span class='glyphicon glyphicon-star'></span>";
    }

    for (let i = 0; i < emptyStars; i++) {
        html += "<span class='glyphicon glyphicon-star-empty'></span>";
    }

    return html;
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
    let buttonsDIV = document.createElement("div");

	/* Options for containerDIV */
	containerDIV.className = "result_container";

	/* Options for headerDIV */
	headerDIV.className = "result_header";

	/* Options for nameDIV */
	nameDIV.className = "result_rr_name";
	nameDIV.appendChild(document.createTextNode(resultNum + ". " + result.name));

	/* Options for starsContDIV */
	starsContDIV.className = "result_stars_cont";
	starsContDIV.innerHTML = this.getStarsHTML(result.stars);
	
    if (result.tagUrls && result.tagUrls.length) {
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
	descDIV.appendChild(document.createTextNode(result.description || ""));

	/* Options for photosContDIV */
	photosContDIV.className = "result_photos_cont image-group";

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
    buttonsDIV.className = "result_buttons_div";

    let openInMapsButton = document.createElement("button");
    openInMapsButton.className = "btn btn-success";
    openInMapsButton.appendChild(document.createTextNode("Navigate in Maps"));
    openInMapsButton.addEventListener("click", function() {
        let originStr = tnav.location.recentLoc.lat + ',' + tnav.location.recentLoc.lng;
        let destinationStr = result.lat + ',' + result.lng;

        window.open("https://www.google.com/maps/dir/?api=1&origin=" + originStr + "&destination=" + destinationStr + "&travelmode=walking");
    });

    let directionsButton = document.createElement("button");
    directionsButton.className = "btn btn-primary";
    directionsButton.appendChild(document.createTextNode("Directions"));
    directionsButton.addEventListener("click", function() {
        tnav.interactiveMap.navigation.startNavigation(new google.maps.LatLng(
            result.lat,
            result.lng
        ));
    });

    let reviewsButton = document.createElement("button");
    reviewsButton.className = "btn btn-warning";
    reviewsButton.appendChild(document.createTextNode("Reviews"));
    reviewsButton.addEventListener("click", function() {
        tnav.interactiveMap.reviews.showWithRestroom(result);
    });

    let reportButton = document.createElement("button");
    reportButton.className = "btn btn-danger";
    reportButton.appendChild(document.createTextNode("Report"));
    reportButton.addEventListener("click", function(e) {
        e.target.disabled = true;
        tnav.interactiveMap.reportRestroom(result.id); 
    });

    buttonsDIV.appendChild(openInMapsButton);
    buttonsDIV.appendChild(directionsButton);
    buttonsDIV.appendChild(reviewsButton);
    buttonsDIV.appendChild(reportButton);

	/* Add/append childs */
	headerDIV.appendChild(nameDIV);
	headerDIV.appendChild(starsContDIV);
	containerDIV.appendChild(headerDIV);
	containerDIV.appendChild(tagsContDIV);
	containerDIV.appendChild(descDIV);
	containerDIV.appendChild(photosContDIV);
    containerDIV.appendChild(buttonsDIV);


	return containerDIV;
};

tnav.interactiveMap.ui.addResultCont = function(resultCont) {
	this.e.results.appendChild(resultCont);
};

tnav.interactiveMap.ui.clearResults = function() {
    let results = this.e.results.querySelectorAll(".result_container");

    for (let i = 0; i < results.length; i++) {
        this.e.results.removeChild(results[i]);
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

    ImageGallery.addThumbnailListeners();
};

tnav.interactiveMap.ui.init = function() {
	this.adjustAll();
	this.addListeners();
};

tnav.interactiveMap.ui.addListeners = function() {
	window.addEventListener("resize", this.evtCallbacks.windowResized.bind(this));
};

tnav.interactiveMap.ui.init();
