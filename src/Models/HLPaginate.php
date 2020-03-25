<?php

namespace MashinaMashina\CI4HLPager\Models;

use MashinaMashina\CI4HLPager\Pager\HLPager;
use Config\Services;

trait HLPaginate {

	public function HLpaginate(int $perPage = 20, string $group = 'default', int $page = 0)
	{
		$config = new \Config\Pager();
		$view = Services::renderer();
		
		$pager = HLPager($config, $view);
		
		if ($pager->getCurrentPageString($group) === 'last')
		{
			$total = $this->countAllResults(false);
			
			$page = ceil($total / $perPage);
		}
		else
		{
			$page = $pager->getCurrentPage($group);
		}
		
		$offset = ($page - 1) * $perPage;

		$results = $this->findAll(($perPage + 1), $offset);
		
		$this->pager = $pager->store($group, $page, $perPage, $offset + count($results));

		return array_slice($results, 0, $perPage);
	}

}
