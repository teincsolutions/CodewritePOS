<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Shield\Authorization\Groups;

class SettingController extends BaseController
{
    public function general()
    {
        $data = [
            'title' => 'General Settings',
        ];
        return view('pages/settings/general', $data);
    }

    public function group_permissions($key = null)
    {
        if ($key === null) return;

        $groups = new Groups();
        $group = $groups->info($key);

        $data = [
            'title' => "Edit $group->title Permissions",
            'group' => $group,
        ];
        return view('pages/settings/group_permissions', $data);
    }

    /**
     * return json for savePermissions
     * @return Response - http response
     */
    public function save_permissions()
    {
        $inputs = $this->request->getVar();
        $groups = new Groups();
        $group = $groups->info($inputs['group']);

        if (!$group) return $this->response->setJSON(
            [
                'status' => false,
                'message' => "No Group Selected!",
                'input' => $inputs,
            ]
        );

        if (isset($inputs['permissions'])) {
            $group->setPermissions($inputs['permissions']);
            return $this->response->setJSON(
                [
                    'status' => true,
                    'data' => $groups->info($inputs['group']),
                    'message' => "Permission Saved Successfully!",
                    'input' => $inputs,
                ]
            );
        } else {
            return $this->response->setJSON(
                [
                    'status' => false,
                    'message' => "No Permissions Selected!",
                    'input' => $inputs,
                ]
            );
        }
    }

    /**
     * return json for savePermissions
     * @return Response - http response
     */
    public function save_settings()
    {
        $inputs = $this->request->getVar();
        foreach ($inputs as $key => $val)
            setting("App.$key", $val);


        return $this->response->setJSON([
            'status' => true,
            'message' => "Permission Saved Successfully!",
            'input' => $inputs,
        ]);
    }

    /**
     * return json for savePermissions
     * @return Response - http response
     */
    public function save_group()
    {
        $inputs = $this->request->getVar();
        $alias = strtolower(str_replace(' ', '-', $inputs['title']));
        $group = [$alias => ['title' => $inputs['title'], 'description' => $inputs['description']],];
        $groups = setting('AuthGroups.groups');
        $groups[$alias] = $inputs;
        setting('AuthGroups.groups', $groups);

        $groups = new Groups();
        $group  = $groups->info($alias);
        if ($group) {
            return $this->response->setJSON(
                [
                    'status' => true,
                    'data' => $group,
                    'message' => "Group Created Successfully!",
                    'input' => $inputs,
                ]
            );
        } else {
            return $this->response->setJSON(
                [
                    'status' => false,
                    'message' => "No Permissions Selected!",
                    'input' => $inputs,
                ]
            );
        }
    }

    public function groups()
    {
        $data = [
            'title' => 'Group Permissions',
        ];
        return view('pages/settings/groups', $data);
    }

    public function delete_group(string $group)
    {
        $notAllowed = array_merge(setting('AuthGroups.disabledGroup'), [setting('AuthGroups.defaultGroup')]);

        if (in_array($group, $notAllowed))
            return $this->response->setJSON([
                'status' => false,
                'message' => "Don't have Permission to Delete this Group!",
            ]);

        $groups =  setting('AuthGroups.groups');
        unset($groups[$group]);
        setting('AuthGroups.groups', $groups);

        return $this->response->setJSON([
            'status' => true,
            'message' => "Group deleted successfully!",
        ]);
    }
}
