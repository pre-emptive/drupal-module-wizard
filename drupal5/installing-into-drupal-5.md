#Installing wizard.module into Drupal 5

To install the wizard module:

* Download the wizard module archive file
* Unpack the archive into your sites/all/modules directory (or unpack the archive and FTP to your web host and put the directory and files in the sites/all/modules area)
* In the Drupal administration screen, go to the Modules page (admin/build/modules)
* Look for the Development section, select "Drupal Wizard API" and save changes

Drupal 5.1 has some limitations with respect to checkboxes. The Wizard API can work around these problems if required. This is configurable as an administration setting for the Wizard API.

If using wizard.module for the first time, it may be advisable to also install the wizard demonstration module called Gandalf. The installation procedure is the same as for wizard.module. Gandalf adds a menu item to users with sufficient permissions, which provides access to the demonstration.
