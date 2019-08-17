<?
/**
 * @author Mixail Sayapin
 * https://ms-web.ru
 */

/**
 * @author Mixail Sayapin
 * https://ms-web.ru
 */

namespace CALC;
class DB
{

	private static $db = null; // Единственный экземпляр класса, чтобы не создавать множество подключений
	private $mysqli; // Идентификатор соединения
	private $sym_query = "{?}"; // "Символ значения в запросе"
	public $prefix;

	/* Получение экземпляра класса. Если он уже существует, то возвращается, если его не было, то создаётся и возвращается (паттерн Singleton) */
	public static function getDB()
	{
		if (self::$db == null) self::$db = new DB();
		return self::$db;
	}

	/* private-конструктор, подключающийся к базе данных, устанавливающий локаль и кодировку соединения */
	private function __construct()
	{
		global $config;
		$this->mysqli = new \mysqli($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);
		$this->mysqli->query("SET lc_time_names = 'ru_RU'");
		$this->mysqli->query("SET NAMES 'utf8'");
		$this->prefix = $config['db_prefix'];
	}

	/* Вспомогательный метод, который заменяет "символ значения в запросе" на конкретное значение, которое проходит через "функции безопасности" */
	private function getQuery($query, $params)
	{
		if (is_array($params)) {
			for ($i = 0; $i < count($params); $i++) {
				$pos = strpos($query, $this->sym_query);
				$arg = "'" . $this->mysqli->real_escape_string($params[$i]) . "'";
				$query = substr_replace($query, $arg, $pos, strlen($this->sym_query));
			}
		}
		else if ($params) {
			$pos = strpos($query, $this->sym_query);
			$arg = "'" . $this->mysqli->real_escape_string($params) . "'";
			$query = substr_replace($query, $arg, $pos, strlen($this->sym_query));
		}
		return $query;
	}

	/* SELECT-метод, возвращающий таблицу результатов */
	public function get_results($query, $params = false, $asArray = false)
	{
		$result_set = $this->mysqli->query($this->getQuery($query, $params));
		if (!$result_set) return false;
		return $asArray ? $this->getAssoc($result_set) : $this->getObj($result_set);
	}

	/* SELECT-метод, возвращающий одну строку с результатом */
	public function get_row($query, $params = false)
	{
		$result_set = $this->mysqli->query($this->getQuery($query, $params));
		if ($result_set->num_rows != 1) return false;
		else return $result_set->fetch_assoc();
	}

	/* SELECT-метод, возвращающий значение из конкретной ячейки */
	public function get_var($query, $params = false)
	{
		$result_set = $this->mysqli->query($this->getQuery($query, $params));
		if ((!$result_set) || ($result_set->num_rows != 1)) return false;
		else {
			$arr = array_values($result_set->fetch_assoc());
			return $arr[0];
		}
	}

	/* НЕ-SELECT методы (INSERT, UPDATE, DELETE). Если запрос INSERT, то возвращается id последней вставленной записи */
	public function query($query, $params = false)
	{
		$success = $this->mysqli->query($this->getQuery($query, $params));
		if ($success) {
			if ($this->mysqli->insert_id === 0) return true;
			else return $this->mysqli->insert_id;
		} else return false;
	}

	/* Преобразование result_set в двумерный массив */
	private function getAssoc($result_set)
	{
		$array = array();
		while (($row = $result_set->fetch_assoc()) != false) {
			$array[] = $row;
		}
		return $array;
	}

	/**
	 *
	 * @param $result_set
	 * @return array
	 */
	private function getObj($result_set)
	{
		$array = array();
		while (($row = $result_set->fetch_object()) != false) {
			$array[] = $row;
		}
		return $array;
	}

	/* При уничтожении объекта закрывается соединение с базой данных */
	public function __destruct()
	{
		if ($this->mysqli) $this->mysqli->close();
	}


}
