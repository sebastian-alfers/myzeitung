<div id="leftcolwapper">
				<div class="leftcol">
					<div class="leftcolcontent">
					
					<h5>Veröffentlichen: </h5>

					<a class="btn btn-send"><span class="send-icon"></span>Veröffentlichen</a>
					<a class="btn"><span class="icon-tick-mini"></span>Speichern</a>
                    <hr />
                    <h5>Media</h5>

                    <form id="file_upload"
                        action="<?php echo FULL_BASE_URL.DS.'posts/ajxImageProcess'; ?>"
                        method="POST" enctype="multipart/form-data"><input
                        type="file" name="file" multiple>

                        	 <span>+</span><?php __('Add Images'); ?>
                        <input type="hidden" name="hash" value="<?php echo $hash; ?>" />
                    </form>

                        <a class="btn "><span>+</span><?php __('Add Video'); ?></a>

					<hr>
					
					<h5>Themen:</h5>
						<ul class="themes">
							<li><a>Politik<span class="icon icon-delete"></span></a></li>
							<li><a>Atompolitik <span class="icon icon-delete"></span></a></li>
						</ul>
						
						<a class="btn "><span>+</span>Thema hinzufügen</a>
					<hr>
					
					<h5>Quellen:</h5>
					
					<ul class="themes">
							<li><a>Bild.de/Atompolitik/xyz<span class="icon icon-delete"></span></a></li>
							<li><a>heise-online.de/news/article <span class="icon icon-delete"></span></a></li>
					</ul>
					
     					<a class="btn "><span>+</span>Quelle hinzufügen</a>
													
						 </div><!-- /.leftcolcontent -->	
						</div><!-- /.leftcol -->
						
				</div>