<div id="leftcolwapper">
				<div class="leftcol">
					<div class="leftcolcontent">
					<?php /*
					<h5>Veröffentlichen: </h5>

					<a class="btn btn-send"><span class="send-icon"></span>Veröffentlichen</a>
					<a class="btn"><span class="icon-tick-mini"></span>Speichern</a>
                    <hr />
                        */?>
                    <h5><?php echo __('Media', true);?></h5>

                    <form id="file_upload"
                        action="<?php echo FULL_BASE_URL.DS.'posts/ajxImageProcess'; ?>"
                        method="POST" enctype="multipart/form-data"><input
                        type="file" name="file" multiple>

                        	 <span>+</span><?php __('Add Images'); ?>
                        <input type="hidden" name="hash" id="hash" value="<?php echo $hash; ?>" />
                    </form>

                        <a class="btn gray" id="add_url_video_link"><span>+</span><?php __('Add Video'); ?></a>

					<hr>
					
					<h5><?php __('Topic'); ?></h5>
                        <?php echo $this->Form->create('Post', array("enctype" => "multipart/form-data", 'id' => 'tmp_form_topic'));?>
                            <?php echo $this->Form->input('topic_id', array('label' => false, 'id' => 'SelectPostTopicId')); ?>
						</form>
                        <br /><br />
						<a class="btn gray" onclick="topicDialog()"><span>+</span><?php __('Add Topic'); ?></a>
					<hr>

					<h5><?php __('Links/References'); ?></h5>
                    <a class="btn gray" id="btn-add-link"><span>+</span><?php echo __('Add links', true);?></a>

                    <hr>
                    <h5><?php __('Comments allowed?'); ?></h5>
                          <?php echo $this->Form->input('allow_comments', array('type' => 'select', 'label' => false, 'id' => 'SelectPostAllowComments' , 'options' => $allow_comments)); ?>

						 </div><!-- /.leftcolcontent -->	
						</div><!-- /.leftcol -->
						
				</div>