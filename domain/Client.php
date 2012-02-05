<?php
class Client {
	private $id;     		// uniquely identifies the donor or recipient
							// e.g. ÒFood Lion LauÓ 
	private $name;			// e.g. ÒFood Lion #1698 Laurel BayÓ
	private $chain_name;	// e.g., ÒFood LionÓ (usually blank)
	private $area;			// ÒHHIÓ, ÒSUNÓ, or ÒBFTÓ
	private $type;			// ÒdonorÓ or ÒrecipientÓ
	private $address;       // street address Ð string
	private $city;			// city
	private $state;			// 2-letter abbrev - usually ÒSCÓ
	private $zip; 	      	// zip code Ð integer
	private $geocoordinates; // array pair: [latitude, longitude] for navigation
	private $phone1;		// primary phone
	private $phone2;		// secondary phone
	private $days;			// array of days for pick-up or delivery
							// e.g. [ÒMondayÓ, ÒWednesdayÓ]
	private $feed_america;	// ÒyesÓ or ÒnoÓ
	private $notes; 		// notes written by the team captain or coordinator
}
?>