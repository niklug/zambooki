window.addEvent('domready', function () {
    $$('.rating').each(function(el){
        if(isNaN(el.innerHTML)) {
			return;
		}
        rating=parseFloat(el.innerHTML);
        if (isNaN(rating)) rating=0;
        el.empty();
        for (var j=1; j<=5; j++)
        {
            img_name= 'star-0.png';
            if (j<= Math.ceil(rating))
                img_name= 'star-1.png';
            if ((j==Math.ceil(rating))&& (Math.round(rating)<j))
                img_name= 'star-2.png';
            var img=new Element('img', {src:image_link_dir+img_name ,title:'Rating: '+rating,class:'auction_star',border:0,width:'16px'});
            el.adopt(img);
        }
    });
});

