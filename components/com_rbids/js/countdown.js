String.prototype.trim = function () {
    return this.replace(/^\s*/, "").replace(/\s*$/, "");
}
String.prototype.copyTo = function (substr) {
    return this.substring(0,this.indexOf(substr)).trim();
}
String.prototype.deleteFrom = function (substr) {
    return this.slice(this.indexOf(substr)+substr.length);
}
var auction_countdown = new Class({
    //update every 1000ms
    frequency: 1000,
    initialize:function(span_class,s_day,expired){
        this.currentlyExecuting = false;
        if($defined(span_class)){
            this.span_class=span_class;
        }else{
            this.span_class='timer';
        }
        if($defined(s_day)){
            this.s_day=s_day;
        }else{
            this.s_day='Days';
        }
        if($defined(expired)){
            this.expired=expired;
        }else{
            this.expired='Expired';
        }
		this.stop();
        this.timer=setInterval(this.onTimerEvent.bind(this),this.frequency);
    },
    onTimerEvent: function() {
		if (!this.currentlyExecuting) {
		
			try {
			
				this.currentlyExecuting = true;
				this.execute();
			} finally {
			
				this.currentlyExecuting = false;
			}
		}
		return this;
    },
	stop: function() {
	
		if (!this.timer) return this;
		clearInterval(this.timer);
		this.timer = null;
		return this
	},
    
    toDoubleDigit: function(i){
    	var s=new String(i);
    	if (s.length==0) s='00'
    	else
    		if (s.length==1) s='0'+s;
    	return s;
    },
    decrementTime: function(s_time){
		if (s_time==this.expired) return false;
        
		d=0;
		h=0;
		m=0;
		s=0;
		if (s_time.indexOf(this.s_day)>=0){
			ds=s_time.copyTo(this.s_day);
			s_time=s_time.deleteFrom(this.s_day+',');
			if(!isNaN(ds)){
				d=parseInt(ds,10);
			}
		}
		if (s_time.indexOf(':')>=0){
			hs=s_time.copyTo(':');
			s_time=s_time.deleteFrom(':');
			if(!isNaN(hs)){
				h=parseInt(hs,10);
			}
		}
		if (s_time.indexOf(':')>=0){
			ms=s_time.copyTo(':');
			s_time=s_time.deleteFrom(':');
			if(!isNaN(ms)){
				m=parseInt(ms,10);
			}
		}
		if(!isNaN(s_time)){
			s=parseInt(s_time,10);
		}
		timedout=false;
		if (s>0){
			s--;
		}else{
			s=59;
			if(m>0){
				m--;
			}else{
				m=59;
				if(h>0){
					h--;
				}else{
					h=23;
					if(d>0){
						d--;
					}else{
						timedout=true;
					}
				}
			}
		}
        
		newval='';
		if(!timedout){
			if(d>0)
				newval=d+' '+this.s_day+', ';
			newval+=this.toDoubleDigit(h)+':'+this.toDoubleDigit(m)+':'+this.toDoubleDigit(s);
		}else{
			newval=this.expired;
		}
		return newval;
    },    
	execute: function() {
	    var obj=this;
        $$(this.span_class).each(function(el){
            if (el.innerHTML==obj.expired) return;
            newval=obj.decrementTime( el.innerHTML );
            if(newval) el.innerHTML=newval;
            if (newval==obj.expired) el.set('class','expired');
        });
	}    
});
