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
	nameDIV.className = "results_rr_name";
	nameDIV.appendChild(document.createTextNode(result.name));

	/* Options for starsContDIV */
	starsContDIV.className = "result_stars_cont";
	let fullStars = result.rating;
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

	/* Options for tags */
	tagsContDIV.className = "results_tag_cont";
	for (let i = 0; i < result.tagUrls.length; i++) {
		let tagImage = document.createElement("img");
		tagImage.setAttribute("src", result.tagUrls[i]);
		tagImage.setAttribute("alt", "tag");
		tagsContDIV.appendChild(tagImage);
	}
	/* Options for descDIV */
	descDIV.className = "results_desc";
	descDIV.appendChild(document.createTextNode(result.desc));

	/* Options for photosContDIV */
	photosContDIV.className = "results_photos_cont";
	for (let i = 0; i < result.photoUrls.length; i++) {
		let photoImg = document.createElement ("img");
		photoImg.setAttribute ("src", result.photoUrls[i]);
		photoImg.setAttribute("alt", "photo");
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