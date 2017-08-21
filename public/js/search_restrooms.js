let SearchRestrooms = {
    e: {
      searchInp: document.getElementById("rr_search_input"),
      resultsCont: document.getElementById("search_results")
    },
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
	/* Create the DIVs :) for the containers */
	let searchResultDIV = document.createElement("div");
	let resultInfoDIV = document.createElement("div");
	let resultDeleteDIV = document.createElement("div");
	let nameHeading = document.createElement("h6");
	let descriptionParagraph = document.createElement("p");
	let deleteButton = document.createElement("button");

	/* Add class names for the DIVs */
	searchResultDIV.className = "search_result";

	/* Options for resultsInfoDIV */
	resultInfoDIV.className = "ss_rr_info_cont";
	nameHeading.appendChild(document.createTextNode(restroom.name));
	descriptionParagraph.appendChild(document.createTextNode(restroom.description));
	resultInfoDIV.appendChild(nameHeading);
	resultInfoDIV.appendChild(descriptionParagraph);

	resultDeleteDIV.className = "ss_rr_delete_cont";
	deleteButton.className = "btn btn-danger";

	deleteButton.addEventListener("click", function() {

		BL.httpGET("/delete/" + restroom.id, null, function() {

			this.evtCallbacks.inputChanged.bind(this)();

		}.bind(this));

	}.bind(this));

	deleteButton.appendChild(document.createTextNode("Delete"));
	resultDeleteDIV.appendChild(deleteButton);

	searchResultDIV.appendChild(resultInfoDIV);
	searchResultDIV.appendChild(resultDeleteDIV);

	return searchResultDIV;
};

SearchRestrooms.init();
