/**
 * Canvas adapted from D:\Web\potatodie.nl\lab\spirograph\curve_animator.js (2015)
 * Seems like a good Canvas foundation for me
 *
 * It makes common drawing operations a little less verbose
 */
var Canvas = {

  init: function ( el_id, w, h, style ) {

    this.elem = document.getElementById( el_id );
    this.elem.width = w;
    this.elem.height = h;
    this.ctx = this.elem.getContext('2d');
    this.ctx.translate(w/2, h/2);
    
    for ( var s in style ) {
      this.ctx[s] = style[s];
    }    
    
  },

  clear: function ( fillStyle ) {
    if ( fillStyle ) {
      this.ctx.fillStyle = fillStyle;
      this.ctx.fillRect(-this.elem.width/2, -this.elem.height/2, this.elem.width, this.elem.height);
    }
    else {
      this.ctx.clearRect(-this.elem.width/2, -this.elem.height/2, this.elem.width, this.elem.height);
    }
  },

  path: function ( points, style, scale ) {
    for ( var s in style ) {
      this.ctx[s] = style[s];
    }

    this.ctx.beginPath();
    // Transfer points to the coordinate system of the canvas
    var p =  points[0];//.multiply ( scale );
    this.ctx.moveTo ( p.x, p.y );
    
    for ( var i = 1; i < points.length; i++ ) {
      p =  points[i];//.multiply ( scale );
      this.ctx.lineTo ( p.x, p.y );
    }
    
    this.ctx.stroke();
  },

  circle: function ( p, r, c) {
    // this.ctx.globalAlpha = 0.5;
    this.ctx.beginPath();
    this.ctx.fillStyle = c;
    this.ctx.arc(p.x, p.y,r,0,Math.PI*2,true);
    this.ctx.fill();    
    this.ctx.closePath();
  },

  objectFit:function(o, mode) {
    // Well outer would automatically be parent element, no?
    i = this.elem;

    var fit = function() {
      var W = o.offsetWidth;
      var H = o.offsetHeight;
      // For images you'd use naturalWidth etc., but that doesn't work for canvas
      var w = i.width;
      var h = i.height;

      var R = W/H;
      var r = w/h;
      if ((mode == 'contain' && R > r) || (mode == 'cover' && r > R)) {
        // if you're clever you could say (mode=='contain') == (R>r)
        var f = H/h;
        i.style.width = w * f + "px";
        i.style.marginLeft = 0.5 * (W - w*f) + "px";
        i.style.marginTop = 0;
      }
      else {
        var f = W/w;
        i.style.width = "100%";
        i.style.marginTop = 0.5 * (H - h*f) + "px";
        i.style.marginLeft= 0;  
      }
    }
    window.addEventListener('resize', fit, false);
    fit();
  } 
}