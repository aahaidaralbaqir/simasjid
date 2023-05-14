<?php 

namespace App\Util;
use App\Constant\Constant;

class Common {
	
	public static function getRole()
	{
		return [Constant::ROLE_HOKAGE => 'hokage',
				Constant::ROLE_RAIKAGE => 'raikage'];
	}

	public static function getStatusExcept($statuses = array())
	{
		$status = [];
		foreach(self::getStatus() as $key => $value)
			if (!in_array($key, $statuses)) $status[$key] = $value; 
		return $status;
	}

	public static function getStatus()
	{
		return [Constant::STATUS_DRAFT => Constant::STATUS_DRAFT_NAME,
				Constant::STATUS_PUBLISHED => Constant::STATUS_PUBLISHED_NAME,
				Constant::STATUS_ACTIVE => Constant::STATUS_ACTIVE_NAME,
				Constant::STATUS_INACTIVE => Constant::STATUS_INACTIVE_NAME];
	}

	public static function getStatusById($id)
	{
		$available_status = self::getStatus();
		if (!array_key_exists($id, $available_status))
			return 'Unknow status';
		return $available_status[$id];	
	}

	public static function getCategories()
	{
		return [Constant::CATEGORY_KAJIAN => Constant::CATEGORY_KAJIAN_NAME,
				Constant::CATEGORY_KHUTBAH => Constant::CATEGORY_KHUTBAH_NAME,
				Constant::CATEGORY_BULETIN => Constant::CATEGORY_BULETIN_NAME];
	}

	public static function getCategoriesById(int $id)
	{
		$available_categories = self::getCategories();
		if (!array_key_exists($id, $available_categories))
			return 'Unknow categories';
		return $available_categories[$id];	
	}

	public static function getRoleNameById(int $id)
	{
		$available_role = self::getRole();
		if (!array_key_exists($id, $available_role))
			return 'Unknow role';
		return $available_role[$id];
	}

	public static function getDefaultAvatar() 
	{
		return '/img/user/user-01.png';
	}

	public static function getStorage($storage_name, $filename) 
	{
		return '/storage/' .$storage_name. '/' . $filename;
	}

	public static function getFileName($fullpath) 
	{
		$path = explode('/', $fullpath);
		if (empty($path))
			return $fullpath;
		return $path[count($path) - 1];
	}
}