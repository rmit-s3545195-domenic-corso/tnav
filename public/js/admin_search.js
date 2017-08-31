let SearchRestrooms = {
    e: {
      searchInp: document.getElementById("search_input"),
      resultsCont: document.getElementById("results_cont")
    }
};

SearchRestrooms.evtCallbacks = {
    inputChanged: function() {
    	this.clearResults();

        BL.httpGET("/search-query", {q: this.e.searchInp.value}, function(response) {
        	responseObj = JSON.parse(response);

        	for (let i = 0; i < responseObj.length; i++) {
        		this.e.resultsCont.appendChild(this.generateResultElement(responseObj[i]));
        	}
        }.bind(this));
    }
};

SearchRestrooms.init = function() {
    this.addListeners();
};

SearchRestrooms.addListeners = function() {
    this.e.searchInp.addEventListener("input", this.evtCallbacks.inputChanged.bind(this));
};

SearchRestrooms.clearResults = function() {
	while (this.e.resultsCont.firstChild) {
		this.e.resultsCont.removeChild(this.e.resultsCont.firstChild);
	}
};

SearchRestrooms.generateResultElement = function(restroom) {
	/* Create the DIVs for the containers */
	let mainContDIV = document.createElement("div");
	let nameHeading = document.createElement("a");
	let descriptionParagraph = document.createElement("p");

	/* Add class names for the DIVs */
	mainContDIV.className = "search_result";


    nameHeading.className = "rr_title";
    nameHeading.setAttribute("href", "/edit/" + restroom.id);
    nameHeading.setAttribute("target", "_blank");
	nameHeading.appendChild(document.createTextNode(restroom.name));
	descriptionParagraph.appendChild(document.createTextNode(restroom.description));

	mainContDIV.appendChild(nameHeading);
	mainContDIV.appendChild(descriptionParagraph);

	return mainContDIV;
};

SearchRestrooms.init();
