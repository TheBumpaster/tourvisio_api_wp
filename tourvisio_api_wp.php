<?php
		/*
		 Plugin Name:       TourVisio API Integration
		 Plugin URI:        http://docs.santsg.com/tourvisio/
		 Description:       TourVisio APIs, formerly known as TourVisio Web Services, provide easier, faster and more flexible access to the SAN system functionality and products. Through the Internet, you can integrate our products and services. APIs are ideal for developers who want to build or update a customized booking application for their website with TourVisio.
		 Version:           1.0.0
		 Author:            Ismar Hadzic
		 License:           MIT
		 License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
		 Text Domain:       TourVisio
		 */
		
		$minimalPHPVersion = '5.0';
		
		/**
		 * Alert
		 */
		function noticePHPVersion() {
				global $minimalRequiredPHPVersion;
				
				echo '<div class="aler">' .
						'<p>Minimal required PHP version is <strong>'. $minimalRequiredPHPVersion .'</strong></p>' .
						'<p>Your server is running on PHP version <strong>'. phpversion().'</strong></p>'.
						'</div>';
		}
		
		/**
		 * Check before init
		 * @return bool
		 */
		function checkPHPVersion() {
				global $minimalRequiredPHPVersion;
				
				if(version_compare(phpversion(), $minimalRequiredPHPVersion) < 0) {
						add_action('admin_notices', 'noticePHPVersion');
						return false;
				}
				return true;
		}
		
		/**
		 * Init
		 */
		if (checkPHPVersion()) {
				include_once('init.php');
				initializeTourVisioAPI(__FILE__);
		}