<?php

namespace App\RolePermission;

class RolePermission
{

    public static $ADMIN = "admin";
    public static $INSTITUTE = 'institute';
    public static $TEACHER = 'teacher';
    public static $STUDENT = 'student';

    private static function adminPermissions()
    {
        return (object) [
            'roles' => [
                 [
                    'title' => RolePermission::$ADMIN,
                    'value' => RolePermission::$ADMIN,
                ],
                 [
                    'title' => RolePermission::$INSTITUTE,
                    'value' => RolePermission::$INSTITUTE,
                ],
                 [
                    'title' => RolePermission::$TEACHER,
                    'value' => RolePermission::$TEACHER,
                ],
                 [
                    'title' => RolePermission::$STUDENT,
                    'value' => RolePermission::$STUDENT,
                ],
            ],
            'role' => RolePermission::$ADMIN,
            'permissions' => (object) [
                'category' => (object) ['can_view' => false, 'can_add' => false, 'can_edit' => false, 'can_delete' => false],
                'dashboard' => (object) ['can_view' => true, 'can_add' => false, 'can_edit' => false, 'can_delete' => false],
            ],
        ];
    }

    private static function institutePermissions()
    {
        return (object) [
            'roles' => [
                 [
                    'title' => RolePermission::$INSTITUTE,
                    'value' => RolePermission::$INSTITUTE,
                ],
                 [
                    'title' => RolePermission::$TEACHER,
                    'value' => RolePermission::$TEACHER,
                ],
            ],
            'role' => RolePermission::$INSTITUTE,
            'permissions' => (object) [
                'category' => (object) ['can_view' => true, 'can_add' => true, 'can_edit' => false, 'can_delete' => false],
                'dashboard' => (object) ['can_view' => true, 'can_add' => true, 'can_edit' => false, 'can_delete' => false],
            ],
        ];
    }

    private static function teacherPermissions()
    {
        return (object) [
            'roles' => [
                [
                    'title' => RolePermission::$TEACHER,
                    'value' => RolePermission::$TEACHER,
                ],
            ],
            'role' => RolePermission::$TEACHER,
            'permissions' => (object) [
                'category' => (object) ['can_view' => true, 'can_add' => true, 'can_edit' => false, 'can_delete' => false],
                'dashboard' => (object) ['can_view' => true, 'can_add' => true, 'can_edit' => false, 'can_delete' => false],
            ],
        ];
    }

    private static function studentPermissions()
    {
        return (object) [
            'roles' => [],
            'role' => RolePermission::$STUDENT,
            'permissions' => (object) [
                'category' => (object) ['can_view' => true, 'can_add' => true, 'can_edit' => false, 'can_delete' => false],
                'dashboard' => (object) ['can_view' => true, 'can_add' => true, 'can_edit' => false, 'can_delete' => false],
            ],
        ];
    }

    public static function Permission($role)
    {
        switch ($role) {
            case (RolePermission::$ADMIN):
                return RolePermission::adminPermissions();

            case (RolePermission::$INSTITUTE):
                return RolePermission::institutePermissions();

            case (RolePermission::$TEACHER):
                return RolePermission::teacherPermissions();

            case (RolePermission::$STUDENT):
                return RolePermission::studentPermissions();

            default:
                return RolePermission::studentPermissions();
        }
    }
}
