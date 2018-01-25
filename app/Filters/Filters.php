<?php

namespace App\Filters;

use Illuminate\Http\Request;

abstract class Filters
{
    protected $request;
    protected $builder;
    protected $filters = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply($builder)
    {
        $this->builder = $builder;
        
        foreach ($this->getFilters() as $filter => $value) {
            if (method_exists($this, $filter)) {
                $this->$filter($value);
            }
        }

        return $this->builder;

        // Usando functions
        // $this->getFilters()
        //     ->filter(function ($filter) {
        //         return method_exists($this, $filter);
        //     })
        //     ->each(function ($filter, $value) {
        //         $this->$filter($value);
        //     });
    }

    private function getFilters()
    {
        //return collect($this->request->only($this->filters))->flip();  // usando functions
        return $this->request->only($this->filters);
    }
}
