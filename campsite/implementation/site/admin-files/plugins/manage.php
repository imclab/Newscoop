<?php
camp_load_translation_strings("plugins");
camp_load_translation_strings("api");

require_once($_SERVER['DOCUMENT_ROOT']."/classes/Input.php");

if (!$g_user->hasPermission('plugin_manager')) {
    camp_html_display_error(getGS("You do not have the right to manage plugins."));
    exit;
}

if (Input::Get('save')) {
    $p_plugins = Input::Get('p_plugins', 'array');
    $p_enabled = Input::Get('p_enabled', 'array');
    
    
    // delete from DB those which were uninstalled
    foreach (CampPlugin::getAll() as $CampPlugin) {
        if (!$p_plugins[$CampPlugin->getName()]) {
            $CampPlugin->delete();   
        }   
    }
    

    foreach ($p_plugins as $plugin => $version) {
        $CampPlugin = new CampPlugin($plugin);
        
        if ($CampPlugin->exists()) {
            if ($CampPlugin->getVersion() != $version) {
                // update plugin
                $CampPlugin->delete();
                $CampPlugin->create($plugin, $version);        
            }
        } else {
            // add plugin
            $CampPlugin->create($plugin, $version);
        }
           
        // enable/disable 
        if ($p_enabled[$plugin]) {
            $CampPlugin->enable();   
        } else {
            $CampPlugin->disable();   
        }
    }
}

if (Input::Get('upload_package')) {
    $file = $_FILES['package'];
    if ($Plugin = CampPlugin::extractPackage($file['tmp_name'], &$log)) {
        $success = getGS('The plugin $1 was sucessfully installed.', $Plugin->getName());
    } else {
        $error = $log;   
    }
    //$Plugin->enable();    
}

if (Input::Get('p_uninstall')) {
    $Plugin = new CampPlugin(Input::Get('p_plugin', 'string'));
    $Plugin->uninstall();    
}

$crumbs = array();
$crumbs[] = array(getGS("Plugins"), "");
$crumbs[] = array(getGS("Manage"), "");
echo camp_html_breadcrumbs($crumbs);

?>
<P>
<FORM name="plugin_upload" action="manage.php" method='POST' enctype='multipart/form-data'>
<table cellpadding="0" cellspacing="0" class="action_buttons" style="padding-bottom: 5px;">
  <tr>
    <td>
      <IMG SRC="<?php echo $Campsite["ADMIN_IMAGE_BASE_URL"]; ?>/add.png" BORDER="0" alt="<?php  putGS('Add new image'); ?>">
      <?php putGS('Upload Plugin'); ?>
      <input type="file" name="package" class="button">
    </td>
    <td valign="bottom">&nbsp;<input type="submit" name="upload_package" value="<?php putGS('Upload') ?>" class="button"></td>
  </tr>
</table>

<?php
if ($success) {
    ?>
    <table cellpadding="0" cellspacing="0" class="action_buttons" style="padding-bottom: 5px;">
      <tr>
        <td class="info_message" ><?php echo $success ?></td>
      </tr>
   </table>
   <?php
} else {
    ?>
    <table cellpadding="0" cellspacing="0" class="action_buttons" style="padding-bottom: 5px;">
      <tr>
        <td class="error_message" ><?php echo $error ?></td>
      </tr>
   </table>
   <?php  
}
?>

<P>
<?php if (count($infos = CampPlugin::getPluginInfos()) > 0) { ?>
<FORM name="plugins_enabled" action="manage.php">
<TABLE BORDER="0" CELLSPACING="1" CELLPADDING="3" class="table_list" width="95%">
    <TR class="table_list_header">
        <TD ALIGN="LEFT" VALIGN="TOP"><B><?php  putGS("Name"); ?></B></TD>
        <TD ALIGN="LEFT" VALIGN="TOP"><B><?php  putGS("Version"); ?></B></TD>
        <TD ALIGN="LEFT" VALIGN="TOP"><B><?php  putGS("Description"); ?></B></TD>
        <TD align="center" VALIGN="TOP"><B><?php  putGS("Enabled"); ?></B></TD>
        <TD align="center" VALIGN="TOP"><B><?php  putGS("Uninstall"); ?></B></TD>
    </TR>
    <?php
    $color=0;
    foreach ($infos as $info) {
        $checked = '';
        if (CampPlugin::isPluginEnabled($info['name'])) {
            $checked = 'checked="checked"';
        }
        ?>
        <TR <?php  if ($color) { $color=0; ?>class="list_row_even"<?php  } else { $color=1; ?>class="list_row_odd"<?php  } ?>>
            <TD width="100px">
                <?php  p($info['label']); ?>
            </TD>
            
            <td width="100px">
                <?php p($info['version']) ?>
            </td>
    
            <TD width="*">
                <?php  p($info['description']); ?>&nbsp;
            </TD>
    
            <TD  width="80px" align="center">
                <input type="hidden" name="p_plugins[<?php p(htmlspecialchars($info['name']))?>]" value="<?php p(htmlspecialchars($info['version'])) ?>">
                
                <input type="checkbox" name="p_enabled[<?php p(htmlspecialchars($info['name']))?>]" <?php p($checked) ?>>
            </TD>
            
            <TD  width="80px" align="center">
               <a href="manage.php?p_plugin=<?php p(htmlspecialchars($info['name']))?>&amp;p_uninstall=1" onClick="return confirm('<?php putGS('Are you sure to uninstall this plugin? All plugin data will be deleted !') ?>')">
                 <IMG SRC="<?php echo $Campsite["ADMIN_IMAGE_BASE_URL"] ?>/delete.png" BORDER="0" ALT="<?php putGS('Delete plugin')?>" TITLE="<?php putGS('Delete plugin') ?>">
               </a>
            </TD>
        </TR>
    <?php
    }
    ?>
    <tr class="table_list_header">
        <td colspan="5" align="center">
            <input type="submit" name="save" value="<?php putGS('Save') ?>" class="button">
        </td>
    </tr>
</table>
</form>
<?php } else { ?>
    <BLOCKQUOTE>
    <LI><?php  putGS('No plugins found.'); ?></LI>
    </BLOCKQUOTE>
<?php } ?>
<?php camp_html_copyright_notice(); ?>