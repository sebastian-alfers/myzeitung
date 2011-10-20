<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
        <title><?php echo $title_for_layout;?></title>
	</head>
<body style="height:100%; background-color: #EBEBEB; margin:0; padding:0;" >
	<table valign="top" border="0" bgcolor="#EBEBEB" width="100%" height="100%" style="font-family: 'Helvetica Neue', 'Helvetica' , 'Arial', sans-serif; font-size: 12px; line-height: 19px; color: #242424; margin: 0; padding: 0;" cellpadding="0" cellspacing="0" >
	<tr>
	<td align="center" valign="top">
		<table border="0" bgcolor="#FFFFFF" width="600" style="padding: 8px 20px 20px; margin:0;">

		<tr>
			<td style="padding: 0 0 5px 23px; border-bottom: 1px solid #E4E3E3;"><?php echo $this->Html->image($this->Cf->url($this->Image->resize('assets/logo-mail.gif', 103, null), true), array('alt'  => 'myZeitung.de', 'url' => array('controller' => 'home' ,'action' => 'index'))); ?></td>
		</tr>


	            <?php echo $content_for_layout;?>
        <tr>
            <td style="padding: 0 0 0 0; border-top: 1px solid #E4E3E3; text-align: center;">
                <p>
                    myZeitung © 2011 |
                    <?php echo $this->Html->link(__('Impressum',true), $this->Html->url(array('controller' => 'pages', 'action' => 'display', 'impressum'), true), array('style' => 'color:#6e6e6e;'));?> |
                    <?php echo $this->Html->link(__('AGB',true), $this->Html->url(array('controller' => 'pages', 'action' => 'display', 'agb'), true), array('style' => 'color:#6e6e6e;'));?> |
                    <?php echo $this->Html->link(__('Datenschutzrichtlinien',true), $this->Html->url(array('controller' => 'pages', 'action' => 'display', 'dsr'), true), array('style' => 'color:#6e6e6e;'));?> |
                    <?php echo $this->Html->link(__('Kontakt',true), $this->Html->url(array('controller' => 'pages', 'action' => 'display', 'kontakt'), true), array('style' => 'color:#6e6e6e;'));?>
            	</p>
            </td>
        </tr>


 		</table>
		<table border="0" width="600">
		<tr>
			<td>
			<p style="color:#a3a3a3; padding: 23px; font-size:12px; line-height: 19px; margin: 0;">
				<?php echo sprintf(__('This message was sent to %s.' ,true),$recipient['User']['email']);?>
                <?php if(isset($recipient['User']['id'])):?>
                    <?php echo __('If you don\'t want to receive these emails from myZeitung, you can unsubscribe emails here:' );?>
                    <?php echo $this->Html->link(__('Privacy Settings',true),$this->Html->url(array('controller' => 'users', 'action' => 'accPrivacy'), true) , array('style' => 'color:#a3a3a3'));?>
                <?php endif;?>
			</p>
			</td>
		</tr>
		</table>

	</td>
	</tr>

	</table>

</body>
</html>
