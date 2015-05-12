$(window).load(function() {
    function get_x(e){
    	return e.pageX;
    }
    function get_y(e){
    	return e.pageY;
    }
    
    var x,y;
    var t = [];
    var ID = [];
    var holder_str = '.gall_thumbs_all';
    var holder = $(holder_str);
    
    var totalW = 0;
    holder.find('>ul>li>a').each(function (){
        totalW += $(this).outerWidth(true);
        
    });  
    /*totalW=1565;*/
    /*totalW-=200;*/

    
    
    function scrollGallery(th){     
        var delta = -25;
        
        var parW = $(th).parent().width();
        var parH = $(th).parent().height();
        var w = $(th).width();
        var h = $(th).height(); 
        
        if (x < windowW()*.5) {delta *= -1;}
        t[$(th).index()] += delta;
        
        if (t[$(th).index()] > 0) t[$(th).index()] = 0;
        if (t[$(th).index()] < parW-w) t[$(th).index()] = parW-w;
            
        $(th).stop().animate({'left': t[$(th).index()]},500,'easeOutExpo');
    }
    
    holder.find('>ul')
    .css({'position':'absolute','width':totalW})
    .each(function(ind,el){
        t.push(0); 
    })
    .mousemove(function (e) {
        x = get_x(e);
        y = get_y(e);
    })
    .hover(
        function(e){       
           var _this = this; 
           ID.push(setInterval(function () {scrollGallery(_this);},73));      
        },
        function(e){
            for (var i=0;i<ID.length;i++){clearInterval(ID[i]);}
        }
    )
    $(window).resize(function (){
        var _w = windowW()
        if (windowW() > totalW) {_w = totalW+(windowW() - totalW)/2;}
        holder.css('width',_w);
        scrollGallery(holder.children('ul'));     
    });
});
