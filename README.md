This is an OpenSource project by studer + raimann ag, CH-Burgdorf (https://studer-raimann.ch)

## Installation

### Install SrTile-Plugin
Start at your ILIAS root directory
```bash
mkdir -p Customizing/global/plugins/Services/UIComponent/UserInterfaceHook
cd Customizing/global/plugins/Services/UIComponent/UserInterfaceHook
git clone https://github.com/studer-raimann/SrTile.git SrTile
```
Update, activate and config the plugin in the ILIAS Plugin Administration

### Custom event plugins
If you need to adapt some custom SrTile changes which can not be configured to your needs, SrTile will trigger some events, you can listen and react to this in a other custom plugin (plugin type is no matter)

First create or extend a `plugin.xml` in your custom plugin (You need to adapt `PLUGIN_ID` with your own plugin id) to tell ILIAS, your plugins wants to listen to SrTile events (You need also to increase your plugin version for take effect)

```xml
<?php xml version = "1.0" encoding = "UTF-8"?>
<plugin id="PLUGIN_ID">
	<events>
		<event type="listen" id="Plugins/SrTile" />
	</events>
</plugin>
```

In your plugin class implement or extend the `handleEvent` method

```php
...
require_once __DIR__ . "/../../SrTile/vendor/autoload.php";
...
class ilXPlugin extends ...
...
	/**
	 * @inheritDoc
	 */
	public function handleEvent($a_component, $a_event, $a_parameter) {
		switch ($a_component) {
			case IL_COMP_PLUGIN . "/" . ilSrTilePlugin::PLUGIN_NAME:
				switch ($a_event) {
					case ilSrTilePlugin::EVENT_...;
						...
						break;

					default:
						break;
				}
				break;

			default:
				break;
		}
	}
...
```

| Event | Parameters | Purpose |
|-------|------------|---------|
| `ilSrTilePlugin::EVENT_CHANGE_TILE_BEFORE_RENDER` | `tile => object<Tile>` | Change some tile properties before it will be rendered |
| `ilSrTilePlugin::EVENT_SHOULD_NOT_DISPLAY_ALERT_MESSAGE` | `lang_module => string`<br>`lang_key => string`<br>`alert_type => string`<br>`should_not_display => &array` | May you want not to to display all alert messages, so you can filter and add `true` to `should_not_display` (Please note `should_not_display` is a reference variable, if it should not works) |

### Some screenshots
Tiles:
![Tiles](./doc/screenshots/tiles.png)

Tab:
![Tiles](./doc/screenshots/tab.png)

Tile config:
![Tiles](./doc/screenshots/tile_config.png)

### Requirements
* ILIAS 5.3 or ILIAS 5.4
* PHP >=7.0

### Adjustment suggestions
* External users can report suggestions and bugs at https://plugins.studer-raimann.ch/goto.php?target=uihk_srsu_PLSRTILE
* Adjustment suggestions by pull requests via github
* Customer of studer + raimann ag: 
	* Adjustment suggestions which are not yet worked out in detail by Jira tasks under https://jira.studer-raimann.ch/projects/PLSRTILE
	* Bug reports under https://jira.studer-raimann.ch/projects/PLSRTILE

### ILIAS Plugin SLA
Wir lieben und leben die Philosophie von Open Source Software! Die meisten unserer Entwicklungen, welche wir im Kundenauftrag oder in Eigenleistung entwickeln, stellen wir öffentlich allen Interessierten kostenlos unter https://github.com/studer-raimann zur Verfügung.

Setzen Sie eines unserer Plugins professionell ein? Sichern Sie sich mittels SLA die termingerechte Verfügbarkeit dieses Plugins auch für die kommenden ILIAS Versionen. Informieren Sie sich hierzu unter https://studer-raimann.ch/produkte/ilias-plugins/plugin-sla.

Bitte beachten Sie, dass wir nur Institutionen, welche ein SLA abschliessen Unterstützung und Release-Pflege garantieren.
