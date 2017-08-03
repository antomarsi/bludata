<?php

namespace App\Filters;

class UserFilter extends QueryFilter
{
    public function name($value = null)
    {
        return $this->builder->where('name', 'like', "%$value%");
    }
    public function birth($value = null)
    {
        return $this->builder->where('birth', 'like', "%$value%");
    }
    public function created_at($value = null)
    {
        return $this->builder->where('created_at', 'like', "%$value%");
    }

    public function sortBy($sortBy)
    {
        list($column, $order)  = explode(',', $sortBy);

        if ($column) {
            return $this->builder->orderBy($column, $order);
        }
    }
}
