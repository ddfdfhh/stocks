- AttributeFamily:
  - module: 
    - AttributeFamily
  - plural:
    - attribute_families
  - modal:
    - true
  - export:
    - false
  - export_fields:
  - validation:
    - "['name'=>'required|max:200|string']"
    - "['status'=>'required|max:200|string']"
    - "['personal_info'=>'nullable']"
    - "['city_name'=>'nullable']"
    - "['country'=>'nullable']"
    - "['personal_info__json__name'=>'nullable']"
    - "['personal_info__json__qty'=>'nullable']"
    - "['city_name__json__qty'=>'nullable']"
    - "['city_name__json__name'=>'nullable']"
    - "['image'=>'nullable|image']"
  - has_repeating_group:
    - true
  - repeating_group_inputs:
    - "['colname'=>'addon','label'=> 'Addon','inputs'=>[['placeholder'=>'Enter Name','name'=>'personal_info__json__name[]','label'=>'Name','tag'=>'input','type'=>'text','default'=>''],['placeholder'=>'Enter qty','name'=>'personal_info__json__qty[]','label'=>'qty','tag'=>'input','type'=>'number','default'=>'']]]"
    - "['colname'=>'cities','label'=> 'City','inputs'=>[['placeholder'=>'Enter Name','name'=>'city_name__json__name[]','label'=>'Name','tag'=>'input','type'=>'text','default'=>''],['placeholder'=>'Enter qty','name'=>'city_name__json__qty[]','label'=>'qty','tag'=>'input','type'=>'number','default'=>'']]]"
  - toggable_group:
    - "['onval'=>'Active','inputs'=>[['placeholder'=>'Enter Name','name'=>'name','label'=>'Name','tag'=>'input','type'=>'text','default'=>''],['placeholder'=>'Enter qty','name'=>'qty','label'=>'Qty','tag'=>'input','type'=>'text','default'=>'']]]"
  
  - toggable_group_edit:
    - "['onval'=>'Active','inputs'=>[['placeholder'=>'Enter Name','name'=>'x','label'=>'X','tag'=>'input','type'=>'text','default'=>$row->x],['placeholder'=>'Enter y','name'=>'y','label'=>'Y','tag'=>'input','type'=>'text','default'=>$row->y]]]"
  
  - searchable_fields:
    - "['name'=>'name','label'=>'Name']"
           
  - filterable_fields:
    - "['name'=>'created_at','label'=>'Date','type'=>'date']"
    
  - form_image_field_name:
   - "[['field_name'=>'image','single'=>true],['field_name'=>'attribute_images','single'=>false,'image_model_name'=>'AttributeImage','table_name'=>'attribute_images','parent_table_field'=>'attribute_id']]"
  
  - create:
    - "['label'=>'','inputs'=>[ ['placeholder'=>'Enter Id','name'=>'name','label'=>'Name','tag'=>'input','type'=>'text','default'=>''],['placeholder'=>'Enter image','name'=>'image','label'=>'Photo','tag'=>'input','type'=>'file','default'=>''], ['placeholder'=>'','name'=>'status','label'=>'Status ','has_toggle_div'=>['toggle_div_id'=>'status_toggle','inputidforvalue'=>'','plural_lowercase'=>'attribute_families','rowid'=>''],['tag'=>'input','type'=>'radio','multiple'=>false,'value'=>[(object)['label'=>'Active','value'=>'Active'],(object)['label'=>'In Active','value'=>'In-Active']],'inline'=>false,'default'=>'Yes','attr'=>[]], ['name'=>'country','label'=>'Select Country','tag'=>'select','type'=>'select','default'=>'','custom_key_for_option'=>'name','options'=>$this->getList('Country'),'custom_id_for_option'=>'id','multiple'=>false,'attr'=>[]] ] ]"
               
  - edit:
    - "['label'=>'','inputs'=>[ ['placeholder'=>'Enter Id','name'=>'name','label'=>'Name','tag'=>'input','type'=>'text','default'=>$model->name], ['placeholder'=>'','tag'=>'input','name'=>'status','label'=>'Status ','has_toggle_div'=>['toggle_div_id'=>'status_toggle','inputidforvalue'=>'inp-status-{$model->status}','plural_lowercase'=>'attribute_families','rowid'=>$model->id],'type'=>'radio','multiple'=>false,'value'=>[(object)['label'=>'Active','value'=>'Active'],(object)['label'=>'In Active','value'=>'In-Active']],'inline'=>false,'default'=>$model->status,'attr'=>[]], ['name'=>'country','label'=>'Select Country','tag'=>'select','type'=>'select','default'=>$model->country,'custom_key_for_option'=>'name','options'=>$this->getList('Country'),'custom_id_for_option'=>'id','multiple'=>false,'attr'=>[]] ] ]"
               
  - index_page:
    - "['column'=>'name','label'=>'Name','sortable'=>'Yes']"
    - "['column'=>'status','label'=>'Status','sortable'=>'No']"
    - "['column'=>'country','label'=>'Country','sortable'=>'No']"
    - "['column'=>'personal_info','label'=>'PI','sortable'=>'No']"
    - "['column'=>'city_name','label'=>'City Name','sortable'=>'No']"
    - "['column'=>'created_at','label'=>'Date','sortable'=>'Yes']"

     