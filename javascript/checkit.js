function checkBox()
{
	if (document.updates.newsletter_email_address.value == null || document.updates.newsletter_email_address.value.length == 0)
	{
		alert("please fill in your Email address");
		document.updates.newsletter_email_address.focus();
		return false;
	}
	
	var regex = /^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/;
	if (!document.updates.newsletter_email_address.value.match(regex))
	{
		alert("please verify your Email address format.");
		document.updates.newsletter_email_address.focus();
		return false;
	}
	
	if (document.updates.newletter_region.selectedIndex == 0)
	{
		alert("please select a Region");
		return false;
	}	
}