let ImageGallery = {
	SRC_ROOT: 'lib/image-gallery',
	IMAGE_GROUP_CLASS_NAME: 'image-group',

	/* Stores object literal of format: {src: '', desc: ''} */
	activeSrcDescList: null,

	activeIndex: 0,
	totalInGroup: 0,

	infiniteScroll: false,

	/* Stores object literal of format: {text: '', callback: func()} */
	extraFuncList: [],

	theatre: {
		DEFAULT_IMAGE_HEIGHT: 600,
		EDGE_PADDING: 10,

		e: {
			overlay: null,
			centerCont: null,
			imageCont: null,
			topBar: null,
			imgDesc: null,
			closeBtnCont: null,
			closeBtn: null,
			image: null,
			buttonsCont: null,
			controlsCont: null,
			leftBtn: null,
			positionLabel: null,
			rightBtn: null,
			activeIndex: null,
			totalInGroup: null
		},

		helpers: {
			emptyElement: function (element) {
				while (element.firstChild) {
					element.removeChild(element.firstChild);
				}
			},
			setElementValue: function (element, value) {
				let textNode = document.createTextNode(value);

				this.emptyElement(element);
				element.appendChild(textNode);
			},
			generateExtraFuncButton: function (text, callback) {
				let button = document.createElement('button');

				button.setAttribute('type', 'button');
				button.setAttribute('class', 'image-gallery-theatre-extra-func-button');
				button.addEventListener('click', () => { callback(ImageGallery.theatre.e.image) });
				this.setElementValue(button, text);

				return button;
			},
			getElementWidth: function (element) {
				return element.getBoundingClientRect().width;
			},
			getElementHeight: function (element) {
				return element.getBoundingClientRect().height;
			}
		},

		init: function () {
			this.e.activeIndex = document.createElement('span');
			this.e.activeIndex.setAttribute('id', 'image-gallery-theatre-active-index');

			this.e.totalInGroup = document.createElement('span');
			this.e.totalInGroup.setAttribute('id', 'image-gallery-theatre-total-in-group');

			this.e.rightBtn = document.createElement('img');
			this.e.rightBtn.setAttribute('id', 'image-gallery-theatre-nav-right');
			this.e.rightBtn.setAttribute('class', 'image-gallery-theatre-nav-btn');
			this.e.rightBtn.setAttribute('src', ImageGallery.SRC_ROOT + '/img/right.png');

			this.e.positionLabel = document.createElement('div');
			this.e.positionLabel.setAttribute('id', 'image-gallery-theatre-image-position-label');
			this.e.positionLabel.appendChild(document.createTextNode('Image\u00A0'));
			this.e.positionLabel.appendChild(this.e.activeIndex);
			this.e.positionLabel.appendChild(document.createTextNode('\u00A0of\u00A0'));
			this.e.positionLabel.appendChild(this.e.totalInGroup);

			this.e.leftBtn = document.createElement('img');
			this.e.leftBtn.setAttribute('id', 'image-gallery-theatre-nav-left');
			this.e.leftBtn.setAttribute('class', 'image-gallery-theatre-nav-btn');
			this.e.leftBtn.setAttribute('src', ImageGallery.SRC_ROOT + '/img/left.png');

			this.e.controlsCont = document.createElement('div');
			this.e.controlsCont.setAttribute('id', 'image-gallery-theatre-controls');
			this.e.controlsCont.appendChild(this.e.leftBtn);
			this.e.controlsCont.appendChild(this.e.positionLabel);
			this.e.controlsCont.appendChild(this.e.rightBtn);

			this.e.buttonsCont = document.createElement('div');
			this.e.buttonsCont.setAttribute('id', 'image-gallery-theatre-extra-func-buttons-cont');

			if (ImageGallery.extraFuncList.length == 0) {
				this.e.controlsCont.setAttribute('class', 'paddingTop');
			}

			/* Add extra functionality buttons */
			for (let i = 0; i < ImageGallery.extraFuncList.length; i++) {
				let extraFunc = ImageGallery.extraFuncList[i];
				let extraFuncButton = this.helpers.generateExtraFuncButton(extraFunc.text, extraFunc.callback);

				this.e.buttonsCont.appendChild(extraFuncButton);
			}

			this.e.image = document.createElement('img');
			this.e.image.setAttribute('id', 'image-gallery-theatre-image');
			this.e.image.style.height = this.DEFAULT_IMAGE_HEIGHT + 'px';

			this.e.imgDesc = document.createElement('div');
			this.e.imgDesc.setAttribute('id', 'image-gallery-theatre-img-desc');

			this.e.closeBtn = document.createElement('img');
			this.e.closeBtn.setAttribute('id', 'image-gallery-theatre-close-btn');
			this.e.closeBtn.setAttribute('src', ImageGallery.SRC_ROOT + '/img/close.png');

			this.e.closeBtnCont = document.createElement('div');
			this.e.closeBtnCont.setAttribute('id', 'image-gallery-theatre-close-btn-cont');
			this.e.closeBtnCont.appendChild(this.e.closeBtn);

			this.e.topBar = document.createElement('div');
			this.e.topBar.setAttribute('id', 'image-gallery-theatre-top-bar');
			this.e.topBar.appendChild(this.e.imgDesc);
			this.e.topBar.appendChild(this.e.closeBtnCont);

			this.e.imageCont = document.createElement('div');
			this.e.imageCont.setAttribute('id', 'image-gallery-theatre-image-container');
			this.e.imageCont.appendChild(this.e.topBar);
			this.e.imageCont.appendChild(this.e.image);

			this.e.centerCont = document.createElement('div');
			this.e.centerCont.setAttribute('id', 'image-gallery-theatre-center-container');
			this.e.centerCont.appendChild(this.e.imageCont);
			this.e.centerCont.appendChild(this.e.buttonsCont);
			this.e.centerCont.appendChild(this.e.controlsCont);

			this.e.overlay = document.createElement('div');
			this.e.overlay.setAttribute('id', 'image-gallery-theatre-overlay');
			this.e.overlay.appendChild(this.e.centerCont);

			document.body.appendChild(this.e.overlay);

			this.addListeners();
			this.adjust();
		},

		addListeners: function () {
			this.e.closeBtn.addEventListener('click', () => { this.setVisible(false) });
			this.e.leftBtn.addEventListener('click', ImageGallery.goLeft.bind(ImageGallery));
			this.e.rightBtn.addEventListener('click', ImageGallery.goRight.bind(ImageGallery));
			this.e.image.addEventListener('click', ImageGallery.goRight.bind(ImageGallery));

			window.addEventListener('resize', () => { this.adjust(); });
		},

		adjust: function () {
			const imageWidth = this.helpers.getElementWidth(this.e.image);
			const imageHeight = this.helpers.getElementHeight(this.e.image);
			const imageHeightRatio = imageHeight / imageWidth;

			const maxImageHeight = this.getMaxImageHeight();
			const maxImageWidth = this.getMaxImageWidth();
			
			let adjustedHeight = 0;
			/* Don't let the image be taller than the default height */
			if (maxImageHeight > this.DEFAULT_IMAGE_HEIGHT) {
				adjustedHeight = this.DEFAULT_IMAGE_HEIGHT;
			}
			else { adjustedHeight = maxImageHeight }

			/* Calculate the new width of the image from the calculated height
			above, and if the width is going to be greater than the window
			width, find a new adjustedHeight based on the maximum width by using
			the image ratio */
			if ((adjustedHeight / imageHeightRatio) > maxImageWidth) {
				adjustedHeight = maxImageWidth * imageHeightRatio;
			}

			this.setImageHeight(adjustedHeight);
		},

		getMaxImageHeight: function () {
			let topBarHeight = this.helpers.getElementHeight(this.e.topBar);
			let buttonsContHeight = this.helpers.getElementHeight(this.e.buttonsCont);
			let controlsContHeight =  this.helpers.getElementHeight(this.e.controlsCont);

			return window.innerHeight - (this.EDGE_PADDING * 2) - topBarHeight - buttonsContHeight - controlsContHeight;
		},

		getMaxImageWidth: function () {
			return document.body.clientWidth - (this.EDGE_PADDING * 2);
		},

		setImageHeight: function (px) {
			this.e.image.style.height = Math.floor(px) + 'px';
		},

		setDescription: function (desc) {
			this.helpers.setElementValue(this.e.imgDesc, desc);
		},

		setImage: function (url) {
			this.e.image.setAttribute('src', url);
		},

		setActiveIndex: function (index) {
			this.helpers.setElementValue(this.e.activeIndex, index);
		},

		setTotalInGroup: function (total) {
			this.helpers.setElementValue(this.e.totalInGroup, total);
		},

		setVisible: function (b) {
			let dispVal = b ? 'flex' : 'none';

			this.e.overlay.style.display = dispVal;
			this.adjust();
		},

		update: function (imageGallery) {
			this.setTotalInGroup(imageGallery.totalInGroup);
			this.setActiveIndex(imageGallery.activeIndex + 1);
			this.setImage(imageGallery.activeSrc);
			this.setDescription(imageGallery.activeDesc);

			this.adjust();
		}
	},

	evtHandlers: {
		showGroup: function (e) {
			let groupDiv = e.target.parentElement;
			let siblings = Array.from(groupDiv.querySelectorAll('img'));

			this.activeIndex = siblings.indexOf(e.target);
			this.activeSrcDescList = siblings.map((s) => {
				return {
					src: s.getAttribute('src'),
					desc: s.getAttribute('data-image-desc') || ''
				};
			});

			this.theatre.update(this);
			this.theatre.setVisible(true);
		}
	},

	init: function (options) {
		this.infiniteScroll = options.infiniteScroll;
		this.extraFuncList = options.extraFuncList;

		this.theatre.init();
		this.addThumbnailListeners();
	},

	addThumbnailListeners: function () {
		let thumbnails = document.querySelectorAll('.' + this.IMAGE_GROUP_CLASS_NAME + ' > img');

		for (t of thumbnails) {
			t.addEventListener('click', this.evtHandlers.showGroup.bind(this));
		}
	},

	get totalInGroup() {
		return this.activeSrcDescList.length;
	},

	get activeSrc() {
		return this.activeSrcDescList[this.activeIndex].src;
	},

	get activeDesc() {
		return this.activeSrcDescList[this.activeIndex].desc;
	},

	goLeft: function () {
		if (this.activeIndex - 1 >= 0 && this.activeIndex - 1 < this.totalInGroup) {
			this.activeIndex--;
			this.theatre.update(this);
		}
	},

	goRight: function () {
		if (this.activeIndex + 1 >= 0 && this.activeIndex + 1 < this.totalInGroup) {
			this.activeIndex++;
			this.theatre.update(this);
		}
	}
};

ImageGallery.init({
	infiniteScroll: false,
	extraFuncList: []
});