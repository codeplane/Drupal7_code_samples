<?php

/**
 * @file
 * Code-samples.
 */

/**
 * Custom function to fetch the parent terms from a vocabulary.
 */
function module_topics_parent_terms() {
  $query = db_select('taxonomy_term_data', 'ttd');
  $query->fields('ttd', array('tid'));
  $query->condition('tth.parent', '0', '=');
  $query->condition('ttd.vid', '6', '=');
  $query->innerjoin('taxonomy_term_hierarchy', 'tth', 'tth.tid = ttd.tid');
  $object = $query->execute()->fetchAll();
  foreach ($object as $label => $pair) {
    $result[] = $pair->tid;
  }
  return $result;
}

/**
 * Custom function added to validate email domain.
 */
function custom_email_domain_validation(&$form, &$form_state, $form_id) {
  global $user;
  $new_email = $form_state['values']['recipients'];
  if (array_key_exists(5, $user->roles)) {
    $allowed = array('domainname.org');
    $explodedEmail = explode('@', $new_email);
    $domain = array_pop($explodedEmail);
    if (!in_array($domain, $allowed)) {
      form_set_error('mail', t('invalid email'));
    }
  }
}

/**
 * Implements hook_search_api_viewsserch().
 */
 function module_search_api_views_query_alter(&$view, &$query) {
  if (isset($_GET['search_full_text_radios'])) {
    switch ($_GET['search_full_text_radios']) {
      case 'title':
        $field_set = $query->getFields();
        $field_set = array(0 => 'title');
        $query->fields($field_set);
      case 'description':
        $field_set = array(0 => 'field_short_description');
      break;
      case 'keywords':
        $field_set = array(0 => 'field_keywords:name');
      break;
      case 'exact_match':
        $field_set = array(0 => 'search_api_aggregation_1');
      break;
      case 'partner_name':
        $field_set = array(0 => 'field_partner:name');
      break;
    }
  }
}

/**
 * Redirect users to different pages based on their roles.
 */
function contact_redirect() {
  global $user;
  switch ($_GET['page']) {
    case 'contact-us':
      if (in_array('internal user', $user->roles)) {
        drupal_goto('content/about-contact-staff');
      }
      elseif (in_array('external user', $user->roles) || in_array('anonymous user', $user->roles)) {
        drupal_goto('content/about-contact-client');
      }
      break;

    case 'faq':
      if (in_array('internal user', $user->roles)) {
        drupal_goto('content/about-new-features-staff');
      }
      elseif (in_array('external user', $user->roles) || in_array('anonymous user', $user->roles)) {
        drupal_goto('content/about-new-features-client');
      }
      break;

    case 'academy-faq':
      if (in_array('internal user', $user->roles)) {
        drupal_goto('content/about-wb-academy-staff');
      }
      elseif (in_array('external user', $user->roles) || in_array('anonymous user', $user->roles)) {
        drupal_goto('content/about-wb-academy-client');
      }
      break;

    case 'talks-faq':
      if (in_array('internal user', $user->roles)) {
        drupal_goto('content/about-wb-talks-staff');
      }
      elseif (in_array('external user', $user->roles) || in_array('anonymous user', $user->roles)) {
        drupal_goto('content/about-wb-talks-client');
      }
      break;

    case 'connect-faq':
      if (in_array('internal user', $user->roles)) {
        drupal_goto('content/about-wb-connect-staff');
      }
      elseif (in_array('external user', $user->roles) || in_array('anonymous user', $user->roles)) {
        drupal_goto('content/about-wb-connect-client');
      }
      break;

    case 'tech-question':
      if (in_array('internal user', $user->roles)) {
        drupal_goto('content/about-technical-questions-staff');
      }
      elseif (in_array('external user', $user->roles) || in_array('anonymous user', $user->roles)) {
        drupal_goto('content/about-technical-questions-client');
      }
      break;

  }
}
