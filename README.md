=== WoWpi Guild ===
Contributors: Avenirer
Donate link: https://www.patreon.com/wowpi
Tags: World of Warcraft, Armory, guild, roster, WoW
Requires at least: 4.7
Tested up to: 5.4
Stable tag: 4.3
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

You want a proper World of Warcraft's guild website but you don't know how? Look no further. This is the plugin for your guild's needs. It imports everything you need related to the guild members,and gives you the basis for a good guild website.

== Description ==

This plugin started from an older plugin named WoWpi. That plugin was developped with the gamer in mind. But then, it seemed that a lot of guilds wanted to use the plugin for their needs. So that plugin kept evolving until it couldn't anymore.

This time I am working on a plugin that revolves around guilds in World of Warcraft. I hope it will evolve further, becoming the go to tool for a guild's website needs.

= Highlights =

**Guild Roster**

**Character post types**

**Recruitment Widget**


== Installation ==

= Roster =
After installation, it is time to use it. Until now, we have access to the guild roster by using a shortcode:

`[wowpi_guild_roster]` - this will permit you to show the guild roster

`[wowpi_guild_roster ranks="0|1|2"]` - this will only show the guild roster with the ranks 0 (Guild Master), Rank 1 and Rank 2

`[wowpi_guild_roster ranks="0:Guild Master|1:Wolves|2:Pups"]` - this will allow you to have custom names for your ranks

`[wowpi_guild_roster id="your_custom_id_for_table]` - this will add the `id` attribute to the table. Only do this if you want to radically change the way the table looks.

`[wowpi_guild_roster class="custom_css_class"]` - this will allow you to add/change a CSS class to your table. Only do  this if you want to have custom styling for your roster.

`[wowpi_guild_roster rows="25"]` - this will allow you to paginate your table, each page having 25 or any number of members you wish per page.
 
 `[wowpi_guild_roster rows="all"]` - this will allow you to have the whole guild roster shown, without pagination.
 
= Members post type =

If you are a developer, or know a thing or two about creating templates for custom post types, now you can create character pages for you guild members, in which you can have a personal description. I didn't use the body of the post thinking that this would be a great way to allow players to tell their own story about their character.

Later on, I will concentrate on that part by making default templates that will allow any admins to have an idea about how they can change the pages themselves.

= Recruitment widget =

Do you need to increase your ranks with specific classes and/or specializations. You can use the new WoWpi Recruitment Widget in order to tell the world what are your guild's needs.

== Frequently Asked Questions ==

= Is this plugin a new version of WoWpi plugin ? =

No, this is a total different breed. It adds custom post types specific to characters, taxonomies specific to races, classes, specializations, etc.

= Is this de go to tool? =

Not yet, but it aims to be. With the help of your inputs, I will keep on working on this plugin. Also, please become a patreon of my work (https://www.patreon.com/wowpi). 

== Screenshots ==

1. Register for a developer account on Battle.net, and create a client in order to use the data about your guild.
2. Once you've created the client, you will receive a client ID and client secret that you must pass to the plugin settings.
3. After you activate the plugin you should go to the plugin's settings page, at step 1 and put the Client ID and Client secret, and also the region and locale (language). Now push the "Save changes" button. If everything works ok, you should receive a confirmation message.
4. On step 2 of the WoWpi Guild settings you must mention the region and also the guild slug that appears on the roster page in the guild page on World of Warcraft.
5. On step 3, you should synchronize the races, classes and specializations, and the guild roster. Make sure that the checkbox "Synch with all character data" is also checked.
6. Once you've synchronize all data, you can see the "Members" admin section. The members are organized by races, classes, specializations, genders and play status.
7. After activating the plugin, the members will be updated each hour five at a time (of course, if the website is visited), or you can reimport the members from the admin section. If something doesn't work, make sure you've synchronized the classes and specializations in the WoWpi Guild settings section (step 3). Also, the members that were not active lately in game, will be automatically set to Draft and set as inactive.

== Changelog ==
= 1.3.0 =
* Added recruitment widget
= 1.2.2 =
* Quick fixes related to the launch of plugin on Wordpress.org repository
= 1.0 =
* The launching of the plugin

== Upgrade Notice ==

Usually, after you update the plugin to the latest version, a good idea would be to repeat the steps made during the installation. Don't worry. I will do my best to not destroy previous content.
