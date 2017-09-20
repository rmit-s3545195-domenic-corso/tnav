tnav.interactiveMap.reviews = {
    REPORT_THRESHOLD: 10,
    e: {
        map: document.getElementById("map"),
        overlay: document.getElementById("reviews_cont"),
        
        closeBtn: document.getElementById("reviews_cont_close_btn"),
        starsCont: document.getElementById("reviews_cont_stars_cont"),
        restroomName: document.getElementById("reviews_cont_restroom_name"),
        authorInput: document.getElementById("reviews_cont_author_inp"),
        bodyInput: document.getElementById("reviews_cont_body_inp"),
        submitBtn: document.getElementById("reviews_cont_submit_btn"),
        allReviewsCont: document.getElementById("reviews_cont_all_reviews_cont")
    },
    
    activeRestroom: null,
    selectedStars: 1,
    
    evtCallbacks: {
        starPressed: function(e) {
            let starPressed = e.target;
            let starPressedIndex = BL.getChildIndex(starPressed);
            
            this.e.starsCont.innerHTML = tnav.interactiveMap.ui.getStarsHTML(starPressedIndex + 1);
            this.selectedStars = starPressedIndex + 1;
            this.addStarListeners();
        },
        closeBtnPressed: function() {
            this.setOverlayVisible(false);
        },
        submitBtnPressed: function() {
            /* Build request parameters. */
            let reviewProps = {};
            
            reviewProps.restroom_id = this.activeRestroom.id;
            reviewProps.author = this.e.authorInput.value;
            reviewProps.body = this.e.bodyInput.value;
            reviewProps.stars = this.selectedStars;
            
            this.ajax.addReview(reviewProps);
        }
    }
};

tnav.interactiveMap.reviews.addStarListeners = function() {
    let stars = this.e.starsCont.querySelectorAll("span");
    
    for (let i = 0; i < stars.length; i++) {
        stars[i].addEventListener("click", this.evtCallbacks.starPressed.bind(this));
    }
};

tnav.interactiveMap.reviews.addListeners = function() {
    this.e.closeBtn.addEventListener("click",
        this.evtCallbacks.closeBtnPressed.bind(this));
    
    this.e.submitBtn.addEventListener("click",
        this.evtCallbacks.submitBtnPressed.bind(this));
};

tnav.interactiveMap.reviews.init = function(mapElem) {
    this.addListeners();
};

/* Show overlay and hide map when true, hide overlay and show mp
when false. */
tnav.interactiveMap.reviews.setOverlayVisible = function(b) {
    let overlayDispVal, mapOpacVal;
    
    switch (b) {
        case true:
            overlayDispVal = "block";
            mapOpacVal = "0";
            window.scrollTo(0, this.e.overlay.offsetTop);
            break;
        case false:
        default:
            overlayDispVal = "none";
            mapOpacVal = "1";
            break;
    }
    
    this.e.overlay.style.display = overlayDispVal;
    this.e.map.style.opacity = mapOpacVal;
};

/* Generates HTML (parent element) for a review as part of 'All Reviews' */
tnav.interactiveMap.reviews.generateReview = function(review) {
    let containerDiv = document.createElement("div");
    let authorDiv = document.createElement("div");
    let starsDiv = document.createElement("div");
    let bodyDiv = document.createElement("div");
    let addedDiv = document.createElement("div");
    let reportDiv = document.createElement("div");
    
    containerDiv.setAttribute("class", "restroom_review");
    
    authorDiv.setAttribute("class", "restroom_review_author");
    authorDiv.appendChild(document.createTextNode(review.author || ""));
    containerDiv.appendChild(authorDiv);
    containerDiv.appendChild(document.createTextNode(" - "));
    
    starsDiv.setAttribute("class", "restroom_review_stars");
    starsDiv.innerHTML = tnav.interactiveMap.ui.getStarsHTML(review.stars);
    containerDiv.appendChild(starsDiv);
    
    bodyDiv.setAttribute("class", "restroom_review_body");
    bodyDiv.appendChild(document.createTextNode(review.body || ""));
    containerDiv.appendChild(bodyDiv);
    
    addedDiv.setAttribute("class", "restroom_review_added");
    addedDiv.appendChild(document.createTextNode(review.created_at));
    containerDiv.appendChild(addedDiv);
    
    reportDiv.setAttribute("class", "restroom_review_report");
    reportDiv.appendChild(document.createTextNode("REPORT"));
    reportDiv.addEventListener("click", function() {
        reportDiv.innerHTML = "REPORTED";
        reportDiv.style.color = "#CCC";
        this.ajax.reportReview(review.id);
    }.bind(this));
    
    containerDiv.appendChild(reportDiv);
    
    return containerDiv;
};

tnav.interactiveMap.reviews.clearAllReviews = function() {
    while (this.e.allReviewsCont.firstChild) {
        this.e.allReviewsCont.removeChild(this.e.allReviewsCont.firstChild);
    }
};

tnav.interactiveMap.reviews.showWithRestroom = function(restroom) {
    this.clearAllReviews();
    
    /* Show each restroom as part of review. */
    for (let r of restroom.reviews) {
        if (r.reports >= this.REPORT_THRESHOLD) {
            continue;
        }

        if (!r.author) {
            r.author = "Anonymous";
        }
        this.e.allReviewsCont.appendChild(this.generateReview(r));
    }
    
    this.e.restroomName.innerHTML = restroom.name;
    this.e.starsCont.innerHTML = tnav.interactiveMap.ui.getStarsHTML(0);
    this.e.authorInput.value = "";
    this.e.bodyInput.value = "";
    
    this.setOverlayVisible(true);
    this.activeRestroom = restroom;
    this.addStarListeners();
};

tnav.interactiveMap.reviews.ajax = {
    reportReview: function(id) {
        BL.httpGET('report-review/' + id, {}, function(response) {
            console.log(response);
        });
    },
    addReview: function(reviewProps) {
        BL.httpGET('add-review', reviewProps, function(response) {
            if (response == "SUCCESS") {
                tnav.interactiveMap.reviews.setOverlayVisible(false);
                alert("Review submitted!");
            } else {
                alert(response);
            }
        });
    }
};