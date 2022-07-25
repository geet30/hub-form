<?php

namespace App\Services;

use App\Models\{
  Action as ActionModel,
  Evidence as EvidenceModel,
  ActionDocument as ActionDocumentModel,
  ShareTemplate as ShareTemplateModel,
};
use Carbon\Carbon;
use Auth;
use Illuminate\Http\Request;

class Action
{

  /**
   * get LegalReminder
   * @return collection
   */
  public function getListing($id = null, $status = null, $request)
  {
    $conditions[] = ['user_id', $id];
    if (!empty($status)) {
      array_push($conditions, ['status', $status]);
    }
    if (request()->has('r')) {
      array_push($conditions, ['title', 'like', '%' . request()->r . '%']);
    }
    if (request()->has('location_id') && request()->location_id !== null) {
      array_push($conditions, ['location_id', '=',  request()->location_id]);
    }
    if (request()->has('asignee_id') && request()->asignee_id !== null) {
      array_push($conditions, ['assined_user_id', '=', request()->asignee_id]);
    }
    if (request()->has('priority') && request()->priority !== null) {
      array_push($conditions, ['priority', '=', request()->priority]);
    }
    if (request()->has('status') && request()->status !== null) {
      array_push($conditions, ['status', '=', request()->status]);
    }
    if (!empty(request()->due_date) && request()->due_date == 1) {
      $listing = ActionModel::with(
        array(
          'completedForm' => function ($query) {
            $query->select('id', 'form_id');
          },
          'questions' => function ($query) {
            $query->select('id', 'section_id', 'text', 'question_type', 'type_option');
          },
          // 'assignee_user', 'user'
        )
      )->where($conditions)->orderBy('due_date', 'asc')->paginate(10);
    } else {
      $listing = ActionModel::with(
        array(
          'completedForm' => function ($query) {
            $query->select('id', 'form_id');
          },
          'questions' => function ($query) {
            $query->select('id', 'section_id', 'text', 'question_type', 'type_option');
          },
          // 'assignee_user', 'user'
        )
      )->where($conditions)->orderBy('id', 'desc')->paginate(10);
    }
    return $listing;
  }


  public function getListingByRole($id = null, $status = null, $request)
  {
    $conditions[] = ['i_ref_user_role_id', $id];
    if (!empty($status)) {
      array_push($conditions, ['status', $status]);
    }
    if (request()->has('r')) {
      array_push($conditions, ['title', 'like', '%' . request()->r . '%']);
    }
    if (request()->has('location_id') && request()->location_id !== null) {
      array_push($conditions, ['location_id', '=',  request()->location_id]);
    }
    if (request()->has('asignee_id') && request()->asignee_id !== null) {
      array_push($conditions, ['assined_user_id', '=', request()->asignee_id]);
    }
    if (request()->has('priority') && request()->priority !== null) {
      array_push($conditions, ['priority', '=', request()->priority]);
    }
    if (request()->has('status') && request()->status !== null) {
      array_push($conditions, ['status', '=', request()->status]);
    }
    if (!empty(request()->due_date) && request()->due_date == 1) {
      $listing = ActionModel::with(
        array(
          'completedForm' => function ($query) {
            $query->select('id', 'form_id');
          },
          'questions' => function ($query) {
            $query->select('id', 'section_id', 'text', 'question_type', 'type_option');
          },
          // 'assignee_user', 'user'
        )
      )->where($conditions)->orderBy('due_date', 'asc')->paginate(10);
    } else {
      $listing = ActionModel::with(
        array(
          'completedForm' => function ($query) {
            $query->select('id', 'form_id');
          },
          'questions' => function ($query) {
            $query->select('id', 'section_id', 'text', 'question_type', 'type_option');
          },
          // 'assignee_user', 'user'
        )
      )->where($conditions)->orderBy('id', 'desc')->paginate(10);
    }
    return $listing;
  }

  public function actionsAssignedToCurrntUser($id = null)
  {
    $conditions[] = ['assined_user_id', $id];
    // array_push($conditions,['status','!=', 1]);
    array_push($conditions, ['status', '!=', 5]);
    if (!empty($status)) {
      array_push($conditions, ['status', $status]);
    }
    if (request()->has('r')) {
      array_push($conditions, ['title', 'like', '%' . request()->r . '%']);
    }
    if (request()->has('location_id') && request()->location_id !== null) {
      array_push($conditions, ['location_id', '=',  request()->location_id]);
    }
    if (request()->has('asignee_id') && request()->asignee_id !== null) {
      array_push($conditions, ['assined_user_id', '=', request()->asignee_id]);
    }
    if (request()->has('priority') && request()->priority !== null) {
      array_push($conditions, ['priority', '=', request()->priority]);
    }
    if (request()->has('status') && request()->status !== null) {
      array_push($conditions, ['status', '=', request()->status]);
    }

    if (!empty(request()->due_date) && request()->due_date == 1) {
      $listing = ActionModel::with(
        array(
          'completedForm' => function ($query) {
            $query->select('id', 'form_id','status','template_id');
          },
          'questions' => function ($query) {
            $query->select('id', 'section_id', 'text', 'question_type', 'type_option');
          },
          // 'assignee_user', 'user'
        )
      )->where($conditions)->orderBy('due_date', 'asc')->paginate(10);
    } else {
      $listing = ActionModel::with(
        array(
          'completedForm' => function ($query) {
            $query->select('id', 'form_id','status','template_id');
          },
          'questions' => function ($query) {
            $query->select('id', 'section_id', 'text', 'question_type', 'type_option');
          },
          // 'assignee_user', 'user'
        )
      )->where($conditions)->orderBy('id', 'desc')->paginate(10);
    }
    return $listing;
  }



  public function actionsAssignedToCurrntUserByRole($id = null)
  {
    $conditions[] = ['i_ref_assined_role_id', $id];
    // array_push($conditions,['status','!=', 1]);
    array_push($conditions, ['status', '!=', 5]);
    if (!empty($status)) {
      array_push($conditions, ['status', $status]);
    }
    if (request()->has('r')) {
      array_push($conditions, ['title', 'like', '%' . request()->r . '%']);
    }
    if (request()->has('location_id') && request()->location_id !== null) {
      array_push($conditions, ['location_id', '=',  request()->location_id]);
    }
    if (request()->has('asignee_id') && request()->asignee_id !== null) {
      array_push($conditions, ['assined_user_id', '=', request()->asignee_id]);
    }
    if (request()->has('priority') && request()->priority !== null) {
      array_push($conditions, ['priority', '=', request()->priority]);
    }
    if (request()->has('status') && request()->status !== null) {
      array_push($conditions, ['status', '=', request()->status]);
    }

    if (!empty(request()->due_date) && request()->due_date == 1) {
      $listing = ActionModel::with(
        array(
          'completedForm' => function ($query) {
            $query->select('id', 'form_id','status','template_id');
          },
          'questions' => function ($query) {
            $query->select('id', 'section_id', 'text', 'question_type', 'type_option');
          },
          // 'assignee_user', 'user'
        )
      )->where($conditions)->orderBy('due_date', 'asc')->paginate(10);
    } else {
      $listing = ActionModel::with(
        array(
          'completedForm' => function ($query) {
            $query->select('id', 'form_id','status','template_id');
          },
          'questions' => function ($query) {
            $query->select('id', 'section_id', 'text', 'question_type', 'type_option');
          },
          // 'assignee_user', 'user'
          
        )
      )->where($conditions)->orderBy('id', 'desc')->paginate(10);
    }
    return $listing;
  }



  public function getDetail($id)
  {
    $details = ActionModel::with([
      'completedForm', 'questions',
      // 'assignee_user' => function ($query) {
      //   $query->select('id', 'vc_fname', 'vc_mname', 'vc_lname');
      // },
      'ActionDocuments' => function ($query) {
        $query->select('id', 'action_id', 'file_name', 'file_type');
      },
      // 'user' => function ($query) {
      //   $query->select('id', 'vc_fname', 'vc_mname', 'vc_lname');
      // },
      'evidences', 
      // 'close_by' => function ($query) {
      //   $query->select('id', 'vc_fname', 'vc_mname', 'vc_lname');
      // },
      'business_unit' => function ($query) {
        $query->select('id', 'vc_short_name');
      },
      'department' => function ($query) {
        $query->select('id', 'vc_name');
      },
      'project' => function ($query) {
        $query->select('id', 'vc_name');
      }
    ])->where('id', $id)->first();
    return $details;
  }

  public function add_action($request)
  {
    $old_actions = ActionModel::withTrashed()->count();
    $nextAction = (int) $old_actions + 1;
    $action = $request->only([
      'completed_form_id', 'section_id', 'question_id', 'question_id',
      'title', 'descriptions', 'user_id', 'assined_user_id', 'business_unit_id',
      'department_id', 'project_id', 'location_id', 'reocurring_actions', 'status',
      'priority', 'due_date'
    ]);
    $action['action_id'] = 'A00' . $nextAction;
    $actionRow = ActionModel::create($action);

    $picture = $request->file('information');

    if (!empty($picture)) {

      $savePicture = [];

      for ($i = 0; $i < count($picture); $i++) {

        $filename = $this->nameToUnique($picture[$i]->getClientOriginalName());

        $destinationPath = 'information/';


        if ($picture[$i]->move($destinationPath, $filename)) {

          $file_type = $picture[$i]->getClientMimeType();

          $document_type = '';
          if (str_contains($file_type, 'image')) {
            $document_type = ActionDocumentModel::TYPE_IMAGE;
          } else if (str_contains($file_type, 'audio')) {
            $document_type = ActionDocumentModel::TYPE_AUDIO;
          } else if (str_contains($file_type, 'pdf')) {
            $document_type = ActionDocumentModel::TYPE_PDF;
          } else if (str_contains($file_type, 'video')) {
            $document_type = ActionDocumentModel::TYPE_VIDEO;
          } else {
            $document_type = ActionDocumentModel::TYPE_DOCUMENT;
          }

          $fileRow = [
            "file_name" => ($filename) ? $filename : null,
            "file_type" => ($document_type) ? $document_type : null,
          ];
          array_push($savePicture, $fileRow);
        }
      }

      $actionRow->ActionDocuments()->createMany($savePicture);
    }
    if ($actionRow) {
      return ["message" => "saved", "action_id" => $actionRow->id, "assined_user_id" => $actionRow->assined_user_id,'i_ref_assined_role_id'=>$actionRow->i_ref_assined_role_id];
    }
  }

  /**
   * @var ImageNameUnique
   */
  protected function nameToUnique($fileName, $limit = 15)
  {
    $extension = pathinfo($fileName, PATHINFO_EXTENSION);
    return \Str::random($limit) . '.' . $extension;
  }

  public function close_action($request)
  {
    $actions_id =  $request->actions_id;
    if (!empty($actions_id)) {
        
      $user=CheckUserTypeAndGetRoleID($request->closed_by);

      $action = ActionModel::where('id', $actions_id)
        ->update([
          'status' => $request->status,
          'close_date' => $request->close_date,
          'closed_by' => $request->closed_by,
          'comments' => $request->comment,
          'i_ref_closed_by_role_id'=>$user['role_id'],
        ]);

      if ($action == 1) {
        $picture = $request->file('evidence');
        if (!empty($picture)) {

          for ($i = 0; $i < count($picture); $i++) {

            $filename = $this->nameToUnique($picture[$i]->getClientOriginalName());
            // $filename = (base64_encode(openssl_random_pseudo_bytes(30))).'.'.$picture->getClientOriginalExtension();// RANDOM NAME

            $destinationPath = 'evidences/';


            if ($picture[$i]->move($destinationPath, $filename)) {

              $file_type = $picture[$i]->getClientMimeType();

              $evidence_type = '';
              if (str_contains($file_type, 'image')) {
                $evidence_type = EvidenceModel::TYPE_IMAGE;
              } else if (str_contains($file_type, 'audio')) {
                $evidence_type = EvidenceModel::TYPE_AUDIO;
              } else if (str_contains($file_type, 'pdf')) {
                $evidence_type = EvidenceModel::TYPE_PDF;
              } else if (str_contains($file_type, 'video')) {
                $evidence_type = EvidenceModel::TYPE_VIDEO;
              } else {
                $evidence_type = EvidenceModel::TYPE_DOCUMENT;
              }
              $Evidence = new EvidenceModel();
              $Evidence->action_id =  $request->actions_id;
              $Evidence->file_name = $filename;
              $Evidence->file_type = !empty($evidence_type) ? $evidence_type : null;

              $Evidence->save();
            }
          }
        }
        return "saved";
      }
    } else {
      return "id_required";
    }
  }

  public function change_action_status($request)
  {
    if ($request->status == 5 && empty($request->comments)) {
      return "comment_required";
    } elseif ($request->status == 5 && !empty($request->comments)) {
      $comments = $request->comments;
    } else {
      $comments = null;
    }
    $actions_id =  $request->action_id;
    // $action = ActionModel::where('id', $actions_id)
    //   ->update([
    //     'status' => $request->status,
    //     'comments' => $comments
    //   ]);
      $updateAction = ActionModel::find($actions_id);
      $input['comment'] = $comments;
      $input['status'] = $request->status;
      $action=  $updateAction->fill($input)->save();
    if (!empty($action)) {
      return "saved";
    } else {
      return "not_saved";
    }
  }

  public function upcoming_actions_and_templates($request)
  {
    $user_id =  $request->user_id;
    $date = Carbon::today()->subDays(7);
    $conditions[] = ['assined_user_id', $user_id];

    if ($request->search_keyword) {
      array_push($conditions, ['title', 'like', '%' . $request->search_keyword . '%']);
    }
    $action_listing = ActionModel::with(['completedForm', 'questions'])
    ->where($conditions)->where('created_at', '>=', $date)->where('status', '1')->get();

    $search_key = $request->search_keyword;

    $templete_listing = ShareTemplateModel::with(['template' => function ($query) use ($search_key) {
      $query->where('template_name', 'like', '%' . $search_key . '%');
    }])->where('created_at', '>=', $date)->where('user_id', $user_id)->get();
    return ['actions' => $action_listing, 'share_templates' => $templete_listing];
  }


  public function upcoming_actions_and_templates_By_Role($request,$roleID)
  {
    $user_id =  $request->user_id;
    $date = Carbon::today()->subDays(7);
    $conditions[] = ['i_ref_assined_role_id', $roleID];

    if ($request->search_keyword) {
      array_push($conditions, ['title', 'like', '%' . $request->search_keyword . '%']);
    }
    $action_listing = ActionModel::with(['completedForm', 'questions'])
    ->where($conditions)->where('created_at', '>=', $date)->where('status', '1')->get();

    $search_key = $request->search_keyword;

    $templete_listing = ShareTemplateModel::with(
      ['template' => function ($query) use ($search_key) {
      $query->where('template_name', 'like', '%' . $search_key . '%');
    }])->where('created_at', '>=', $date)->where('i_ref_user_role_id', $roleID)->get();
    return ['actions' => $action_listing, 'share_templates' => $templete_listing];
  }

  /**
   * send push notifications to android device
   * @param array $tokens
   * @param array $data
   * @param string $title
   * @param array $body
   * @param string $firebaseKey
   */
  function sendPushNotification($tokens, $data, $title, $body, $firebaseKey)
  {
    $url = 'https://fcm.googleapis.com/fcm/send';
    $fields = array(
      'registration_ids' => [$tokens],
      'notification' => [
        'title' => $title,
        'body' => $body
      ],
      'data' => $data
    );
    $headers = array(
      'Content-Type:application/json',
      'Authorization:key=' . $firebaseKey
    );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
  }

  /**
   * send APNS notification
   * @param \Illuminate\Http\Request
   * @param \Illuminate\Http\Response
   */
  public function sendAPNSNotification($token, $pay_load)
  {
    $apnsHost = 'gateway.sandbox.push.apple.com';
    $apnsCert = '';
    $apnsPort = 2195;
    $apnsPass = '';
    $payload['aps'] = $pay_load;
    $output = json_encode($payload);
    $token = pack('H*', str_replace(' ', '', $token));
    $apnsMessage = chr(0) . chr(0) . chr(32) . $token . chr(0) . chr(strlen($output)) . $output;
    $streamContext = stream_context_create();
    stream_context_set_option($streamContext, 'ssl', 'local_cert', $apnsCert);
    stream_context_set_option($streamContext, 'ssl', 'passphrase', $apnsPass);
    $apns = stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $error, $errorString, 2, STREAM_CLIENT_CONNECT, $streamContext);
    $result = fwrite($apns, $apnsMessage);
    fclose($apns);
    if (intval($result)) {
      return response()->json([
        CODE => HTTP_STATUS_200OK
        HTTP_STATUS => HTTP_STATUS_OK,
        HTTP_MESSAGE => trans('message.notification_sent_successfully'),
      ], HTTP_STATUS_OK); 
    }

    return response()->json([
      HTTP_STATUS => HTTP_STATUS_SERVER_ERROR,
      HTTP_MESSAGE => trans('message.error_sent_notification'),
      response => 
    ], HTTP_STATUS_OK);
  }


  /**
   * Update action
   */
  public function updateAction($id, Request $request)
  {
    try {
      $status = $request->status == 5 ? 1 : $request->status;

      $input = $request->only(['title', 'descriptions', 'assined_user_id', 'business_unit_id', 'department_id', 'priority', 'due_date', 'project_id']);
      $input['status'] = $status;
      $assined_role_id= CheckUserTypeAndGetRoleID($input['assined_user_id']);
      $input['i_ref_assined_role_id'] = $assined_role_id['role_id'];
      $row = ActionModel::findOrFail($id);
      $row->fill($input);
      $row->save();
      return returnResponse(HTTP_STATUS_OK, true, "Action updated successfully.");
    } catch (\Illuminate\Database\QueryException $ex) {
      return returnResponse(HTTP_STATUS_SERVER_ERROR, false, $ex->getMessage());
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
      return returnResponse(HTTP_STATUS_SERVER_ERROR, false, $ex->getMessage());
    } catch (Exception $ex) {
      return returnResponse(HTTP_STATUS_SERVER_ERROR, false, $ex->getMessage());
    }
  }
}
