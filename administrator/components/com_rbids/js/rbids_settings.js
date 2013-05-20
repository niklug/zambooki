window.addEvent('domready', function () {
   $('date_format').addEvent('change',function (){
        $('date_format_span').set('html',RBidsSettings.showDateInFormat(this.value));
    }).fireEvent('change');;
    $('date_time_format').addEvent('change',function (){
         $('date_time_format_span').set('html',RBidsSettings.showTimeInFormat(this.value));
     }).fireEvent('change');

});



var MONTH_NAMES=new Array('January','February','March','April','May','June','July','August','September','October','November','December','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
var DAY_NAMES=new Array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sun','Mon','Tue','Wed','Thu','Fri','Sat');

var RBidsSettings={
        LZ: function (x) {return(x<0||x>9?"":"0")+x},
        formatDate: function (format) {
            date = new Date()
            format=format+"";
            var result="";
            var i_format=0;
            var c="";
            var token="";
            var y=date.getYear()+"";
            var M=date.getMonth()+1;
            var d=date.getDate();
            var E=date.getDay();
            var H=date.getHours();
            var m=date.getMinutes();
            var s=date.getSeconds();
            var yyyy,yy,MMM,MM,dd,hh,h,mm,ss,ampm,HH,H,KK,K,kk,k;
            // Convert real date parts into formatted versions
            var value=new Object();
            if (y.length < 4) {y=""+(y-0+1900);}
            value["y"]=""+y;
            value["yyyy"]=y;
            value["yy"]=y.substring(2,4);
            value["M"]=M;
            value["MM"]=RBidsSettings.LZ(M);
            value["MMM"]=MONTH_NAMES[M-1];
            value["NNN"]=MONTH_NAMES[M+11];
            value["d"]=d;
            value["dd"]=RBidsSettings.LZ(d);
            value["E"]=DAY_NAMES[E+7];
            value["EE"]=DAY_NAMES[E];
            value["H"]=H;
            value["HH"]=RBidsSettings.LZ(H);
            if (H==0){value["h"]=12;}
            else if (H>12){value["h"]=H-12;}
            else {value["h"]=H;}
            value["hh"]=RBidsSettings.LZ(value["h"]);
            if (H>11){value["K"]=H-12;} else {value["K"]=H;}
            value["k"]=H+1;
            value["KK"]=RBidsSettings.LZ(value["K"]);
            value["kk"]=RBidsSettings.LZ(value["k"]);
            if (H > 11) { value["a"]="PM"; }
            else { value["a"]="AM"; }
            value["m"]=m;
            value["mm"]=RBidsSettings.LZ(m);
            value["s"]=s;
            value["ss"]=RBidsSettings.LZ(s);
            while (i_format < format.length) {
                c=format.charAt(i_format);
                token="";
                while ((format.charAt(i_format)==c) && (i_format < format.length)) {

                    token += format.charAt(i_format++);
                    }
                if (value[token] != null) { result=result + value[token]; }
                else { result=result + token; }
                }
            return result;
        },

        showDateInFormat:function (format){
            var buf_format;
            //var now = new Date();

            switch(format){
                case 'Y-m-d':
                    buf_format = "yyyy-MM-dd";
                break;
                case 'Y-d-m':
                    buf_format = "yyyy-dd-MM";
                break;
                case 'm/d/Y':
                    buf_format = "MM/dd/yyyy";
                break;
                case 'd/m/Y':
                    buf_format = "dd/MM/yyyy";
                break;
                case 'd.m.Y':
                    buf_format = "dd.MM.yyyy";
                break;
                case 'D, F d Y':
                    buf_format = "E, MMM dd yyyy";
                break;
            }

            return RBidsSettings.formatDate(buf_format);
        },

        showTimeInFormat: function (format){
            var buf_format;

            switch(format){
                case'H:i':
                    buf_format = "HH:mm";
                break;
                case'h:iA':
                    buf_format = "h:mm a";
                break;
            }
            return RBidsSettings.formatDate(buf_format);
        }
}
