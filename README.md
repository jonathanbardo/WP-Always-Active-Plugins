# Always-Active-Plugins

Force activation of provided plugins, also preventing their deactivation

**Contributors:** [westonruter](http://profiles.wordpress.org/westonruter), [jonathanbardo](http://profiles.wordpress.org/jonathanbardo)
**Requires at least:** 3.5 
**Tested up to:** 3.9  
**Stable tag:** trunk (master)  
**License:** [GPLv2 or later](http://www.gnu.org/licenses/gpl-2.0.html)  

## Description ##

Not every plugin works in the mu-plugins folder. This plugin can circumvent that issue by forcing the activation of certain plugins in the plugins directory.

This plugin must be loaded in the mu-plugins directory. Once it is loaded, one can implement a filter to force network activate a plugin network wise.

```php
add_filter(
	'auto_activated_network_required_plugins', 
	function( $plugins ) {
		$plugins[] = 'PLUGIN_FOLDER/PLUGIN_FILE.php';
		return $plugins; 
	}
);
```
