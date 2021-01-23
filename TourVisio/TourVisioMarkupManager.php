<?php
		
		
		/**
		 * Class TourVisioMarkupManager
		 */
		class TourVisioMarkupManager {
				/**
				 * @param $text
				 * @return string
				 */
				public function generateParagraph($text, $className = null) {
						return '<p class="'. $className .'">' . $text . '</p>';
				}
				
				/**
				 * @param $text
				 * @param int $h
				 * @return string
				 */
				public function generateHeader($text, $h = 1, $className = null) {
						return '<h' . $h . ' class="'. $className .'">' . $text . '</h' . $h . '>';
				}
				
				/**
				 * @return string
				 */
				public function generateBreakLine() {
						return '<br />';
				}
				
				public function generateInput($type, $name, $placeholder, $value, $className) {
						return '<input name="'. $name .'" class="'. $className .'" type="'. $type .'" placeholder="'. $placeholder .'" value="'. $value .'" />';
				}
				
				/**
				 * @param $fields
				 * @param string $action
				 * @return string
				 */
				public function generateForm($fields, $action = '', $className = null) {
						$content = '<form class="'. $className .'" action="'. $action .'" method="post">';
						$content .= '<ul class="list-group list-group-flush">';
						foreach($fields as $key => $value) {
								$content .= '<li class="list-group-item">'
										.'<input name="'. $key .'" type="text" class="form-control" id="'. $key .'" value="'. $value[1] .'" placeholder="">'
										.'<label for="'. $key .'" class="label">'. $value[0] .'</label>'
										.'</li>';
						}
						$content .= '<li class="list-group-item">';
						
						$content .= '<div class="row">';
						
						$content .= '<div class="col col-6">';
						$content .= '<input name="action" class="btn btn-primary form-control" type="submit" value="Save" />';
						$content .= '</div>';
						
						$content .= '<div class="col col-6">';
						$content .= '<input name="action" class="btn btn-warning form-control" type="submit" value="Delete" />';
						$content .= '</div>';
						
						$content .= '</div>';
						
						$content .= '</li>';
						
						$content .= '</ul>'
								.'</form>';

						
						return $content;
				}
				
				public function generateTable($columns, $rows, $firstRowHeader = false) {
						
						$content = '<table class="table table-borderless table-sm" >';
						
						$content .= '<thead>';
						$content .= '<tr>';
						foreach ($columns as $header => $value) {
								$content .= '<th scope="col">' . $value[0] . '</th>';
						}
						$content .= '</tr>';
						$content .= '</thead>';
						
						$content .= '<tbody>';
						
						if ($firstRowHeader) {
								
										$content .= '<tr>';
								for ($i = 0; $i < count($rows); $i++) {

										for ($j = 0; $j < count($rows[$i]); $j++) {
												$content .= '<td scope="row">'. $rows[$i][$j] . '</td>';
										}
										
								}
								$content .= '</tr>';
								
								
						} else {
								$content .= '<tr>';
								
								foreach ($rows as $row => $data) {
										$content .= '<td>' . $data[0] . '</td>';
										
								}
								$content .= '</tr>';
								
						}
						
						$content .= '</tbody>';
						$content .= '</table>';
						
						return $content;
				}
				
				public function generateList($items = []) {
						var_dump($items);
						$content = '<ul>';
						
						foreach($items as $item) {
								$content .= '<li><a href="'. get_site_url() .'/booking?hotelId='. $item->giataInfo->hotelId .'">'. $item->country->name .' | '. $item->city->name .'</a></li>';
						}
						
						return $content;
				}
		}