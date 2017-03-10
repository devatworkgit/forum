<?php

namespace Drupal\user_registrationpassword\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Functionality tests for User registration password module: admin form.
 *
 * @group UserRegistrationPassword.
 */
class UserRegistrationPasswordAccountSettingFormTest extends WebTestBase {

  /**
   * Modules to install.
   *
   * @var array
   */
  public static $modules = array('user_registrationpassword');

  /**
   * User with administer account settings privileges.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $adminUser;

  protected function setUp() {
    parent::setUp();
    $this->adminUser = $this->drupalCreateUser(array('administer account settings'));
    $this->drupalLogin($this->adminUser);
  }

  /**
   * Test the working of the option list provided by this module.
   */
  function testRegisterFormOption() {
    $this->drupalGet('admin/config/people/accounts');

    foreach (array(USER_REGISTRATIONPASSWORD_NO_VERIFICATION,
                   USER_REGISTRATIONPASSWORD_VERIFICATION_DEFAULT,
                   USER_REGISTRATIONPASSWORD_VERIFICATION_PASS) as $option) {
      $edit = array();
      $edit['user_registrationpassword_registration'] = $option;
      $this->drupalPostForm('admin/config/people/accounts', $edit, t('Save configuration'));

      $this->assertNoText(t('An illegal choice has been detected. Please contact the site administrator.'), 'Value was correctly set and saved,');
      $this->assertText(t('The configuration options have been saved.'), 'Successfully saved configuration.');

      $config = $this->config('user_registrationpassword.settings');
      if ($config->get('registration') == $option) {
        $this->assertTrue(TRUE, 'Registration option is set correctly.');
      }
      else {
        $this->assertTrue(FALSE, 'Registration option is not set correctly.');
      }
    }
  }

}