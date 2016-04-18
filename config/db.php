<?php

/**
 * Class SimpleDBICache
 */
class SimpleDBICache extends SimpleDBI
{
    protected $use_cache = true;
    private $cache_row  = array();
    private $cache_rows = array();
    private $cache_columns = array();

    protected function __construct($dsn, $username, $password, $driver_options)
    {
        // command line interface から実行された場合 cache しない
        if (php_sapi_name() == 'cli') {
            $this->use_cache = false;
        }
        parent::__construct($dsn, $username, $password, $driver_options);
    }

    /**
     * $sql と $params から、ユニークなキーを返す
     *
     * @param string $sql
     * @param array $params
     * @return string
     */
    public function generateKey($sql, array $params = array())
    {
        return serialize(array($sql, $params));
    }

    /**
     * SQL を実行する
     *
     * @param string $sql
     * @param array $params
     */
    public function query($sql, array $params = array())
    {
        parent::query($sql, $params);

        if ($this->use_cache === false) {
            return;
        }

        // INSERT,UPDATE,DELETE の場合は該当テーブルのキャッシュをクリア
        list($type, $table) = $this->getQueryMeta($sql);
        if (in_array($type, array('insert', 'delete', 'update'))) {
            if (isset($this->cache_row[$table])) {
                unset($this->cache_row[$table]);
            }
            if (isset($this->cache_rows[$table])) {
                unset($this->cache_rows[$table]);
            }
            if (isset($this->cache_columns[$table])) {
                unset($this->cache_columns[$table]);
            }
        }
    }

    /**
     * SQLクエリから、クエリ種別と対象テーブルを返す
     *
     * @param string $sql
     * @return array
     */
    private function getQueryMeta($sql)
    {
        preg_match('/DELETE\sFROM\s(\S+)\s?/', $sql, $matches);
        if (isset($matches[1])) {
            return array('delete', trim($matches[1], ';'));
        }

        preg_match('/FROM\s(\S+)\s?/', $sql, $matches);
        if (isset($matches[1])) {
            return array('select', trim($matches[1], ';'));
        }

        preg_match('/(?:INSERT|REPLACE)\sINTO\s(\S+)\s?/', $sql, $matches);
        if (isset($matches[1])) {
            return array('insert', trim($matches[1], ';'));
        }

        preg_match('/UPDATE\s(\S+)\sSET\s?/', $sql, $matches);
        if (isset($matches[1])) {
            return array('update', trim($matches[1], ';'));
        }

        return array('unknown', '');
    }

    /**
     * SQL を実行して、結果から最初の1行を取得する
     *
     * @param string $sql
     * @param array $params
     * @return array|bool
     */
    public function row($sql, array $params = array())
    {
        if ($this->use_cache === false) {
            return parent::row($sql, $params);
        }

        $key = $this->generateKey($sql, $params);
        list($type, $table) = $this->getQueryMeta($sql);

        if (isset($this->cache_row[$table][$key])) {
            if (strpos($sql, 'FOR UPDATE') === false && strpos($sql, 'LOCK IN SHARE') === false) {
                $ts = microtime(true);
                list($sql, $params) = self::parseSQL($sql, $params);
                $access_time = microtime(true) - $ts;
                $this->onQueryEnd($sql, $params, $access_time);
                return $this->cache_row[$table][$key];
            }
        }

        $row = parent::row($sql, $params);
        if (!isset($this->cache_row[$table])) {
            $this->cache_row[$table] = array();
        }
        $this->cache_row[$table][$key] = $row;
        return $row;
    }

    /**
     * SQL を実行して、結果からすべての行を取得する
     *
     * @param string $sql
     * @param array $params
     * @return array
     */
    public function rows($sql, array $params = array())
    {
        if ($this->use_cache === false) {
            return parent::rows($sql, $params);
        }

        $key = $this->generateKey($sql, $params);
        list($type, $table) = $this->getQueryMeta($sql);

        if (isset($this->cache_rows[$table][$key])) {
            if (strpos($sql, 'FOR UPDATE') === false && strpos($sql, 'LOCK IN SHARE') === false) {
                $ts = microtime(true);
                list($sql, $params) = self::parseSQL($sql, $params);
                $access_time = microtime(true) - $ts;
                return $this->cache_rows[$table][$key];
            }
        }

        $rows = parent::rows($sql, $params);
        if (!isset($this->cache_rows[$table])) {
            $this->cache_rows[$table] = array();
        }
        $this->cache_rows[$table][$key] = $rows;

        return $rows;
    }

    /**
     * SQL を実行して、結果からすべての行を取得する
     *
     * @param string $sql
     * @param array $params
     * @param int $column_number
     * @return array
     */
    public function columns($sql, array $params = array(), $column_number = 0)
    {
        if ($this->use_cache === false) {
            return parent::columns($sql, $params, $column_number);
        }

        $key = $this->generateKey($sql, $params);
        list($type, $table) = $this->getQueryMeta($sql);

        if (isset($this->cache_columns[$table][$key])) {
            if (strpos($sql, 'FOR UPDATE') === false && strpos($sql, 'LOCK IN SHARE') === false) {
                $ts = microtime(true);
                list($sql, $params) = self::parseSQL($sql, $params);
                $access_time = microtime(true) - $ts;
                return $this->cache_columns[$table][$key];
            }
        }

        $rows = parent::columns($sql, $params, $column_number);
        if (!isset($this->cache_columns[$table])) {
            $this->cache_columns[$table] = array();
        }
        $this->cache_columns[$table][$key] = $rows;

        return $rows;
    }

}

/**
 * Class DB
 */
class DB extends SimpleDBICache
{
    /**
     * @param $dsn
     * @param $username
     * @param $password
     * @param $driver_options
     */
    public function __construct($dsn, $username, $password, $driver_options)
    {
        $this->pdo = new PDO($dsn, $username, $password, $driver_options);

        // エラーモードを例外に設定
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->dsn = $dsn;
    }


    /**
     * @param null $destination
     *
     * @return DB
     */
    public static function conn($destination = null)
    {
        $conn = parent::conn($destination);
        $conn->query("SET NAMES utf8");
        return $conn;
    }

    public function begin()
    {
        if (count($this->trans_stack) == 0) {
            $this->pdo->beginTransaction();
        }
        array_push($this->trans_stack, 'A');
    }

    public function commit()
    {
        if (count($this->trans_stack) <= 1) {
            $this->pdo->commit();
        }
        array_pop($this->trans_stack);
    }

    public function rollback()
    {
        if (count($this->trans_stack) <= 1) {
            $this->pdo->rollBack();
        }
        array_pop($this->trans_stack);
    }


    public function setUseCache($flag)
    {
        $this->use_cache = $flag;
    }



    /**
     * Transaction内に居るかを判定する。
     * @return bool
     */
    public function isInTransaction()
    {
        return $this->pdo->inTransaction();
    }

    /**
     * すべてのTransactionをロールバックして解放する。
     */
    public function allRollBack()
    {
        if($this->isInTransaction() === true) {
            $count = count($this->trans_stack);
            for($i=0;$i<$count;$i++) {
                $this->rollback();
            }
        }
    }


}
