<?php if(count($references) > 0):?>
	<?php foreach($references as $paper):?>
		<div style="float: left; margin: 0 5px 5px 0;"><?php
		$link_data = array();
		$link_data['url'] = $paper['Paper']['Route'][0]['source'];
		//$link_data['custom'] = array('class' => 'user-image');
		
		//build the mouseover-text
		$hover = array();
		$paper_name = $paper['Paper']['title'];
		$hover[] = sprintf(__('Name: %1$s', true),$paper_name);
		if(isset($paper['references']) && !empty($paper['references'])){
			if(isset($paper['references']['whole_user_in_paper'])){
				$whole_user_in_paper = __('All posts appear in this paper.', true);
				$hover[] = $whole_user_in_paper;
			}

			if(isset($paper['references']['user_topic_in_paper'])){
				foreach($paper['references']['user_topic_in_paper'] as $topic_in_category){
					$topic_name = $topic_in_category['Topic']['name'];
					$whole_user_in_category = sprintf(__('Posts of the topic %1$s appear in this paper', true), $topic_name);
					$hover[] = $whole_user_in_category;
				}
			}				
			
			if(isset($paper['references']['user_topic_in_category'])){
				foreach($paper['references']['user_topic_in_category'] as $ref_user_topic_in_category){
					$topic_name = $ref_user_topic_in_category['Topic']['name'];
					$category_name = $ref_user_topic_in_category['Category']['name'];
					$whole_user_in_paper = sprintf(__('Posts of the topic %1$s appear in the category %2$s', true), $topic_name, $category_name);
					$hover[] = $whole_user_in_paper;
				}
			}
			
			if(isset($paper['references']['whole_user_in_category'])){
				foreach($paper['references']['whole_user_in_category'] as $whole_user_in_category){
					$category_name = $whole_user_in_category['Category']['name'];
					$whole_user_in_category = sprintf(__('All posts appear in the category %1$s', true), $category_name);
					$hover[] = $whole_user_in_category;
				}
			}	


		}
		
		$title = '<ul>';
		foreach($hover as $string){
			$title .= '<li>'.$string.'</li>';
		}
		$title .= '</ul>';
		
		
		//if(isset($reference['User']['name']) && !empty($reference['User']['name'])) $name.= " (".$reference['User']['name'].")";
		echo $image->render($paper['Paper'], 50, 50, array('title' => $title, 'class' => 'tt-title'), $link_data, 'paper'); ?></div>
	<?php endforeach; ?>
<?php endif;?>

<script>
$('.tt-title').tipsy({ fade: false, opacity: 1, gravity: 'sw', html: true});
</script>
