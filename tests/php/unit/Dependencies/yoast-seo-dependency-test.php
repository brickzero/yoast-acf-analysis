<?php

namespace Yoast\AcfAnalysis\Tests\Dependencies;

use Brain\Monkey;

/**
 * Class Yoast_SEO_Dependency_Test
 *
 * @package Yoast\AcfAnalysis\Tests\Dependencies
 */
class Yoast_SEO_Dependency_Test extends \PHPUnit_Framework_TestCase {

	/**
	 * Whether or not to preserve the global state.
	 *
	 * @var bool
	 */
	protected $preserveGlobalState = false;

	/**
	 * Whether or not to run the test in a separate process.
	 *
	 * @var bool
	 */
	protected $runTestInSeparateProcess = true;

	/**
	 * Sets up test fixtures.
	 *
	 * @return void
	 */
	protected function setUp() {
		parent::setUp();
		Monkey\setUp();
	}

	/**
	 * Tears down test fixtures previously setup.
	 *
	 * @return void
	 */
	protected function tearDown() {
		Monkey\tearDown();
		parent::tearDown();
	}

	/**
	 * Tests the scenario where dependency can't be found.
	 *
	 * @return void
	 */
	public function testFail() {
		$testee = new \Yoast_ACF_Analysis_Dependency_Yoast_SEO();

		$this->assertFalse( $testee->is_met() );
	}

	/**
	 * Tests the scenario where dependency can be found.
	 *
	 * @return void
	 */
	public function testPass() {
		define( 'WPSEO_VERSION', '4.0.0' );

		$testee = new \Yoast_ACF_Analysis_Dependency_Yoast_SEO();
		$this->assertTrue( $testee->is_met() );
	}

	/**
	 * Tests the scenario where an old version of the dependency isn't compatible.
	 *
	 * @return void
	 */
	public function testOldVersion() {
		define( 'WPSEO_VERSION', '2.0.0' );

		$testee = new \Yoast_ACF_Analysis_Dependency_Yoast_SEO();
		$this->assertFalse( $testee->is_met() );
	}

	/**
	 * Tests the admin notice.
	 *
	 * @return void
	 */
	public function testAdminNotice() {
		$testee = new \Yoast_ACF_Analysis_Dependency_Yoast_SEO();
		$testee->register_notifications();

		$this->assertTrue( has_action( 'admin_notices', array( $testee, 'message_plugin_not_activated' ) ) );
	}

	/**
	 * Tests the admin notice regarding the minimum version.
	 *
	 * @return void
	 */
	public function testAdminNoticeMinimumVersion() {
		define( 'WPSEO_VERSION', '2.0.0' );

		$testee = new \Yoast_ACF_Analysis_Dependency_Yoast_SEO();
		$testee->register_notifications();

		$this->assertTrue( has_action( 'admin_notices', array( $testee, 'message_minimum_version' ) ) );
	}
}
