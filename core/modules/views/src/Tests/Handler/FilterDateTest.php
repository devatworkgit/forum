<?php

namespace Drupal\views\Tests\Handler;

use Drupal\config\Tests\SchemaCheckTestTrait;
use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\node\Entity\NodeType;
use Drupal\views\Views;

/**
 * Tests the core Drupal\views\Plugin\views\filter\Date handler.
 *
 * @group views
 */
class FilterDateTest extends HandlerTestBase {
  use SchemaCheckTestTrait;

  /**
   * Views used by this test.
   *
   * @var array
   */
  public static $testViews = array('test_filter_date_between');

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = array('node', 'views_ui', 'datetime');

  protected function setUp() {
    parent::setUp();

    // Add a date field so we can test datetime handling.
    NodeType::create([
      'type' => 'page',
      'name' => 'Page',
    ])->save();

    // Setup a field storage and field, but also change the views data for the
    // entity_test entity type.
    $field_storage = FieldStorageConfig::create([
      'field_name' => 'field_date',
      'type' => 'datetime',
      'entity_type' => 'node',
    ]);
    $field_storage->save();

    $field = FieldConfig::create([
      'field_name' => 'field_date',
      'entity_type' => 'node',
      'bundle' => 'page',
    ]);
    $field->save();

    // Add some basic test nodes.
    $this->nodes = array();
    $this->nodes[] = $this->drupalCreateNode(array('created' => 100000, 'field_date' => 10000));
    $this->nodes[] = $this->drupalCreateNode(array('created' => 200000, 'field_date' => 20000));
    $this->nodes[] = $this->drupalCreateNode(array('created' => 300000, 'field_date' => 30000));
    $this->nodes[] = $this->drupalCreateNode(array('created' => time() + 86400, 'field_date' => time() + 86400));

    $this->map = array(
      'nid' => 'nid',
    );
  }

  /**
   * Runs other test methods.
   */
  public function testDateFilter() {
    $this->_testOffset();
    $this->_testBetween();
    $this->_testUiValidation();
    $this->_testFilterDateUI();
    $this->_testFilterDatetimeUI();
  }

  /**
   * Test the general offset functionality.
   */
  protected function _testOffset() {
    $view = Views::getView('test_filter_date_between');

    // Test offset for simple operator.
    $view->initHandlers();
    $view->filter['created']->operator = '>';
    $view->filter['created']->value['type'] = 'offset';
    $view->filter['created']->value['value'] = '+1 hour';
    $view->executeDisplay('default');
    $expected_result = array(
      array('nid' => $this->nodes[3]->id()),
    );
    $this->assertIdenticalResultset($view, $expected_result, $this->map);
    $view->destroy();

    // Test offset for between operator.
    $view->initHandlers();
    $view->filter['created']->operator = 'between';
    $view->filter['created']->value['type'] = 'offset';
    $view->filter['created']->value['max'] = '+2 days';
    $view->filter['created']->value['min'] = '+1 hour';
    $view->executeDisplay('default');
    $expected_result = array(
      array('nid' => $this->nodes[3]->id()),
    );
    $this->assertIdenticalResultset($view, $expected_result, $this->map);
  }

  /**
   * Tests the filter operator between/not between.
   */
  protected function _testBetween() {
    $view = Views::getView('test_filter_date_between');

    // Test between with min and max.
    $view->initHandlers();
    $view->filter['created']->operator = 'between';
    $view->filter['created']->value['min'] = format_date(150000, 'custom', 'Y-m-d H:i:s');
    $view->filter['created']->value['max'] = format_date(200000, 'custom', 'Y-m-d H:i:s');
    $view->executeDisplay('default');
    $expected_result = array(
      array('nid' => $this->nodes[1]->id()),
    );
    $this->assertIdenticalResultset($view, $expected_result, $this->map);
    $view->destroy();

    // Test between with just max.
    $view->initHandlers();
    $view->filter['created']->operator = 'between';
    $view->filter['created']->value['max'] = format_date(200000, 'custom', 'Y-m-d H:i:s');
    $view->executeDisplay('default');
    $expected_result = array(
      array('nid' => $this->nodes[0]->id()),
      array('nid' => $this->nodes[1]->id()),
    );
    $this->assertIdenticalResultset($view, $expected_result, $this->map);
    $view->destroy();

    // Test not between with min and max.
    $view->initHandlers();
    $view->filter['created']->operator = 'not between';
    $view->filter['created']->value['min'] = format_date(100000, 'custom', 'Y-m-d H:i:s');
    $view->filter['created']->value['max'] = format_date(200000, 'custom', 'Y-m-d H:i:s');

    $view->executeDisplay('default');
    $expected_result = array(
      array('nid' => $this->nodes[2]->id()),
      array('nid' => $this->nodes[3]->id()),
    );
    $this->assertIdenticalResultset($view, $expected_result, $this->map);
    $view->destroy();

    // Test not between with just max.
    $view->initHandlers();
    $view->filter['created']->operator = 'not between';
    $view->filter['created']->value['max'] = format_date(200000, 'custom', 'Y-m-d H:i:s');
    $view->executeDisplay('default');
    $expected_result = array(
      array('nid' => $this->nodes[2]->id()),
      array('nid' => $this->nodes[3]->id()),
    );
    $this->assertIdenticalResultset($view, $expected_result, $this->map);
  }

  /**
   * Make sure the validation callbacks works.
   */
  protected function _testUiValidation() {

    $this->drupalLogin($this->drupalCreateUser(array('administer views', 'administer site configuration')));

    $this->drupalGet('admin/structure/views/view/test_filter_date_between/edit');
    $this->drupalGet('admin/structure/views/nojs/handler/test_filter_date_between/default/filter/created');

    $edit = array();
    // Generate a definitive wrong value, which should be checked by validation.
    $edit['options[value][value]'] = $this->randomString() . '-------';
    $this->drupalPostForm(NULL, $edit, t('Apply'));
    $this->assertText(t('Invalid date format.'), 'Make sure that validation is run and the invalidate date format is identified.');
  }

  /**
   * Test date filter UI.
   */
  protected function _testFilterDateUI() {
    $this->drupalLogin($this->drupalCreateUser(array('administer views')));
    $this->drupalGet('admin/structure/views/nojs/handler/test_filter_date_between/default/filter/created');
    $this->drupalPostForm(NULL, array(), t('Expose filter'));
    $this->drupalPostForm(NULL, array(), t('Grouped filters'));

    $edit = array();
    $edit['options[group_info][group_items][1][title]'] = 'simple-offset';
    $edit['options[group_info][group_items][1][operator]'] = '>';
    $edit['options[group_info][group_items][1][value][type]'] = 'offset';
    $edit['options[group_info][group_items][1][value][value]'] = '+1 hour';
    $edit['options[group_info][group_items][2][title]'] = 'between-offset';
    $edit['options[group_info][group_items][2][operator]'] = 'between';
    $edit['options[group_info][group_items][2][value][type]'] = 'offset';
    $edit['options[group_info][group_items][2][value][min]'] = '+1 hour';
    $edit['options[group_info][group_items][2][value][max]'] = '+2 days';
    $edit['options[group_info][group_items][3][title]'] = 'between-date';
    $edit['options[group_info][group_items][3][operator]'] = 'between';
    $edit['options[group_info][group_items][3][value][min]'] = format_date(150000, 'custom', 'Y-m-d H:i:s');
    $edit['options[group_info][group_items][3][value][max]'] = format_date(250000, 'custom', 'Y-m-d H:i:s');

    $this->drupalPostForm(NULL, $edit, t('Apply'));

    $this->drupalGet('admin/structure/views/nojs/handler/test_filter_date_between/default/filter/created');
    foreach ($edit as $name => $value) {
      $this->assertFieldByName($name, $value);
      if (strpos($name, '[value][type]')) {
        $radio = $this->cssSelect('input[name="' . $name . '"][checked="checked"][type="radio"]');
        $this->assertEqual((string) $radio[0]['value'], $value);
      }
    }

    $this->drupalPostForm('admin/structure/views/view/test_filter_date_between', array(), t('Save'));
    $this->assertConfigSchemaByName('views.view.test_filter_date_between');

    // Test that the exposed filter works as expected.
    $this->drupalGet('admin/structure/views/view/test_filter_date_between/edit');
    $this->drupalPostForm(NULL, array(), t('Update preview'));
    $results = $this->cssSelect('.view-content .field-content');
    $this->assertEqual(count($results), 4);
    $this->drupalPostForm(NULL, array('created' => '1'), t('Update preview'));
    $results = $this->cssSelect('.view-content .field-content');
    $this->assertEqual(count($results), 1);
    $this->assertEqual((string) $results[0], $this->nodes[3]->id());
    $this->drupalPostForm(NULL, array('created' => '2'), t('Update preview'));
    $results = $this->cssSelect('.view-content .field-content');
    $this->assertEqual(count($results), 1);
    $this->assertEqual((string) $results[0], $this->nodes[3]->id());
    $this->drupalPostForm(NULL, array('created' => '3'), t('Update preview'));
    $results = $this->cssSelect('.view-content .field-content');
    $this->assertEqual(count($results), 1);
    $this->assertEqual((string) $results[0], $this->nodes[1]->id());

    // Change the filter to a single filter to test the schema when the operator
    // is not exposed.
    $this->drupalPostForm('admin/structure/views/nojs/handler/test_filter_date_between/default/filter/created', array(), t('Single filter'));
    $edit = array();
    $edit['options[operator]'] = '>';
    $edit['options[value][type]'] = 'date';
    $edit['options[value][value]'] = format_date(350000, 'custom', 'Y-m-d H:i:s');
    $this->drupalPostForm(NULL, $edit, t('Apply'));
    $this->drupalPostForm('admin/structure/views/view/test_filter_date_between', array(), t('Save'));
    $this->assertConfigSchemaByName('views.view.test_filter_date_between');

    // Test that the filter works as expected.
    $this->drupalPostForm(NULL, array(), t('Update preview'));
    $results = $this->cssSelect('.view-content .field-content');
    $this->assertEqual(count($results), 1);
    $this->assertEqual((string) $results[0], $this->nodes[3]->id());
    $this->drupalPostForm(NULL, array('created' => format_date(250000, 'custom', 'Y-m-d H:i:s')), t('Update preview'));
    $results = $this->cssSelect('.view-content .field-content');
    $this->assertEqual(count($results), 2);
    $this->assertEqual((string) $results[0], $this->nodes[2]->id());
    $this->assertEqual((string) $results[1], $this->nodes[3]->id());
  }

  /**
   * Test datetime grouped filter UI.
   */
  protected function _testFilterDatetimeUI() {
    $this->drupalLogin($this->drupalCreateUser(array('administer views')));
    $this->drupalPostForm('admin/structure/views/nojs/add-handler/test_filter_date_between/default/filter', ['name[node__field_date.field_date_value]' => 'node__field_date.field_date_value'], t('Add and configure filter criteria'));

    $this->drupalPostForm(NULL, array(), t('Expose filter'));
    $this->drupalPostForm(NULL, array(), t('Grouped filters'));

    $edit = array();
    $edit['options[group_info][group_items][1][title]'] = 'simple-offset';
    $edit['options[group_info][group_items][1][operator]'] = '>';
    $edit['options[group_info][group_items][1][value][type]'] = 'offset';
    $edit['options[group_info][group_items][1][value][value]'] = '+1 hour';
    $edit['options[group_info][group_items][2][title]'] = 'between-offset';
    $edit['options[group_info][group_items][2][operator]'] = 'between';
    $edit['options[group_info][group_items][2][value][type]'] = 'offset';
    $edit['options[group_info][group_items][2][value][min]'] = '+1 hour';
    $edit['options[group_info][group_items][2][value][max]'] = '+2 days';
    $edit['options[group_info][group_items][3][title]'] = 'between-date';
    $edit['options[group_info][group_items][3][operator]'] = 'between';
    $edit['options[group_info][group_items][3][value][min]'] = format_date(150000, 'custom', 'Y-m-d H:i:s');
    $edit['options[group_info][group_items][3][value][max]'] = format_date(250000, 'custom', 'Y-m-d H:i:s');

    $this->drupalPostForm(NULL, $edit, t('Apply'));

    $this->drupalPostForm('admin/structure/views/view/test_filter_date_between', array(), t('Save'));
    $this->assertConfigSchemaByName('views.view.test_filter_date_between');
  }

}
