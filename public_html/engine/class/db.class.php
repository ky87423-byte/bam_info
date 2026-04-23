<?php
/**
 * DBConnection - MySQLi wrapper.
 * Safe when connection fails: _query returns false, no prepare() on null.
 */
class DBConnection {

	private $conn;
	private $last_result;

	public function __construct() {
		$db_config = array(
			'host' => 'localhost',
			'user' => '',
			'pass' => '',
			'name' => ''
		);
		$config_file = defined('NFE_PATH') ? NFE_PATH . '/data/db_config.php' : dirname(dirname(__DIR__)) . '/data/db_config.php';
		if (is_file($config_file)) {
			include $config_file;
			if (isset($db_config) && is_array($db_config)) {
				$db_config = array_merge(array('host'=>'','user'=>'','pass'=>'','name'=>''), $db_config);
			}
		}
		$db_config['host'] = !empty($db_config['host']) ? $db_config['host'] : (getenv('DB_HOST') ?: 'localhost');
		$db_config['user'] = isset($db_config['user']) ? $db_config['user'] : (getenv('DB_USER') ?: '');
		$db_config['pass'] = isset($db_config['pass']) ? $db_config['pass'] : (getenv('DB_PASS') ?: '');
		$db_config['name'] = !empty($db_config['name']) ? $db_config['name'] : (getenv('DB_NAME') ?: '');
		$this->conn = @new mysqli($db_config['host'], $db_config['user'], $db_config['pass'], $db_config['name']);
		if ($this->conn && $this->conn->connect_error) {
			trigger_error('DBConnection failed: ' . $this->conn->connect_error, E_USER_WARNING);
		}
		if ($this->is_connected()) {
			$this->conn->set_charset('utf8mb4');
		}
	}

	public function is_connected() {
		return $this->conn && !$this->conn->connect_error;
	}

	public function get_error() {
		if (!$this->conn) return '';
		if ($this->conn->connect_error) return $this->conn->connect_error;
		return $this->conn->error ?: '';
	}

	public function _query($sql, $params = array()) {
		$this->last_result = null;
		if (!$this->is_connected()) {
			return false;
		}
		if (empty($params)) {
			$r = $this->conn->query($sql);
			$this->last_result = $r;
			return $r;
		}
		$stmt = $this->conn->prepare($sql);
		if (!$stmt) {
			$this->last_result = false;
			return false;
		}
		$values = array_values($params);
		$types = '';
		foreach ($values as $p) {
			if (is_int($p)) $types .= 'i';
			elseif (is_float($p)) $types .= 'd';
			else $types .= 's';
		}
		$stmt->bind_param($types, ...$values);
		$ok = $stmt->execute();
		if ($ok && $stmt->result_metadata()) {
			$this->last_result = $stmt->get_result();
			$stmt->close();
			return $this->last_result;
		}
		$this->last_result = $ok;
		$stmt->close();
		return $ok;
	}

	public function query_fetch($sql, $params = array()) {
		$result = $this->_query($sql, $params);
		if (!$result || !($result instanceof mysqli_result)) {
			return null;
		}
		$row = $result->fetch_assoc();
		$result->free();
		return $row ?: null;
	}

	public function query_fetch_rows($sql, $params = array()) {
		$result = $this->_query($sql, $params);
		if (!$result || !($result instanceof mysqli_result)) {
			return array();
		}
		$rows = array();
		while ($row = $result->fetch_assoc()) {
			$rows[] = $row;
		}
		$result->free();
		return $rows;
	}

	public function afetch($result) {
		if (!$result || !($result instanceof mysqli_result)) {
			return null;
		}
		return $result->fetch_assoc();
	}

	public function num_rows($result) {
		if (!$result || !($result instanceof mysqli_result)) {
			return 0;
		}
		return $result->num_rows;
	}

	public function insert_id() {
		if (!$this->is_connected()) return 0;
		return $this->conn->insert_id;
	}

	/**
	 * IN절 안전 실행: 값 배열을 정수로 캐스팅하여 SQL 인젝션 방지.
	 * 사용 예) $db->query_in("select * from nf_shop where `no` in (%s)", $ids_array);
	 * 빈 배열이면 false 반환 (WHERE IN () 는 MySQL 에러).
	 */
	public function query_in($sql_template, array $ids) {
		if (empty($ids)) return false;
		$safe_ids = array_map('intval', $ids);
		$placeholders = implode(',', $safe_ids);
		$sql = sprintf($sql_template, $placeholders);
		return $this->_query($sql);
	}

	/**
	 * Builds a SET/INSERT fragment: `key`=?, `key2`=?, ...
	 * The passed $arr is used as the bind params array in the subsequent _query() call.
	 */
	public function query_q(&$arr) {
		$parts = array();
		foreach ($arr as $k => $v) {
			$parts[] = '`' . $k . '`=?';
		}
		return implode(', ', $parts);
	}

	/**
	 * Checks whether a column exists in a table.
	 */
	public function is_field($table, $field) {
		if (!$this->is_connected()) return false;
		$result = $this->conn->query(
			"SHOW COLUMNS FROM `" . $this->conn->real_escape_string($table) . "` LIKE '" . $this->conn->real_escape_string($field) . "'"
		);
		if (!$result) return false;
		$exists = $result->num_rows > 0;
		$result->free();
		return $exists;
	}

	/**
	 * Checks whether a table exists in the current database.
	 */
	public function is_table($table) {
		if (!$this->is_connected()) return false;
		$result = $this->conn->query(
			"SHOW TABLES LIKE '" . $this->conn->real_escape_string($table) . "'"
		);
		if (!$result) return false;
		$exists = $result->num_rows > 0;
		$result->free();
		return $exists;
	}

	public function escape($str) {
		if (!$this->is_connected()) return addslashes($str);
		return $this->conn->real_escape_string($str);
	}
}
