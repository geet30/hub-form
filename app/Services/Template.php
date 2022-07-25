<?php
  namespace App\Services;
  use App\Models\{
    Template as TemplateModel,
  };
  // use Carbon\Carbon;

  class Template {
    /**
    * get LegalReminder
    * @return collection
    */
    public function getListing($name = null, $request)
    {
      if(isset($request['user_id']) && !empty($request['user_id'])){
        $userId = $request['user_id'];
        if(auth()->user()->user_type="supplier"){
          $listing = TemplateModel::with(['scopeMethodology',
          'sections.questions.guides.documents', 
          'sections.questions.dropdown_type.options'])->where
          ('template_name', 'like', '%' . $name . '%')->where('published', 1)
          ->whereRaw("`user_id` = $userId OR `id` IN 
          (SELECT `template_id` FROM `share_templates` WHERE `user_id` = $userId)")
          ->orderBy('id','desc')->paginate(10);
        }else{
            $userId = auth()->user()->users_details->i_ref_company_id;
            $listing = TemplateModel::with(['scopeMethodology',
            'sections.questions.guides.documents', 
            'sections.questions.dropdown_type.options'])->where
            ('template_name', 'like', '%' . $name . '%')->where('published', 1)
            ->whereRaw("`i_ref_user_role_id` = $userId OR `id` IN 
            (SELECT `template_id` FROM `share_templates` WHERE `i_ref_user_role_id` = $userId)")
            ->orderBy('id','desc')->paginate(10);
        }




        return $listing;
      }
    }

    public function getTemplateDetail($id = null)
    {
      $details = TemplateModel::with([
        'scopeMethodology','sections.questions.guides.documents', 
      'sections.questions.dropdown_type.options'])->where('id',$id)->first();
      return $details;
    }
  }
