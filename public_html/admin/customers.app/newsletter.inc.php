<?php

  document::$snippets['title'][] = language::translate('title_newsletter', 'Newsletter');

  breadcrumbs::add(language::translate('title_customers', 'Customers'), document::link(WS_DIR_ADMIN, array('doc' => 'customers'), array('app')));
  breadcrumbs::add(language::translate('title_newsletter', 'Newsletter'));

  if (!isset($_GET['template'])) $_GET['template'] = 'raw';
?>
<style>
#service-providers li a {
  position: relative;
  padding: 10px;
}
#service-providers li img {
  position: absolute;
  top: 12px;
  left: 10px;
  width: 32px;
  height: 32px;
  vertical-align: middle;
}
#service-providers li .name {
  font-size: 1.5em;
  margin-left: 40px;
  text-align: left;
}
#service-providers li .offer {
  margin-left: 40px;
  text-align: left;
}
</style>

<div class="panel panel-app">
  <div class="panel-heading">
    <?php echo $app_icon; ?> <?php echo language::translate('title_newsletter', 'Newsletter'); ?>
  </div>

  <div class="panel-body">
    <h2><?php echo language::translate('title_list_of_subscribers', 'List of Subscribers'); ?></h2>

    <p class="btn-group">
      <a class="btn btn-default<?php echo (empty($_GET['template']) || $_GET['template'] == 'raw') ? ' active' : null; ?>" href="<?php echo document::href_link('', array('template' => 'raw'), array('app', 'doc')); ?>">Raw</a>
      <a class="btn btn-default<?php echo (isset($_GET['template']) && $_GET['template'] == 'email') ? ' active' : null; ?>" href="<?php echo document::href_link('', array('template' => 'email'), array('app', 'doc')); ?>">Email Formatted</a>
      <a class="btn btn-default<?php echo (isset($_GET['template']) && $_GET['template'] == 'csv') ? ' active' : null; ?>" href="<?php echo document::href_link('', array('template' => 'csv'), array('app', 'doc')); ?>">CSV</a>
    </ul>

    <div class="row" style="margin-bottom: 2em;">
      <div class="col-md-6">
        <h2><?php echo language::translate('title_customers', 'Customers'); ?></h2>
<?php
  $output = '';

    switch($_GET['template']) {
      case 'csv':
        $output .= 'Firstname;Lastname;Email Address' . PHP_EOL;
        break;
    }

  $customers_query = database::query(
    "select firstname, lastname, email from ". DB_TABLE_CUSTOMERS ."
    where newsletter
    order by firstname, lastname;"
  );
  while ($customer = database::fetch($customers_query)) {
    switch($_GET['template']) {
      case 'email':
        $output .= '"'. $customer['firstname'] .' '. $customer['lastname'] .'" <'. $customer['email'] .'>;' . PHP_EOL;
        break;
      case 'csv':
        $output .= implode(';', array($customer['firstname'], $customer['lastname'], $customer['email'])) . PHP_EOL;
        break;
      case 'raw':
        $output .= $customer['email'] . PHP_EOL;
        break;
    }
  }

  echo functions::form_draw_textarea('subscribers', $output, 'style="height: 400px;"');
?>
      </div>

      <div class="col-md-6">
        <h2><?php echo language::translate('title_guests', 'Guests'); ?></h2>
<?php
  $output = '';

    switch($_GET['template']) {
      case 'csv':
        $output .= 'Firstname;Lastname;Email Address' . PHP_EOL;
        break;
    }

  $customers_query = database::query(
    "select customer_firstname as firstname, customer_lastname as lastname, customer_email as email from ". DB_TABLE_ORDERS ."
    where customer_id = 0
    group by customer_email
    order by customer_firstname, customer_lastname;"
  );
  while ($customer = database::fetch($customers_query)) {
    switch($_GET['template']) {
      case 'email':
        $output .= '"'. $customer['firstname'] .' '. $customer['lastname'] .'" <'. $customer['email'] .'>;' . PHP_EOL;
        break;
      case 'csv':
        $output .= implode(';', array($customer['firstname'], $customer['lastname'], $customer['email'])) . PHP_EOL;
        break;
      case 'raw':
      default:
        $output .= $customer['email'] . PHP_EOL;
        break;
    }
  }

  echo functions::form_draw_textarea('subscribers', $output, 'style="height: 400px;"');
?>
      </div>
    </div>

    <ul id="service-providers" class="list-inline">
      <li>
        <a href="http://eepurl.com/JAeav" target="_blank" class="btn btn-default">
          <img src="<?php echo WS_DIR_ADMIN . 'customers.app/mailchimp.png'; ?>" alt="" />
          <div class="name">MailChimp</div>
          <div class="offer">xMexpi gives you $30 free credits</div>
        </a>
      </li>
    </ul>
  </div>
</div>
