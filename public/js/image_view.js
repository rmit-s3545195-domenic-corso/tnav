let ImageViewer = {
    e: {
        // Get the enlarged screen, caption, left, right, close elements
        photo_cont: document.getElementById("photo_cont"),
        image_content: document.getElementById("rr_image_display"),
        caption: document.getElementById("caption"),
        left_btn: document.getElementById("left"),
        right_btn: document.getElementById("right"),
        close_btn: document.getElementById("close"),
        restroom_image: document.getElementsByClassName('restroom_img'),
        current_photo_count: document.getElementById("current_photo_number"),
        total_photo_count: document.getElementById("total_photo_count"),
        photo_location: 1,
        current_photo_cont: null
    }
};

ImageViewer.evtCallbacks = {
    imageClicked: function(mouseEvent) {

        /* Setting the current photo container to the parent element of clicked target image */
        this.e.current_photo_cont = mouseEvent.target.parentElement;

        /* Set the enlarged photo container to show */
        this.e.photo_cont.style.display = "block";

        /* set the photo container image source to the current clicked source */
        this.e.image_content.src = mouseEvent.target.getAttribute("src");

        /* Set the Caption text to the current image alt */
        this.e.caption.innerHTML = mouseEvent.target.getAttribute("alt");

        /* Set the inital photo count elements */
        this.e.photo_location = ImageViewer.findCurrentIndex(0) + 1;

        this.e.current_photo_count.innerHTML = this.e.photo_location;

        this.e.total_photo_count.innerHTML = this.e.current_photo_cont.children.length;
    },

    closeClicked: function() {
        this.e.photo_cont.style.display = "none";
    },

    leftClicked: function(mouseEvent) {
        this.changeImageLeft();
    },

    rightClicked: function(mouseEvent) {
        this.changeImageRight();
    }
};

ImageViewer.changeImageRight = function (mouseEvent) {
    let array_location = ImageViewer.findCurrentIndex(0);

    if (array_location + 1 < this.e.current_photo_cont.children.length) {
        this.e.image_content.src = this.e.current_photo_cont.children[array_location + 1].getAttribute("src");
    } else {
        this.e.image_content.src = this.e.current_photo_cont.children[0].getAttribute("src");
    }

    /* Set the new photo location number to + 1 if the location now is less than the total length */
    if (array_location + 1 < this.e.current_photo_cont.children.length) {
        this.e.photo_location = this.e.photo_location + 1;
        this.e.current_photo_count.innerHTML = this.e.photo_location;
    }

    /* Reset the photo location to 1 if the current location + 1 is greater or equal to the photo container length */
    if (array_location + 1 >= this.e.current_photo_cont.children.length) {
        this.e.photo_location = 1;
        this.e.current_photo_count.innerHTML = this.e.photo_location;
    }
};

ImageViewer.changeImageLeft = function (mouseEvent) {
    /* Set an initial array location to 0 */
    /* For each element in the current photo container if the src matches, then set the array location to that location */
    let array_location = ImageViewer.findCurrentIndex(0);

    /* If the array location - 1 goes past 0 and is not -1 then do arrray_location - 1 to get the previous image
    * Else go the last image in the current photo container */
    if (array_location - 1 != -1) {
        this.e.image_content.src = this.e.current_photo_cont.children[array_location - 1].getAttribute("src");
    } else {
        this.e.image_content.src = this.e.current_photo_cont.children[this.e.current_photo_cont.children.length - 1].getAttribute("src");
    }

    /* If the array location - 1 is not -1 then set the photo location to one down  */
    if (array_location - 1 != -1) {
        this.e.photo_location = this.e.photo_location - 1;
        this.e.current_photo_count.innerHTML = this.e.photo_location;
    }

    /* if the array location - 1 is -1 then set the photo location the total length in the container */
    if (array_location - 1 == -1) {
        this.e.photo_location = this.e.current_photo_cont.children.length;
        this.e.current_photo_count.innerHTML = this.e.photo_location;
    }
};

ImageViewer.findCurrentIndex = function(array_location) {
    for(let i = 0; i < this.e.current_photo_cont.children.length; i++)
    {
        if(this.e.image_content.getAttribute("src") == this.e.current_photo_cont.children[i].getAttribute("src")) {
            array_location = i;
        }
    }

    return array_location;
};

ImageViewer.addListeners = function() {
    // Add Event Listeners for specific elements
    for (let i = 0; i < this.e.restroom_image.length; i++) {
        this.e.restroom_image[i].addEventListener("click", this.evtCallbacks.imageClicked.bind(this));
    }

    this.e.close_btn.addEventListener("click", this.evtCallbacks.closeClicked.bind(this));

    document.addEventListener("keydown", function(keyevent) {
        if (keyevent.keyCode == 27) {
          this.e.photo_cont.style.display = "none";
        }

        if (keyevent.keyCode == 37) {
          ImageViewer.changeImageLeft();
        }

        if (keyevent.keyCode == 39) {
          ImageViewer.changeImageRight();
        }
    }.bind(this));

    this.e.left_btn.addEventListener("click", this.evtCallbacks.leftClicked.bind(this));

    this.e.right_btn.addEventListener("click", this.evtCallbacks.rightClicked.bind(this));
};

ImageViewer.init = function() {
    this.addListeners();
};

ImageViewer.init();
