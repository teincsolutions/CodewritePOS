<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter.com/user_guide/extending/common.html
 */

use CodeIgniter\Model;
use CodeIgniter\Database\RawSql;


if (!function_exists('toDatatableResult')) {

    function toDatatableResult(Model $model, array $inputs = null, $callback=null): array
    {
        $total = $model->countAllResults();
        if (isset($inputs['date_from']) || isset($inputs['date_to'])) {
            if (!empty($inputs['date_from']) || !empty($inputs['date_to'])) {
                $model->groupStart();
                $model->where(new RawSql("DATE(" . $inputs['date_range_column'] . ")" . ' >='), $inputs['date_from']);
                $model->where(new RawSql("DATE(" . $inputs['date_range_column'] . ")" . ' <='), $inputs['date_to']);
                $model->groupEnd();
            }
        }

        if (isset($inputs['fields'])) {
            $model->like($inputs['fields']);
        }

        if (isset($inputs['columns'])) {
            $model->groupStart();
            foreach ($inputs['columns'] as $col) {
                if (isset($col['searchable']) && $col['searchable'] && isset($col['name']) && $col['name'])
                    $model->orLike($col['name'], $inputs['search']['value'], 'both');
                else if (isset($col['name']) && $col['name'])   $model->orLike($col['name'], $inputs['search']['value'], 'both');;
            }
            $model->groupEnd();
        }
        if (isset($inputs['order'])) {
            foreach ($inputs['order'] as $order) {
                $model->orderBy($inputs['columns'][$order['column']]['name'], $order['dir']);
            }
        }

        $data = $model->findAll();
        $filtered = sizeof($data);
        if($callback)
            foreach($data as $key => $item)
                $data[$key] = $callback($item);
        return  [
            'draw' => isset($inputs['draw']) ? $inputs['draw'] : 1,
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $data,
            'inputs' => $inputs,
        ];
    }
}

if (!function_exists('getActiveUrl')) {
    function getActiveUrl(string $match, $return = 'active'): mixed
    {
        return url_is($match) ? $return : null;
    }
}
