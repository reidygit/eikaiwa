<?
include_once("../php/ez_sql.php");

$id = $user[0]->user_id;

if (!isset($_GET["order"]))
{
	$order = "ASC";
}
	else
	{
		$order = $_GET["order"];
	}

global $db;
$query = "SELECT paypal_subscriptions.subscr_id,
			  paypal_subscriptions.txn_type,
			  paypal_subscriptions.item_name, 
			  paypal_subscriptions.mc_gross, 
			  DATE_FORMAT(paypal_subscriptions.date, '%Y-%d-%m') as date,
			  paypal_subscriptions.txn_id
		  FROM paypal_subscriptions
		  INNER JOIN users_subscriptions
		  ON users_subscriptions.subscr_id = paypal_subscriptions.subscr_id AND
		  users_subscriptions.user_id = $id
		  ORDER BY paypal_subscriptions.date $order, paypal_subscriptions.subscr_id DESC";
$results = $db->get_results($query);
$count = 0;
?>
<table cellpadding="0" cellspacing="0" style="font-family: arial, helvetica, sans-serif; font-size: 11px; border-style: solid; border-color: #8C1414; border-width: 1px;">
<tr>
	<td style="padding: 2px 8px 2px 8px; border-bottom-style: solid; border-bottom-color: #8C1414; border-bottom-width: 1px; font-weight: bold;">Subscription ID</td>
	<td style="padding: 2px 8px 2px 8px; border-bottom-style: solid; border-bottom-color: #8C1414; border-bottom-width: 1px; font-weight: bold;">Transaction Type</td>
	<td style="padding: 2px 8px 2px 8px; border-bottom-style: solid; border-bottom-color: #8C1414; border-bottom-width: 1px; font-weight: bold;">Subscription Name</td>
	<td style="padding: 2px 8px 2px 8px; border-bottom-style: solid; border-bottom-color: #8C1414; border-bottom-width: 1px; font-weight: bold;">Amount</td>
	<td style="padding: 2px 8px 2px 8px; border-bottom-style: solid; border-bottom-color: #8C1414; border-bottom-width: 1px; font-weight: bold;">Date <a href="?edituser&email=<? print $email; ?>&order=DESC"><img src="../img/sort_down.gif" border="0" alt="" style="padding-bottom: 1px;"></a>&nbsp;<a href="?edituser&email=<? print $email; ?>&order=ASC"><img src="../img/sort_up.gif" border="0" alt="" style="padding-bottom: 1px;"></a></td>
	<td style="padding: 2px 8px 2px 8px; border-bottom-style: solid; border-bottom-color: #8C1414; border-bottom-width: 1px; font-weight: bold;">Transaction ID</td>
</tr>
<?
if (count($results) > 1)
{
	foreach ($results as $result)
	{
	$class = ( $count & 1 ) ? 'padding: 4px 8px 4px 8px; background: #ccc;' : 'padding: 4px 8px 4px 8px;';
	?>
	<tr>
	<?
		foreach($result as $key=>$value)
		{	
			
			if ($value == "PREMIUM nihongo.fm Monthly Subscription")
						{
							?><td style="<? print $class; ?>">Monthly Subscription</td><?
						}
							elseif ($value == "PREMIUM nihongo.fm Yearly Subscription")
							{
								?><td style="<? print $class; ?>">Annual Subscription</td><?
							}
								else
								{
									?><td style="<? print $class; ?>"><? print $value; ?></td><?
								}
			
		}
	?>
	</tr>
	<?
	$count++;
	}
}
	else
	{
		?><tr><td colspan="8" style="">there are no subscriptions for this user</td></tr><?
	}
?>
</table>