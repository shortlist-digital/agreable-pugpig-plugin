<?php namespace AgreablePugpigPlugin\Services;

class SubscriptionResponse {

  function subs_write_comment(&$writer, $comment)
  {
    $count = 0;
    $comment = str_replace('--', '- -', $comment, $count);
    if ($count>0) {
      // might still have double-hyphens separated by spaces as
      // str_replace doesn't repeat the replacement in text that is inserted
      $comment = str_replace('--', '- -', $comment, $count);
      $comment .= ' (double hyphens have been separated by a space)';
    }

    $writer->writeComment(' ' . $comment . ' ');
  }

  function subs_write_comments(&$writer, $comments)
  {
    foreach ($comments as $comment) {
      $this->subs_write_comment($writer, $comment);
    }
  }

  function subs_write_category(&$writer, $scheme, $value)
  {
    $writer->startElement('category');
    $writer->writeAttribute('scheme', $scheme);
    $writer->writeAttribute('value', $value);
    $writer->endElement();
  }

  function subs_write_categories(&$writer, $categories)
  {
    foreach ($categories as $scheme => $value) {
      $this->subs_write_category($writer, $scheme, $value);
    }
  }

  function subs_write_userinfo(&$writer, $userinfo)
  {
    if (!empty($userinfo['categories'])) {
      $writer->startElement('userinfo');
      $this->subs_write_categories($writer, $userinfo['categories']);
      $writer->endElement();
    }
  }

  function subs_start_xml_writer()
  {
    header('Content-type: text/xml');
    header('Cache-Control: no-cache, must-revalidate, post-check=0, pre-check=0');
    $writer = new XMLWriter();
    $writer->openMemory();
    $writer->setIndent(true);
    $writer->setIndentString('  ');
    $writer->startDocument('1.0', 'UTF-8');

    return $writer;
  }

  function subs_end_xml_writer($writer)
  {
    $writer->endDocument();
    echo $writer->outputMemory();
    exit; // Don't do the usual Drupal caching headers etc when completing the request
  }

  // ************************************************************************
  // Generate response for a sign in
  // $token - The token returned, or empty on failure
  // $comments - any extra comments for the feed, mainly for debug
  // ************************************************************************
  /*
  <?xml version="1.0" encoding="UTF-8" standalone="yes"?>
  <token>BSDF</token>

  <?xml version="1.0" encoding="UTF-8" standalone="yes"?>
  <error status="Creds do not match" />

  */
  function subs_sign_in_response($token, $comments = array(), $fail_status='notrecognised', $fail_message="Invalid credentials", $secret = null)
  {
    $comments[] =  "Generated: " . date(DATE_RFC822);

    $writer = $this->subs_start_xml_writer();

    if (!empty($token)) {
      $writer->startElement('token');
      if (!empty($secret)) {
        $password = pugpig_generate_password("", $token, $secret);
        $writer->writeAttribute('global_auth_password', $password);
      }
      $writer->text($token);
    } else {
      $writer->startElement('error');
      $writer->writeAttribute('status', $fail_status);
      $writer->writeAttribute('message', $fail_message);
    }
    $writer->endElement();
    $this->subs_write_comments($writer, $comments);
    $this->subs_end_xml_writer($writer);
  }

  // ************************************************************************
  // Generate response for a verify subscription
  // $state - The token used to for access
  // * unknown - user isn't recognized
  // * active - user is an active subscriber
  // * inactive - user's subscription has lapsed
  // * suspended - user's subscription has been temporarily suspended
  // * error - the token is illegal
  // $comments - any extra comments for the feed, mainly for debug
  // $issues - NULL means they have access to all issues
  // ************************************************************************
  /*
  <?xml version="1.0" encoding="UTF-8" standalone="yes"?>
  <subscription state="active"/>
  */
  function subs_verify_subscription_response($state, $comments = array(), $message = '', $issues = null, $userinfo=array())
  {
    $comments[] =  "Generated: " . date(DATE_RFC822);

    if (!in_array($state, array('unknown','active','inactive','stale','suspended'))) {
      $comments[] = "Mapping $state to 'unknown";
      $state = 'unknown';
    }

    $writer = $this->subs_start_xml_writer();

    $writer->startElement('subscription');
    $writer->writeAttribute('state', $state);
    if (!empty($message)) {
      $writer->writeAttribute('message', $message);
    }

    $this->subs_write_comments($writer, $comments);

    $this->subs_write_userinfo($writer, $userinfo);

    if (isset($issues)) {
      $writer->startElement('issues');
      foreach ($issues as $issue) {
        $writer->startElement('issue');
        $writer->text($issue);
        $writer->endElement();
      }
      $writer->endElement();
    } elseif ($state == "active") {
      $this->subs_write_comment($writer, "User ($state) has access to all issues.");
    } else {
      $this->subs_write_comment($writer, "User ($state) has access to nothing.");
    }

    $writer->endElement();
    $this->subs_end_xml_writer($writer);
  }

  // ************************************************************************
  // Generate edition credentials in the standard response Pugpig format
  // $product_id - ID of the product (normally the edition ID matching the OPDS feed)
  // $secret - The secret used to generate the credentials
  // $entitled - True if entitled, false otherwise
  // $comments - any extra comments for the feed, mainly for debug
  // $extras - any extra valurs for a positive response
  // ************************************************************************
  /*
  <?xml version="1.0" encoding="UTF-8" standalone="yes"?>
  <credentials>
    <userid>USER-ID</userid>
    <password>PASSWORD</password>
  </credentials>

  <?xml version="1.0" encoding="UTF-8" standalone="yes"?>
  <error status="NOT_ENTITLED"/>
  */

  //TODO: $state is never used here any more, which is correct.
  // Need a refactor of lots of things to remove it
  function subs_edition_credentials_response($product_id, $secret, $entitled = false, $state, $comments = array(), $extras = array(), $error_message = '', $token='', $extra_headers = array())
  {
    $comments[] =  "Generated: " . date(DATE_RFC822);
    $comments[] =  "Requested Product ID: " . $product_id;

    $writer = $this->subs_start_xml_writer();

    if ($entitled) {
      $username = empty($token) ? sha1(uniqid(mt_rand())) : $token;
      $password = pugpig_generate_password($product_id, $username, $secret);

      $writer->startElement('credentials');
      $writer->writeElement('userid', $username);
      $writer->writeElement('password', $password);


      foreach ($extra_headers as $key => $value) {
            $writer->startElement('header');
            $writer->writeAttribute('name', $key);
            $writer->text($value);
            $writer->endElement();
      }

      $this->subs_write_comments($writer, $comments);
      foreach ($extras as $name => $value) $writer->writeElement($name, $value);

      $writer->endElement();
    } else {

      $writer->startElement('credentials');

      $writer->startElement('error');
      $writer->writeAttribute('status', "notentitled");
      if (!empty($error_message)) $writer->writeAttribute('message', $error_message);
      $writer->endElement();

      subs_write_comments($writer, $comments);
      foreach ($extras as $name => $value) $writer->writeElement($name, $value);
      $writer->endElement();

      $writer->endElement();

    }

    $writer->endDocument();

    $this->subs_end_xml_writer($writer);

  }

}
