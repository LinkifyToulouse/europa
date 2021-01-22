<?php

namespace Extra {
	
	class AxSQL {
		const ACTION_INSERT = 'INSERT';
		const ACTION_DELETE = 'DELETE';
		const ACTION_UPDATE = 'UPDATE';
		const ACTION_SELECT = 'SELECT';
		
		public static $pdo;
		
		public static function init() {
			if (isset(\App\Kernel\EuropaInitiator::$database['database'])) {
				$db = \App\Kernel\EuropaInitiator::$database['database'];
				if (isset(\App\Kernel\EuropaInitiator::$database['database']['port']) && \App\Kernel\EuropaInitiator::$database['database']['port'] != null) {
					$dsn = "mysql:dbname=".$db['dbname'].";host=".$db['host'].";port=".$db['port'];
				} else {
					$dsn = "mysql:dbname=".$db['dbname'].";host=".$db['host'];
				}
				self::$pdo = new \PDO($dsn, $db['username'], $db['password']);
			}
		}
		
		public static function request($table, $action, $values = null, $conditions = null, string $addParameters = '') {
			if ($action == 'SELECT') {
				$sel = '';
				if (\gettype($values) == 'array') {
					foreach($values as $k=>$v) {
						$sel = $sel.$v.', ';
					}
					$sel = \substr($sel, 0, \strlen($sel) - 2);
				} else {
					$sel = '*';
				}
				$cond = '';
				if ($conditions != null) {
					$cond = 'WHERE ';
					$execcond = [];
					foreach ($conditions as $k=>$v) {
						$cond = $cond.$k.' = :'.$k.' AND ';
						$execcond[$k] = $v;
					}
					$cond = \substr($cond, 0, \strlen($cond) - 5);
					$req = self::$pdo->prepare("SELECT $sel FROM $table $cond $addParameters");
					$req->execute($execcond);
					$RET = [];
					while ($resp = $req->fetch()) {
						$RET[] = $resp;
					}
					$req->closeCursor(); 
					return $RET;
				} else {
					$req = self::$pdo->query("SELECT $sel FROM $table $addParameters");
					$RET = [];
					while ($resp = $req->fetch()) {
						$RET[] = $resp;
					}
					$req->closeCursor(); 
					return $RET;
				}
			} else if ($action == 'UPDATE') {
				if ($values != null) {
					$var = '';
					$val = [];
					foreach ($values as $k=>$v) {
						$var = $var.$k.' = :'.$k.', ';
						$val[$k] = $v;
					}
					$var = \substr($var, 0, \strlen($var) - 2);
					
					if ($conditions != null) {
						$cond = '';
						$execcond = [];
						foreach ($conditions as $k=>$v) {
							$cond = $cond.$k.' = :'.$k.' AND ';
							$execcond[$k] = $v;
						}
						$cond = \substr($cond, 0, \strlen($cond) - 5);
						$val = \array_merge($val, $execcond);
						
						$req = self::$pdo->prepare("UPDATE $table SET $var WHERE $cond $addParameters");
						return $req->execute($val);
					} else {
						$req = self::$pdo->prepare("UPDATE $table SET $var $addParameters");
						return $req->execute($val);
					}
				}
			} else if ($action == 'INSERT') {
				$var = '';
				$val = '';
				$execCond = [];
				foreach ($values as $k=>$v) {
					$var = $var.':'.$k.', ';
					$val = $val.$k.', ';
					$execCond[$k] = $v;
				}
				$var = \substr($var, 0, \strlen($var) - 2);
				$val = \substr($val, 0, \strlen($val) - 2);
				$req = self::$pdo->prepare("INSERT INTO $table($val) VALUES($var) $addParameters");
				return $req->execute($execCond);
			} else if ($action == 'DELETE') {
				$var = '';
				$execCond = [];
				foreach ($values as $k=>$v) {
					$var = $var.$k.' = :'.$k.' AND ';
					$execCond[$k] = $v;
				}
				$var = \substr($var, 0, \strlen($var) - 5);
				$req = self::$pdo->prepare("DELETE FROM $table WHERE $var $addParameters");
				return $req->execute($execCond);
			}
		}
		
		public static function query($sql) {
			return self::$pdo->query($sql);
		}
		
		public static function prepare($sql) {
			return self::$pdo->prepare($sql);
		}
		
		public static function execute($elements) {
			return self::$pdo->execute($elements);
		}
		
	}
	
}
