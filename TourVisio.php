<?php
		
		require('TourVisio/TourVisioInstaller.php');
		
		/**
		 * Main Plugin Class
		 * Class TourVisio
		 */
		class TourVisio extends TourVisioInstaller {
				/**
				 * @return string
				 */
				protected function getMainPluginFilename() {
						return 'tourvisio_api_wp.php';
				}
				
				/**
				 * @return false|string
				 */
				public function getDisplayName() {
						return 'TourVisioAPI';
				}
				
				/**
				 * @return array|\string[][]
				 */
				public function getOptionMetaData() {
						return [
								'tourvisio_api_uri' => ['TourVisio API Host', ''],
								'tourvisio_agency_code' => ['TourVisio API Agency', ''],
								'tourvisio_user_code' => ['TourVisio API User', ''],
								'tourvisio_password' => ['TourVisio API Password', '']
						];
				}
				
				/**
				 *
				 */
				public function registerActions() {
						add_action('admin_menu', [&$this, 'addSettingsSubMenuPage']);
						if (strpos($_SERVER['REQUEST_URI'], get_class($this). 'Settings') !== false) {
								add_action('admin_enqueue_scripts', [&$this, 'enqueueScriptsAdmin']);
						}
						add_action('wp_enqueue_scripts', [&$this, 'enqueueScriptsClient']);
						
						// Add shortcodes
						add_action('init', [&$this, 'addShortCodes']);
				}
				
				/**
				 *
				 */
				public function initOptions() {
						if(!empty($this->getOptionMetaData())) {
								foreach($this->getOptionMetaData() as $key => $value) {
										if (is_array($value) && count($value) > 0) {
												$this->addOption($key, $value[1]);
										}
								}
						}
				}
				
				/**
				 *
				 */
				public function enqueueScriptsAdmin() {
						// Admin page
						wp_enqueue_script('jquery');
						wp_enqueue_script('bootstrap.js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js');
						
						wp_enqueue_style('bootstrap.css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css');
						
						wp_enqueue_style('airoexoress-admin.css', plugins_url('/tourvisio_api_wp/assets/css/admin.css') );
						wp_enqueue_script('airoexoress-admin.js', plugins_url( '/tourvisio_api_wp/assets/js/admin.js'), ['jquery'], null, true);
				}
				
				/**
				 *
				 */
				public function enqueueScriptsClient() {
						// Client related
						wp_enqueue_script('jquery');
						
						wp_enqueue_script('bootstrap.js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js');
						wp_enqueue_style('bootstrap.css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css');
						
						wp_enqueue_style('airoexoress-client.css', plugins_url('/tourvisio_api_wp/assets/css/public.css'));
						wp_enqueue_script('airoexoress-client.js', plugins_url('/tourvisio_api_wp/assets/js/public.js'), ['jquery'], null, true);
						
				}
				
				/**
				 *
				 */
				public function addShortCodes() {
						// Add shortcodes
						add_shortcode('tourvisio_search', [&$this, 'parseSearchPageHTML']);
				}
				
				/**
				 *
				 */
				public function upgrade(){
						
						// Complete upgrades by increasing the version
						if($this->isSavedVersionOlder($this->getVersion())){
								$this->saveInstalledVersion();
						}
				}
				
		}