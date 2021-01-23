<?php
		
		/**
		 * Class TourVisioRequestHandler
		 */
		class TourVisioRequestHandler {
				/**
				 * @var
				 */
				private $endpoint;
				
				/**
				 * TourVisioRequestHandler constructor.
				 * @param $endpoint
				 */
				public function __construct($endpoint) {
						$this->endpoint = $endpoint;
				}
				
				/**
				 * @param $payload
				 * @return mixed
				 */
				public function login($payload) {
						$uri = $this->endpoint . '/api/authenticationservice/login';
						
						$http = array(
								'http' => // The wrapper to be used
										array(
												'method'  => 'POST', // Request Method
												'header'  => 'Content-type: application/json',
												'content' => json_encode($payload)
										)
						);
						$context = stream_context_create($http);
						$contents = @file_get_contents($uri, false, $context);
						
						return $contents !== false ? json_decode($contents) : false;
				}
				
				/**
				 * @param $payload
				 * @param $token
				 * @return mixed
				 */
				public function getArrivalAutocomplete($payload, $token) {
						
						$uri = $this->endpoint . '/api/productservice/getarrivalautocomplete';
						
						$http = array(
								'http' => // The wrapper to be used
										array(
												'method'  => 'POST', // Request Method
												'header'  => 'Content-type: application/json\n\rAuthorization: Bearer ' . $token,
												'content' => json_encode($payload)
										)
						);
						$context = stream_context_create($http);
						$contents = file_get_contents($uri, false, $context);
						
						return json_decode($contents);
						
				}
				
				/**
				 * @param $payload
				 * @param $token
				 * @return mixed
				 */
				public function priceSearch($payload, $token) {
						
						$uri = $this->endpoint . '/api/productservice/pricesearch';
						
						$http = array(
								'http' => // The wrapper to be used
										array(
												'method'  => 'POST', // Request Method
												'header'  => 'Content-type: application/json\n\rAuthorization: Bearer ' . $token,
												'content' => json_encode($payload)
										)
						);
						$context = stream_context_create($http);
						$contents = file_get_contents($uri, false, $context);
						
						return json_decode($contents);
				}
				
				/**
				 * @param $payload
				 * @param $token
				 * @return mixed
				 */
				public function getProductInfo($payload, $token) {
						
						$uri = $this->endpoint . '/api/productservice/getproductInfo';
						
						$http = array(
								'http' => // The wrapper to be used
										array(
												'method'  => 'POST', // Request Method
												'header'  => 'Content-type: application/json\n\rAuthorization: Bearer ' . $token,
												'content' => json_encode($payload)
										)
						);
						$context = stream_context_create($http);
						$contents = file_get_contents($uri, false, $context);
						
						return json_decode($contents);
				}
				
				/**
				 * @param $payload
				 * @param $token
				 * @return mixed
				 */
				public function getOfferDetails($payload, $token) {
						
						$uri = $this->endpoint . '/api/productservice/getofferdetails';
						
						$http = array(
								'http' => // The wrapper to be used
										array(
												'method'  => 'POST', // Request Method
												'header'  => 'Content-type: application/json\n\rAuthorization: Bearer ' . $token,
												'content' => json_encode($payload)
										)
						);
						$context = stream_context_create($http);
						$contents = file_get_contents($uri, false, $context);
						
						return json_decode($contents);
				}
		}