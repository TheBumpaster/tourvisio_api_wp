<?php
		
		/**
		 * Function initializer of plugin classes
		 * @param $mainFile
		 */
		function initializeTourVisioAPI($mainFile) {
				// Start the plugin
				require_once('TourVisio.php');
				$tourVisioAPI = new TourVisio(dirname($mainFile));
				
				// Install or upgrade
				if(!$tourVisioAPI->isInstalled()) {
						$tourVisioAPI->install();
				} else {
						$tourVisioAPI->upgrade();
				}
				
				// Register actions, filters and shortcodes
				$tourVisioAPI->registerActions();
				
				// Register events
				register_activation_hook($mainFile, [&$tourVisioAPI, 'activate']);
				register_deactivation_hook($mainFile, [&$tourVisioAPI, 'deactivate']);
				
		}