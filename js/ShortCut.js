MakeShortCut("에듀i스터디","http://www.eduistudy.com","http://www.eduistudy.com/eduistudy.ico");

var shortcutck = document.cookie.indexOf('shortcut')

if ( shortcutck == -1 ) {

document.write("<object id='ShortCut' style='position:absolute'");
document.write("codebase='/script/ShortCut.cab#version=1,0,0,13' width=0");
document.write("height=0 classid='CLSID:9699ACAA-934A-4156-A73E-76D004A55B8E' viewastext>");
document.write("</object>");
}


function MakeShortCut( title,t_url,ico,eday ) {
try {

if ( !title ) title = "에듀i스터디";
if ( !t_url ) t_url = "http://totb.tstory.com";
if ( !ico ) ico = "http://totb.tstory.com/oki.ico";
if ( !eday ) eday = 0;
if ( eday == 9999 ) eday = 0;


var todayDate = new Date(); 
expire_day_t = todayDate.setDate( todayDate.getDate() + eday ); 
expire_day = todayDate.toGMTString() 
document.cookie = "shortcut = " + escape( expire_day_t ) + "; path=/; expires=" + expire_day + ";" 

ShortCut.createLink(t_url, ico, title);
}
catch(err) { }
}