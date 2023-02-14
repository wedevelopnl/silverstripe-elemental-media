# Upgrading from v2 to v3

You can follow the following steps to update ElementalMedia 2.x to ElementalMedia 3.x

## Upgrade steps
* Change in your `composer.json` 
  * `"thewebmen/silverstripe-elemental-media": "^2.0"` => ``"wedevelopnl/silverstripe-elemental-media": "^3.0"``
* Run `composer update wedevelopnl/silverstripe-elemental-media`
* Update all configuration yaml-references to the new namespace
  * `WeDevelop\ElementalMedia\Model\ElementalMedia` => `WeDevelop\ElementalMedia\Model\ElementalMedia`
* If you have local template overrides for `TheWebmen/ElementalMedia/Model/ElementalMedia.ss` you need to update the folder name to `WeDevelop/ElementalMedia/Model/ElementalMedia.ss`.
* Run an `dev/build`
* Run the following task to migrate the namespace for `ElementalMedia` in the database
  * `php vendor/silverstripe/framework/cli-script.php dev/tasks/migrate-elemental-media-namespace`
* You're ready to go!
