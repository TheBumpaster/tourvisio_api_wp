<?php
		
		require('TourVisioUIHandler.php');
		require('TourVisioService.php');
		
		/**
		 * Class TourVisioManager
		 */
		class TourVisioManager {
				
				protected $auth;
				
				/**
				 * @return false|string
				 */
				public function getDisplayName() {
						return get_Class($this);
				}
				
				/**
				 * @param $name
				 * @return string
				 */
				public function prefix($name) {
						$prefix = $this->getDisplayName() . '_';
						
						if(strpos($name, $prefix) === 0) {
								return $name; // Already prefixed
						}
						
						return $prefix.$name;
				}
				
				/**
				 * @return array
				 */
				public function getOptionMetaData() {
						return [];
				}
				
				/**
				 * @param $name
				 * @param null $default
				 * @return mixed
				 */
				public function getOption($name, $default = null) {
						$option = $this->prefix($name);
						$value = get_option($option);
						if(!$value && $default) {
								$value = $default;
						}
						return $value;
				}
				
				/**
				 * @param $name
				 * @param $value
				 * @return mixed
				 */
				public function addOption($name, $value) {
						return add_option($this->prefix($name), $value);
				}
				
				/**
				 * @param $name
				 * @return mixed
				 */
				public function deleteOption($name) {
						return delete_option($this->prefix($name));
				}
				
				/**
				 * @param $name
				 * @param $value
				 * @return mixed
				 */
				public function updateOption($name, $value) {
						return update_option($this->prefix($name), $value);
				}
				
				/**
				 * @return array
				 */
				public function getAuthorizedData() {
						return isset($this->auth) ? $this->auth : [];
				}
				
				/**
				 *
				 */
				protected function deleteOptions() {
						$options = $this->getOptionMetaData();
						if(is_array($options)) {
								foreach ($options as $key => $value) {
										$name = $this->prefix($key);
										delete_option($name);
								}
						}
				}
				
				/**
				 * @return string
				 */
				protected function handleAuthorization() {
						
						if($this->getOption('isAuthorized')) {
								$date = strtotime($this->getOption('expiresOn'));
								$dateNow = time();
								if ($dateNow > $date) {
										$this->updateOption('isAuthorized', false);
										$this->deleteOption('token');
										$this->deleteOption('expiresOn');
										$this->handleAuthorization();
								}
								$formatedDate = date("Y/m/d H:i:s", strtotime($this->getOption('expiresOn')));
								return 'Authorized successfully! Token expires on ' . $formatedDate;
								
						} else {
								if($this->getOption('tourvisio_agency_code') && $this->getOption('tourvisio_user_code') && $this->getOption('tourvisio_password') && $this->getOption('tourvisio_api_uri')) {
										TourVisioService::setup($this->getOption('tourvisio_api_uri'));
										
										$this->auth = TourVisioService::authorize([
												"Agency" => $this->getOption('tourvisio_agency_code'),
												"User" => $this->getOption('tourvisio_user_code'),
												"Password" => $this->getOption('tourvisio_password')
										]);
										
										if($this->auth && $this->auth->header->success) {
												$this->updateOption('isAuthorized', true);
												$this->updateOption('token', $this->auth->body->token);
												$this->updateOption('expiresOn', $this->auth->body->expiresOn);
												
												$this->updateOption('user_info_code', $this->auth->body->userInfo->code);
												$this->updateOption('user_info_name', $this->auth->body->userInfo->name);
												$this->updateOption('user_info_mainAgency', $this->auth->body->userInfo->mainAgency);
												$this->updateOption('user_info_agency_code', $this->auth->body->userInfo->agency->code);
												$this->updateOption('user_info_agency_name', $this->auth->body->userInfo->agency->name);
												$this->updateOption('user_info_agency_registerCode', $this->auth->body->userInfo->agency->registerCode);
												$this->updateOption('user_info_office_code', $this->auth->body->userInfo->office->code);
												$this->updateOption('user_info_office_name', $this->auth->body->userInfo->office->name);
												$this->updateOption('user_info_operator_code', $this->auth->body->userInfo->operator->code);
												$this->updateOption('user_info_operator_name', $this->auth->body->userInfo->operator->name);
												$this->updateOption('user_info_operator_thumbnail', $this->auth->body->userInfo->operator->thumbnail);
												$this->updateOption('user_info_market_code', $this->auth->body->userInfo->market->code);
												$this->updateOption('user_info_market_name', $this->auth->body->userInfo->market->name);
												$this->updateOption('user_info_market_favicon', $this->auth->body->userInfo->market->favicon);
												
												$formatedDate = date("Y/m/d H:i:s", strtotime($this->getOption('expiresOn')));
												return 'Authorized successfully! Token expires on ' . $formatedDate;
										} else {
												$this->updateOption('isAuthorized', false);
												$this->deleteOption('token');
												$this->deleteOption('expiresOn');
												$message = 'Failed authorizing! ';
												if($this->auth) {
														$message .= $this->auth->header->message;
												} else {
														$message .= 'Host is not available, check your provided details.';
												}
												return $message;
										}
										
								} else {
										return 'Fill all details to initialize authentication.';
								}
						}
				}
				
				/**
				 *
				 */
				public function parseSettingPageHTML() {
						if (!current_user_can('manage_options')) {
								wp_die('You do not have the power here.', 'AiroExpress API');
						}
						
						$metadata = $this->getOptionMetaData();
						$data = $metadata;
						// Save posted options
						if ($metadata != null && count($metadata) > 0) {
								foreach($metadata as $key => $value) {
										if(isset($_POST['action'])) {
												if ($_POST['action'] === 'Save') {
														if (isset($_POST[$key])) {
																$this->updateOption($key, $_POST[$key]);
														}
												} else if ($_POST['action'] === 'Delete') {
														$this->deleteOption($key);
												}
										}
										$data[$key][1] = $this->getOption($key);
								}
						}
						
						// Handle UI Manager
						$uiHandler = new TourVisioUIHandler();
						$message = $this->handleAuthorization();
						
						$authData = [
								'user_info_code' => $this->getOption('user_info_code'),
								'user_info_name' => $this->getOption('user_info_name'),
								'user_info_agency_code' => $this->getOption('user_info_agency_code'),
								'user_info_agency_name' => $this->getOption('user_info_agency_name'),
								'user_info_agency_registerCode' => $this->getOption('user_info_agency_registerCode'),
								'user_info_office_code' => $this->getOption('user_info_office_code'),
								'user_info_office_name' => $this->getOption('user_info_office_name'),
								'user_info_operator_code' => $this->getOption('user_info_operator_code'),
								'user_info_operator_name' => $this->getOption('user_info_operator_name'),
								'user_info_operator_thumbnail' => $this->getOption('user_info_operator_thumbnail'),
								'user_info_market_code' => $this->getOption('user_info_market_code'),
								'user_info_market_name' => $this->getOption('user_info_market_name'),
								'user_info_market_favicon' => $this->getOption('user_info_market_favicon'),
						];
						
						echo $uiHandler->createAdminSettingsPage([
								'savedVersion' => $this->getSavedVersion(),
								'phpVersion' => phpversion(),
								'displayName' => $this->getDisplayName(),
								'formData' => $data,
								'authMessage' => $message,
								'authData' => $authData,
						]);
				}
				
				public function parseSearchPageHTML() {
						$uiHandler = new TourVisioUIHandler();
						TourVisioService::setup($this->getOption('tourvisio_api_uri'));
						$message = $this->handleAuthorization();
						$payload = [];
						
						if (isset($_GET['search_type']) && isset($_GET['query'])) {
								//
								$payload['result'] = TourVisioService::getArrivalAutocomplete([
										'ProductType' => 2, // only Hotels,
										'Query' => $_GET['query']
								], $this->getOption('token'));
								
								$payload['query'] = $_GET['query'];
						}
						
						echo $uiHandler->createSearchPage($payload);
				}
		}