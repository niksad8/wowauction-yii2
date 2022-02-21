# Wowauction codebase

This repository is the backend and php generated frontend of Webauctioneer.com website.

There are many components of this software package : 

- this backend server
- the frontend is just the php generated data
- the c# automation program that will get the auctioneer data and upload it to the website. 
- there is a new react interface that I was trying complete but didnt get the time.
- backend commands in the commands folder these are executed and some as services
- discord integration scripts for the discord interaction

## Requirements 
- php 7.3+ 
- node 14+
- database mysql
- discord account setup for discord integration

## Database Setup 
The database is provided in the wowauction.sql file. It contains all the tables you will need to get started. 

### Data you need from WoW
the following data needs to come from WoW. There is a lot of data that needs to come from the WoW dbc. We have importer commands. 

What are the dbcs you need? 
- Spell.dbc - this is needed to get a list of all spells(we need this cause all crafting items are actually spells which are created) and thie ingredients.
- ItemExtendedCost.dbc - needed to get information on how an item can be brought from a vendor(if any). eg: how can i get a Artic Fur? buy for gold or exchange some other items. This information is included in the item page where we show the cost price of an item.
- SpellIcon.dbc - to get the icon names for each crafting recipe
- ItemDisplayInfo.dbc - to get the icon names for items that are to be crafted or used

### Additional Information in the database
We get some additional information in the database from the TrinityDb database. This includes the tables `creature_template` and `item_template`. 

## Commands in the software.
Commands can be accessed using the `yii` script. eg: `yii something_here`

- ### `GetProfessionSpellsController` or `./yii get-profession-spells/index` 
   this is used to populate the table of `profession_spells`. This data is used in the profession page. WE get this information from WowHead.  

- ### `GuildLoggerController` or `./yii guild-logger/index`
   this script is not used any more but its a constant script which queries the armory of warmane's guild api. It is used to log online players in the guild and people who have left the guild or joined it.

- ### `ImportFromCsvController` or `./yii import-from-csv/itemclass` `./yii import-from-csv/itemsubclass`
   reads the ItemClass.dbc.csv file and populates the item_class table. Also can read the ItemSubClass.dbc.csv to populate the item_sub_class table.

- ### `ImportIconsController` or `./yii import-icons/index`
   reads the ItemDisplayInfo.dbc.csv file to populate the item_display_info table

- ### `ItemBuildController`has many commands
  
  - `./yii item-build/setup-vendors`
  
     this command is responsible for reading the ItemExtendedCost.dbc.csv file and populate the `item_build` table. This sciript will read the dbc and determine the required ingredients needed to *BUY* an item. This is only related to vendors.
  - `./yii item-build/setup-spells`
     
     this commands reads the Spell.dbc.csv and SpellIcon.dbc.csv. Items responsible to populate the spell_master table with details about a spell name, icon and so on. It also builds `item_build` with recepies and items needed that come from professions. Remember all crafting recipes are spells.
  - `./yii item-build/process-items`
    
     this is the heart if the whole website. This command is responsible reading the data in `auction_item` and comparing it to `old_auction_item` table to look for trends (up ,down or same). It also calculates the cost price, averages , means, min , max. After the whole process is done we populate the table `item_price` with all this information.
- `./yii process-alerts/index`

   there used to be a system where people can put in notifications on items and get emails this script is to be run in the background to read the notifications table and setd emails if needed.

- `./yii reduce-data/index`

   as the data grows the database also grows and becomes slow. I  created a script that would be run monthly and any data collected 2 months prior would be heavily reduced. there are be multiple scans of the AH and for each scan we produce a data point in the item_price table. Sometimes we can choose to scan ever 3 hours which means we will get 8 price points for each item, each server. This script will reduce old data from the item_price table from 8 points a day to 1 per day by calculating the average and storing that average and deleteing the rest.

- `./yii processahdata/index`

  this will read a lua file that has been created by Auctioneer and put all that data in to the `auction_item` table. Move the current `action_item` table items into `old_auction_item` table.

- `./yii runimports/index`
  
  this is a service command that should be run in the background at all times. It will scan the `process_queue` table and process all pending scans.
# Setup

- setup the database using `wowauction.sql`
- make sure yii can access that database by editing the `config/web.php` and `config/console.php` file.
- Setup email creds if you need to
- first import the tables from trinitydb you will need `creature_template` `item_template`, `npc_vendor` table.
- run the following commands
  - `./yii item-build/setup-spells` 
  - `./yii item-build/setup-vendors`
  - `./yii get-profession-spells/index` 
  - `./yii import-from-csv/itemclass` 
  - `./yii import-from-csv/itemsubclass`
  - `./yii import-icons/index`
- This should setup the data you need. You will need to then setup a user in the `users` table make sure the field `is_admin` is 1 so that you have admin access on this user. The password please look in the Users model and look at `setPassword` member function 
- you will then be able to login using the admin user and be able to see the extra admin controls to add, delete servers, realms
- make sure `./yii runimports/index` is run in the background at all times.

# Tables in the Database
- `users` this is the user table people can sign up for a normal account(not admin). You can make a admin by setting the `is_admin` to 1
- `user_settings` this contains the settings for a user. Stored in option_name, value pair.
- `user_item_alerts` contains alerts stored by the user which can then be triggered. Once a trigger is triggered the alert is disabled. this is determined by setting the alert_sent=1
- `spell_master` this is the spell list, usually contains all the recipes in a profession and item. It contains all the spells from the spell.dbc
- `slot_translation` is the slot of an item, this is the same slot we can filter by in the auction house.
- `slot_master` not used table
- `servers` this table contains the servers, these entries will appear on the main page
- `realms` the realms that are present in a server. also contains data about login stored in `data`. We also have a update_schedule which stores the seconds it has to be updated. eg: 3600 is every 1 hour 21600 is every 6 hours 
- `factions` the factions in the realm. Used to identify the different AH present. it should always be 1 = Horde, 2 = Alliance, 3 = Neutral
- `expansion` the expansion that is used in a realm.
- `professions` contains a list of professions, this is shown in the profession page or realm page
- `profession_spells` contains all the recipes that are present in a profession, contains spell_ids
- `auction_item` this contains the records that were read from a auctioneer scan. contains details about an item, and its prices on the auction house
- `old_auction_item` this contains the previous records that were read from auctioneer, this is used to calculate things like trends and so on.
- `auction_timer` converts auction_timer id to text shown in auction house
- `item_template` comes from the trinitydb contains the item template comes from dbc
- `npc_vendor` contains the vendors that are present in the game, contains the items that the vendor is selling and the prices for each item they are selling
- `scan_stats` this is the summary information about Auction house scan this is calculated when the auctioneer scan has been processed. This information is shown in the server page and realm page.
- `process_queue` this table is populated when a scan is completed in auctioneer and the file has been uploaded to the server. When the file has been processed the `completed` field is set to 1
- `notifications` this contains all the notifications that are generated for a user and once sent to the user (via email or discord) marked as sent
- `item_sub_class` contains the subclass of an item, where id is the sub_class_id(as per dbc) and class_id is the class of the item. The sub class the is second drop down in the auction house filter you see on the left panel.
- `item_class` contains the class of the item, this is the main category we see on the left side panel in the auction house.
- `item_display_info` contains the icons that are to be associated with the item. this contains the item_display_id which is stored in item_template this table translates that id in to a name we can display to the user.
- `item_build` contains the ingredients needed to create an item and where to get that information from. This will contain spells(for profession) and vendor information
- `item_prices` this is a large table that is calculated from the `auction_item` table. This table contains all the summary information for the item. eg: avg, mean, median, min, max, trend
- `email_templates` contains templates for emails that are to be sent to the user
- `creature_template` contains information about creatures in the world this is used to look up vendors in the `item_build` table
- `ahscan_attempts` this is used by the desktop application to see if there are any scans that should performed.
- `ah_main_cat` used in the search screen where you can filter items by this field.
- `ah_sub_cat` used in the search screen to prepare the list
- `email_subscriptions` not used
- `guild_log_characters` not used in this context
- `gulid_log_events` now used in this context
- `guild_log_settings` not used in this context

# How this all works
- we first need the server table to be populated with a server entry
- then we need to make make a realm entry this will have a field call `data`
   this field should have a JSON data eg :

   `{"h":{"username":"abcd","password":"efgh","auctioneer":"Vynna","keybind":"]"},"a":{"username":"ghyu","password":"yuuio","auctioneer":"eoch","keybind":"]"}}`

   in this structure we need to provide two entries one is for each faction eg: h for horde and a for alliance. you need to provide the username and password, 
   auctioneer is the name of the auctioneer who is really near to your bot character (we use `/tar <aucctioneer_name>` in wow to select the auctioneer)
   the keybind is the key we use to *Interact* with the auctioneer. This will cause the auction screen to be opened.
- the desktop app will query to sever to see if there are any pending scans. 
- if there are any pending scans we download the username, password and other data and download it all.
- the wow exe is opened and the user is logged in and the auction house is scanned.
- the resulting auction house lua file from Auctioneer data is updated to the server.
- a new entry is make in `process_queue` table
- the backend command of `./yii runimports/index` will pick up entries in the table and start the processing of this information.
- populate all the required data in the tables.


   