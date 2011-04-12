				<div id="leftcolwapper">
				<div class="leftcol">
					<div class="leftcolcontent">
							<div class="userstart">
								<img class="userimage" src="../img/user-image.jpg" alt="fourbai image" />
								<a class="btn" href=""><span>+</span>Abonnieren</a>
							</div>
							<h4>fourbai</h4>
							<?php if($user['User']['firstname'] or $user['User']['name']):?>
							<p><strong><?php echo __('Name:'); ?></strong><?php echo $user['User']['firstname'].' '.$user['User']['name'];?></p>
							<?php endif;?>
							<p><strong><?php echo __('Joined:'); ?></strong><?php echo $this->Time->timeAgoInWords($user['User']['created'], array('end' => '+1 Year'));?></p>
							<p><strong><?php echo __('About me:')?></strong> Lorem ipsum dolor sit amet, consetet. m voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea</p>
							<p class="user-url"><strong>URL: </strong><a href="">www.4bai.de</a></p>
							<hr />
							<h6>Themen</h6>
							<ul>
	    						<li><a href="" >Alle Themen (30)</a></li>
	    						<li><a href="" >Politik (11)</a></li>
	    						<li><a href="" >Fu§ball (9)</a></li>
	    						<li><a href="" >Programmieren(10)</a></li>
	    					</ul>
							<hr />
							<ul>
								<li>Abonenten: 2359 / 40</li>
								<li>Abonements: 100 / 40</li>
								<li>Artikel: 200</li>
								<li>Zeitungen: 3</li>
							</ul>
							<hr />
							<h6>fourbais Zeitungen:</h6>
							<ul class="newslist">
								<li><a href=""><?php  echo $this->Html->image("news-image.jpg"); ?></a></li>
								<li><a href=""><img src="../img/news-image.jpg" alt="" /> Zeitungsname1</a></li>
								<li><a href=""><img src="img/news-image.jpg" alt="" /> Zeitungsname2</a></li>
								<li><a href=""><img src="/img/news-image.jpg" alt="" /> Zeitungsname3</a></li>
							</ul>
							
						 </div><!-- /.leftcolcontent -->	
						</div><!-- /.leftcol -->
						
						<iframe src="http://www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fplatform&amp;width=218&amp;colorscheme=light&amp;show_faces=true&amp;stream=false&amp;header=false&amp;height=268" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:218px; height:268px;"></iframe>
						
				</div><!-- / #leftcolwapper -->