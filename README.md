=== Wowpi Guild ===
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

== Frequently Asked Questions ==

= Is this plugin a new version of WoWpi plugin ? =

No, this is a total different breed. It adds custom post types specific to characters, taxonomies specific to races, classes, specializations, etc.

= Is this de go to tool? =

Not yet, but it aims to be. With the help of your inputs, I will keep on working on this plugin. Also, please become a patreon of my work (https://www.patreon.com/wowpi). 

== Screenshots ==

1. After you activate the plugin and register to Battle.net Developer website, you must set up the plugin.
2. Once the members of your guild are imported from Battle.net, you can see them in the "Members" admin section. After that, the members will be updated each hour five at a time (of course, if the website is visited), or you can reimport the members from the admin section.

== Changelog ==
= 1.2.2 =
* Quick fixes related to the launch of plugin on Wordpress.org repository
= 1.0 =
* The launching of the plugin

== Upgrade Notice ==

Usually, after you update the plugin to the latest version, a good idea would be to repeat the steps made during the installation. Don't worry. I will do my best to not destroy previous content.
