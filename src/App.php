<?php
// +------------------------------------------------------------
// | Author: HanSheng <164897033@qq.com>
// +------------------------------------------------------------
namespace heizuan;

defined('MODULE') or define('MODULE', '');

// 注册自动加载
spl_autoload_register('heizuan\App::autoload', true, true);

class App
{
	private static $load_arr  = [];

	/**
	 * 默认的控制器名
	 * @var string
	 */
	public static $controller = 'Index';

	/**
	 * 默认的方法名
	 * @var string
	 */
	public static $action     = 'main';

	/**
	 * 初始化
	 * @param string $class
	 * @throws \Exception
	 */
	public static function instance($class = '') {
		if($class != ''){
			if(strpos($class, '.') !== false){
				$name_arr = explode('.', $class);
				self::$controller = ucfirst($name_arr[0]);
				self::$action = $name_arr[1];
			}else{
				self::$controller = ucfirst($class);
			}
		}

		define('CONTROLLER', self::$controller);
		define('ACTION', self::$action);

		$full_name = CONTROLLER . 'Controller';

		if(!isset(self::$load_arr[CONTROLLER])){
			self::$load_arr[CONTROLLER] = new $full_name;
		}

		if (method_exists(self::$load_arr[CONTROLLER], ACTION)) {
			$f = ACTION;
			self::$load_arr[CONTROLLER]->$f();
		} else {
			throw new \Exception(CONTROLLER.' 类的 ' . ACTION . ' 方法不存在！');
		}


	}

	/**
	 * 自动加载
	 * @param $className
	 * @return bool
	 */
	public static function autoload($className)
	{
		// 如果以加载过无需重复加载
		if(!empty(self::$load_arr[$className])) {
			return true;
		}

		//扩展类
		$path = HZ_PATH . '/commonTools/' . $className . '.php';
		if(substr($className, -10) == 'Controller'){
			require_once HZ_PATH . MODULE . '/controller/' . $className . '.php';
		}else{
			if(is_file($path)) require_once $path;
		}

		return self::$load_arr[$className] = false;
	}
}

