InteractiveMap.ui = {
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

InteractiveMap.ui.adjustAll = function() {
	if (tnav.isMobile()) {
		this.adjustMapResultsContMobile();
		return;
	}

	this.adjustMapResultsContDesktop();
};

InteractiveMap.ui.adjustMapResultsContDesktop = function() {
	let offsetTop = this.e.mapResultsCont.offsetTop;
	let windowHeight = window.innerHeight;
	let calculatedHeight = windowHeight - offsetTop;

	this.e.mapResultsCont.style.height = calculatedHeight + "px";
};

InteractiveMap.ui.adjustMapResultsContMobile = function() {
	this.e.mapResultsCont.style.height = "400px";
};

InteractiveMap.ui.generateResultCont = function(result) {
	/* Make DIVs */
	let containerDIV = document.createElement("div");
	let headerDIV = document.createElement("div");
	let nameDIV = document.createElement("div");
	let starsContDIV = document.createElement("div");
	let tagsContDIV = document.createElement("div");
	let descDIV = document.createElement("div");
	let photosContDIV = document.createElement("div");

	/* Options for containerDIV */
	containerDIV.className = "result_container";

	/* Options for headerDIV */
	headerDIV.className = "result_header";

	/* Options for nameDIV */
	nameDIV.className = "result_rr_name";
	nameDIV.appendChild(document.createTextNode(result.name));

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
	for (let i = 0; i < result.photoUrls.length; i++) {
		let photoImg = document.createElement ("img");
		photoImg.setAttribute ("src", result.photoUrls[i]);
		photoImg.setAttribute("alt", "photo");
        photoImg.className = "restroom_img";
		photosContDIV.appendChild(photoImg);
	}

	/* Add/append childs */
	headerDIV.appendChild(nameDIV);
	headerDIV.appendChild(starsContDIV);
	containerDIV.appendChild(headerDIV);
	containerDIV.appendChild(tagsContDIV);
	containerDIV.appendChild(descDIV);
	containerDIV.appendChild(photosContDIV);
	return containerDIV;
};

InteractiveMap.ui.addResultCont = function(resultCont) {
	this.e.results.appendChild(resultCont);
};

InteractiveMap.ui.clearResults = function() {
    while (this.e.results.firstChild) {
        this.e.results.removeChild(this.e.results.firstChild);
    }
};

/* Accepts an array of results (restrooms) and updates the panel to include them
in the results */
InteractiveMap.ui.addNewResultSet = function(results) {
    this.clearResults();

    let resultCont;
    for (let i = 0; i < results.length; i++) {
        resultCont = this.generateResultCont(results[i]);
        this.addResultCont(resultCont);
    }
};

InteractiveMap.ui.init = function() {
	this.adjustAll();
	this.addListeners();

	/* dev (remove after) */
	let resultObj = {
		name: "Restroom Name",
		desc: "It's pretty good in here",
		rating: 4,
		photoUrls: [
			"img/rr_photo_2.jpg",
			"img/rr_photo_5.jpg",
            "img/rr_photo_1.jpg",
            "img/rr_photo_3.jpg",
            "img/rr_photo_4.jpg",
            "img/rr_photo_4.jpg",
            "img/rr_photo_4.jpg",
            "img/rr_photo_4.jpg",
		],
		tagUrls: [
			"img/unisex.png",
			"img/female.png",
			"img/baby.png"
		]
	};

	let newResult = this.generateResultCont(resultObj);
	this.addResultCont(newResult);
};

InteractiveMap.ui.addListeners = function() {
	window.addEventListener("resize", this.evtCallbacks.windowResized.bind(this));
};

InteractiveMap.ui.init();
