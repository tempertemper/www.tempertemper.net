<div class="upload-progress">
  <div id="preview-template" style="display: none;">
    <div class="dz-preview dz-file-preview notification notification-info" role="alert">
      <div class="dz-details">
        <div class="dz-filename">
          <?php echo PerchUI::icon('assets/upload', 12); ?> 
          <?php echo $Lang->get('Uploading'); ?> <span data-dz-name></span> (<span class="dz-size" data-dz-size></span>)
        </div>
      </div>
      <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
      <div class="dz-error-message"><span data-dz-errormessage></span></div>
    </div>
  </div>
</div>
