let DeleteRestroom = {
    e: {
      searchInp: document.getElementById("rr_search_input")
    },
};

DeleteRestroom.evtCallbacks = {
    inputChanged: function() {
        console.log(this.e.searchInp.value);
    }
};

DeleteRestroom.init = function() {
    this.addListeners();
};

DeleteRestroom.addListeners = function() {
    this.e.searchInp.addEventListener("input", this.evtCallbacks.inputChanged.bind(this));
};

DeleteRestroom.init();
