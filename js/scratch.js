// JavaScript Document
// Utility
var scratch_m={};
if (typeof Object.create !== 'function') {
	ScratchCard.create = function(obj) {
		function F() {};
		F.prototype = obj;
		return new F();
	};
}
var scratchCardId = 0;
(function($, window, document, undefined) {
	var scratchCardTemplate=$("<Canvas class='scratchCanvas' style='display:none'></Canvas>");
	var ScratchCard = {
		init: function(options, elem) {
			var self = this;
			self.elem = elem;
			self.$elem = $(elem);
			self.options = $.extend({}, $.fn.rabidScratchCard.options, options);
			self.options.backgGroundImage = self.$elem.data("backgroundimage");
			self.options.foreGroundImage = self.$elem.data("foregroundimage");
			self.loadedImages = 0;
			self.percentScratched = 0;
			var canvasBgImg = new Image();
			canvasBgImg.onload = function() {
				self.newScratchCanvas = scratchCardTemplate.clone();
				self.dummyCanvas = scratchCardTemplate.clone();
				self.$elem.html(self.newScratchCanvas);
				self.theCanvas = self.newScratchCanvas;
				self.ctx = self.theCanvas[0].getContext("2d");
				self.dummyctx = self.dummyCanvas[0].getContext("2d");
				self.theCanvas.bind('mousedown', $.proxy(self.addDownHandler, self));
				self.theCanvas.bind('mouseup', $.proxy(self.addUpHandler, self));
				self.theCanvas.bind('touchstart', $.proxy(self.touchstartHandler, self));
				self.theCanvas.bind('touchend', $.proxy(self.touchendHandler, self));
				$(self.theCanvas).css({
					"backgroundImage": "url(" + canvasBgImg.src + ")"
				})
				self.theCanvas[0].width = self.dummyCanvas[0].width = self.canvasWidth = canvasBgImg.width;
				self.theCanvas[0].height = self.dummyCanvas[0].height = self.canvasHeight = canvasBgImg.height;
				self.totalPixels = canvasBgImg.width * canvasBgImg.height;
				self.loadedImages++;
				self.theCanvas.css("display", "inline")
				self.initX = self.theCanvas.offset().left;
				self.initY = self.theCanvas.offset().top;
				//	self.ctx.fillRect(0,0,canvasBgImg.width,canvasBgImg.height)
			};
			var bgImg = new Image();
			bgImg.onload = function() {
				self.srcImg = bgImg;
				canvasBgImg.src = self.options.foreGroundImage;
			}
			bgImg.src = self.options.backgGroundImage;
		},
		addDownHandler: function(e) {
			var self = this;
			self.origW = self.theCanvas.width();
			self.origH = self.theCanvas.height();
			var targ;
			if (!e) e = window.event;
			if (e.target) targ = e.target;
			else if (e.srcElement) targ = e.srcElement;
			if (targ.nodeType == 3) // defeat Safari bug
			targ = targ.parentNode;
			// jQuery normalizes the pageX and pageY
			// pageX,Y are the mouse positions relative to the document
			// offset() returns the position of the element relative to the document
			var mouseX = Math.round(e.pageX - $(targ).offset().left);
			var mouseY = Math.round(e.pageY - $(targ).offset().top);
			mouseX = Math.round((self.canvasWidth / self.origW) * mouseX);
			mouseY = Math.round((self.canvasHeight / self.origH) * mouseY);
			self.theCanvas.bind('mousemove', $.proxy(self.mouseMoveHandler, self));
			$(window).bind('mouseup', $.proxy(self.addUpHandler, self));
		},
		addUpHandler: function(e) {
			var self = this;
			self.scratchPercentage(self);
			self.theCanvas.unbind('mousemove');
			$(window).unbind('mouseup', $.proxy(self.addUpHandler, self, true));
		},
		mouseMoveHandler: function(e) {
			var self = this;
			var targ;
			if (!e) e = window.event;
			if (e.target) targ = e.target;
			else if (e.srcElement) targ = e.srcElement;
			if (targ.nodeType == 3) // defeat Safari bug
			targ = targ.parentNode;
			// jQuery normalizes the pageX and pageY
			// pageX,Y are the mouse positions relative to the document
			// offset() returns the position of the element relative to the document
			var mouseX = Math.round(e.pageX - $(targ).offset().left);
			var mouseY = Math.round(e.pageY - $(targ).offset().top);
			mouseX = Math.round((self.canvasWidth / self.origW) * mouseX);
			mouseY = Math.round((self.canvasHeight / self.origH) * mouseY);
			//var mouseX = e.pageX - e.currentTarget.offsetLeft;
			//	mouseY = e.pageY - e.currentTarget.offsetTop;
			self.reveal(mouseX, mouseY, self, self.options.updateOnMouseMove);
		},
		touchstartHandler: function(e) {
			var self = this;
			self.theCanvas.bind('touchmove', $.proxy(self.touchmoveHandler, self));
		},
		touchendHandler: function(e) {
			var self = this;
			self.scratchPercentage(self);
			self.theCanvas.unbind('touchmove');
		},
		touchmoveHandler: function(e) {
			var self = this;
			e.preventDefault();
			var event = window.event;
			mouseX = event.touches[0].pageX - self.initX;
			mouseY = event.touches[0].pageY - self.initY;
			self.reveal(mouseX, mouseY, self, self.options.updateOnFingerMove);
		},
		reveal: function(mouseX, mouseY, self, calculatePercent) {
			self.ctx.save();
			self.dummyctx.fillStyle = "#FF0000";
			self.dummyctx.beginPath();
			self.ctx.beginPath();
			// Can't make a true circle, so we make an arced line that happens to trace a circle - 'i' is used to define our radius.
			self.dummyctx.arc(mouseX, mouseY, self.options.revealRadius, 0, 2 * Math.PI, false);
			self.ctx.arc(mouseX, mouseY, self.options.revealRadius, 0, 2 * Math.PI, false);
			self.ctx.clip();
			self.ctx.drawImage(self.srcImg, 0, 0);
			self.dummyctx.closePath();
			self.ctx.closePath();
			self.dummyctx.fill();
			self.ctx.restore();
			if (calculatePercent) self.scratchPercentage(self);
		},
		scratchPercentage: function(self) {
			var changedPixels = 0,
				imageData = self.dummyctx.getImageData(0, 0, self.dummyctx.canvas.width, self.dummyctx.canvas.height)
			for (var i = 0; i < imageData.data.length; i = i + 4) {
					if (imageData.data[i] == 255) changedPixels++;
				}
			self.percentScratched = Math.ceil(((changedPixels / (imageData.data.length)) * 100) * 4)
			if (typeof self.options.onUpdate === 'function') {
					self.options.onUpdate.call(self, self.percentScratched);
				}
			if (self.options.percentComplete <= self.percentScratched) {
					if (self.options.revealOnComplete) {
						self.ctx.rect(0, 0, self.dummyctx.canvas.width, self.dummyctx.canvas.height);
						self.ctx.clip();
						self.ctx.drawImage(self.srcImg, 0, 0);
					}
					if (typeof self.options.onScratchComplete === 'function') {
						self.options.onScratchComplete.call(self, self.percentScratched);
					}
					self.percentScratched = 100;
					self.theCanvas.unbind('mousedown', $.proxy(self.addDownHandler, self));
					self.theCanvas.unbind('mouseup', $.proxy(self.addUpHandler, self));
					self.theCanvas.unbind('mousemove');
					self.theCanvas.unbind('touchmove', $.proxy(self.touchmoveHandler, self));
                  //  scratch_m="completed";
scratch_m[self.theCanvas.parent().attr('id').split("_")[1]]="completed";
                 /// console.log(scratch_m);
				}
		}
	}
	$.fn.rabidScratchCard = function(options) {
		return this.each(function() {
			var scratchCard = Object.create(ScratchCard);
			scratchCard.init(options, this);
		});
	};
	//Defaults
	$.fn.rabidScratchCard.options = {
		foreGroundImage: null,
		backgGroundImage: null,
		revealRadius: 30,
		percentComplete: 100,
		revealOnComplete: true,
		updateOnMouseMove: true,
		updateOnFingerMove: false,
		onUpdate: null,
		onScratchComplete: null
	};
})(jQuery, window, document);