<?php
/**
 * BTC Price from WallBB
 *
 * Website: https://wallbb.co.uk
 *
 */

// Make sure we can't access this file directly from the browser.
if(!defined('IN_MYBB'))
{
	die('This file cannot be accessed directly.');
}

// cache templates - this is important when it comes to performance
// THIS_SCRIPT is defined by some of the MyBB scripts, including index.php

$plugins->add_hook("global_start", "btcprice");

function btcprice_info()
{
			
    global $mybb, $cache, $db, $lang;

	
	return array(
		"name"			=> "BTC Price",
		"description"	        => "Shows two variables btcprice_in and btcprice_out",
		"website"		=> "https://wallbb.co.uk",
		"author"		=> "WallBB",
		"authorsite"	        => "https://wallbb.co.uk",
		"version"		=> "1.0",
		//"guid" 		=> "",
		"codename"		=> "btcprice",
		"compatibility"         => "18*"
	);
}

function btcprice_install()
{
    global $db, $mybb;

    $btcprice_group = array(
       'name' => 'btcprice',
       'title' => 'BTC Price Plugin',
       'description' => 'This is my plugin and it does some things',
       'disporder' => 5, // The order your setting group will display
       'isdefault' => 0
    );

    $gid = $db->insert_query("settinggroups", $btcprice_group);

    $btcprice_1 = array(
        'name' => 'btcprice_1',
        'title' => 'Enable btcprice?',
        'description' => 'Do you want the btcprice turned on?',
        'optionscode' => 'yesno',
        'value' => '1',
        'disporder' => 1,
	"gid" => $gid
    );
    $db->insert_query('settings', $btcprice_1);


    rebuild_settings();
}

function btcprice_is_installed()
{
    global $mybb;
    if(isset($mybb->settings['btcprice_1']))
    {
    return true;
    }

return false;
}

function btcprice_uninstall()
{
    global $db;

    $db->delete_query('settings', "name IN ('btcprice_1')");
    $db->delete_query('settinggroups', "name = 'btcprice'");

    rebuild_settings();
}

function btcprice_activate()
{

}

function btcprice_deactivate()
{

}


function btcprice()
{
    global $mybb, $cache, $db, $lang, $btcprice;
      $url = "https://api.coinmarketcap.com/v1/ticker/";

  $json = file_get_contents($url);
  $data = json_decode($json, TRUE);

  $rate = $data[0]["price_usd"];    
  $usd_price = 10;     # Let cost of elephant be 10$
  $bitcoin_price = round( $usd_price / $rate , 8 );

    if($mybb->settings['btcprice_1'] == 1)
    {
        // Everyone else
        $btcprice = $rate;
    }

    return true;
}
