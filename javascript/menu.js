function preloadImage(url) 
{
  var i = new Image();
  i.src = url;
  return i;
}

if (document.images)

{  
  // Preload images
  var homeon = preloadImage("./img/home_on.gif");
  var homeoff = preloadImage("./img/home_off.gif");
  var listenon = preloadImage("./img/listen_on.gif");
  var listenoff = preloadImage("./img/listen_off.gif");
  var downloadson = preloadImage("./img/downloads_on.gif");
  var downloadsoff = preloadImage("./img/downloads_off.gif");
  var advertiseon = preloadImage("./img/advertise_on.gif");
  var advertiseoff = preloadImage("./img/advertise_off.gif");
  var abouton = preloadImage("./img/about_on.gif");
  var aboutoff = preloadImage("./img/about_off.gif");
  var faqon = preloadImage("./img/faq_on.gif");
  var faqoff = preloadImage("./img/faq_off.gif");
  var contacton = preloadImage("./img/contact_on.gif");
  var contactoff = preloadImage("./img/contact_off.gif");
  var sellon = preloadImage("./img/sell_on.gif");
  var selloff = preloadImage("./img/sell_off.gif");
}

function mouseOn(imgName)
{
  if (document.images)
	document[imgName].src = eval(imgName + "on.src");
}

function mouseOff(imgName)
{
  if (document.images)
	document[imgName].src = eval(imgName + "off.src");
}