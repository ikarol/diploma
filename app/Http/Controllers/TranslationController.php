<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;

class TranslationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
                'no_tasks' => __('You haven\'t published any tasks yet'),
                'update_task' => __('Update task'),
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
                'new_task' => __('New task'),
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
                'professor' => __('Professor'),
                'created_at' => __('Publication date'),
                'actions' => __('Actions'),
                'no_tasks' => __('There are no tasks for this group'),
                'request' => __('Request'),
                'message' => __('Message'),
                'empty' => __('Empty'),
            ],
            'buttons' => [
                'apply' => __('Apply'),
                'cancel' => __('Cancel'),
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
}
