<?php

namespace MashinaMashina\CI4HLPager\Pager;

use CodeIgniter\Pager\Exceptions\PagerException;
use CodeIgniter\Pager\PagerInterface;
use CodeIgniter\Pager\Pager;
use App\Libraries\HLPager\HLPagerRenderer;

/**
 * Class Pager
 *
 * The Pager class provides semi-automatic and manual methods for creating
 * pagination links and reading the current url's query variable, "page"
 * to determine the current page. This class can support multiple
 * paginations on a single page.
 *
 * @package CodeIgniter\Pager
 */
class HLPager extends Pager implements PagerInterface
{
	public function getCurrentPageString(string $group = 'default')
	{
		$this->ensureGroup($group);
		
		return $this->groups[$group]['currentPage'];
	}
	
	public function links(string $group = 'default', string $template = 'default_highload'): string
	{
		$this->ensureGroup($group);

		return $this->displayLinks($group, $template);
	}
	
	protected function displayLinks(string $group, string $template): string
	{
		$pager = new HLPagerRenderer($this->getDetails($group));

		if (! array_key_exists($template, $this->config->templates))
		{
			throw PagerException::forInvalidTemplate($template);
		}

		return $this->view->setVar('pager', $pager)
						->render($this->config->templates[$template]);
	}
	
	protected function calculateCurrentPage(string $group)
	{
		if (array_key_exists($group, $this->segment))
		{
			try
			{
				$this->groups[$group]['currentPage'] = $this->groups[$group]['uri']->getSegment($this->segment[$group]);
			}
			catch (\CodeIgniter\HTTP\Exceptions\HTTPException $e)
			{
				$this->groups[$group]['currentPage'] = 1;
			}
		}
		else
		{
			$pageSelector = $this->groups[$group]['pageSelector'];

			$page = ($_GET[$pageSelector] ?? 1);
			
			if ($page === 'last')
			{
				$this->groups[$group]['currentPage'] = 'last';
			}
			else
			{
				$page = (int) $page;
				$this->groups[$group]['currentPage'] = $page < 1 ? 1 : $page;
			}
		}
	}
}
