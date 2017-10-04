tnav.interactiveMap.customSearch = {
    geocoder: null,
    acObj: null,
    
    e: {
        inpSearch: document.getElementById("inp_search"),
        btnSearch: document.getElementById("btn_search")
    },
    evtCallbacks: {
        placeChanged: function() {
            let place = this.acObj.getPlace();
            
            if (!place.geometry) {
                return;
            }
        },
        btnClicked: function() {
            let userInp = this.e.inpSearch.value;
            
            this.geocoder.geocode({"address": userInp}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    let firstResultLoc = results[0].geometry.location;
                    let latLng = new google.maps.LatLng();
                    
                    latLng.lat = firstResultLoc.lat();
                    latLng.lng = firstResultLoc.lng();
                    
                    tnav.interactiveMap.fetchRestroomList(latLng);
                }
            });
        }
    }
};

tnav.interactiveMap.customSearch.addListeners = function() {
    google.maps.event.addListener(this.acObj, "place_changed", 
                                  this.evtCallbacks.placeChanged.bind(this));
    
    this.e.btnSearch.addEventListener("click", this.evtCallbacks.btnClicked.bind(this));
    this.e.inpSearch.addEventListener("keypress", function(e) {
        const ENTER_KEY = 13;
        
        if (e.keyCode == ENTER_KEY) {
            this.evtCallbacks.btnClicked.bind(this)();
        }
    }.bind(this));
};

tnav.interactiveMap.customSearch.init = function() {
    this.geocoder = new google.maps.Geocoder();
    
    this.acObj = new google.maps.places.Autocomplete(this.e.inpSearch);
    this.acObj.setTypes(['geocode']);
    
    this.addListeners();
};