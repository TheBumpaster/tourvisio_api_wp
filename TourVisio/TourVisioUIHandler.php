<?php
		
		
		require('TourVisioMarkupManager.php');
		
		/**
		 * Class TourVisioUIHandler
		 */
		class TourVisioUIHandler {
				
				/**
				 * @param $args
				 * @return string
				 */
				public function createAdminSettingsPage($args) {
						$markup = new TourVisioMarkupManager();
						
						$tableSetRows = [];
						$tableSetHeaders = [];
						foreach($args['authData'] as $key => $value) {
								array_push($tableSetRows, [
										$value
								]);
								array_push($tableSetHeaders, [
										$key
								]);
								
						}
						
						
						$page = '<div id="tourvisio_admin" class="col col-12">';
						
						$page .= '<div class="row">';
						$page .= $markup->generateParagraph('Plugin Version: ' . $args['savedVersion'] . ' | PHP Version: ' . $args['phpVersion'] . ' | Author: TheBumpaster');
						
						$page .= $markup->generateHeader($args['displayName'] . ' Settings');
						$page .= '</div>';
						
						$page .= $markup->generateBreakLine();
						
						$page .= '<div class="row">';
						
						// Get data for form
						$page .= '<div id="tourvisio_settings_form" class="col col-5">';
						$page .= '<div class="row">';
						$page .= '<div class="container">';
						$page .= '<div class="card">';
						$page .= '<div class="card-body">';
						$page .= $markup->generateHeader('TourVisioAPI Credentials:', '5', 'card-title');
						$page .= $markup->generateForm($args['formData'], '', 'form');
						$page .= '</div>';
						$page .= '</div>';
						$page .= '</div>';
						$page .= '</div>';
						$page .= '</div>';
						
						// Check current authorized details
						$page .= '<div id="tourvisio_settings_details" class="col col-7">';
						
						$page .= '<div class="row">';
						$page .= '<div class="container">';
						
						$page .= '<div class="card" style="max-width: 100%;">';
						$page .= '<div class="card-body">';
						$page .= $markup->generateHeader('TourVisioAPI Details:', '5', 'card-title');
						$page .= $markup->generateParagraph('<b>'.$args['authMessage'].'</b>', 'card-subtitle');
						$page .= $markup->generateParagraph('Current server time: ' . date('Y/m/d H:i:s'), 'card-subtitle');
						
						$page .= $markup->generateBreakLine();
						
						$page .= '<div style="overflow-x: auto;">';
						$page .= $markup->generateTable($tableSetHeaders, $tableSetRows, false);
						$page .= '</div>';
						
						$page .= '</div>';
						$page .= '</div>';
						
						$page .= '</div>';
						$page .= '</div>';
						
						$page .= '</div>';
						
						$page .= '</div>';
						
						$page .= '</div>';
						
						return $page;
				}
				
				/**
				 * @param $args
				 * @return string
				 */
				public function createSearchPage($args) {
						$markup = new TourVisioMarkupManager();
						
						$page = '<div class="container">';
						
						$page .= '<div class="col col-12">';
						$page .= '<form class="row" action="'. get_site_url() .'/booking" method="GET">';
						
						$page .= '<div class="col col-8">';
						$page .= $markup->generateInput('text', 'query', "Antalya", isset($args['query']) ? $args['query'] : null, "form-control");
						$page .= $markup->generateInput('hidden', 'search_type', null, 'places', "form-control");
						$page .= '</div>';
						
						$page .= '<div class="col col-3">';
						$page .= $markup->generateInput('submit', 'submit', null, 'Search', "form-control");
						$page .= '</div>';
						
						$page .= '<div class="col col-12">';
						$page .= $markup->generateParagraph('<i>Search for places to find offers</i>');
						$page .= '</div>';
						
						$page .= '</form>';
						
						$page .= '<div class="row">';
						$page .= $markup->generateList(isset($args['result']) ? $args['result']->body->items : []);
						$page .= '</div>';
						
						$page .= '</div>';
						
						
						$page .= '</div>';
						
						return $page;
				}
		}