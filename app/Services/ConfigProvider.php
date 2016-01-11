<?php namespace AgreablePugpigPlugin\Services;

class ConfigProvider {

  public function get() {
    return array(
      'itunes_secret' => get_field('pugpig_itunes_secret', 'option'),
      'pugpig_subscription_prefix' => get_field('pugpig_subscription_prefix', 'option'),
      'pupig_secret' => get_field('pugpig_secret', 'option')
    );
  }
}
