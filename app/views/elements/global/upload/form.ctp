<div id="fileupload">
    <form action="<?php echo FULL_BASE_URL.DS.'posts/ajxImageProcess'; ?>" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="hash" id="hash" value="<?php echo $hash; ?>" />
        <?php if(isset($paper_id) && !empty($paper_id)): ?>
            <input type="hidden" name="paper_id" id="paper_id" value="<?php echo $paper_id; ?>" />
        <?php endif; ?>
        <div class="fileupload-buttonbar">
            <label class="fileinput-button">
                <span>Add files...</span>
                <input type="file" name="files[]" multiple>
            </label>
            <button type="submit" class="start">Start upload</button>
            <button type="reset" class="cancel">Cancel upload</button>
            <button type="delete" class="cancel delete-profile-picture"><?php __('Delete profile picture'); ?></button>
            <button type="delete" class="cancel delete-paper-picture" id="<?php echo $paper_id; ?>"><?php __('Delete paper picture'); ?></button>
        </div>
    </form>
    <div class="fileupload-content">
        <div class="fileupload-progressbar"></div>
        <table class="files"></table>
    </div>
</div>
<script id="template-upload" type="text/x-jquery-tmpl">
    <tr class="template-upload{{if error}} ui-state-error{{/if}}">
        <td class="preview"></td>
        <td class="name">${name}</td>
        <td class="size">${sizef}</td>
        {{if error}}
            <td class="error" colspan="2"><?php __('Error'); ?>:
                {{if error === 'maxFileSize'}}<?php __('File is too big'); ?>
                {{else error === 'minFileSize'}}<?php __('File is too small'); ?>
                {{else error === 'acceptFileTypes'}}<?php __('Filetype not allowed'); ?>
                {{else error === 'maxNumberOfFiles'}}<?php __('Max number of files exceeded'); ?>
                {{else}}${error}
                {{/if}}
            </td>
        {{else}}
            <td class="progress"><div></div></td>
            <td class="start"><button><?php __('Start'); ?></button></td>
        {{/if}}
        <td class="cancel"><button><?php __('Cancel'); ?></button></td>
    </tr>
</script>
<script id="template-download" type="text/x-jquery-tmpl">
    <tr class="template-download{{if error}} ui-state-error{{/if}}">
        {{if error}}
            <td></td>
            <td class="name">${name}</td>
            <td class="size">${sizef}</td>
            <td class="error" colspan="2"><?php __('Error'); ?>:
                {{if error === 1}}<?php __('File exceeds upload_max_filesize'); ?>
                {{else error === 2}}<?php __('File exceeds MAX_FILE_SIZE (HTML form directive)'); ?>
                {{else error === 3}}<?php __('File was only partially uploaded'); ?>
                {{else error === 4}}<?php __('No File was uploaded'); ?>
                {{else error === 5}}<?php __('Missing a temporary folder'); ?>
                {{else error === 6}}<?php __('Failed to write file to disk'); ?>
                {{else error === 7}}<?php __('File upload stopped by extension'); ?>
                {{else error === 'maxFileSize'}}<?php __('File is too big'); ?>
                {{else error === 'minFileSize'}}<?php __('File is too small'); ?>
                {{else error === 'acceptFileTypes'}}<?php __('Filetype not allowed'); ?>
                {{else error === 'maxNumberOfFiles'}}<?php __('Max number of files exceeded'); ?>
                {{else error === 'uploadedBytes'}}<?php __('Uploaded bytes exceed file size'); ?>
                {{else error === 'emptyResult'}}<?php __('Empty file upload result'); ?>
                {{else}}${error}
                {{/if}}
            </td>
        {{else}}
            <td class="preview">
                {{if thumbnail_url}}
                    <a href="${url}" target="_blank"><img src="${thumbnail_url}"></a>
                {{/if}}
            </td>
            <td class="name">
                <img src="/${path}${name}" height="100" />
            </td>
            <td class="size">${sizef}</td>
            <td colspan="2"></td>
        {{/if}}
        <td class="delete">
            ${url}
            ${error}
            <button data-type="${delete_type}" data-url="${delete_url}">Delete</button>
        </td>
    </tr>
</script>