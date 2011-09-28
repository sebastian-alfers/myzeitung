<?php if(isset($open_graph) && !empty($open_graph)):

    if($open_graph['image'] == ''){
        $open_graph['image'] = $this->Cf->url($this->Image->resize('assets/logo-icon.png', 200, 200));
    }
    else{
        $open_graph['image'] = $this->Cf->url($this->Image->resize($open_graph['image'], 200, 200));
    }
    ?>
    <meta property="og:title" content="<?php echo $this->element('global/title');?>" />
    <meta property="og:type" content="<?php echo $open_graph['type']; ?>" />
    <meta property="og:url" content="<?php echo $open_graph['url']; ?>" />
    <meta property="og:image" content="<?php echo $open_graph['image']; ?>" />
    <meta property="og:site_name" content="<?php echo $open_graph['site_name']; ?>" />
    <meta property="fb:admins" content="<?php echo $open_graph['admins']; ?>" />
<?php endif; ?>