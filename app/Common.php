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

use CodeIgniter\Database\MySQLi\Builder;
use CodeIgniter\Model;
use CodeIgniter\Database\RawSql;


if (!function_exists('toDatatableResult')) {

    function toDatatableResult(Model $model, array $inputs = null, $joins = null, $callback = null)
    {
        if ($joins)
            foreach ($joins as $key => $join)
                $model->join($join['table'], $join['cond'], $join['type'] ?? '', null);

        $total = $model->countAllResults(false);

        if (isset($inputs['date_from']) || isset($inputs['date_to'])) {
            if (!empty($inputs['date_from']) || !empty($inputs['date_to'])) {
                $model->groupStart();
                $model->where(new RawSql("DATE(" . $inputs['date_range_column'] . ")" . ' >='), date('Y-m-d', strtotime($inputs['date_from'])));
                $model->where(new RawSql("DATE(" . $inputs['date_range_column'] . ")" . ' <='),  date('Y-m-d', strtotime($inputs['date_to'])));
                $model->groupEnd();
            }
        }

        if (isset($inputs['fields'])) {
            foreach ($inputs['fields'] as $field => $val) {
                if (!empty(trim($val)) && in_array($field, ['type', 'user_id', 'payment_type']))
                    $model->where($field, $val);
                else if (!empty(trim($val))) $model->like($field, $val);
            }
        }

        if (isset($inputs['columns'])) {
            $model->groupStart();
            foreach ($inputs['columns'] as $col) {
                if (isset($col['searchable']) && $col['searchable'] && isset($col['name']) && $col['name'])
                    $model->orLike($col['name'], trim($inputs['search']['value']), 'both');
                else if (isset($col['name']) && $col['name'])   $model->orLike($col['name'], $inputs['search']['value'], 'both');;
            }
            $model->groupEnd();
        }
        if (isset($inputs['order'])) {
            foreach ($inputs['order'] as $order) {
                $model->orderBy($inputs['columns'][$order['column']]['name'], $order['dir']);
            }
        }
        $length = 10;
        $start = 0;
        $filtered = $model->countAllResults(false);

        if (isset($inputs['length']) && isset($inputs['start'])) {
            $length = intval($inputs['length']);
            $start = intval($inputs['start']);
            $data = $model->findAll($length, $start);
        } else {
            $data = $model->findAll();
        }

        if ($callback)
            foreach ($data as $key => $item)
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

if (!function_exists('toBuilderDatatableResult')) {

    function toBuilderDatatableResult(Builder $model, array $inputs = null, $callback = null)
    {
        $total = $model->countAllResults(false);

        if (isset($inputs['date_from']) || isset($inputs['date_to'])) {
            if (!empty($inputs['date_from']) || !empty($inputs['date_to'])) {
                $model->groupStart();
                $model->where(new RawSql("DATE(" . $inputs['date_range_column'] . ")" . ' >='), date('Y-m-d', strtotime($inputs['date_from'])));
                $model->where(new RawSql("DATE(" . $inputs['date_range_column'] . ")" . ' <='),  date('Y-m-d', strtotime($inputs['date_to'])));
                $model->groupEnd();
            }
        }

        if (isset($inputs['fields'])) {
            foreach ($inputs['fields'] as $field => $val) {
                if (!empty(trim($val)) && in_array($field, ['type', 'user_id', 'payment_type']))
                    $model->where($field, $val);
                else if (!empty(trim($val))) $model->like($field, $val);
            }
        }

        if (isset($inputs['columns'])) {
            $model->groupStart();
            foreach ($inputs['columns'] as $col) {
                if (isset($col['searchable']) && $col['searchable'] && isset($col['name']) && $col['name'])
                    $model->orLike($col['name'], trim($inputs['search']['value']), 'both');
                else if (isset($col['name']) && $col['name'])   $model->orLike($col['name'], $inputs['search']['value'], 'both');;
            }
            $model->groupEnd();
        }
        if (isset($inputs['order'])) {
            foreach ($inputs['order'] as $order) {
                $model->orderBy($inputs['columns'][$order['column']]['name'], $order['dir']);
            }
        }
        $length = 10;
        $start = 0;
        $filtered = $model->countAllResults(false);

        if (isset($inputs['length']) && isset($inputs['start'])) {
            $length = intval($inputs['length']);
            $start = intval($inputs['start']);
            $data = $model->get($length, $start)->getResult();
        } else {
            $data = $model->get()->getResult();
        }

        if ($callback)
            foreach ($data as $key => $item)
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

if (!function_exists('toSelect2Result')) {

    /**
     * @param Model $model
     * @param array $columns Columns to search for request
     * @param array $inputs Request input data
     * @param function $callback Callback to modify each result
     */
    function toSelect2Result(Model $model, array $columns, array $inputs, $select = "*", $joins = null): array
    {
        $term = isset($inputs['term']) ? $inputs['term'] : '';
        $take = 10;
        $page = isset($inputs['page']) ? $inputs['page'] : 1;
        $skip = ($page - 1) * $take;

        if (isset($inputs['filter']) && is_array($inputs['filter']))
            foreach ($inputs['filter'] as $field => $val)
                $model->where($field, $val);

        if ($joins)
            foreach ($joins as $key => $join)
                $model->join($join['table'], $join['cond'], $join['type'] ?? '', null);

        $total = $model->countAllResults(false);

        $model->select($select,false);
        $model->groupStart();
        foreach ($columns as $row) $model->orLike($row, $term);
        $model->groupEnd();
        $model->limit($take, $skip);

        if (isset($inputs['filter']) && is_array($inputs['filter']))
            foreach ($inputs['filter'] as $field => $val)
                $model->where($field, $val);

        $data = $model->findAll($take, $skip);

        return  [
            'results' => $data,
            'pagination' => [
                'more' => ($skip + $take < $total),
                'page' => intval($page),
                'totalRows' => $total,
                'totalPages' => intval($total / $take + ($total % $take > 0 ? 1 : 0)),
            ],
            'inputs' => $inputs,
        ];
    }
}

if (!function_exists('toSelect2BuilderResult')) {

    /**
     * @param Model $model
     * @param array $columns Columns to search for request
     * @param array $inputs Request input data
     * @param function $callback Callback to modify each result
     */
    function toSelect2BuilderResult(Builder $model, array $columns, array $inputs, $select = "*", $joins = null): array
    {
        $term = isset($inputs['term']) ? $inputs['term'] : '';
        $take = 10;
        $page = isset($inputs['page']) ? $inputs['page'] : 1;
        $skip = ($page - 1) * $take;

        if (isset($inputs['filter']) && is_array($inputs['filter']))
            foreach ($inputs['filter'] as $field => $val)
                $model->where($field, $val);

        if ($joins)
            foreach ($joins as $key => $join)
                $model->join($join['table'], $join['cond'], $join['type'] ?? '', null);

        $total = $model->countAllResults(false);

        $model->select($select,false);
        $model->groupStart();
        foreach ($columns as $row) $model->orLike($row, $term);
        $model->groupEnd();
        $model->limit($take, $skip);

        if (isset($inputs['filter']) && is_array($inputs['filter']))
            foreach ($inputs['filter'] as $field => $val)
                $model->where($field, $val);

        $data = $model->get()->getResult();

        return  [
            'results' => $data,
            'pagination' => [
                'more' => ($skip + $take < $total),
                'page' => intval($page),
                'totalRows' => $total,
                'totalPages' => intval($total / $take + ($total % $take > 0 ? 1 : 0)),
            ],
            'inputs' => $inputs,
        ];
    }
}

if (!function_exists('getActiveUrl')) {
    function getActiveUrl(string $match, $return = 'active')
    {
        return url_is($match) ? $return : null;
    }
}
