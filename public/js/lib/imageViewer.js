let imageViewer = {
    activeIndex: 0,
    currentPhotoUrls: [],

    e: {
        /* Elements for the enlarged image screen */
        enlargedPhotoContainer: document.getElementById("photo_cont"),
        enlargedImageElement: document.getElementById("rr_image_display"),
        caption: document.getElementById("caption"),
        leftBtn: document.getElementById("left"),
        rightBtn: document.getElementById("right"),
        closeBtn: document.getElementById("close"),
        currentPhotoNumber: document.getElementById("current_photo_number"),
        totalPhotoNumber: document.getElementById("total_photo_number"),
    },
};

imageViewer.evtCallbacks = {
    imageClicked: function(mouseEvent) {
        /* Setting the current photo container to the parent element of clicked target image in an array */
        let photoContainer = mouseEvent.target.parentElement;
        let allImages = Array.from(photoContainer.getElementsByClassName('restroom_img'));

        this.currentPhotoUrls = allImages.map(function(imageElement) {
            return imageElement.getAttribute("src");
        });

        /* Set the active index to the element that is equal in the current photo container array */
        this.activeIndex = allImages.indexOf(mouseEvent.target);

        this.e.enlargedPhotoContainer.style.display = "block";

        this.e.enlargedImageElement.src = mouseEvent.target.getAttribute("src");

        this.e.caption.innerHTML = mouseEvent.target.getAttribute("alt");

        /* Set the inital photo count elements */
        this.e.currentPhotoNumber.innerHTML = this.activeIndex + 1;
        this.e.totalPhotoNumber.innerHTML = this.currentPhotoUrls.length;
    },

    closeImage: function() {
        this.e.enlargedPhotoContainer.style.display = "none";
    },

    previousImage: function() {
        if (this.activeIndex - 1 < 0) {
            this.activeIndex = this.currentPhotoUrls.length - 1;
            return this.update();
        }

        this.activeIndex--;
        this.update();
    },

    nextImage: function() {
        if (this.activeIndex + 1 >= this.currentPhotoUrls.length) {
            this.activeIndex = 0;
            return this.update();
        }

        this.activeIndex++;
        this.update();
    }
};

imageViewer.update = function () {
    this.e.enlargedImageElement.setAttribute("src", this.currentPhotoUrls[this.activeIndex]);
    this.e.currentPhotoNumber.innerHTML = this.activeIndex + 1;
};

imageViewer.addListeners = function() {
    let restroomImages = document.getElementsByClassName('restroom_img');

    for (let i = 0; i < restroomImages.length; i++) {
        restroomImages[i].addEventListener("click", this.evtCallbacks.imageClicked.bind(this));
    }

    this.e.closeBtn.addEventListener("click", this.evtCallbacks.closeImage.bind(this));
    this.e.leftBtn.addEventListener("click", this.evtCallbacks.previousImage.bind(this));
    this.e.rightBtn.addEventListener("click", this.evtCallbacks.nextImage.bind(this));

    document.addEventListener("keydown", function(keyevent) {
        const EXIT_KEY = 27, LEFT_KEY = 37, RIGHT_KEY = 39;

        switch (keyevent.keyCode) {
            case EXIT_KEY:
                this.e.enlargedPhotoContainer.style.display = "none";
                break;
            case LEFT_KEY:
                this.evtCallbacks.previousImage.bind(this)();
                break;
            case RIGHT_KEY:
                this.evtCallbacks.nextImage.bind(this)();
                break;
        }
    }.bind(this));
};

imageViewer.init = function() {
    this.addListeners();
};

imageViewer.init();
