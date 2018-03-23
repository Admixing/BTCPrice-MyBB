<?php
/**
 * Crypto Price from WallBB
 * Website: https://wallbb.co.uk
 *
 * Collaborated by Nasyr
 * Website: https://welovemybb.com
 *
 */

// Make sure we can't access this file directly from the browser.
if(!defined('IN_MYBB'))
{
	die('This file cannot be accessed directly.');
}

// cache templates - this is important when it comes to performance
// THIS_SCRIPT is defined by some of the MyBB scripts, including index.php

$plugins->add_hook("global_start", "cryptoprice");

function cryptoprice_info()
{
			
    global $mybb, $cache, $db, $lang;

	
	return array(
		"name"			=> "Crypto Price",
		"description"	        => "Displays the current Crypto Prices for multiple coins",
		"website"		=> "https://wallbb.co.uk",
		"author"		=> "WallBB & Nasyr",
		"authorsite"	        => "https://wallbb.co.uk",
		"version"		=> "1.0",
		//"guid" 		=> "",
		"codename"		=> "cryptoprice",
		"compatibility"         => "18*"
	);
}

function cryptoprice_install()
{
    global $db, $mybb;

    $cryptoprice_group = array(
       'name' => 'cryptoprice',
       'title' => 'Crypto Price Plugin',
       'description' => 'Chose which crypto currencies to display.',
       'disporder' => 5, // The order your setting group will display
       'isdefault' => 0
    );

    $gid = $db->insert_query("settinggroups", $cryptoprice_group);
		
		// Enable all coins to show
    $cryptoprice_1 = array(
        'name' => 'cryptoprice_1',
        'title' => 'Enable all crypto currencies?',
        'description' => 'Do you want the cryptoprice turned on?',
        'optionscode' => 'yesno',
        'value' => '1',
        'disporder' => 1,
	"gid" => $gid
    );
    $db->insert_query('settings', $cryptoprice_1);
	
		// Enable bitcoin only to show
	$cryptoprice_2 = array(
        'name' => 'cryptoprice_2',
        'title' => 'Display Bitcoin Only?',
        'description' => 'Do you want to display Bitcoin?',
        'optionscode' => 'yesno',
        'value' => '0',
        'disporder' => 2,
	"gid" => $gid
    );
    $db->insert_query('settings', $cryptoprice_2);
	
		// Enable ethereum only to show
	$cryptoprice_3 = array(
        'name' => 'cryptoprice_3',
        'title' => 'Display Ethereum Only?',
        'description' => 'Do you want to display Ethereum?',
        'optionscode' => 'yesno',
        'value' => '0',
        'disporder' => 3,
	"gid" => $gid
    );
    $db->insert_query('settings', $cryptoprice_3);
	
		// Enable ripple only to show
	$cryptoprice_4 = array(
        'name' => 'cryptoprice_4',
        'title' => 'Display Ripple Only?',
        'description' => 'Do you want to display Ripple?',
        'optionscode' => 'yesno',
        'value' => '0',
        'disporder' => 4,
	"gid" => $gid
    );
    $db->insert_query('settings', $cryptoprice_4);
	
		// Enable bitcoin-cash only to show
	$cryptoprice_5 = array(
        'name' => 'cryptoprice_5',
        'title' => 'Display Bitcoin-Cash Only?',
        'description' => 'Do you want to display Bitcoin-Cash?',
        'optionscode' => 'yesno',
        'value' => '0',
        'disporder' => 5,
	"gid" => $gid
    );
    $db->insert_query('settings', $cryptoprice_5);
	
		// Enable litecoin only to show
	$cryptoprice_6 = array(
        'name' => 'cryptoprice_6',
        'title' => 'Display Litecoin Only?',
        'description' => 'Do you want to display Litecoin?',
        'optionscode' => 'yesno',
        'value' => '0',
        'disporder' => 6,
	"gid" => $gid
    );
    $db->insert_query('settings', $cryptoprice_6);


    rebuild_settings();
}

function cryptoprice_is_installed()
{
    global $mybb;
    if(isset($mybb->settings['cryptoprice_1']))
    {
    return true;
    }

return false;
}

function cryptoprice_uninstall()
{
    global $db;

    $db->delete_query('settings', "name IN ('cryptoprice_1')");
    $db->delete_query('settinggroups', "name = 'cryptoprice'");

    rebuild_settings();
}

function cryptoprice_activate()
{

}

function cryptoprice_deactivate()
{

}


function cryptoprice()
{
    global $mybb, $cache, $db, $lang, $cryptoprice;
      $url = "https://api.coinmarketcap.com/v1/ticker/";

  $json = file_get_contents($url);
  $data = json_decode($json, TRUE);
	
	// Bitcoin price
  $btcprice = $data[0]["price_usd"];    
  $usd_price = 10;     # Let cost of elephant be 10$
  $bitcoin_price = round( $usd_price / $btcprice , 8);
	
	// Ethereun price
  $ethprice = $data[1]["price_usd"];    
  $usd_price = 10;     # Let cost of elephant be 10$
  $ethereum_price = round( $usd_price / $ethprice , 8);
  
	// Ripple price
  $xrpprice = $data[2]["price_usd"];    
  $usd_price = 10;     # Let cost of elephant be 10$
  $ripple_price = round( $usd_price / $xrpprice , 8);
  
 	 // Bitcoin-Cash price
  $bchprice = $data[3]["price_usd"];    
  $usd_price = 10;     # Let cost of elephant be 10$
  $bitcoincash_price = round( $usd_price / $bchprice , 8);
  
  	// Litecoin price
  $ltcprice = $data[4]["price_usd"];    
  $usd_price = 10;     # Let cost of elephant be 10$
  $litecoin_price = round( $usd_price / $ltcprice , 8);

		// All coins online
    if($mybb->settings['cryptoprice_1'] == 1)
    {
		$cryptoprice = "BTC: $" . "$btcprice <br /> ETH: $" . "$ethprice <br /> XRP: $" . "$xrpprice <br /> BCH: $" . "$bchprice <br /> LTC: $" . "$ltcprice";
    }
		// Bitcoin only
	if($mybb->settings['cryptoprice_2'] == 1)
    {
		$cryptoprice = "BTC: $" . "$btcprice";
    }
		// Ethereum only
	if($mybb->settings['cryptoprice_3'] == 1)
    {
		$cryptoprice = "ETH: $" . "$ethprice";
    }
		// Ripple only
	if($mybb->settings['cryptoprice_4'] == 1)
    {
		$cryptoprice = "XRP: $" . "$xrpprice";
    }
		// Bitcoin-Cash only
	if($mybb->settings['cryptoprice_5'] == 1)
    {
		$cryptoprice = "BCH: $" . "$bchprice";
    }
		// Litecoin only
	if($mybb->settings['cryptoprice_6'] == 1)
    {
		$cryptoprice = "LTC: $" . "$ltcprice";
    }
	return true;
}
