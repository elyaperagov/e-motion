<?php

namespace AZ;

class Language
{
	/**
	 * All language variables
	 *
	 * @return $data array of lang variables
	 */
	public static function getData()
	{
		$data['specification'] = __("Характеристики", EM_DOMAIN);
		$data['compare'] = __("Сравнить с версией ", EM_DOMAIN);
		$data['max_speed'] = __("Максимальная скорость", EM_DOMAIN);
		$data['range'] = __("Дистанция", EM_DOMAIN);
		$data['weight'] = __("Вес", EM_DOMAIN);
		$data['torque'] = __("Крутящий момент", EM_DOMAIN);
		$data['road_legal'] = __("Для дорог общего назначения", EM_DOMAIN);
		$data['category'] = __("Водительское удостоверение", EM_DOMAIN);
		$data['km_h'] = __("км/ч", EM_DOMAIN);
		$data['km'] = __("км", EM_DOMAIN);
		$data['kg'] = __("кг", EM_DOMAIN);
		$data['nm'] = __("нм", EM_DOMAIN);
		$data['cm3'] = __("cm3", EM_DOMAIN);
		$data['more'] = __("Подробнее", EM_DOMAIN);
		$data['read_more'] = __("Подробнее", EM_DOMAIN);
		$data['contact'] = __("Связаться", EM_DOMAIN);
		$data['legal'] = __("Правовая информация", EM_DOMAIN);
		$data['blog'] = __("Новости", EM_DOMAIN);
		$data['no_posts'] = __("Пока нет новостей", EM_DOMAIN);
		$data['order'] = __("Оставить заявку", EM_DOMAIN);
		$data['cost'] = __("Стоимость", EM_DOMAIN);

		return $data;
	}
}
