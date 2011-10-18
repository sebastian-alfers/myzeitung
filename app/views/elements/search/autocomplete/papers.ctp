<?php 
/**
 * @param $paper_documents - array of type Apache_Solr_Document Object
 */

if($home == 'true'){
    $maxTitleLen= 45;
    $maxNameLen = 40;
}else{
    $maxTitleLen= 25;
    $maxNameLen = 28;
}
?>

<?php if(isset($paper_documents)): ?>
	<ul>
		<?php foreach($paper_documents as $paper): ?>
			<?php if($paper instanceof Apache_Solr_Document): ?>
			<?php //debug($paper); die(); ?>
				<li class="art autoresult">
					<?php 
			
					if(isset($paper->paper_image)){
						$img = unserialize($paper->paper_image);
					}
					else{
						$img = '';
					}					
					$link_data = array();
					$link_data['url'] = $paper->route_source;
					$link_data['additional'] = 'display:inline;';
					echo $image->render(array('image' => $img), 45, 45,null, $link_data, 'paper',ImageHelper::PAPER);
					?>

					<h6><?php echo $this->Html->link($this->MzText->truncate($paper->paper_title, $maxTitleLen,array('ending' => '...', 'exact' => true, 'html' => false)), $paper->route_source);?></h6>
					<p class="discr"><?php __('by'); echo ' '.$this->Html->link(
                                                                                $this->MzText->truncate(
                                                                                                $this->MzText->generateDisplayname(array('username' =>$paper->user_username, 'name' => $paper->user_name),false),
                                                                                                 $maxNameLen,array('ending' => '...', 'exact' => true, 'html' => false)) ,
                                                                                array('controller' => 'users','action' => 'view', 'username' => strtolower($paper->user_username)));?></p>

				</p></li>				
			<?php endif; ?>			
		<?php endforeach; ?>
	</ul>
<?php endif; ?>
