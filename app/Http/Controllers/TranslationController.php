<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;

class TranslationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except([
            'admin_groups'
        ]);
        $this->middleware('ajax');
    }
    public function professor_diplomas_list()
    {
        $translations = [
            'labels' => [
                'group' => __('Group'),
                'topic' => __('Topic'),
                'description' => __('Description'),
                'technologies' => __('Technologies'),
                'number_of_requests' => __('Number of requests'),
                'created_at' => __('Publication date'),
                'updated_at' => __('Date of update'),
                'actions' => __('Actions'),
                'no_tasks' => __('You haven\'t published any projects yet'),
                'no_groups' => __('There are no any groups yet'),
                'update_project' => __('Update project'),
                'accepted' => __('Accepted'),
                'pending' => __('Pending'),
                'declined' => __('Declined'),
                'student' => __('Student'),
                'empty' => __('Empty'),
            ],
            'buttons' => [
                'edit' => __('Edit'),
                'delete' => __('Delete'),
                'cancel' => __('Cancel'),
                'publish' => __('Publish'),
                'update' => __('Update'),
                'new_project' => __('New project'),
            ]
        ];
        return Response::json([
            'translations' =>$translations
        ]);
    }

    public function student_diplomas_list()
    {
        $translations = [
            'labels' => [
                'group' => __('Group'),
                'topic' => __('Topic'),
                'technologies' => __('Technologies'),
                'description' => __('Description'),
                'professor' => __('Professor'),
                'created_at' => __('Publication date'),
                'actions' => __('Actions'),
                'no_tasks' => __('There are no projects for this group'),
                'request' => __('Request'),
                'message' => __('Message'),
                'empty' => __('Empty'),
                'status' => __('Status'),
                'accepted' => __('Accepted'),
                'pending' => __('Pending'),
                'declined' => __('Declined'),
                'delete_request' => __('Delete request on'),
            ],
            'buttons' => [
                'apply' => __('Apply'),
                'cancel' => __('Cancel'),
                'resend_request' => __('Resend request'),
                'show_tasks' => __('Show tasks'),
                'delete_request' => __('Delete request'),
            ]
        ];
        return Response::json([
            'translations' =>$translations
        ]);
    }

    public function professor_requests_list()
    {
        $translations = [
            'labels' => [
                'group' => __('Group'),
                'topic' => __('Topic'),
                'student' => __('Student'),
                'status' => __('Status'),
                'created_at' => __('Published'),
                'actions' => __('Actions'),
                'no_requests' => __('There are no requests'),
                'accepted' => __('Accepted'),
                'pending' => __('Pending'),
                'declined' => __('Declined'),
                'message' => __('Message'),
                'empty' => __('Empty'),
            ],
            'buttons' => [
                'accept' => __('Accept'),
                'decline' => __('Decline'),
                'accepted' => __('Accepted'),
                'declined' => __('Declined'),
                'pending' => __('Pending'),
                'all' => __('All'),
            ]
        ];
        return Response::json([
            'translations' =>$translations
        ]);
    }

    public function professor_diploma_jobs()
    {
        $translations = [
            'labels' => [
                'description' => __('Description'),
                'created_at' => __('Assigned'),
                'deadline' => __('Deadline'),
                'actions' => __('Actions'),
                'no_jobs' => __('There are no jobs for this project yet'),
                'update_job' => __('Update job'),
            ],
            'buttons' => [
                'edit' => __('Edit'),
                'delete' => __('Delete'),
                'new_job' => __('New job'),
                'publish' => __('Publish'),
                'cancel' => __('Cancel'),
                'update' => __('Update'),
            ]
        ];
        return Response::json([
            'translations' =>$translations
        ]);
    }

    public function professor_courseProjects_list()
    {
        $translations = [
            'labels' => [
                'group' => __('Group'),
                'topic' => __('Topic'),
                'description' => __('Description'),
                'technologies' => __('Technologies'),
                'number_of_requests' => __('Number of requests'),
                'created_at' => __('Publication date'),
                'updated_at' => __('Date of update'),
                'actions' => __('Actions'),
                'no_tasks' => __('You haven\'t published any projects yet'),
                'no_groups' => __('There are no any groups yet'),
                'update_project' => __('Update project'),
                'accepted' => __('Accepted'),
                'pending' => __('Pending'),
                'declined' => __('Declined'),
                'student' => __('Student'),
                'empty' => __('Empty'),
                'disciplines' => __('Disciplines'),
                'select_discipline' => __('Select disciplines'),
            ],
            'buttons' => [
                'edit' => __('Edit'),
                'delete' => __('Delete'),
                'cancel' => __('Cancel'),
                'publish' => __('Publish'),
                'update' => __('Update'),
                'new_project' => __('New project'),
            ]
        ];
        return Response::json([
            'translations' =>$translations
        ]);
    }

    public function student_courseProjects_list()
    {
        $translations = [
            'labels' => [
                'group' => __('Group'),
                'topic' => __('Topic'),
                'technologies' => __('Technologies'),
                'description' => __('Description'),
                'professor' => __('Professor'),
                'created_at' => __('Publication date'),
                'actions' => __('Actions'),
                'no_tasks' => __('There are no projects for this group'),
                'request' => __('Request'),
                'message' => __('Message'),
                'empty' => __('Empty'),
                'status' => __('Status'),
                'accepted' => __('Accepted'),
                'pending' => __('Pending'),
                'declined' => __('Declined'),
                'delete_request' => __('Delete request on'),
                'disciplines' => __('Disciplines'),
                'select_discipline' => __('Select disciplines'),
            ],
            'buttons' => [
                'apply' => __('Apply'),
                'cancel' => __('Cancel'),
                'resend_request' => __('Resend request'),
                'show_tasks' => __('Show tasks'),
                'delete_request' => __('Delete request'),
            ]
        ];
        return Response::json([
            'translations' =>$translations
        ]);
    }


    public function admin_groups()
    {
        $translations = [
            'labels' => [
                'group' => __('Group'),
                'actions' => __('Actions'),
                'no_groups' => __('There are no any groups yet'),
                'update_group' => __('Update group'),
                'new_group' => __('New group'),
            ],
            'buttons' => [
                'edit' => __('Edit'),
                'delete' => __('Delete'),
                'cancel' => __('Cancel'),
                'publish' => __('Publish'),
                'update' => __('Update'),
                'create_group' => __('Create group'),
            ]
        ];
        return Response::json([
            'translations' =>$translations
        ]);
    }

    public function admin_disciplines()
    {
        $translations = [
            'labels' => [
                'discipline' => __('Discipline'),
                'actions' => __('Actions'),
                'no_disciplines' => __('There are no any disciplines yet'),
                'update_discipline' => __('Update discipline'),
                'new_discipline' => __('New discipline'),
            ],
            'buttons' => [
                'edit' => __('Edit'),
                'delete' => __('Delete'),
                'cancel' => __('Cancel'),
                'publish' => __('Publish'),
                'update' => __('Update'),
                'create_discipline' => __('Create discipline'),
            ]
        ];
        return Response::json([
            'translations' =>$translations
        ]);
    }
}
