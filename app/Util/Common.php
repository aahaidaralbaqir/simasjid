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
		return [Constant::CATEGORY_TAFSIR 	=> Constant::CATEGORY_TAFSIR_NAME,
				Constant::CATEGORY_KHUTBAH 	=> Constant::CATEGORY_KHUTBAH_NAME,
				Constant::CATEGORY_HADIST 	=> Constant::CATEGORY_HADIST_NAME,
				Constant::CATEGORY_TASAWUF 	=> Constant::CATEGORY_TASAWUF_NAME,
				Constant::CATEGORY_FIQIH 	=> Constant::CATEGORY_FIQIH_NAME,
				Constant::CATEGORY_ZAKAT => Constant::CATEGORY_ZAKAT_NAME,
				Constant::CATEGORY_INFAQ => Constant::CATEGORY_INFAQ_NAME,
				Constant::CATEGORY_SEDEKAH => Constant::CATEGORY_SEDEKAH_NAME,
				Constant::CATEGORY_QURBAN => Constant::CATEGORY_QURBAN_NAME];
	}

	public static function getPrograms()
	{
		return [Constant::CATEGORY_ZAKAT => Constant::CATEGORY_ZAKAT_NAME,
				Constant::CATEGORY_INFAQ => Constant::CATEGORY_INFAQ_NAME,
				Constant::CATEGORY_SEDEKAH => Constant::CATEGORY_SEDEKAH_NAME,
				Constant::CATEGORY_QURBAN => Constant::CATEGORY_QURBAN_NAME];
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

	public static function getDayOptions()
    {
        return [
            'monday'    => [
				'id'	=> 'monday',
                'name'  => 'Senin',
                'bit'   => pow(2, 0)],
			'tuesday'    => [
				'id'	=> 'tuesday',
				'name'  => 'Selasa',
				'bit'   => pow(2, 1)],
			'wednesday'   => [
				'id'	=> 'wednesday',
				'name'  => 'Rabu',
				'bit'   => pow(2, 2)],
			'thursday'   => [
				'id'	=> 'thursday',
				'name'  => 'Kamis',
				'bit'   => pow(2, 3)],
			'friday'   => [
				'id'	=> 'friday',
				'name'  => 'Jumat',
				'bit'   => pow(2, 4)],
			'saturday'   => [
				'id'	=> 'saturday',
				'name'  => 'Sabtu',
				'bit'   => pow(2, 5)],
			'sunday'   => [
				'id'	=> 'sunday',
				'name'  => 'Minggu',
				'bit'   => pow(2, 6)]];
    }

	public static function getBitOptionsFromValue($bit_options, $value)
    {
        $selected_options = array();

        foreach ($bit_options as $key=>$option)
        {
            if (($value & $option['bit']) === $option['bit'])
                $selected_options[$key] = $option;
        }

        return $selected_options;
    }

	public static function getBitOptionValueFormIds($bit_options, $ids = [])
    {
        $value = 0;
        
        foreach ($ids as $id)
        {
            if (array_key_exists($id, $bit_options))
                $value = $value | $bit_options[$id]['bit'];
        }
        
        return $value;
    }

	public static function getDayOptionsFromValue($value)
    {
        return self::getBitOptionsFromValue(self::getDayOptions(), $value);
    }
    
    public static function getDayValueFromCheckOptionIds($ids = [])
    {
        return self::getBitOptionValueFormIds(self::getDayOptions(), $ids);
    }

	public static function trimWord($text, $maxLength = 100, $suffix = '...') {
		if (strlen($text) <= $maxLength) {
			return $text;
		}
	
		$trimmedText = substr($text, 0, $maxLength);
		$lastSpaceIndex = strrpos($trimmedText, ' ');
	
		if ($lastSpaceIndex !== false) {
			$trimmedText = substr($trimmedText, 0, $lastSpaceIndex);
		}
	
		return $trimmedText . $suffix;
	}

	public static function formatAmount($unit_name, $amount) {
		if ($unit_name != Constant::UNIT_NAME_RUPIAH)
		{
			return $amount . ' ' . $unit_name;
		}
		$formattedAmount = number_format($amount, 0, ',', '.');
		return 'Rp ' . $formattedAmount;
	}

	public static function getDatesFromRange($start, $end, $format='Y-m-d') {
		return array_map(function($timestamp) use($format) {
			return date($format, $timestamp);
		},
		range(strtotime($start) + ($start < $end ? 4000 : 8000), strtotime($end) + ($start < $end ? 8000 : 4000), 86400));
	}
		
}