<?php
		
		
		require('TourVisioManager.php');
		
		/**
		 * Class TourVisioInstaller
		 */
		class TourVisioInstaller extends TourVisioManager
		{
				/**
				 *
				 */
				const installed = 'installed';
				const version = 'version';
				
				private $pluginRootDirectory;
				
				/**
				 * TourVisioInstaller constructor.
				 * @param $rootDir
				 */
				public function __construct ($rootDir) {
						$this->pluginRootDirectory = $rootDir;
				}
				
				/**
				 * @return bool
				 */
				public function isInstalled () {
						return $this->getOption(self::installed) == true;
				}
				
				/**
				 * @return mixed
				 */
				public function markAsInstalled () {
						return $this->updateOption(self::installed, true);
				}
				
				/**
				 * @param $key
				 * @return mixed|null
				 */
				public function getHeaderValue ($key) {
						$data = file_get_contents($this->getPluginDir() . DIRECTORY_SEPARATOR . $this->getMainPluginFilename());
						$match = [];
						
						preg_match('/' . $key . ':\s*(\S+)/', $data, $match);
						if (count($match) >= 1) {
								return $match[1];
						}
						return null;
				}
				
				/**
				 * @return mixed|null
				 */
				public function getVersion () {
						return $this->getHeaderValue('Version');
				}
				
				/**
				 * @param $a
				 * @param $b
				 * @return bool
				 */
				public function isVersionLessThanEqual ($a, $b) {
						return (version_compare($a, $b) <= 0);
				}
				
				/**
				 * @param $version
				 * @return bool
				 */
				public function isSavedVersionOlder ($version) {
						return $this->isVersionLessThanEqual($this->getSavedVersion(), $version);
				}
				
				/**
				 * @return bool
				 */
				public function isInstalledAndUpdateRequired () {
						return $this->isSavedVersionOlder($this->getVersion());
				}
				
				/**
				 *
				 */
				public function install () {
						$this->initOptions();
						$this->saveInstalledVersion();
						$this->markAsInstalled();
				}
				
				/**
				 *
				 */
				public function uninstall () {
						$this->deleteOptions();
						$this->markAsUninstalled();
				}
				
				/**
				 *
				 */
				public function activate () {
						$this->updateOption($this->prefix('active'), true);
				}
				
				/**
				 *
				 */
				public function deactivate () {
						$this->deleteOption($this->prefix('active'));
				}
				
				/**
				 *
				 */
				public function addActions () {
				}
				
				/**
				 *
				 */
				public function addSettingsSubMenuPage () {
						$this->addSettingsSubMenuPageToSettingsMenu();
				}
				
				/**
				 *
				 */
				protected function requireExtraFiles() {
						require_once(ABSPATH . 'wp-includes/pluggable.php');
						require_once(ABSPATH . 'wp-admin/includes/plugin.php');
				}
				
				/**
				 *
				 */
				protected function addSettingsSubMenuPageToSettingsMenu() {
						$this->requireExtraFiles();
						
						add_options_page($this->getDisplayName(), $this->getDisplayName(), 'manage_options', get_class($this) . 'Settings', [&$this, 'parseSettingPageHTML']);
				}
				
				/**
				 * @return mixed
				 */
				protected function markAsUninstalled () {
						return $this->deleteOption(self::installed);
				}
				
				/**
				 * @param $version
				 * @return mixed
				 */
				protected function setVersion ($version) {
						return $this->updateOption(self::version, $version);
				}
				
				/**
				 * @return string
				 */
				protected function getMainPluginFilename () {
						return basename(dirname(__FILE__)) . 'php';
				}
				
				/**
				 * @return mixed
				 */
				protected function getPluginDir () {
						return $this->pluginRootDirectory;
				}
				
				/**
				 * @return mixed
				 */
				protected function getSavedVersion () {
						return $this->getOption(self::version);
				}
				
				/**
				 *
				 */
				protected function saveInstalledVersion () {
						$this->setVersion($this->getVersion());
						
						
				}
		}