<?php
class Client {
	private $id;     		// uniquely identifies the donor or recipient
							// e.g. �Food Lion Lau� 
	private $name;			// e.g. �Food Lion #1698 Laurel Bay�
	private $chain_name;	// e.g., �Food Lion� (usually blank)
	private $area;			// �HHI�, �SUN�, or �BFT�
	private $type;			// �donor� or �recipient�
	private $address;       // street address � string
	private $city;			// city
	private $state;			// 2-letter abbrev - usually �SC�
	private $zip; 	      	// zip code � integer
	private $geocoordinates; // array pair: [latitude, longitude] for navigation
	private $phone1;		// primary phone
	private $phone2;		// secondary phone
	private $days;			// array of days for pick-up or delivery
							// e.g. [�Monday�, �Wednesday�]
	private $feed_america;	// �yes� or �no�
	private $notes; 		// notes written by the team captain or coordinator
}
?>