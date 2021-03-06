<?php
/**
* @package		EasyBlog
* @copyright	Copyright (C) 2010 - 2019 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

class EasyBlogTable extends JTable
{
	protected $_supportNullValue = true;

	public function __construct($table, $key, $db, $dispatcher = null)
	{
		// Set internal variables.
		$this->_tbl = $table;
		$this->_tbl_key = $key;

		// For Joomla 3.2 onwards
		$this->_tbl_keys = array($key);

		$this->_db = $db;

		// Implement JObservableInterface:
		// Create observer updater and attaches all observers interested by $this class:
		if (EB::isJoomla31() && class_exists('JObserverUpdater')) {
			$this->_observers = new JObserverUpdater($this);
			JObserverMapper::attachAllObservers($this);
		}

		if (EB::isJoomla4()) {

			// Create or set a Dispatcher
			if (!is_object($dispatcher) || !($dispatcher instanceof DispatcherInterface)) {
				$dispatcher = JFactory::getApplication()->getDispatcher();
			}

			$this->setDispatcher($dispatcher);

			$event = Joomla\CMS\Event\AbstractEvent::create('onTableObjectCreate', array('subject' => $this));

			$this->getDispatcher()->dispatch('onTableObjectCreate', $event);
		}
	}

	/**
	 * Tired of fixing conflicts with JTable::getInstance . We'll overload their method here.
	 *
	 * @param   string  $type    The type (name) of the JTable class to get an instance of.
	 * @param   string  $prefix  An optional prefix for the table class name.
	 * @param   array   $config  An optional array of configuration values for the JTable object.
	 *
	 * @return  mixed    A JTable object if found or boolean false if one could not be found.
	 *
	 * @link    http://docs.joomla.org/JTable/getInstance
	 * @since   11.1
	 */
	public static function getInstance($type, $prefix = 'JTable', $config = array())
	{
		// Sanitize and prepare the table class name.
		$type = preg_replace('/[^A-Z0-9_\.-]/i', '', $type);
		$tableClass = 'EasyBlogTable' . ucfirst($type);

		if (strtolower($type) == 'user') {
			$type = 'Blogger';
		}

		// Only try to load the class if it doesn't already exist.
		if (!class_exists($tableClass)) {

			// Search for the class file in the JTable include paths.
			$path = dirname( __FILE__ ) . '/' . strtolower( $type ) . '.php';

			// Import the class file.
			include_once $path;
		}

		$table = parent::getInstance($type, 'EasyBlogTable', $config);

		return $table;
	}

	/**
	 * Generic method to retrieve raw json params from the table
	 *
	 * @since   5.1
	 * @access  public
	 */
	final public function getParams()
	{
		$params = new JRegistry($this->params);

		return $params;
	}

	public function bind($src, $ignore = array())
	{
		if (!is_object($src) && !is_array($src)) {
			$src = new stdClass();
		}

		// Joomla 4 compatibility:
		// To Ensure id column type is integer
		if (is_array($src)) {
			if (isset($src['id'])) {
				$src['id'] = (int) $src['id'];
			}
		}

		if (is_object($src)) {
			if (property_exists($src, 'id')) {
				$src->id = (int) $src->id;
			}
		}

		return parent::bind($src);
	}

	/**
	 * Retrieves the translated title
	 *
	 * @since   4.0
	 */
	public function getTitle()
	{
		return JText::_($this->title);
	}

	public function getDBO()
	{
		$db = EB::db();

		return $db;
	}

	/**
	 * On Joomla 4, if table object contains array or objects, storing is problematic unlike Joomla 3.
	 * To fix Joomla 4 storing issues, we override the store behavior and normalize the fields accordingly.
	 *
	 * @since	5.4.0
	 * @access	public
	 */
	public function store($updateNulls = false)
	{
		if (!EB::isJoomla4()) {
			return parent::store($updateNulls);
		}

		$properties = get_object_vars($this);

		foreach ($properties as $key => $value) {
			if ($key != $this->_tbl_key && strpos($key, '_') !== 0) {

				// For Joomla 4, it does not convert array / objects into json strings
				if (is_object($value) || is_array($value)) {
					$this->$key = json_encode($value);
				}
			}
		}

		return parent::store($updateNulls);
	}

	/**
	 * Method to reset class properties to the defaults set in the class
	 * definition. It will ignore the primary key as well as any private class
	 * properties.
	 *
	 * @return  void
	 *
	 * @link    http://docs.joomla.org/JTable/reset
	 * @since   11.1
	 */
	public function reset()
	{
		$properties = get_object_vars($this);
		$columns = array();

		foreach ($properties as $key => $value) {
			if ($key != $this->_tbl_key && strpos($key, '_') !== 0) {
				$columns[] = $value;
			}
		}

		return $columns;
	}
}
