<div id="help">
        <div>
            <ul>
                <li class="headline">Help Center</li>
                <li><span class="helpnav prev browse icon icon-arrow-left-black"></span></li>
                <li><span class="helpnav next browse icon icon-arrow-right-black"></span></li>
                <li id="content"><?php echo isset($default_helptext)? $default_helptext : ''; ?> <?php echo $this->Html->link(__('The myZeitung FAQs provide more information.', true), '/p/myzeitung/faq-zeitung-deutsche-sprache'); ?></li>
                <li class="last"><span class="help-link icon icon-close-help tt-title-north" title="<?php __('Quit Help Center'); ?>"></span></li>
            </ul>
        </div>
    </div>


        <?php //echo $this->MzHelpcenter->getHelpElements();
