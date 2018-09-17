<?php
  defined('_JEXEC') or die;
  JHtml::_('behavior.tabstate');

  $document = JFactory::getDocument();
  $stylesheetUrl = JUri::base() . 'components/com_epkalenteri/css/com_epkalenteri.css'; 
  $document->addStyleSheet($stylesheetUrl);
?>

<?php 
  if (isset($_POST['epkalenteri-submit'])) {
    if (isset($_POST['epkalenteri-api-url']) && strlen($_POST['epkalenteri-api-url']) > 0 && strlen($_POST['epkalenteri-template']) > 0) {

      $apiConfig =new stdClass();
      $apiConfig->id = $_POST['epkalenteri-config-id'];
      $apiConfig->apiurl = $_POST['epkalenteri-api-url'];

      $template = new stdClass();
      $template->id = $_POST['epkalenteri-template-id'];
      $template->template = $_POST['epkalenteri-template'];

      $db = JFactory::getDbo();
      if (isset($apiConfig->id)) {
        $db->updateObject('#__epkalenteriConfig', $apiConfig, 'id');
        echo '<div class="epkalenteri-alert-success"> updated successfully </div>';
      } else {
        $db->insertObject('#__epkalenteriConfig', $apiConfig, 'id');   
        echo '<div class="epkalenteri-alert-success"> updated successfully </div>';
      } 

      if (isset($template->id)) {
        $db->updateObject('#__epkalenteriEventTemplate', $template, 'id');
      } else {
        $db->insertObject('#__epkalenteriEventTemplate', $template, 'id');
      }
    } else {
      echo '<div class="epkalenteri-alert-warning"> Do not leave any fields blank </div>';
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
  
    <?php
      $db = JFactory::getDbo();
      $query = $db->getQuery(true)
        ->select('*')
        ->from($db->quoteName('#__epkalenteriEventTemplate'));
    
      $db->setQuery($query);
      $results = $db->loadObjectList();
      
      $template = NULL;
      $templateId = NULL;
    
      if (sizeof($results) > 0) {
        $template = $results[0]->template;
        $templateId = $results[0]->id;
      }

      if ($templateId) {
        echo '<input type="hidden" value="'.$templateId.'" name="epkalenteri-template-id">';
      }
    ?>
    
    <h3>Event template</h3>

    <textarea name="epkalenteri-template" class="epkalenteri epkalenteri__textarea"><?php echo strlen($template) > 0 ? $template : getDefaultTemplate(); ?></textarea>
    <input type="submit" class="epkalenteri epkalenteri__input_submit" name="epkalenteri-submit" value="Save">
  </form>
</div>

<?php
  function getDefaultTemplate() {
    return 
'
<div class="epkalenteri-event" style="background-image: url({{ event.images.0.url }}) ">

<a href="{{ event.infoUrl.fi }}">
<div class="epkalenteri-event-start-time">
<p>{{ event.startTime | date("d.m.Y") }}</p>
</div>

<div class="epkalenteri-event-header">
<h4>{{ event.name.fi }}</h4>
</div>

<div class="epkalenteri-event-short-description">
<p>{{ event. shortDescription.fi }}</p>
</div>
</a>

</div>
';
  }
?>