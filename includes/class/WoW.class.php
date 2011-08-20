<?php

//################ Redirect if not included ################
if(!defined("INCLUDED"))
{
	header('Location: ../../index.php');
	exit();
}

// here go definitions as borrowed from Acent emu code
define('SKILL_DEFENSE',95);

define('SKILL_UNARMED',162);
define('SKILL_AXES',44);
define('SKILL_2H_AXES',172);
define('SKILL_BOWS',45);
define('SKILL_GUNS',46);
define('SKILL_MACES',54);
define('SKILL_2H_MACES',160);
define('SKILL_POLEARMS',229);
define('SKILL_SWORDS',43);
define('SKILL_2H_SWORDS',55);
define('SKILL_STAVES',136);
define('SKILL_ARMS',26);
define('SKILL_DAGGERS',173);
define('SKILL_SPEARS',227);
	// this class is used to obtain human readable data
	class WoW
	{
		private static $arrItemImages;	// this member contains displayId to image name mapping for items
		
		// Race names
		public static $arrRace = array( 1 => 'Human', 2 => 'Orc', 3 => 'Dwarf', 4 => 'Night Elf', 5 => 'Undead', 6 => 'Tauren', 7 => 'Gnome', 8 => 'Troll', 9 => 'Goblin', 10 => 'Blood Elf', 11 => 'Draenei', 22 => 'Worgen' );
		
		// Class names
		public static $arrClass = array( 1 => 'Warrior', 2 => 'Paladin', 3 => 'Hunter', 4 => 'Rogue', 5 => 'Priest', 6 => 'Death Knight', 7 => 'Shaman', 8 => 'Mage', 9 => 'Warlock', 11 => 'Druid',);
		
		// Map names
		public static $arrMaps = array(
			0 => "Eastern Kingdoms",
			1 => "Kalimdor",
			13 => "Testing",
			25 => "Scott Test",
			30 => "Alterac Valley",
			33 => "Shadowfang Keep",
			34 => "Stormwind Stockade",
			35 => "<unused>StormwindPrison",
			36 => "Deadmines",
			37 => "Azshara Crater",
			42 => "Collin's Test",
			43 => "Wailing Caverns",
			44 => "<unused> Monastery",
			47 => "Razorfen Kraul",
			48 => "Blackfathom Deeps",
			70 => "Uldaman",
			90 => "Gnomeregan",
			109 => "Sunken Temple",
			129 => "Razorfen Downs",
			169 => "Emerald Dream",
			189 => "Scarlet Monastery",
			209 => "Zul'Farrak",
			229 => "Blackrock Spire",
			230 => "Blackrock Depths",
			249 => "Onyxia's Lair",
			269 => "Opening of the Dark Portal",
			289 => "Scholomance",
			309 => "Zul'Gurub",
			329 => "Stratholme",
			349 => "Maraudon",
			369 => "Deeprun Tram",
			389 => "Ragefire Chasm",
			409 => "Molten Core",
			429 => "Dire Maul",
			449 => "Alliance PVP Barracks",
			450 => "Horde PVP Barracks",
			451 => "Development Land",
			469 => "Blackwing Lair",
			489 => "Warsong Gulch",
			509 => "Ruins of Ahn'Qiraj",
			529 => "Arathi Basin",
			530 => "Outland",
			531 => "Ahn'Qiraj Temple",
			532 => "Karazhan",
			533 => "Naxxramas",
			534 => "The Battle for Mount Hyjal",
			540 => "Hellfire Citadel: The Shattered Halls",
			542 => "Hellfire Citadel: The Blood Furnace",
			543 => "Hellfire Citadel: Ramparts",
			544 => "Magtheridon's Lair",
			545 => "Coilfang: The Steamvault",
			546 => "Coilfang: The Underbog",
			547 => "Coilfang: The Slave Pens",
			548 => "Coilfang: Serpentshrine Cavern",
			550 => "Tempest Keep",
			552 => "Tempest Keep: The Arcatraz",
			553 => "Tempest Keep: The Botanica",
			554 => "Tempest Keep: The Mechanar",
			555 => "Auchindoun: Shadow Labyrinth",
			556 => "Auchindoun: Sethekk Halls",
			557 => "Auchindoun: Mana-Tombs",
			558 => "Auchindoun: Auchenai Crypts",
			559 => "Nagrand Arena",
			560 => "The Escape From Durnholde",
			562 => "Blade's Edge Arena",
			564 => "Black Temple",
			565 => "Gruul's Lair",
			566 => "Eye of the Storm",
			568 => "Zul'Aman",
			571 => "Northrend",
			572 => "Ruins of Lordaeron",
			573 => "ExteriorTest",
			574 => "Utgarde Keep",
			575 => "Utgarde Pinnacle",
			576 => "The Nexus",
			578 => "The Oculus",
			580 => "The Sunwell",
			582 => "Transport: Rut'theran to Auberdine",
			584 => "Transport: Menethil to Theramore",
			585 => "Magister's Terrace",
			586 => "Transport: Exodar to Auberdine",
			587 => "Transport: Feathermoon Ferry",
			588 => "Transport: Menethil to Auberdine",
			589 => "Transport: Orgrimmar to Grom'Gol",
			590 => "Transport: Grom'Gol to Undercity",
			591 => "Transport: Undercity to Orgrimmar",
			592 => "Transport: Borean Tundra Test",
			593 => "Transport: Booty Bay to Ratchet",
			594 => "Transport: Howling Fjord Sister Mercy (Quest)",
			595 => "The Culling of Stratholme",
			596 => "Transport: Naglfar",
			597 => "Craig Test",
			598 => "Sunwell Fix (Unused)",
			599 => "Halls of Stone",
			600 => "Drak'Tharon Keep",
			601 => "Azjol-Nerub",
			602 => "Halls of Lightning",
			603 => "Ulduar",
			604 => "Gundrak",
			605 => "Development Land (non-weighted textures)",
			606 => "QA and DVD",
			607 => "Strand of the Ancients",
			608 => "Violet Hold",
			609 => "Ebon Hold",
			610 => "Transport: Tirisfal to Vengeance Landing",
			612 => "Transport: Menethil to Valgarde",
			613 => "Transport: Orgrimmar to Warsong Hold",
			614 => "Transport: Stormwind to Valiance Keep",
			615 => "The Obsidian Sanctum",
			616 => "The Eye of Eternity",
			617 => "Dalaran Sewers",
			618 => "The Ring of Valor",
			619 => "Ahn'kahet: The Old Kingdom",
			620 => "Transport: Moa'ki to Unu'pe",
			621 => "Transport: Moa'ki to Kamagua",
			622 => "Transport: Orgrim's Hammer",
			623 => "Transport: The Skybreaker",
			624 => "Vault of Archavon",
			627 => "unused",
			628 => "Isle of Conquest",
			631 => "Icecrown Citadel",
			632 => "The Forge of Souls",
			637 => "Abyssal Maw Exterior",
			638 => "Gilneas",
			641 => "Transport: Alliance Airship BG",
			642 => "Transport: HordeAirshipBG",
			643 => "Throne of the Tides",
			644 => "Halls of Origination",
			645 => "Blackrock Caverns",
			646 => "Deepholm",
			647 => "Transport: Orgrimmar to Thunder Bluff",
			648 => "LostIsles",
			649 => "Trial of the Crusader",
			650 => "Trial of the Champion",
			651 => "ElevatorSpawnTest",
			654 => "Gilneas2",
			655 => "GilneasPhase1",
			656 => "GilneasPhase2",
			657 => "The Vortex Pinnacle",
			658 => "Pit of Saron",
			659 => "Lost Isles Volcano Eruption",
			660 => "Deephome Ceiling",
			661 => "Lost Isles Town in a Box",
			662 => "Transport: Alliance Vashj'ir Ship",
			668 => "Halls of Reflection",
			669 => "Blackwing Descent",
			670 => "Grim Batol",
			671 => "The Bastion of Twilight",
			672 => "Transport: The Skybreaker (Icecrown Citadel Raid)",
			673 => "Transport: Orgrim's Hammer (Icecrown Citadel Raid)",
			674 => "Transport: Ship to Vashj'ir",
			712 => "Transport: The Skybreaker (IC Dungeon)",
			713 => "Transport: Orgrim's Hammer (IC Dungeon)",
			718 => "Trasnport: The Mighty Wind (Icecrown Citadel Raid)",
			719 => "Mount Hyjal Phase 1",
			720 => "Firelands",
			721 => "Firelands Terrain 2",
			723 => "Stormwind",
			724 => "The Ruby Sanctum",
			725 => "The Stonecore",
			726 => "Twin Peaks",
			727 => "STV Diamond Mine BG",
			728 => "The Battle for Gilneas (Old City Map)",
			730 => "Maelstrom Zone",
			731 => "Stonetalon Bomb",
			732 => "Tol Barad",
			734 => "Ahn'Qiraj Terrace",
			736 => "Twilight Highlands Dragonmaw Phase",
			738 => "Ship to Vashj'ir (Orgrimmar -> Vashj'ir)",
			739 => "Vashj'ir Sub - Horde",
			740 => "Vashj'ir Sub - Alliance",
			741 => "Twilight Highlands Horde Transport",
			742 => "Vashj'ir Sub - Horde - Circling Abyssal Maw",
			743 => "Vashj'ir Sub - Alliance circling Abyssal Maw",
			746 => "Uldum Phase Oasis",
			747 => "Transport: Deepholm Gunship",
			748 => "Transport: Onyxia/Nefarian Elevator",
			749 => "Transport: Gilneas Moving Gunship",
			750 => "Transport: Gilneas Static Gunship",
			751 => "Redridge - Orc Bomb",
			752 => "Redridge - Bridge Phase One",
			753 => "Redridge - Bridge Phase Two",
			754 => "Throne of the Four Winds",
			755 => "Lost City of the Tol'vir",
			757 => "Baradin Hold",
			759 => "Uldum Phased Entrance",
			760 => "Twilight Highlands Phased Entrance",
			761 => "The Battle for Gilneas",
			762 => "Twilight Highlands Zeppelin 1",
			763 => "Twilight Highlands Zeppelin 2",
			764 => "Uldum - Phase Wrecked Camp",
			765 => "Krazzworks Attack Zeppelin",
			766 => "Transport: Gilneas Moving Gunship 02",
			767 => "Transport: Gilneas Moving Gunship 03",
		);
		
		// Area names
		public static $arrZones;
		
		
		//////		
		// Item display related
		//////
		
		// item display related
		public static $itemIType = array( 0 => 'Not Equippable',
																			1 => 'Head',
																			2 => 'Neck',
																			3 => 'Shoulder',
																			4 => 'Shirt',
																			5 => 'Chest',
																			6 => 'Waist',
																			7 => 'Legs',
																			8 => 'Feet',
																			9 => 'Wrists',
																			10 => 'Hands',
																			11 => 'Finger',
																			12 => 'Trinket',
																			13 => 'One-Hand',
																			14 => 'Shield',
																			15 => 'Ranged',
																			16 => 'Back',
																			17 => 'Two-Hand',
																			18 => 'Bag',
																			19 => 'Tabard',
																			20 => 'Chest',
																			21 => 'Main Hand',
																			22 => 'Off Hand',
																			23 => 'Held In Off-hand',
																			24 => 'Projectile',
																			25 => 'Thrown',
																			26 => 'Ranged',
																			27 => 'Quiver',
																			28 => 'Relic');
																			
		public static $itemBonding = array( 0 => 'None',
																				1 => 'Binds when picked up',
																				2 => 'Binds when equipped',
																				3 => 'Binds when used');
		public static $itemDamage = array( 0 => 'Damage',
																				1 => 'Holy Damage',
																				2 => 'Fire Damage',
																				3 => 'Nature Damage',
																				4 => 'Frost Damage',
																				5 => 'Shadow Damage',
																				6 => 'Arcane Damage');
		public static $itemStats = array ( 0 => 'None',
																				1 => 'Health',
																				2 => 'Unknown',
																				3 => 'Agility',
																				4 => 'Strength',
																				5 => 'Intellect',
																				6 => 'Spirit',
																				7 => 'Stamina',
																				8 => 'Unknown',
																				9 => 'Unknown',
																				10 => 'Unknown',
																				11 => 'Weapon Skill Rating',
																				12 => 'Defense Rating',
																				13 => 'Dodge Rating',
																				14 => 'Parry Rating',
																				15 => 'Block Rating',
																				16 => 'Hit Rating',
																				17 => 'Ranged Hit Rating',
																				18 => 'Spell Hit Rating',
																				19 => 'Melee Critical Strike Rating',
																				20 => 'Ranged Critical Strike Rating',
																				21 => 'Spell Critical Strike Rating',
																				22 => 'Melee Resist Rating',
																				23 => 'Ranged Resist Rating',
																				24 => 'Spell Resist Rating',
																				25 => 'Melee Crit Resist Rating',
																				26 => 'Ranged Crit Resist Rating',
																				27 => 'Spell Crit Resist Rating',
																				28 => 'Melee Haste Rating',
																				29 => 'Ranged Haste Rating',
																				30 => 'Spell Haste Rating',
																				31 => 'Hit Rating',
																				32 => 'Critical Strike Rating',
																				33 => 'Resist Rating',
																				34 => 'Crit Resist Rating',
																				35 => 'Resilience Rating',
																				36 => 'Haste');
		
		public static $itemClass = array( 0 => 'Consumable',
																			1 => 'Container',
																			2 => 'Weapon',
																			3 => 'Jewelry',
																			4 => 'Armor',
																			5 => 'Reagent',
																			6 => 'Projectile',
																			7 => 'Trade Goods',
																			8 => 'Generic',
																			9 => 'Recipe',
																			10 => 'Money',
																			11 => 'Quiver',
																			12 => 'Quest',
																			13 => 'Key',
																			14 => 'Permanent',
																			15 => 'Miscellaneous',
																			16 => 'Glyph');
																			
		public static $itemSubclass = array(
																		0 => array( 0 => 'Food',
																								1 => 'Liquid',
																								2 => 'Potion',
																								3 => 'Enchainment',
																								4 => 'Bandage'),
																		2 => array(	0 => 'Axe',
																								1 => 'Axe', // 2H
																								2 => 'Bow',
																								3 => 'Gun',
																								4 => 'Mace',
																								5 => 'Mace', // 2H
																								6 => 'Polearm',
																								7 => 'Sword',
																								8 => 'Sword', // 2H
																								10 => 'Staff',
																								11 => 'Exotic',
																								12 => 'Exotic', // 2H
																								13 => 'Fist Weapon',
																								14 => 'Miscellaneous',
																								15 => 'Dagger',
																								16 => 'Thrown',
																								17 => 'Spear',
																								18 => 'Crossbow',
																								19 => 'Wand',
																								20 => 'Fishing Pole'),
																		4 => array(	0 => '', //'Miscellaneous',
																								1 => 'Cloth',
																								2 => 'Leather',
																								3 => 'Mail',
																								4 => 'Plate',
																								6 => 'Shield'),
																		6 => array(	2 => 'Arrow',
																								3 => 'Bullet'),
																		7 => array(	0 => 'Trade Goods',
																								1 => 'Parts',
																								2 => 'Explosives',
																								3 => 'Devices'),
																		9 => array(	0 => 'Book',
																								1 => 'Leatherworking',
																								2 => 'Tailoring',
																								3 => 'Engineering',
																								4 => 'Blacksmithing',
																								5 => 'Cooking',
																								6 => 'Alchemy',
																								7 => 'First Aid',
																								8 => 'Enchanting',
																								9 => 'Fishing')
																								);

		//Account Game Flags
		public static $clientFlags = array(
			0 => 'World of Warcraft Classic', 
			8 => 'The Burning Crusade',
			16 => 'Wrath of the Lich King', 
			24 => 'Wrath of the Lich King and Burning Crusade', 
			null => "Unknown"
		);
		
		//Race to Faction
		public static $arrFaction = array(
				1 => "Alliance",
				2 => "Horde",
				3 => "Alliance",
				4 => "Alliance",
				5 => "Horde",
				6 => "Horde",
				7 => "Alliance",
				8 => "Horde",
				9 => "Alliance",
				10 => "Horde",
				11 => "Alliance",
				22 => "Horde",
		);
		
		//Race to Faction ID
		public static $arrFactionId = array(
				1 => 1,
				2 => 2,
				3 => 1,
				4 => 1,
				5 => 2,
				6 => 2,
				7 => 1,
				8 => 2,
				9 => 1,
				10 => 2,
				11 => 1,
				22 => 2,
		);
		
		//Gender
		public static $arrGender = array( 0 => 'Male', 1 => 'Female');
		
		//PVP ranks
		public static $arrPVPRanks = array(
				"Alliance" => array(
					0 => 'No Rank',
					1 => 'Private',
					2 => 'Corporal',
					3 => 'Sergeant',
					4 => 'Master Sergeant',
					5 => 'Sergeant Major',
					6 => 'Knight',
					7 => 'Knight-Lieutenant',
					8 => 'Knight-Captain',
					9 => 'Knight-Champion',
					10 => 'Lieutenant-Commander',
					11 => 'Commander',
					12 => 'Marshal',
					13 => 'Field Marshal',
					14 => 'Grand Marshal',
				),
				"Horde" => array(
					0 => 'No Rank',
					1 => 'Scout',
					2 => 'Grunt',
					3 => 'Sergeant',
					4 => 'Senior-Sergeant',
					5 => 'First-Sergeant',
					6 => 'Stone Guard',
					7 => 'Blood Guard',
					8 => 'Legionnaire',
					9 => 'Centurion',
					10 => 'Champion',
					11 => 'Lieutenant General',
					12 => 'General',
					13 => 'Warlord',
					14 => 'High Warlord',
				),
		);
		
		/**
		 * ********************
		 * Icons
		 * ********************
		 */
		public static $classIcons = array(
				1 => "images/icons/class/1.gif",
				2 => "images/icons/class/2.gif",
				3 => "images/icons/class/3.gif",
				4 => "images/icons/class/4.gif",
				5 => "images/icons/class/5.gif",
				6 => "images/icons/class/6.gif",
				7 => "images/icons/class/7.gif",
				8 => "images/icons/class/8.gif",
				9 => "images/icons/class/9.gif",
				11 => "images/icons/class/11.gif",
				14 => "images/icons/class/14.gif",
		);
		
		public static $raceIcons = array(
			0 => array(
				 1 => 'images/icons/race/1-0.gif',
				 2 => 'images/icons/race/2-0.gif',
				 3 => 'images/icons/race/3-0.gif',
				 4 => 'images/icons/race/4-0.gif',
				 5 => 'images/icons/race/5-0.gif',
				 6 => 'images/icons/race/6-0.gif',
				 7 => 'images/icons/race/7-0.gif',
				 8 => 'images/icons/race/8-0.gif',
				 9 => 'images/icons/race/9-0.gif',
				 10 => 'images/icons/race/10-0.gif',
				 11 => 'images/icons/race/11-0.gif',
				 22 => 'images/icons/race/22-0.gif',
			),
			1 => array(
				 1 => 'images/icons/race/1-1.gif',
				 2 => 'images/icons/race/2-1.gif',
				 3 => 'images/icons/race/3-1.gif',
				 4 => 'images/icons/race/4-1.gif',
				 5 => 'images/icons/race/5-1.gif',
				 6 => 'images/icons/race/6-1.gif',
				 7 => 'images/icons/race/7-1.gif',
				 8 => 'images/icons/race/8-1.gif',
				 9 => 'images/icons/race/9-1.gif',
				 10 => 'images/icons/race/10-1.gif',
				 11 => 'images/icons/race/11-1.gif',
				 22 => 'images/icons/race/22-1.gif',
			)
		);
		
		public static $pvprankIcons = array(
			"Alliance" => array(
				0 => 'images/icons/pvpranks/rank_default_0.gif',
				1 => 'images/icons/pvpranks/rank1.gif',
				2 => 'images/icons/pvpranks/rank2.gif',
				3 => 'images/icons/pvpranks/rank3.gif',
				4 => 'images/icons/pvpranks/rank4.gif',
				5 => 'images/icons/pvpranks/rank5.gif',
				6 => 'images/icons/pvpranks/rank6.gif',
				7 => 'images/icons/pvpranks/rank7.gif',
				8 => 'images/icons/pvpranks/rank8.gif',
				9 => 'images/icons/pvpranks/rank9.gif',
				10 => 'images/icons/pvpranks/rank10.gif',
				11 => 'images/icons/pvpranks/rank11.gif',
				12 => 'images/icons/pvpranks/rank12.gif',
				13 => 'images/icons/pvpranks/rank13.gif',
				14 => 'images/icons/pvpranks/rank14.gif',
			),
			"Horde" => array(
				0 => 'images/icons/pvpranks/rank_default_1.gif',
				1 => 'images/icons/pvpranks/rank1.gif',
				2 => 'images/icons/pvpranks/rank2.gif',
				3 => 'images/icons/pvpranks/rank3.gif',
				4 => 'images/icons/pvpranks/rank4.gif',
				5 => 'images/icons/pvpranks/rank5.gif',
				6 => 'images/icons/pvpranks/rank6.gif',
				7 => 'images/icons/pvpranks/rank7.gif',
				8 => 'images/icons/pvpranks/rank8.gif',
				9 => 'images/icons/pvpranks/rank9.gif',
				10 => 'images/icons/pvpranks/rank10.gif',
				11 => 'images/icons/pvpranks/rank11.gif',
				12 => 'images/icons/pvpranks/rank12.gif',
				13 => 'images/icons/pvpranks/rank13.gif',
				14 => 'images/icons/pvpranks/rank14.gif',
			)
		);
		
		public static $factionIcons = array(
				1 => "images/icons/pvpranks/rank_default_0.gif",
				2 => "images/icons/pvpranks/rank_default_1.gif",
				3 => "images/icons/pvpranks/rank_default_0.gif",
				4 => "images/icons/pvpranks/rank_default_0.gif",
				5 => "images/icons/pvpranks/rank_default_1.gif",
				6 => "images/icons/pvpranks/rank_default_1.gif",
				7 => "images/icons/pvpranks/rank_default_0.gif",
				8 => "images/icons/pvpranks/rank_default_1.gif",
				9 => "images/icons/pvpranks/rank_default_0.gif",
				10 => "images/icons/pvpranks/rank_default_1.gif",
				11 => "images/icons/pvpranks/rank_default_0.gif",
				22 => "images/icons/pvpranks/rank_default_1.gif",
		);
		
		//////
		//
		// Methods to access static properties.
		//
		//////
		
		// get mapping of item displayid to picture name
		public static function getItemImages()
		{
			include_once("resources/php/ItemDisplayInfoArray.php");
			foreach($arrItemImages as $key => $val)
			{
				wow::$arrItemImages[$key] = $val;
			}
		}
		
		public static function getZonesArray()
		{
			include_once("resources/php/AreaTableArray.php");
			foreach($arrZones as $key => $val)
			{
				wow::$arrZones[$key] = $val;
			}
		}
		
		public static function banned_id_to_status($id)
		{
			if($id == 0)
			{
				return "<font color='green'>Not Banned</font>";
			}
			if($id == 1)
			{
				return "<font color='red'>Permanently banned</font>";
			}
			if($id >= 2)
			{
				if(date("F j, Y, g:i a", $id) !== false)
				{
					$banned = "<font color='red'>Banned till " . date("j F Y, g:i a", $id)."</font>";
				}
				else
				{
					$banned = "Unknown";
				}
				return $banned;
			}
		}
		
		public static function muted_id_to_status($id)
		{
			if($id == 0)
			{
				return "<font color='green'>Not muted</font>";
			}
			if($id == 1)
			{
				return "<font color='red'>Permanently muted</font>";
			}
			if($id >= 2)
			{
				if(date("j F Y, g:i a", $id) !== false)
				{
					$banned = "<font color='red'>Muted till " . date("j F Y, g:i a", $id)."</font>";
				}
				else
				{
					$banned = "Unknown";
				}
				return $banned;
			}
		}
		
		public static function last_ip($li)
		{
			if($li == "0.0.0.0")
			{
				return "Never Logged in/Not logged in";
			}
			elseif($li == "0")
			{
				return "Never Logged in/Not logged in";
			}
			elseif($li == 0)
			{
				return "Never Logged in/Not logged in";
			}
			elseif($li == null)
			{
				return "Never Logged in/Not logged in";
			}
			else
			{
				return $li;
			}
		}
		
		public static function last_login($ll)
		{
			if($ll == "0")
			{
				return "Never Logged in/Not logged in";
			}
			elseif($ll == 0)
			{
				return "Never Logged in/Not logged in";
			}
			elseif($ll == null)
			{
				return "Never Logged in/Not logged in";
			}
			else
			{
				$datetime = date_create($ll);
				$datetime = date_format($datetime, "j F Y, g:i a");
				return $datetime;
			}
		}
		
		public static function deathstate($ds)
		{
			if($ds == 0)
			{
				return "<font color='lightgreen'>Alive</font>";
			}
			else
			{
				return "<font color='red'>Dead</font>";
			}
		}
		
		// other methods
		
	}// end of wow class

?>