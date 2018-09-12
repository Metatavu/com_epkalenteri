<?php
  defined('_JEXEC') or die;
  JHtml::_('behavior.tabstate');

  $document = JFactory::getDocument();
  $stylesheetUrl = JUri::base() . 'components/com_epkalenteri/css/com_epkalenteri.css'; 
  $document->addStyleSheet($stylesheetUrl);
?>

<?php 
  if (isset($_POST['epkalenteri-submit'])) {
    if (isset($_POST['epkalenteri-api-url']) && strlen($_POST['epkalenteri-api-url']) > 0) {

      $apiConfig =new stdClass();
      $apiConfig->id = $_POST['epkalenteri-config-id'];
      $apiConfig->apiurl = $_POST['epkalenteri-api-url'];


      $db = JFactory::getDbo();
      if (isset($apiConfig->id)) {
        $db->updateObject('#__epkalenteriConfig', $apiConfig, 'id');
        echo '<div class="epkalenteri-alert-success"> APIURL updated successfully </div>';
      } else {
        $db->insertObject('#__epkalenteriConfig', $apiConfig, 'id');   
        echo '<div class="epkalenteri-alert-success"> APIURL updated successfully </div>';
      } 
    } else {
      echo '<div class="epkalenteri-alert-warning"> Do not leave APIURL blank </div>';
    }
  }
?>

<?php 
  $db = JFactory::getDbo();
  $query = $db->getQuery(true)
    ->select('*')
    ->from($db->quoteName('#__epkalenteriConfig'));

  $db->setQuery($query);
  $results = $db->loadObjectList();
  
  $apiurl = NULL;
  $id = NULL;

  if (sizeof($results) > 0) {
    $apiurl = $results[0]->apiurl;
    $id = $results[0]->id;
  }
?>

<div class="epkalenteri epkalenteri__form_container">
  <h3>Api settings</h3>

  <form method="post" class="epkalenteri epkalenteri__form">
    <?php
      if ($id) {
        echo '<input type="hidden" value="'.$id.'" name="epkalenteri-config-id">';
      }
    ?>
    <label>API URL</label>
    <input type="text" class="epkalenteri epkalenteri__input_text" placeholder="API URL" value="<?php echo $apiurl; ?>" name="epkalenteri-api-url">

    <input type="submit" class="epkalenteri epkalenteri__input_submit" name="epkalenteri-submit" value="Save">
  </form>
</div>