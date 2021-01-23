<?php
		
		
		require('TourVisioRequestHandler.php');
		
		/**
		 * Class TourVisioService
		 */
		class TourVisioService {
				/**
				 * @var
				 */
				private static $api;
				
				/**
				 * TourVisioService constructor.
				 */
				private final function __construct () {
				}
				
				/**
				 * @param $hostEndpoint
				 */
				public static function setup ($hostEndpoint) {
						if (!isset(self::$api)) {
								self::$api = new TourVisioRequestHandler($hostEndpoint);
						}
				}
				
				/**
				 * @param $payload
				 * @return mixed
				 */
				public static function authorize ($payload) {
						return self::$api->login($payload);
				}
				
				/**
				 * @param $payload
				 * @param $token
				 * @return mixed
				 */
				public static function getArrivalAutocomplete ($payload, $token) {
						return self::$api->getArrivalAutocomplete($payload, $token);
				}
				
				/**
				 * @param $payload
				 * @param $token
				 * @return mixed
				 */
				public static function getProductInfo ($payload, $token) {
						return self::$api->getProductInfo($payload, $token);
				}
				
				/**
				 * @param $payload
				 * @param $token
				 * @return mixed
				 */
				public static function priceSearch ($payload, $token) {
						return self::$api->priceSearch($payload, $token);
				}
				
				/**
				 * @param $payload
				 * @param $token
				 * @return mixed
				 */
				public static function getOfferDetails ($payload, $token) {
						return self::$api->getOfferDetails($payload, $token);
				}
		}