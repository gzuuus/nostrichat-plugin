# Description
The Nostrichat plugin allows you to easily integrate Nostrichat into your WordPress site. With this plugin, you can add a live chat widget to your pages or posts, and allow site visitors to communicate with you in real time.

## Installation
1. Download this repo or directly download the file "nostrichat-plugin.zip".
2. Go to your wordpress > plugins and "Add new", then press "Upload plugin". 
3. Select the file "nostrichat-plugin.zip" and then "Install Now".
4. Activate the plugin and go to the settings page of nostrichat.
5. Fill in the required fields and enjoy :)


## Basic usage
+ To use this plugin, you must first configure your public key fields and the list of relays in the settings page "WordPress admin dashboard > Settings (right sidebar) > Nostrichat".
+ Once these are filled in, you can simply use the following shortcode on any wordpress page: [nostrichat].
+ You can also use the following argument within the shortcode to specify the chat type 'chat-type=" "', supported values are 'GLOBAL' and 'DM'.
+ 💡 If you add the shortcode without specifying this argument, the default chat type will be 'GLOBAL'.
+ Example shortcode for dm: [nostrichat chat-type="DM"].
