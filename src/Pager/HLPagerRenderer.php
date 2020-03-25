<?php

namespace MashinaMashina\CI4HLPager\Pager;

use CodeIgniter\Pager\PagerRenderer;

class HLPagerRenderer extends PagerRenderer
{
	protected $perPage;
	
	public function __construct(array $details)
	{
		$this->perPage   = $details['perPage'];
		
		parent::__construct($details);
	}
	
	public function hasNext(): bool
	{
		return ($this->current * $this->perPage) < $this->total;
	}
	
	public function getLast(): string
	{
		$uri = clone $this->uri;

		if ($this->segment === 0)
		{
			$uri->addQuery($this->pageSelector, 'last');
		}
		else
		{
			$uri->setSegment($this->segment, 'last');
		}

		return (string) $uri;
	}
}